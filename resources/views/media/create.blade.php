<x-app-layout>
    <x-slot name="header">
        <div class="dashboard-heading media-heading">
            <div>
                <p class="eyebrow">Yearbook office / assets</p>
                <h1>Upload new media</h1>
            </div>
            <a href="{{ route('media.index') }}" class="text-link">Back to media <span aria-hidden="true">←</span></a>
        </div>
    </x-slot>

    <div class="dashboard-wrap" style="max-width: 820px; margin: 0 auto;">
        <style>
            .media-create-card{padding:28px;border:1px solid #d8e3ef;border-radius:22px;background:#fff;box-shadow:0 10px 35px rgba(0,42,92,.06)}
            .media-create-field{margin-bottom:22px}.media-create-field label{display:block;margin-bottom:8px;color:#002a5c;font-size:.78rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase}.media-create-field input,.media-create-field textarea{width:100%;box-sizing:border-box;border:1px solid #cbd8e6;border-radius:11px;padding:11px 12px;color:#002a5c;background:#fff;font:inherit}.media-create-field input:focus,.media-create-field textarea:focus{outline:3px solid rgba(255,176,52,.18);border-color:#ffb034}.media-create-help{display:block;margin-top:7px;color:#64748b;font-size:.78rem;line-height:1.5}.media-tag-list{display:flex;flex-wrap:wrap;gap:7px;margin-top:10px}.media-tag{display:inline-flex;align-items:center;gap:5px;padding:6px 10px;border-radius:999px;background:#eaf2fb;color:#002a5c;font-size:.74rem;font-weight:700}.media-tag button{border:0;background:transparent;color:inherit;padding:0;cursor:pointer;font-weight:900;line-height:1}.media-tag-entry{display:flex;gap:8px}.media-tag-entry input{flex:1}.media-tag-add{border:1px solid #cbd8e6;background:#f7fbff;color:#002a5c;border-radius:10px;padding:0 14px;font-weight:800;cursor:pointer}.media-create-actions{display:flex;gap:10px}.media-create-actions>*{flex:1}.media-alert{margin-bottom:20px;padding:16px 18px;border-radius:12px}.media-alert-error{background:#fff4f4;border:1px solid #f0b7b7;color:#8b1e1e}.media-alert-success{background:#f0faf4;border:1px solid #b7dec5;color:#17663a}.media-limits{margin-bottom:20px;padding:18px 20px;background:#f5f9fd;border:1px solid #d8e3ef;border-radius:12px}.media-limits-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;color:#64748b;font-size:14px}.media-limits strong{color:#002a5c}@media(max-width:600px){.media-limits-grid{grid-template-columns:1fr}.media-tag-entry{flex-direction:column}.media-tag-add{padding:10px}.media-create-actions{flex-direction:column}}
        </style>

        @if (session('error'))
            <div class="media-alert media-alert-error" role="alert" aria-live="polite"><strong>Upload failed</strong><div>{{ session('error') }}</div></div>
        @endif
        @if ($errors->any())
            <div class="media-alert media-alert-error" role="alert" aria-live="polite">
                <strong>Please fix the following errors:</strong>
                <ul style="margin:8px 0 0;padding-left:20px;">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="media-limits">
            <div style="font-weight:800;color:#002a5c;margin-bottom:8px;">Media upload limits</div>
            <div class="media-limits-grid">
                <div><strong>Images</strong><br>10 MB</div>
                <div><strong>Videos</strong><br>100 MB</div>
                <div><strong>Documents</strong><br>20 MB</div>
            </div>
        </div>

        <div id="client-upload-error" class="media-alert media-alert-error" role="alert" aria-live="polite" hidden></div>

        <form id="media-upload-form" action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data" class="media-create-card">
            @csrf

            <div class="media-create-field">
                <label for="file">Media file <span style="color:#b42318">*</span></label>
                <input type="file" id="file" name="file" required accept="image/jpeg,image/jpg,image/png,image/webp,video/mp4,video/quicktime,video/x-msvideo,.pdf,.doc,.docx,.ppt,.pptx">
                <small id="file-help" class="media-create-help">Images over 5 MB will ask for confirmation before compression. Videos up to 100 MB and documents up to 20 MB are allowed.</small>
                @error('file')<p style="color:#b42318;margin:6px 0 0">{{ $message }}</p>@enderror
            </div>

            <div class="media-create-field">
                <label for="caption">Caption</label>
                <input type="text" id="caption" name="caption" value="{{ old('caption') }}" maxlength="255" placeholder="Enter a caption for this media">
                @error('caption')<p style="color:#b42318;margin:6px 0 0">{{ $message }}</p>@enderror
            </div>

            <div class="media-create-field">
                <label for="alt_text">Alternative text</label>
                <input type="text" id="alt_text" name="alt_text" value="{{ old('alt_text') }}" maxlength="255" placeholder="Describe the image for accessibility">
                @error('alt_text')<p style="color:#b42318;margin:6px 0 0">{{ $message }}</p>@enderror
            </div>

            <div class="media-create-field">
                <label for="credit">Credit</label>
                <input type="text" id="credit" name="credit" value="{{ old('credit') }}" maxlength="255" placeholder="Photographer or source">
                @error('credit')<p style="color:#b42318;margin:6px 0 0">{{ $message }}</p>@enderror
            </div>

            <div class="media-create-field">
                <label for="tag-input">Tags</label>
                <div class="media-tag-entry">
                    <input type="text" id="tag-input" maxlength="50" placeholder="e.g. graduation, ceremony, 2026" autocomplete="off" aria-describedby="tag-help">
                    <button type="button" id="add-tag" class="media-tag-add">+ Add tag</button>
                </div>
                <div id="tag-list" class="media-tag-list" aria-live="polite">
                    @foreach (old('tags', []) as $tag)
                        @if (trim((string)$tag) !== '')
                            <span class="media-tag"><span>{{ $tag }}</span><button type="button" data-remove-tag aria-label="Remove tag {{ $tag }}">×</button><input type="hidden" name="tags[]" value="{{ $tag }}"></span>
                        @endif
                    @endforeach
                </div>
                <small id="tag-help" class="media-create-help">Tags are optional. Use short searchable keywords such as <strong>graduation</strong>, <strong>research</strong>, <strong>campus</strong>, or <strong>2026</strong>. Press Enter or click Add tag to add one.</small>
                @error('tags')<p style="color:#b42318;margin:6px 0 0">{{ $message }}</p>@enderror
                @error('tags.*')<p style="color:#b42318;margin:6px 0 0">{{ $message }}</p>@enderror
            </div>

            <div class="media-create-actions">
                <a href="{{ route('media.index') }}" class="button button-muted">Cancel</a>
                <button id="upload-button" type="submit" class="button button-navy">Upload media <span aria-hidden="true">→</span></button>
            </div>
        </form>
    </div>

    <div id="compression-modal" hidden aria-hidden="true" style="position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;padding:20px;background:rgba(0,20,45,.62)">
        <div role="dialog" aria-modal="true" aria-labelledby="compression-title" style="width:min(100%,520px);background:#fff;border-radius:18px;box-shadow:0 24px 70px rgba(0,0,0,.22);overflow:hidden">
            <div style="padding:24px 26px 18px;border-bottom:1px solid #e2e8f0"><h3 id="compression-title" style="margin:0;color:#002a5c;font-size:22px">This image is large</h3></div>
            <div style="padding:22px 26px;color:#475569;line-height:1.6">
                <p>The selected image is larger than the recommended upload size. It will be compressed before uploading. Compression may slightly reduce image quality.</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px"><div><small>Original size</small><strong id="compression-original-size" style="display:block;color:#002a5c"></strong></div><div><small>Estimated compressed size</small><strong id="compression-estimated-size" style="display:block;color:#002a5c"></strong></div></div>
                <p id="compression-status" hidden aria-live="polite" style="font-weight:700;color:#002a5c"></p>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:10px;padding:18px 26px;background:#f8fafc;border-top:1px solid #e2e8f0"><button type="button" id="compression-reject" class="button button-muted">Reject</button><button type="button" id="compression-accept" class="button button-navy">Accept compression</button></div>
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
            const tagInput = document.getElementById('tag-input');
            const tagList = document.getElementById('tag-list');
            const addTagButton = document.getElementById('add-tag');
            const limits = { image: 10 * 1024 * 1024, video: 100 * 1024 * 1024, document: 20 * 1024 * 1024 };
            const imageCompressionThreshold = 5 * 1024 * 1024;
            const extensionTypes = { jpg:'image',jpeg:'image',png:'image',webp:'image',mp4:'video',mov:'video',avi:'video',pdf:'document',doc:'document',docx:'document',ppt:'document',pptx:'document' };
            let compressionAccepted = false;
            let pendingImage = null;

            function formatBytes(bytes){return bytes>=1024*1024?`${(bytes/(1024*1024)).toFixed(1).replace('.0','')} MB`:`${Math.ceil(bytes/1024)} KB`;}
            function getType(file){return extensionTypes[file.name.split('.').pop().toLowerCase()]||null;}
            function showError(message){errorBox.textContent=message;errorBox.hidden=false;fileInput.setAttribute('aria-invalid','true');}
            function clearError(){errorBox.textContent='';errorBox.hidden=true;fileInput.removeAttribute('aria-invalid');}

            function addTag(){
                const value=tagInput.value.trim();
                if(!value)return;
                const values=Array.from(tagList.querySelectorAll('input[name="tags[]"]')).map(el=>el.value.toLowerCase());
                if(values.includes(value.toLowerCase())){tagInput.value='';return;}
                if(values.length>=30){showError('You can add a maximum of 30 tags to one media item.');return;}
                const wrapper=document.createElement('span'); wrapper.className='media-tag';
                const text=document.createElement('span'); text.textContent=value;
                const remove=document.createElement('button'); remove.type='button'; remove.dataset.removeTag=''; remove.setAttribute('aria-label',`Remove tag ${value}`); remove.textContent='×';
                const hidden=document.createElement('input'); hidden.type='hidden'; hidden.name='tags[]'; hidden.value=value;
                wrapper.append(text,remove,hidden); tagList.appendChild(wrapper); tagInput.value=''; tagInput.focus();
            }
            addTagButton.addEventListener('click',addTag);
            tagInput.addEventListener('keydown',e=>{if(e.key==='Enter'){e.preventDefault();addTag();}});
            tagList.addEventListener('click',e=>{const remove=e.target.closest('[data-remove-tag]');if(remove)remove.closest('.media-tag').remove();});

            function openCompressionModal(file){pendingImage=file;originalSize.textContent=formatBytes(file.size);estimatedSize.textContent=formatBytes(Math.max(250*1024,Math.round(file.size*.35)));compressionStatus.hidden=true;compressionStatus.textContent='';acceptButton.disabled=false;rejectButton.disabled=false;modal.hidden=false;modal.style.display='flex';modal.setAttribute('aria-hidden','false');acceptButton.focus();}
            function closeCompressionModal(){modal.hidden=true;modal.style.display='none';modal.setAttribute('aria-hidden','true');pendingImage=null;}
            function rejectImageCompression(){closeCompressionModal();compressionAccepted=false;fileInput.value='';help.textContent='Images over 5 MB require compression confirmation before upload.';showError('The image was not uploaded because compression was rejected.');}
            function replaceSelectedFile(blob,originalFile){const compressedFile=new File([blob],`${originalFile.name.replace(/\.[^.]+$/,'')}-compressed.jpg`,{type:'image/jpeg',lastModified:Date.now()});try{const dt=new DataTransfer();dt.items.add(compressedFile);fileInput.files=dt.files;return true;}catch(e){return false;}}
            function compressImage(file){return new Promise((resolve,reject)=>{const image=new Image();const objectUrl=URL.createObjectURL(file);image.onload=function(){URL.revokeObjectURL(objectUrl);const maxDimension=2400;const scale=Math.min(1,maxDimension/Math.max(image.naturalWidth,image.naturalHeight));const width=Math.max(1,Math.round(image.naturalWidth*scale));const height=Math.max(1,Math.round(image.naturalHeight*scale));const canvas=document.createElement('canvas');canvas.width=width;canvas.height=height;const context=canvas.getContext('2d',{alpha:false});if(!context){reject(new Error('Your browser could not prepare this image for compression.'));return;}context.imageSmoothingEnabled=true;context.imageSmoothingQuality='high';context.drawImage(image,0,0,width,height);canvas.toBlob(blob=>blob?resolve(blob):reject(new Error('The image could not be compressed.')),'image/jpeg',.82);};image.onerror=function(){URL.revokeObjectURL(objectUrl);reject(new Error('The image could not be read by the browser.'));};image.src=objectUrl;});}

            fileInput.addEventListener('change',function(){clearError();compressionAccepted=false;const file=fileInput.files[0];if(!file)return;const type=getType(file);if(!type){showError('Unsupported file type. Please choose an accepted image, video, or document.');fileInput.value='';return;}if(file.size>limits[type]){showError(`${type.charAt(0).toUpperCase()+type.slice(1)} exceeds the allowed upload size.`);fileInput.value='';return;}if(type==='image'&&file.size>imageCompressionThreshold){openCompressionModal(file);}});
            acceptButton.addEventListener('click',async function(){if(!pendingImage)return;acceptButton.disabled=true;rejectButton.disabled=true;compressionStatus.hidden=false;compressionStatus.textContent='Compressing image…';try{const blob=await compressImage(pendingImage);if(blob.size>limits.image){throw new Error('The compressed image is still larger than the 10 MB limit. Please choose a smaller image.');}if(!replaceSelectedFile(blob,pendingImage)){throw new Error('Your browser could not prepare the compressed image for upload.');}compressionAccepted=true;help.textContent=`Compressed image ready: ${formatBytes(blob.size)}. Quality may be slightly reduced.`;compressionStatus.textContent='Compression complete. You can upload the image now.';setTimeout(closeCompressionModal,450);}catch(error){compressionStatus.textContent=error.message;acceptButton.disabled=false;rejectButton.disabled=false;}});
            rejectButton.addEventListener('click',rejectImageCompression);
            form.addEventListener('submit',function(e){const file=fileInput.files[0];if(!file){e.preventDefault();showError('Please select a media file.');return;}if(getType(file)==='image'&&file.size>imageCompressionThreshold&&!compressionAccepted){e.preventDefault();openCompressionModal(file);return;}button.disabled=true;button.textContent='Uploading…';});
            modal.addEventListener('click',e=>{if(e.target===modal)rejectImageCompression();});
            document.addEventListener('keydown',e=>{if(e.key==='Escape'&&!modal.hidden)rejectImageCompression();});
        });
    </script>
</x-app-layout>
