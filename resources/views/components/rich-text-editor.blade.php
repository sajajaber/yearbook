@props([
    'name',
    'value' => '',
    'rows' => 6,
    'placeholder' => 'Write your text here...',
])

<div class="rich-text-editor" data-rich-text-editor>
    <div class="rich-text-toolbar" role="toolbar" aria-label="Text formatting">
        <button type="button" class="rich-text-tool" data-command="bold" title="Bold" aria-label="Bold"><strong>B</strong></button>
        <button type="button" class="rich-text-tool" data-command="italic" title="Italic" aria-label="Italic"><em>I</em></button>
    </div>

    <div
        class="rich-text-surface"
        contenteditable="true"
        role="textbox"
        aria-multiline="true"
        data-placeholder="{{ $placeholder }}"
        data-value="{{ $value }}"
        style="min-height: {{ max(120, $rows * 28) }}px;"
    ></div>

    <textarea name="{{ $name }}" class="rich-text-input" rows="{{ $rows }}" hidden>{{ $value }}</textarea>
    <p class="rich-text-hint">Use <strong>bold</strong> or <em>italic</em> to emphasize text. Text colors stay consistent with the yearbook design.</p>
</div>

@once
    <style>
        .rich-text-editor {
            width: 100%;
            border: 1px solid var(--line, #d8e3ef);
            border-radius: 14px;
            background: #fff;
            overflow: hidden;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .rich-text-editor:focus-within {
            border-color: var(--ink, #002a5c);
            box-shadow: 0 0 0 3px rgba(0, 42, 92, .08);
        }

        .rich-text-toolbar {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 8px 10px;
            border-bottom: 1px solid var(--line, #d8e3ef);
            background: #f8fafc;
        }

        .rich-text-tool {
            width: 34px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid transparent;
            border-radius: 8px;
            background: transparent;
            color: var(--ink, #002a5c);
            cursor: pointer;
            font: inherit;
            transition: background .18s ease, border-color .18s ease, transform .18s ease;
        }

        .rich-text-tool:hover,
        .rich-text-tool.is-active {
            background: #fff;
            border-color: var(--line, #d8e3ef);
        }

        .rich-text-tool:active {
            transform: scale(.95);
        }

        .rich-text-surface {
            padding: 15px 16px;
            outline: 0;
            color: var(--ink, #002a5c);
            font: inherit;
            line-height: 1.7;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .rich-text-surface:empty::before {
            content: attr(data-placeholder);
            color: var(--ink-soft, #64748b);
            opacity: .75;
            pointer-events: none;
        }

        .rich-text-surface p {
            margin: 0 0 .75em;
        }

        .rich-text-surface p:last-child {
            margin-bottom: 0;
        }

        .rich-text-hint {
            margin: 0;
            padding: 0 16px 12px;
            color: var(--ink-soft, #64748b);
            font-size: 11px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-rich-text-editor]').forEach((editor) => {
                if (editor.dataset.ready === 'true') return;
                editor.dataset.ready = 'true';

                const surface = editor.querySelector('.rich-text-surface');
                const input = editor.querySelector('.rich-text-input');
                const buttons = editor.querySelectorAll('.rich-text-tool');

                const allowedTags = ['B', 'STRONG', 'I', 'EM', 'P', 'BR'];

                const cleanNode = (node) => {
                    if (node.nodeType === Node.TEXT_NODE) return document.createTextNode(node.nodeValue);
                    if (node.nodeType !== Node.ELEMENT_NODE) return document.createDocumentFragment();

                    const tag = node.tagName.toUpperCase();
                    if (!allowedTags.includes(tag)) {
                        const fragment = document.createDocumentFragment();
                        node.childNodes.forEach(child => fragment.appendChild(cleanNode(child)));
                        return fragment;
                    }

                    const clean = document.createElement(tag.toLowerCase());
                    node.childNodes.forEach(child => clean.appendChild(cleanNode(child)));
                    return clean;
                };

                const sanitize = (html) => {
                    const template = document.createElement('template');
                    template.innerHTML = html || '';
                    const fragment = document.createDocumentFragment();
                    template.content.childNodes.forEach(node => fragment.appendChild(cleanNode(node)));
                    const wrapper = document.createElement('div');
                    wrapper.appendChild(fragment);
                    return wrapper.innerHTML;
                };

                const initialValue = input.value || surface.dataset.value || '';
                surface.innerHTML = sanitize(initialValue);

                const sync = () => {
                    input.value = sanitize(surface.innerHTML);
                    buttons.forEach(button => {
                        const command = button.dataset.command;
                        button.classList.toggle('is-active', document.queryCommandState(command));
                    });
                };

                buttons.forEach(button => {
                    button.addEventListener('mousedown', (event) => event.preventDefault());
                    button.addEventListener('click', () => {
                        surface.focus();
                        document.execCommand(button.dataset.command, false, null);
                        sync();
                    });
                });

                surface.addEventListener('input', sync);
                surface.addEventListener('keyup', sync);
                surface.addEventListener('mouseup', sync);
                surface.addEventListener('paste', (event) => {
                    event.preventDefault();
                    const text = (event.clipboardData || window.clipboardData).getData('text/plain');
                    document.execCommand('insertText', false, text);
                    sync();
                });

                surface.closest('form')?.addEventListener('submit', sync);
                sync();
            });
        });
    </script>
@endonce