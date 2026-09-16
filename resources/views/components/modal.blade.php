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
            return [...$el.querySelectorAll(selector)]
                .filter(el => ! el.hasAttribute('disabled'))
        },
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 },
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
    <div
        x-show="show"
        class="fixed inset-0 transform transition-all"
        x-on:click="show = false"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
    </div>

    <div
        x-show="show"
        class="mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        {{ $slot }}

        @if ($isMediaEditModal && $mediaEditItem?->type === 'image')
            <div class="media-modal-ai" id="media-modal-ai-{{ $mediaEditId }}" data-media-id="{{ $mediaEditId }}">
                <div class="media-modal-ai-heading">
                    <div>
                        <strong>✨ AI editorial assistance</strong>
                        <span>Generate suggestions, then edit them before saving.</span>
                    </div>
                    <span class="media-modal-ai-badge">Image</span>
                </div>

                <div class="media-modal-ai-buttons">
                    <button type="button" class="media-modal-ai-button" data-media-ai-caption>Regenerate caption</button>
                    <button type="button" class="media-modal-ai-button" data-media-ai-tags>Regenerate tags</button>
                </div>

                <div class="media-modal-tags">
                    <div class="media-modal-tags-label">Tags</div>
                    <div class="media-modal-tag-list" data-media-tag-list>
                        @foreach (($mediaEditItem->tags ?? []) as $tag)
                            <span class="media-modal-tag" data-media-tag>
                                <span>{{ $tag }}</span>
                                <button type="button" data-remove-media-tag aria-label="Remove tag {{ $tag }}">×</button>
                                <input type="hidden" name="tags[]" value="{{ $tag }}" data-media-tag-input>
                            </span>
                        @endforeach
                    </div>
                    <div class="media-modal-tag-entry">
                        <input type="text" maxlength="50" placeholder="Add a tag" data-media-tag-text>
                        <button type="button" class="media-modal-tag-add" data-add-media-tag>+ Add</button>
                    </div>
                </div>

                <div class="media-modal-ai-status" data-media-ai-status role="status" aria-live="polite"></div>
            </div>

            <style>
                .media-modal-ai{margin:0 28px 28px;padding:16px 18px;border:1px solid #d8e3ef;border-radius:14px;background:linear-gradient(135deg,#f7fbff,#fff);color:#002a5c}.media-modal-ai-heading{display:flex;align-items:flex-start;justify-content:space-between;gap:12px}.media-modal-ai-heading strong{display:block;font-size:.8rem}.media-modal-ai-heading span{display:block;margin-top:3px;color:#64748b;font-size:.72rem}.media-modal-ai-badge{padding:4px 8px!important;margin:0!important;border-radius:999px;background:#eaf2fb;color:#002a5c!important;font-weight:800;font-size:.62rem!important;white-space:nowrap}.media-modal-ai-buttons{display:flex;gap:8px;margin-top:12px;flex-wrap:wrap}.media-modal-ai-button,.media-modal-tag-add{border:1px solid #cbd8e6;background:#fff;color:#002a5c;border-radius:9px;padding:8px 11px;font-size:.72rem;font-weight:800;cursor:pointer}.media-modal-ai-button:hover,.media-modal-tag-add:hover{border-color:#ffb034;background:#fffaf2}.media-modal-ai-button:disabled{opacity:.55;cursor:wait}.media-modal-tags{margin-top:15px}.media-modal-tags-label{margin-bottom:7px;font-size:.68rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase}.media-modal-tag-list{display:flex;flex-wrap:wrap;gap:6px}.media-modal-tag{display:inline-flex;align-items:center;gap:5px;padding:5px 8px;border-radius:999px;background:#eaf2fb;color:#002a5c;font-size:.7rem;font-weight:700}.media-modal-tag button{border:0;background:transparent;color:inherit;padding:0;cursor:pointer;font-weight:900}.media-modal-tag-entry{display:flex;gap:7px;margin-top:8px}.media-modal-tag-entry input{min-width:0;flex:1;border:1px solid #cbd8e6;border-radius:9px;padding:8px 10px;color:#002a5c}.media-modal-ai-status{margin-top:9px;color:#64748b;font-size:.72rem;line-height:1.5}.media-modal-ai-status.is-error{color:#8b1e1e}.media-modal-ai-status.is-success{color:#17663a}@media(max-width:600px){.media-modal-ai{margin-left:18px;margin-right:18px}.media-modal-tag-entry{flex-direction:column}.media-modal-tag-add{padding:9px}}
            </style>

            <script>
                (() => {
                    const root = document.getElementById('media-modal-ai-{{ $mediaEditId }}');
                    if (!root || root.dataset.ready === 'true') return;
                    root.dataset.ready = 'true';
                    const mediaId = root.dataset.mediaId;
                    const form = root.closest('[x-show]')?.querySelector('form') || root.parentElement.querySelector('form');
                    const caption = form?.querySelector('#caption-{{ $mediaEditId }}');
                    const tagList = root.querySelector('[data-media-tag-list]');
                    const status = root.querySelector('[data-media-ai-status]');
                    const captionButton = root.querySelector('[data-media-ai-caption]');
                    const tagsButton = root.querySelector('[data-media-ai-tags]');
                    const tagText = root.querySelector('[data-media-tag-text]');
                    const addTag = root.querySelector('[data-add-media-tag]');
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

                    function syncTagsToForm(){
                        if(!form || !tagList)return;
                        form.querySelectorAll('[data-modal-media-tag-input]').forEach(el=>el.remove());
                        tagList.querySelectorAll('[data-media-tag-input]').forEach(input=>{
                            const hidden=input.cloneNode(true);
                            hidden.removeAttribute('data-media-tag-input');
                            hidden.setAttribute('data-modal-media-tag-input','');
                            form.appendChild(hidden);
                        });
                    }
                    function addTagValue(value){
                        value=String(value||'').trim();
                        if(!value)return;
                        const existing=[...tagList.querySelectorAll('[data-media-tag-input]')].map(el=>el.value.toLowerCase());
                        if(existing.includes(value.toLowerCase()))return;
                        if(existing.length>=30){status.textContent='A media item can have a maximum of 30 tags.';status.className='media-modal-ai-status is-error';return;}
                        const chip=document.createElement('span');chip.className='media-modal-tag';
                        const text=document.createElement('span');text.textContent=value;
                        const remove=document.createElement('button');remove.type='button';remove.dataset.removeMediaTag='';remove.setAttribute('aria-label',`Remove tag ${value}`);remove.textContent='×';
                        const hidden=document.createElement('input');hidden.type='hidden';hidden.name='tags[]';hidden.value=value;hidden.dataset.mediaTagInput='';
                        chip.append(text,remove,hidden);tagList.appendChild(chip);syncTagsToForm();
                    }
                    function run(button,url,mode){
                        button.disabled=true;status.className='media-modal-ai-status';status.textContent=mode==='caption'?'Generating a new caption…':'Generating searchable tags…';
                        fetch(url,{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},credentials:'same-origin'})
                            .then(async response=>{const data=await response.json();if(!response.ok)throw new Error(data.message||'AI generation failed.');return data;})
                            .then(data=>{if(mode==='caption'&&caption){caption.value=data.caption||'';status.textContent='Caption suggestion added. Review or edit it before saving.';}else if(mode==='tags'&&Array.isArray(data.tags)){data.tags.forEach(addTagValue);status.textContent='Tag suggestions added. Review, remove, or edit them before saving.';}status.className='media-modal-ai-status is-success';})
                            .catch(error=>{status.textContent=`AI assistance was unavailable: ${error.message}`;status.className='media-modal-ai-status is-error';})
                            .finally(()=>{button.disabled=false;});
                    }
                    captionButton?.addEventListener('click',()=>run(captionButton,'{{ route('media.generate-caption', $mediaEditId) }}','caption'));
                    tagsButton?.addEventListener('click',()=>run(tagsButton,'{{ route('media.generate-tags', $mediaEditId) }}','tags'));
                    addTag?.addEventListener('click',()=>{addTagValue(tagText.value);tagText.value='';tagText.focus();});
                    tagText?.addEventListener('keydown',event=>{if(event.key==='Enter'){event.preventDefault();addTag.click();}});
                    tagList?.addEventListener('click',event=>{const remove=event.target.closest('[data-remove-media-tag]');if(remove){remove.closest('[data-media-tag]').remove();syncTagsToForm();}});
                    syncTagsToForm();
                })();
            </script>
        @endif
    </div>
</div>
