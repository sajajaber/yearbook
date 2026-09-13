<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Upload New Media') }}</h2>
    </x-slot>

    <div style="padding: 20px; max-width: 760px; margin: 0 auto;">
        @if ($errors->any())
            <div style="margin-bottom: 20px; padding: 16px 18px; background: #fff4f4; border: 1px solid #f0b7b7; color: #8b1e1e; border-radius: 10px;">
                <p style="font-weight: 700; margin: 0 0 8px 0;">{{ __('Please fix the following errors:') }}</p>
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="margin-bottom: 18px; padding: 18px 20px; background: #f5f9fd; border: 1px solid #d8e3ef; border-radius: 12px;">
            <div style="font-weight: 800; color: #002a5c; margin-bottom: 8px;">{{ __('Media upload limits') }}</div>
            <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; color: #64748b; font-size: 14px;">
                <div><strong style="color: #002a5c;">{{ __('Images') }}</strong><br>10 MB</div>
                <div><strong style="color: #002a5c;">{{ __('Videos') }}</strong><br>100 MB</div>
                <div><strong style="color: #002a5c;">{{ __('Documents') }}</strong><br>20 MB</div>
            </div>
        </div>

        <div id="client-upload-error" role="alert" aria-live="polite" hidden style="margin-bottom: 20px; padding: 16px 18px; background: #fff4f4; border: 1px solid #f0b7b7; color: #8b1e1e; border-radius: 10px;"></div>

        <form id="media-upload-form" action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data" style="border: 1px solid #d8e3ef; padding: 24px; border-radius: 14px; background: #fff; box-shadow: 0 8px 24px rgba(0,42,92,.06);">
            @csrf

            <div style="margin-bottom: 22px;">
                <label for="file" style="display: block; margin-bottom: 8px; font-weight: 700; color: #002a5c;">
                    {{ __('Media File') }} <span style="color: #b42318;">*</span>
                </label>
                <input
                    type="file"
                    id="file"
                    name="file"
                    required
                    accept="image/jpeg,image/jpg,image/png,image/webp,video/mp4,video/quicktime,video/x-msvideo,.pdf,.doc,.docx,.ppt,.pptx"
                    style="display: block; width: 100%; padding: 10px; border: 1px solid #cbd8e5; border-radius: 8px; box-sizing: border-box; background: #f8fbfe;"
                />
                <small id="file-help" style="color: #64748b; display: block; margin-top: 7px;">{{ __('Images up to 10 MB, videos up to 100 MB, and documents up to 20 MB.') }}</small>
                @error('file')
                    <p style="color: #b42318; margin: 6px 0 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="caption" style="display: block; margin-bottom: 8px; font-weight: 700; color: #002a5c;">{{ __('Caption') }}</label>
                <input type="text" id="caption" name="caption" value="{{ old('caption') }}" placeholder="{{ __('Enter a caption for this media') }}" maxlength="255" style="display: block; width: 100%; padding: 10px; border: 1px solid #cbd8e5; border-radius: 8px; box-sizing: border-box;" />
                @error('caption')<p style="color: #b42318; margin: 6px 0 0 0;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="alt_text" style="display: block; margin-bottom: 8px; font-weight: 700; color: #002a5c;">{{ __('Alternative Text') }}</label>
                <input type="text" id="alt_text" name="alt_text" value="{{ old('alt_text') }}" placeholder="{{ __('Describe the image for accessibility') }}" maxlength="255" style="display: block; width: 100%; padding: 10px; border: 1px solid #cbd8e5; border-radius: 8px; box-sizing: border-box;" />
                @error('alt_text')<p style="color: #b42318; margin: 6px 0 0 0;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 22px;">
                <label for="credit" style="display: block; margin-bottom: 8px; font-weight: 700; color: #002a5c;">{{ __('Credit') }}</label>
                <input type="text" id="credit" name="credit" value="{{ old('credit') }}" placeholder="{{ __('Photographer or source') }}" maxlength="255" style="display: block; width: 100%; padding: 10px; border: 1px solid #cbd8e5; border-radius: 8px; box-sizing: border-box;" />
                @error('credit')<p style="color: #b42318; margin: 6px 0 0 0;">{{ $message }}</p>@enderror
            </div>

            <div style="display: flex; justify-content: space-between; gap: 10px;">
                <a href="{{ route('media.index') }}" style="flex: 1; padding: 11px; background: #f5f7fa; color: #334155; text-decoration: none; text-align: center; border: 1px solid #d8e3ef; border-radius: 8px; cursor: pointer;">{{ __('Cancel') }}</a>
                <button id="upload-button" type="submit" style="flex: 1; padding: 11px; background: #002a5c; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 700;">{{ __('Upload Media') }}</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('media-upload-form');
            const fileInput = document.getElementById('file');
            const errorBox = document.getElementById('client-upload-error');
            const help = document.getElementById('file-help');
            const button = document.getElementById('upload-button');

            const limits = {
                image: 10 * 1024 * 1024,
                video: 100 * 1024 * 1024,
                document: 20 * 1024 * 1024,
            };

            const extensionTypes = {
                jpg: 'image', jpeg: 'image', png: 'image', webp: 'image',
                mp4: 'video', mov: 'video', avi: 'video',
                pdf: 'document', doc: 'document', docx: 'document',
                ppt: 'document', pptx: 'document'
            };

            function formatBytes(bytes) {
                return bytes >= 1024 * 1024
                    ? `${(bytes / (1024 * 1024)).toFixed(1).replace('.0', '')} MB`
                    : `${Math.ceil(bytes / 1024)} KB`;
            }

            function getType(file) {
                const extension = file.name.split('.').pop().toLowerCase();
                return extensionTypes[extension] || null;
            }

            function showError(message) {
                errorBox.textContent = message;
                errorBox.hidden = false;
                fileInput.setAttribute('aria-invalid', 'true');
            }

            function clearError() {
                errorBox.textContent = '';
                errorBox.hidden = true;
                fileInput.removeAttribute('aria-invalid');
            }

            fileInput.addEventListener('change', function () {
                clearError();
                const file = fileInput.files[0];
                if (!file) return;

                const type = getType(file);
                if (!type) {
                    showError('{{ __('Unsupported file type. Please choose an image, video, or supported document.') }}');
                    fileInput.value = '';
                    return;
                }

                const limit = limits[type];
                help.textContent = `${file.name} · ${formatBytes(file.size)} · {{ __('Maximum') }} ${formatBytes(limit)}`;

                if (file.size > limit) {
                    showError(`{{ __('This file is too large.') }} ${formatBytes(file.size)}. {{ __('The maximum allowed size for') }} ${type} {{ __('files is') }} ${formatBytes(limit)}.`);
                    fileInput.value = '';
                    help.textContent = '{{ __('Images up to 10 MB, videos up to 100 MB, and documents up to 20 MB.') }}';
                }
            });

            form.addEventListener('submit', function (event) {
                clearError();
                const file = fileInput.files[0];
                if (!file) return;

                const type = getType(file);
                const limit = type ? limits[type] : null;

                if (!type || !limit || file.size > limit) {
                    event.preventDefault();
                    showError(type && limit
                        ? `{{ __('This file is too large.') }} ${formatBytes(file.size)}. {{ __('The maximum allowed size for') }} ${type} {{ __('files is') }} ${formatBytes(limit)}.`
                        : '{{ __('Unsupported file type. Please choose an image, video, or supported document.') }}'
                    );
                    return;
                }

                button.disabled = true;
                button.textContent = '{{ __('Uploading…') }}';
                button.style.opacity = '0.7';
                button.style.cursor = 'wait';
            });
        });
    </script>

    <style>
        @media (max-width: 640px) {
            #media-upload-form > div:last-child {
                flex-direction: column;
            }

            #media-upload-form > div:last-child > * {
                width: 100%;
                box-sizing: border-box;
            }
        }
    </style>
</x-app-layout>
