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

    <div class="dashboard-wrap" style="max-width:820px;margin:0 auto;">
        <style>
            .media-create-card{padding:28px;border:1px solid #d8e3ef;border-radius:22px;background:#fff;box-shadow:0 10px 35px rgba(0,42,92,.06)}
            .media-create-field{margin-bottom:22px}.media-field-label{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:8px}.media-field-label label{margin:0;color:#002a5c;font-size:.78rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase}.media-ai-inline{border:1px solid #cbd8e6;background:#f7fbff;color:#002a5c;border-radius:8px;padding:5px 9px;font-size:.68rem;font-weight:800;cursor:pointer;white-space:nowrap}.media-ai-inline:hover{border-color:#ffb034;background:#fffaf2}.media-ai-inline:disabled{opacity:.55;cursor:wait}.media-create-field input,.media-create-field textarea{width:100%;box-sizing:border-box;border:1px solid #cbd8e6;border-radius:11px;padding:11px 12px;color:#002a5c;background:#fff;font:inherit}.media-create-field input:focus,.media-create-field textarea:focus{outline:3px solid rgba(255,176,52,.18);border-color:#ffb034}.media-create-help{display:block;margin-top:7px;color:#64748b;font-size:.78rem;line-height:1.5}.media-ai-status{min-height:18px;margin-top:5px;color:#64748b;font-size:.72rem}.media-ai-status.is-error{color:#8b1e1e}.media-ai-status.is-success{color:#17663a}.media-tag-list{display:flex;flex-wrap:wrap;gap:7px;margin-top:10px}.media-tag{display:inline-flex;align-items:center;gap:5px;padding:6px 10px;border-radius:999px;background:#eaf2fb;color:#002a5c;font-size:.74rem;font-weight:700}.media-tag button{border:0;background:transparent;color:inherit;padding:0;cursor:pointer;font-weight:900;line-height:1}.media-tag-entry{display:flex;gap:8px}.media-tag-entry input{flex:1}.media-tag-add{border:1px solid #cbd8e6;background:#f7fbff;color:#002a5c;border-radius:10px;padding:0 14px;font-weight:800;cursor:pointer}.media-create-actions{display:flex;gap:10px}.media-create-actions>*{flex:1}.media-alert{margin-bottom:20px;padding:16px 18px;border-radius:12px}.media-alert-error{background:#fff4f4;border:1px solid #f0b7b7;color:#8b1e1e}.media-limits{margin-bottom:20px;padding:18px 20px;background:#f5f9fd;border:1px solid #d8e3ef;border-radius:12px}.media-limits-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;color:#64748b;font-size:14px}.media-limits strong{color:#002a5c}.compression-modal{position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;padding:20px;background:rgba(0,20,45,.62)}.compression-dialog{width:min(100%,520px);background:#fff;border-radius:18px;box-shadow:0 24px 70px rgba(0,0,0,.22);overflow:hidden}.compression-head{padding:24px 26px 18px;border-bottom:1px solid #e2e8f0}.compression-head h3{margin:0;color:#002a5c;font-size:22px}.compression-body{padding:22px 26px;color:#475569;line-height:1.6}.compression-stats{display:grid;grid-template-columns:1fr 1fr;gap:12px}.compression-stats strong{display:block;color:#002a5c}.compression-actions{display:flex;justify-content:flex-end;gap:10px;padding:18px 26px;background:#f8fafc;border-top:1px solid #e2e8f0}@media(max-width:600px){.media-limits-grid{grid-template-columns:1fr}.media-tag-entry,.media-create-actions{flex-direction:column}.media-tag-add{padding:10px}.media-field-label{align-items:flex-start}.compression-stats{grid-template-columns:1fr}}
        </style>

        @if(session('error'))<div class="media-alert media-alert-error" role="alert">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="media-alert media-alert-error" role="alert"><strong>Please fix the following errors:</strong><ul style="margin:8px 0 0;padding-left:20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

        <div class="media-limits">
            <div style="font-weight:800;color:#002a5c;margin-bottom:8px;">Media upload limits</div>
            <div class="media-limits-grid"><div><strong>Images</strong><br>10 MB</div><div><strong>Videos</strong><br>100 MB</div><div><strong>Documents</strong><br>20 MB</div></div>
        </div>

        <div id="client-upload-error" class="media-alert media-alert-error" role="alert" hidden></div>

        <form id="media-upload-form" action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data" class="media-create-card">
            @csrf
            <div class="media-create-field">
                <div class="media-field-label"><label for="file">Media file <span style="color:#b42318">*</span></label></div>
                <input type="file" id="file" name="file" required accept="image/jpeg,image/jpg,image/png,image/webp,video/mp4,video/quicktime,video/x-msvideo,.pdf,.doc,.docx,.ppt,.pptx">
                <small id="file-help" class="media-create-help">Images over 5 MB will ask for confirmation before compression. Videos up to 100 MB and documents up to 20 MB are allowed.</small>
                @error('file')<p style="color:#b42318;margin:6px 0 0">{{ $message }}</p>@enderror
            </div>

            <div class="media-create-field">
                <div class="media-field-label"><label for="caption">Caption</label><button type="button" id="generate-caption" class="media-ai-inline" disabled>✨ Generate</button></div>
                <input type="text" id="caption" name="caption" value="{{ old('caption') }}" maxlength="255" placeholder="Enter a caption for this media">
                <div id="caption-ai-status" class="media-ai-status" role="status" aria-live="polite"></div>
                @error('caption')<p style="color:#b42318;margin:6px 0 0">{{ $message }}</p>@enderror
            </div>

            <div class="media-create-field">
                <div class="media-field-label"><label for="alt_text">Alternative text</label></div>
                <input type="text" id="alt_text" name="alt_text" value="{{ old('alt_text') }}" maxlength="255" placeholder="Describe the image for accessibility">
                @error('alt_text')<p style="color:#b42318;margin:6px 0 0">{{ $message }}</p>@enderror
            </div>

            <div class="media-create-field">
                <div class="media-field-label"><label for="credit">Credit</label></div>
                <input type="text" id="credit" name="credit" value="{{ old('credit') }}" maxlength="255" placeholder="Photographer or source">
                @error('credit')<p style="color:#b42318;margin:6px 0 0">{{ $message }}</p>@enderror
            </div>

            <div class="media-create-field">
                <div class="media-field-label"><label for="tag-input">Tags</label><button type="button" id="generate-tags" class="media-ai-inline" disabled>✨ Generate</button></div>
                <div class="media-tag-entry"><input type="text" id="tag-input" maxlength="50" placeholder="e.g. graduation, ceremony, 2026" autocomplete="off" aria-describedby="tag-help"><button type="button" id="add-tag" class="media-tag-add">+ Add tag</button></div>
                <div id="tag-list" class="media-tag-list" aria-live="polite">
                    @foreach(old('tags',[]) as $tag) @if(trim((string)$tag)!=='')<span class="media-tag"><span>{{ $tag }}</span><button type="button" data-remove-tag aria-label="Remove tag {{ $tag }}">×</button><input type="hidden" name="tags[]" value="{{ $tag }}"></span>@endif @endforeach
                </div>
                <div id="tags-ai-status" class="media-ai-status" role="status" aria-live="polite"></div>
                <small id="tag-help" class="media-create-help">Tags are optional. AI suggestions can be edited or removed before uploading.</small>
                @error('tags')<p style="color:#b42318;margin:6px 0 0">{{ $message }}</p>@enderror
                @error('tags.*')<p style="color:#b42318;margin:6px 0 0">{{ $message }}</p>@enderror
            </div>

            <div class="media-create-actions"><a href="{{ route('media.index') }}" class="button button-muted">Cancel</a><button id="upload-button" type="submit" class="button button-navy">Upload media <span aria-hidden="true">→</span></button></div>
        </form>
    </div>

    <div id="compression-modal" class="compression-modal" hidden aria-hidden="true">
        <div class="compression-dialog" role="dialog" aria-modal="true" aria-labelledby="compression-title">
            <div class="compression-head"><h3 id="compression-title">This image is large</h3></div>
            <div class="compression-body"><p>The selected image is larger than the recommended upload size. It will be compressed before uploading. Compression may slightly reduce image quality.</p><div class="compression-stats"><div><small>Original size</small><strong id="compression-original-size"></strong></div><div><small>Estimated compressed size</small><strong id="compression-estimated-size"></strong></div></div><p id="compression-status" hidden aria-live="polite"></p></div>
            <div class="compression-actions"><button type="button" id="compression-reject" class="button button-muted">Reject</button><button type="button" id="compression-accept" class="button button-navy">Accept compression</button></div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded',()=>{
        const form=document.getElementById('media-upload-form'),fileInput=document.getElementById('file'),errorBox=document.getElementById('client-upload-error'),help=document.getElementById('file-help'),uploadButton=document.getElementById('upload-button');
        const caption=document.getElementById('caption'),tagInput=document.getElementById('tag-input'),tagList=document.getElementById('tag-list'),addTagButton=document.getElementById('add-tag');
        const generateCaption=document.getElementById('generate-caption'),generateTags=document.getElementById('generate-tags'),captionStatus=document.getElementById('caption-ai-status'),tagsStatus=document.getElementById('tags-ai-status');
        const modal=document.getElementById('compression-modal'),accept=document.getElementById('compression-accept'),reject=document.getElementById('compression-reject'),originalSize=document.getElementById('compression-original-size'),estimatedSize=document.getElementById('compression-estimated-size');
        const limits={image:10*1024*1024,video:100*1024*1024,document:20*1024*1024},types={jpg:'image',jpeg:'image',png:'image',webp:'image',mp4:'video',mov:'video',avi:'video',pdf:'document',doc:'document',docx:'document',ppt:'document',pptx:'document'};
        let selectedImage=null,aiToken=0;
        const bytes=n=>n>=1048576?`${(n/1048576).toFixed(1).replace('.0','')} MB`:`${Math.ceil(n/1024)} KB`;
        const type=f=>types[f?.name.split('.').pop().toLowerCase()]||null;
        function error(msg){errorBox.textContent=msg;errorBox.hidden=false} function clearError(){errorBox.hidden=true;errorBox.textContent=''}
        function addTagValue(value){value=String(value||'').trim();if(!value)return;const existing=[...tagList.querySelectorAll('input[name="tags[]"]')].map(i=>i.value.toLowerCase());if(existing.includes(value.toLowerCase())||existing.length>=30)return;const chip=document.createElement('span');chip.className='media-tag';const text=document.createElement('span');text.textContent=value;const remove=document.createElement('button');remove.type='button';remove.dataset.removeTag='';remove.textContent='×';remove.setAttribute('aria-label',`Remove tag ${value}`);const hidden=document.createElement('input');hidden.type='hidden';hidden.name='tags[]';hidden.value=value;chip.append(text,remove,hidden);tagList.appendChild(chip)}
        function addTag(){const v=tagInput.value.trim();const before=tagList.querySelectorAll('input[name="tags[]"]').length;addTagValue(v);if(tagList.querySelectorAll('input[name="tags[]"]').length>before){tagInput.value='';tagInput.focus()}}
        addTagButton.onclick=addTag;tagInput.onkeydown=e=>{if(e.key==='Enter'){e.preventDefault();addTag()}};tagList.onclick=e=>{const b=e.target.closest('[data-remove-tag]');if(b)b.closest('.media-tag').remove()};
        function setAiEnabled(enabled){generateCaption.disabled=!enabled;generateTags.disabled=!enabled}
        async function requestAi(file,only=null){if(!file||type(file)!=='image')return;const token=++aiToken;setAiEnabled(false);if(!only||only==='caption'){generateCaption.textContent='✨ Generating…';captionStatus.textContent='Generating caption…'}if(!only||only==='tags'){generateTags.textContent='✨ Generating…';tagsStatus.textContent='Generating tags…'}const data=new FormData();data.append('file',file);try{const response=await fetch('{{ route('media.ai-suggestions') }}',{method:'POST',body:data,headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||'{{ csrf_token() }}','Accept':'application/json','X-Requested-With':'XMLHttpRequest'},credentials:'same-origin'});const payload=await response.json();if(token!==aiToken)return;if(!response.ok)throw new Error(payload.message||'AI suggestions could not be generated.');if((!only||only==='caption')&&payload.caption){caption.value=payload.caption;captionStatus.textContent='Suggestion added — review or edit it before uploading.';captionStatus.className='media-ai-status is-success'}if((!only||only==='tags')&&Array.isArray(payload.tags)){payload.tags.forEach(addTagValue);tagsStatus.textContent='Suggestions added — review or remove them before uploading.';tagsStatus.className='media-ai-status is-success'}}catch(e){if(token!==aiToken)return;if(!only||only==='caption'){captionStatus.textContent=`AI unavailable: ${e.message}`;captionStatus.className='media-ai-status is-error'}if(!only||only==='tags'){tagsStatus.textContent=`AI unavailable: ${e.message}`;tagsStatus.className='media-ai-status is-error'}}finally{if(token===aiToken){setAiEnabled(true);generateCaption.textContent='✨ Generate';generateTags.textContent='✨ Generate'}}}
        generateCaption.onclick=()=>requestAi(selectedImage,'caption');generateTags.onclick=()=>requestAi(selectedImage,'tags');
        function openCompression(file){originalSize.textContent=bytes(file.size);estimatedSize.textContent=bytes(Math.max(250*1024,Math.round(file.size*.35)));modal.hidden=false;modal.setAttribute('aria-hidden','false');accept.focus()}
        function closeCompression(){modal.hidden=true;modal.setAttribute('aria-hidden','true')}
        function compress(file){return new Promise((resolve,reject)=>{const img=new Image(),url=URL.createObjectURL(file);img.onload=()=>{URL.revokeObjectURL(url);const max=2400,scale=Math.min(1,max/Math.max(img.naturalWidth,img.naturalHeight)),w=Math.max(1,Math.round(img.naturalWidth*scale)),h=Math.max(1,Math.round(img.naturalHeight*scale)),canvas=document.createElement('canvas');canvas.width=w;canvas.height=h;const ctx=canvas.getContext('2d');if(!ctx)return reject(new Error('Browser compression is unavailable.'));ctx.drawImage(img,0,0,w,h);canvas.toBlob(b=>b?resolve(b):reject(new Error('Image compression failed.')),'image/jpeg',.84)};img.onerror=()=>{URL.revokeObjectURL(url);reject(new Error('The image could not be read.'))};img.src=url})}
        async function prepare(file){clearError();if(!file){setAiEnabled(false);return}const t=type(file);if(!t){error('This file type is not supported.');fileInput.value='';return}if(file.size>limits[t]){error(`${t.charAt(0).toUpperCase()+t.slice(1)} files must be ${bytes(limits[t])} or smaller.`);fileInput.value='';setAiEnabled(false);return}selectedImage=t==='image'?file:null;setAiEnabled(t==='image');captionStatus.textContent='';tagsStatus.textContent='';captionStatus.className='media-ai-status';tagsStatus.className='media-ai-status';if(t==='image'){help.textContent=file.size>5*1024*1024?'Large image selected. Compression confirmation is required before upload.':'Image selected. AI suggestions can be generated or refreshed before upload.';if(file.size>5*1024*1024){openCompression(file)}else{requestAi(file)}}else{help.textContent=t==='video'?'Video selected. AI suggestions are available for images only.':'Document selected. AI suggestions are available for images only.'}}
        fileInput.onchange=()=>prepare(fileInput.files[0]);
        reject.onclick=()=>{closeCompression();selectedImage=null;fileInput.value='';setAiEnabled(false);error('The image was not uploaded because compression was rejected.')};
        accept.onclick=async()=>{const file=fileInput.files[0];if(!file)return;accept.disabled=true;reject.disabled=true;try{const blob=await compress(file);const compressed=new File([blob],`${file.name.replace(/\.[^.]+$/,'')}-compressed.jpg`,{type:'image/jpeg'});const dt=new DataTransfer();dt.items.add(compressed);fileInput.files=dt.files;selectedImage=compressed;closeCompression();help.textContent='Image compressed and ready. AI suggestions can be edited before upload.';requestAi(compressed)}catch(e){closeCompression();error(e.message)}finally{accept.disabled=false;reject.disabled=false}};
        form.onsubmit=()=>{uploadButton.disabled=true;uploadButton.textContent='Uploading…'};
    });
    </script>
</x-app-layout>
