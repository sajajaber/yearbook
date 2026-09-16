@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl'
])

@php
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth];

$isMediaEditModal = str_starts_with($name, 'edit-media-');
$mediaEditId = $isMediaEditModal ? (int) str_replace('edit-media-', '', $name) : null;
$mediaEditItem = $mediaEditId ? \App\Models\Media::find($mediaEditId) : null;
@endphp

<div
    x-data="{
        show: @js($show),
        focusables() {
            let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'
            return [...$el.querySelectorAll(selector)].filter(el => !el.hasAttribute('disabled'))
        },
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1 },
    }"
    x-init="$watch('show', value => {
        if (value) {
            document.body.classList.add('overflow-y-hidden');
            {{ $attributes->has('focusable') ? 'setTimeout(() => firstFocusable().focus(), 100)' : '' }}
        } else {
            document.body.classList.remove('overflow-y-hidden');
        }
    })"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
    x-on:keydown.shift.tab.prevent="prevFocusable().focus()"
    x-show="show"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
    style="display: {{ $show ? 'block' : 'none' }};"
>
    <div x-show="show" class="fixed inset-0 transform transition-all" x-on:click="show = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
    </div>

    <div x-show="show" class="mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        {{ $slot }}

        @if ($isMediaEditModal && $mediaEditItem?->type === 'image')
            <script>
            (() => {
                const root = document.currentScript?.parentElement;
                if (!root || root.dataset.mediaAiReady === 'true') return;
                root.dataset.mediaAiReady = 'true';
                const mediaId = @js($mediaEditId);
                const existingTags = @js(array_values($mediaEditItem->tags ?? []));
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content || @js(csrf_token());

                function setup() {
                    const form = root.querySelector('form[action*="/media/"]');
                    if (!form || form.dataset.mediaAiReady === 'true') return;
                    const caption = form.querySelector('#caption-' + mediaId);
                    const captionField = caption?.closest('.media-edit-field');
                    const actions = form.querySelector('.media-modal-actions');
                    if (!caption || !captionField || !actions) return;
                    form.dataset.mediaAiReady = 'true';

                    const makeButton = (text) => {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'media-ai-inline-modal';
                        button.textContent = text;
                        return button;
                    };
                    const addStatus = (parent) => {
                        const status = document.createElement('span');
                        status.className = 'media-ai-inline-status';
                        status.setAttribute('role', 'status');
                        status.setAttribute('aria-live', 'polite');
                        parent.appendChild(status);
                        return status;
                    };

                    const captionLabel = captionField.querySelector('label');
                    if (captionLabel) {
                        const row = document.createElement('div');
                        row.className = 'media-ai-label-row';
                        captionLabel.parentNode.insertBefore(row, captionLabel);
                        row.appendChild(captionLabel);
                        const button = makeButton('✨ Regenerate');
                        row.appendChild(button);
                        const status = addStatus(captionField);
                        button.addEventListener('click', async () => {
                            button.disabled = true;
                            button.textContent = '✨ Generating…';
                            status.textContent = 'Generating…';
                            try {
                                const response = await fetch(@js(route('media.generate-caption', $mediaEditId)), {method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},credentials:'same-origin'});
                                const data = await response.json();
                                if (!response.ok) throw new Error(data.message || 'AI generation failed.');
                                caption.value = data.caption || '';
                                status.textContent = 'Suggestion added. Review it before saving.';
                                status.className = 'media-ai-inline-status success';
                            } catch (error) {
                                status.textContent = 'AI unavailable: ' + error.message;
                                status.className = 'media-ai-inline-status error';
                            } finally {
                                button.disabled = false;
                                button.textContent = '✨ Regenerate';
                            }
                        });
                    }

                    const tagsField = document.createElement('div');
                    tagsField.className = 'media-edit-field media-ai-tags-field';
                    tagsField.innerHTML = '<div class="media-ai-label-row"><label>Tags</label></div><div class="media-modal-tag-list"></div><div class="media-modal-tag-entry"><input type="text" maxlength="50" placeholder="Add a tag" autocomplete="off"><button type="button" class="media-modal-tag-add">+ Add</button></div>';
                    form.insertBefore(tagsField, actions);
                    const tagLabelRow = tagsField.querySelector('.media-ai-label-row');
                    const regenerateTags = makeButton('✨ Regenerate');
                    tagLabelRow.appendChild(regenerateTags);
                    const tagList = tagsField.querySelector('.media-modal-tag-list');
                    const tagInput = tagsField.querySelector('input');
                    const addTag = tagsField.querySelector('.media-modal-tag-add');
                    const tagStatus = addStatus(tagsField);

                    function syncTags() {
                        form.querySelectorAll('[data-modal-media-tag]').forEach(el => el.remove());
                        tagList.querySelectorAll('input[data-tag-value]').forEach(input => {
                            const hidden = document.createElement('input');
                            hidden.type = 'hidden'; hidden.name = 'tags[]'; hidden.value = input.value; hidden.dataset.modalMediaTag = 'true';
                            form.appendChild(hidden);
                        });
                    }
                    function addTag(value) {
                        value = String(value || '').trim();
                        if (!value) return;
                        const values = [...tagList.querySelectorAll('input[data-tag-value]')].map(i => i.value.toLowerCase());
                        if (values.includes(value.toLowerCase()) || values.length >= 30) return;
                        const chip = document.createElement('span'); chip.className = 'media-modal-tag';
                        const text = document.createElement('span'); text.textContent = value;
                        const remove = document.createElement('button'); remove.type='button'; remove.textContent='×'; remove.setAttribute('aria-label','Remove tag ' + value);
                        const hidden = document.createElement('input'); hidden.type='hidden'; hidden.value=value; hidden.dataset.tagValue='true';
                        remove.onclick=()=>{chip.remove();syncTags()}; chip.append(text,remove,hidden); tagList.appendChild(chip); syncTags();
                    }
                    existingTags.forEach(addTag);
                    addTag.onclick=()=>{addTag(tagInput.value);tagInput.value='';tagInput.focus()};
                    tagInput.onkeydown=e=>{if(e.key==='Enter'){e.preventDefault();addTag.click()}};

                    regenerateTags.addEventListener('click', async () => {
                        regenerateTags.disabled=true; regenerateTags.textContent='✨ Generating…'; tagStatus.textContent='Generating…';
                        try {
                            const response=await fetch(@js(route('media.generate-tags',$mediaEditId)),{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},credentials:'same-origin'});
                            const data=await response.json(); if(!response.ok)throw new Error(data.message||'AI generation failed.');
                            if(Array.isArray(data.tags)) data.tags.forEach(addTag);
                            tagStatus.textContent='Suggestions added. Review or remove them before saving.'; tagStatus.className='media-ai-inline-status success';
                        } catch(error){tagStatus.textContent='AI unavailable: '+error.message;tagStatus.className='media-ai-inline-status error'}
                        finally{regenerateTags.disabled=false;regenerateTags.textContent='✨ Regenerate'}
                    });
                    syncTags();
                }
                const observer = new MutationObserver(setup);
                observer.observe(root, {childList:true,subtree:true});
                setup();
            })();
            </script>
            <style>
                .media-ai-label-row{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:8px}.media-ai-label-row label{margin:0!important}.media-ai-inline-modal{border:1px solid #cbd8e6;background:#f7fbff;color:#002a5c;border-radius:8px;padding:5px 9px;font-size:.68rem;font-weight:800;cursor:pointer;white-space:nowrap}.media-ai-inline-modal:hover{border-color:#ffb034;background:#fffaf2}.media-ai-inline-modal:disabled{opacity:.55;cursor:wait}.media-ai-inline-status{display:block;margin-top:5px;color:#64748b;font-size:.7rem;line-height:1.4}.media-ai-inline-status.success{color:#17663a}.media-ai-inline-status.error{color:#8b1e1e}.media-modal-tag-list{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:8px}.media-modal-tag{display:inline-flex;align-items:center;gap:5px;padding:5px 8px;border-radius:999px;background:#eaf2fb;color:#002a5c;font-size:.7rem;font-weight:700}.media-modal-tag button{border:0;background:transparent;color:inherit;padding:0;cursor:pointer;font-weight:900}.media-modal-tag-entry{display:flex;gap:7px}.media-modal-tag-entry input{min-width:0;flex:1;border:1px solid var(--line);border-radius:9px;padding:9px 10px;color:var(--ink);font:inherit}.media-modal-tag-add{border:1px solid var(--line);background:#f7fbff;color:var(--ink);border-radius:9px;padding:8px 10px;font-size:.72rem;font-weight:800;cursor:pointer}.media-modal-tag-add:hover{border-color:var(--red)}
            </style>
        @endif
    </div>
</div>
