<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Upload New Media') }}</h2>
    </x-slot>

    <div style="padding: 20px; max-width: 760px; margin: 0 auto;">
        @if (session('error'))
            <div role="alert" aria-live="polite" style="margin-bottom: 20px; padding: 16px 18px; background: #fff4f4; border: 1px solid #f0b7b7; color: #8b1e1e; border-radius: 10px;">
                <p style="font-weight: 700; margin: 0 0 6px 0;">{{ __('Upload failed') }}</p>
                <p style="margin: 0;">{{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div role="alert" aria-live="polite" style="margin-bottom: 20px; padding: 16px 18px; background: #fff4f4; border: 1px solid #f0b7b7; color: #8b1e1e; border-radius: 10px;">
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
                <small id="file-help" style="color: #64748b; display: block; margin-top: 7px;">{{ __('Images over 5 MB will ask for confirmation before compression. Videos up to 100 MB and documents up to 20 MB are allowed.') }}</small>
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
                <input type="text" id="credit" name="credit" value="{{ old('credit') }}" placeholder="{{ __('Photographer or source') }}" maxlength="255" style="display: block; width: 100%; padding: 10px; border: 1px solid #cbd8ef; border-radius: 8px; box-sizing: border-box;" />
                @error('credit')<p style="color: #b42318; margin: 6px 0 0 0;">{{ $message }}</p>@enderror
            </div>

            <div style="display: flex; justify-content: space-between; gap: 10px;">
                <a href="{{ route('media.index') }}" style="flex: 1; padding: 11px; background: #f5f7fa; color: #334155; text-decoration: none; text-align: center; border: 1px solid #d8e3ef; border-radius: 8px; cursor: pointer;">{{ __('Cancel') }}</a>
                <button id="upload-button" type="submit" style="flex: 1; padding: 11px; background: #002a5c; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 700;">{{ __('Upload Media') }}</button>
            </div>
        </form>
    </div>

    <div id="compression-modal" hidden aria-hidden="true" style="position: fixed; inset: 0; z-index: 9999; display: none; align-items: center; justify-content: center; padding: 20px; background: rgba(0, 20, 45, .62);">
        <div role="dialog" aria-modal="true" aria-labelledby="compression-title" style="width: min(100%, 520px); background: #fff; border-radius: 18px; box-shadow: 0 24px 70px rgba(0,0,0,.22); overflow: hidden;">
            <div style="padding: 24px 26px 18px; border-bottom: 1px solid #e2e8f0;">
                <div style="font-size: 12px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; color: #d08b00; margin-bottom: 8px;">{{ __('Image size notice') }}</div>
                <h3 id="compression-title" style="margin: 0; color: #002a5c; font-size: 22px;">{{ __('This image is large') }}</h3>
            </div>
            <div style="padding: 22px 26px; color: #475569; line-height: 1.6;">
                <p style="margin: 0 0 16px;">{{ __('The selected image is larger than the recommended upload size. It will be compressed before uploading. Compression may slightly reduce image quality.') }}</p>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 18px;">
                    <div style="padding: 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;"><small style="display:block;color:#64748b;">{{ __('Original size') }}</small><strong id="compression-original-size" style="display:block;color:#002a5c;margin-top:3px;"></strong></div>
                    <div style="padding: 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;"><small style="display:block;color:#64748b;">{{ __('Estimated compressed size') }}</small><strong id="compression-estimated-size" style="display:block;color:#002a5c;margin-top:3px;"></strong></div>
                </div>
                <p id="compression-status" hidden aria-live="polite" style="margin: 0; font-weight: 700; color: #002a5c;"></p>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; padding: 18px 26px; background: #f8fafc; border-top: 1px solid #e2e8f0;">
                <button type="button" id="compression-reject" style="padding: 10px 16px; background: #fff; color: #334155; border: 1px solid #cbd5e1; border-radius: 9px; font-weight: 700; cursor: pointer;">{{ __('Reject') }}</button>
                <button type="button" id="compression-accept" style="padding: 10px 16px; background: #002a5c; color: #fff; border: 1px solid #002a5c; border-radius: 9px; font-weight: 700; cursor: pointer;">{{ __('Accept compression') }}</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('media-upload-form');
            const fileInput = document.getElementById('file');
            const errorBox = document.getElementById('client-upload-error');
            const help = document.getElementById('file-help');
            const button = document.getElementById('upload-button');
            const modal = document.getElementById('compression-modal');
            const acceptButton = document.getElementById('compression-accept');
            const rejectButton = document.getElementById('compression-reject');
            const originalSize = document.getElementById('compression-original-size');
            const estimatedSize = document.getElementById('compression-estimated-size');
            const compressionStatus = document.getElementById('compression-status');

            const limits = {
                image: 10 * 1024 * 1024,
                video: 100 * 1024 * 1024,
                document: 20 * 1024 * 1024,
            };

            const imageCompressionThreshold = 5 * 1024 * 1024;
            const extensionTypes = {
                jpg: 'image', jpeg: 'image', png: 'image', webp: 'image',
                mp4: 'video', mov: 'video', avi: 'video',
                pdf: 'document', doc: 'document', docx: 'document',
                ppt: 'document', pptx: 'document'
            };

            let compressionAccepted = false;
            let pendingImage = null;

            function formatBytes(bytes) {
                if (bytes >= 1024 * 1024) {
                    return `${(bytes / (1024 * 1024)).toFixed(1).replace('.0', '')} MB`;
                }
                return `${Math.ceil(bytes / 1024)} KB`;
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

            function openCompressionModal(file) {
                pendingImage = file;
                originalSize.textContent = formatBytes(file.size);
                estimatedSize.textContent = formatBytes(Math.max(250 * 1024, Math.round(file.size * 0.35)));
                compressionStatus.hidden = true;
                compressionStatus.textContent = '';
                acceptButton.disabled = false;
                rejectButton.disabled = false;
                acceptButton.textContent = '{{ __('Accept compression') }}';
                modal.hidden = false;
                modal.style.display = 'flex';
                modal.setAttribute('aria-hidden', 'false');
                acceptButton.focus();
            }

            function closeCompressionModal() {
                modal.hidden = true;
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
                pendingImage = null;
            }

            function rejectImageCompression() {
                closeCompressionModal();
                compressionAccepted = false;
                fileInput.value = '';
                help.textContent = '{{ __('Images over 5 MB require compression confirmation before upload.') }}';
                showError('{{ __('The image was not uploaded because compression was rejected.') }}');
            }

            function replaceSelectedFile(blob, originalFile) {
                const extension = 'jpg';
                const baseName = originalFile.name.replace(/\.[^.]+$/, '');
                const compressedFile = new File([blob], `${baseName}-compressed.${extension}`, {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                });

                try {
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(compressedFile);
                    fileInput.files = dataTransfer.files;
                    return true;
                } catch (error) {
                    return false;
                }
            }

            function compressImage(file) {
                return new Promise((resolve, reject) => {
                    const image = new Image();
                    const objectUrl = URL.createObjectURL(file);

                    image.onload = function () {
                        URL.revokeObjectURL(objectUrl);

                        const maxDimension = 2400;
                        const scale = Math.min(1, maxDimension / Math.max(image.naturalWidth, image.naturalHeight));
                        const width = Math.max(1, Math.round(image.naturalWidth * scale));
                        const height = Math.max(1, Math.round(image.naturalHeight * scale));
                        const canvas = document.createElement('canvas');
                        canvas.width = width;
                        canvas.height = height;

                        const context = canvas.getContext('2d', { alpha: false });
                        if (!context) {
                            reject(new Error('{{ __('Your browser could not prepare this image for compression.') }}'));
                            return;
                        }

                        context.imageSmoothingEnabled = true;
                        context.imageSmoothingQuality = 'high';
                        context.drawImage(image, 0, 0, width, height);

                        canvas.toBlob(function (blob) {
                            if (!blob) {
                                reject(new Error('{{ __('The image could not be compressed.') }}'));
                                return;
                            }

                            resolve(blob);
                        }, 'image/jpeg', 0.82);
                    };

                    image.onerror = function () {
                        URL.revokeObjectURL(objectUrl);
                        reject(new Error('{{ __('The image could not be read by the browser.') }}'));
                    };

                    image.src = objectUrl;
                });
            }

            fileInput.addEventListener('change', function () {
                clearError();
                compressionAccepted = false;
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

                if (type === 'image' && file.size > imageCompressionThreshold) {
                    openCompressionModal(file);
                    return;
                }

                if (file.size > limit) {
                    showError(`{{ __('This file is too large.') }} ${formatBytes(file.size)}. {{ __('The maximum allowed size for') }} ${type} {{ __('files is') }} ${formatBytes(limit)}.`);
                    fileInput.value = '';
                    help.textContent = '{{ __('Images over 5 MB require compression confirmation before upload.') }}';
                }
            });

            rejectButton.addEventListener('click', rejectImageCompression);

            acceptButton.addEventListener('click', async function () {
                if (!pendingImage) return;

                const image = pendingImage;
                acceptButton.disabled = true;
                rejectButton.disabled = true;
                acceptButton.textContent = '{{ __('Compressing…') }}';
                compressionStatus.hidden = false;
                compressionStatus.textContent = '{{ __('Preparing the compressed image…') }}';

                try {
                    const blob = await compressImage(image);
                    const replaced = replaceSelectedFile(blob, image);

                    if (!replaced) {
                        throw new Error('{{ __('Your browser could not replace the selected file.') }}');
                    }

                    if (blob.size > limits.image) {
                        throw new Error('{{ __('The compressed image is still larger than the 10 MB upload limit. Please choose a smaller image.') }}');
                    }

                    compressionAccepted = true;
                    help.textContent = `${image.name} · ${formatBytes(image.size)} → ${formatBytes(blob.size)} · {{ __('Compressed and ready to upload') }}`;
                    clearError();
                    closeCompressionModal();
                } catch (error) {
                    closeCompressionModal();
                    compressionAccepted = false;
                    fileInput.value = '';
                    showError(error.message || '{{ __('The image could not be compressed.') }}');
                    help.textContent = '{{ __('Images over 5 MB require compression confirmation before upload.') }}';
                }
            });

            form.addEventListener('submit', function (event) {
                clearError();
                const file = fileInput.files[0];
                if (!file) return;

                const type = getType(file);
                const limit = type ? limits[type] : null;

                if (!type || !limit) {
                    event.preventDefault();
                    showError('{{ __('Unsupported file type. Please choose an image, video, or supported document.') }}');
                    return;
                }

                if (type === 'image' && file.size > imageCompressionThreshold && !compressionAccepted) {
                    event.preventDefault();
                    openCompressionModal(file);
                    return;
                }

                if (file.size > limit) {
                    event.preventDefault();
                    showError(`{{ __('This file is too large.') }} ${formatBytes(file.size)}. {{ __('The maximum allowed size for') }} ${type} {{ __('files is') }} ${formatBytes(limit)}.`);
                    return;
                }

                button.disabled = true;
                button.textContent = '{{ __('Uploading…') }}';
                button.style.opacity = '0.7';
                button.style.cursor = 'wait';
            });

            modal.addEventListener('click', function (event) {
                if (event.target === modal) rejectImageCompression();
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && !modal.hidden) rejectImageCompression();
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
