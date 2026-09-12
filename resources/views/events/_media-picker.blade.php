@php
    $selectedMediaIds = collect($selectedMediaIds ?? [])->map(fn ($id) => (int) $id)->all();
@endphp

<section class="form-section event-media-section">
    <div class="form-section-heading">
        <p class="eyebrow">Event media</p>
        <h2>Build the visual story.</h2>
        <p class="upload-note">Select the photos and videos that belong to this event. The same media can be reused across multiple events.</p>
    </div>

    @if ($media->isEmpty())
        <div class="event-media-empty">
            <strong>No media is available yet.</strong>
            <span>Upload media from the Media Library first, then return here to attach it.</span>
            <a href="{{ route('media.create') }}" class="text-link">Upload media <span aria-hidden="true">→</span></a>
        </div>
    @else
        <div class="event-media-toolbar">
            <label class="form-field event-media-search">
                <span>Find media</span>
                <input type="search" id="event-media-search" placeholder="Search by file name, caption, or credit" autocomplete="off">
            </label>
            <span class="event-media-count" id="event-media-count">{{ count($selectedMediaIds) }} selected</span>
        </div>

        <div class="event-media-grid" id="event-media-grid">
            @foreach ($media as $mediaItem)
                @php
                    $isSelected = in_array((int) $mediaItem->id, $selectedMediaIds, true);
                    $searchText = strtolower(trim(($mediaItem->file_name ?? '') . ' ' . ($mediaItem->caption ?? '') . ' ' . ($mediaItem->credit ?? '')));
                @endphp
                <label class="event-media-choice {{ $isSelected ? 'is-selected' : '' }}" data-media-search="{{ $searchText }}">
                    <input type="checkbox" name="media_ids[]" value="{{ $mediaItem->id }}" @checked($isSelected)>
                    <span class="event-media-thumb">
                        @if ($mediaItem->type === 'image')
                            <img src="{{ $mediaItem->thumbnailUrl() }}" alt="{{ $mediaItem->alt_text ?? $mediaItem->file_name }}" loading="lazy">
                        @else
                            <span class="event-media-video-icon" aria-hidden="true">▶</span>
                        @endif
                    </span>
                    <span class="event-media-info">
                        <strong title="{{ $mediaItem->file_name }}">{{ $mediaItem->file_name }}</strong>
                        <small>{{ ucfirst($mediaItem->type) }}@if($mediaItem->caption) · {{ $mediaItem->caption }}@endif</small>
                    </span>
                    <span class="event-media-check" aria-hidden="true">✓</span>
                </label>
            @endforeach
        </div>

        <p class="event-media-no-results" id="event-media-no-results" hidden>No media matches your search.</p>
    @endif
</section>

@once
<style>
    .event-media-section { margin-top: 22px; }
    .event-media-toolbar { display:flex; align-items:flex-end; justify-content:space-between; gap:18px; margin:18px 0; }
    .event-media-search { flex:1; max-width:620px; }
    .event-media-count { padding:10px 13px; border:1px solid #d8e3ef; border-radius:999px; background:#f7fbff; color:#002a5c; font-size:.78rem; font-weight:800; white-space:nowrap; }
    .event-media-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:13px; }
    .event-media-choice { position:relative; display:flex; align-items:center; gap:11px; min-width:0; padding:10px; border:1px solid #d8e3ef; border-radius:15px; background:#fff; cursor:pointer; transition:transform .22s ease, border-color .22s ease, box-shadow .22s ease, background .22s ease; }
    .event-media-choice:hover { transform:translateY(-2px); border-color:#9fb5cc; box-shadow:0 8px 22px rgba(0,42,92,.08); }
    .event-media-choice.is-selected { border-color:#ffb034; background:#fffaf0; box-shadow:0 0 0 2px rgba(255,176,52,.12); }
    .event-media-choice input { position:absolute; opacity:0; pointer-events:none; }
    .event-media-thumb { flex:0 0 58px; width:58px; height:58px; display:grid; place-items:center; overflow:hidden; border-radius:10px; background:#eaf2fb; color:#002a5c; }
    .event-media-thumb img { width:100%; height:100%; object-fit:cover; display:block; }
    .event-media-video-icon { font-size:1.05rem; }
    .event-media-info { min-width:0; display:flex; flex:1; flex-direction:column; gap:4px; }
    .event-media-info strong { overflow:hidden; color:#002a5c; font-size:.78rem; line-height:1.35; text-overflow:ellipsis; white-space:nowrap; }
    .event-media-info small { overflow:hidden; color:#64748b; font-size:.68rem; line-height:1.35; text-overflow:ellipsis; white-space:nowrap; }
    .event-media-check { flex:0 0 25px; width:25px; height:25px; display:grid; place-items:center; border:1px solid #c8d6e5; border-radius:50%; color:transparent; font-size:.7rem; font-weight:900; transition:.2s ease; }
    .event-media-choice.is-selected .event-media-check { border-color:#ffb034; background:#ffb034; color:#002a5c; }
    .event-media-empty { display:flex; flex-direction:column; gap:6px; padding:25px; border:1px dashed #cbd8e6; border-radius:16px; background:#f7fbff; color:#64748b; }
    .event-media-empty strong { color:#002a5c; }
    .event-media-no-results { margin:15px 0 0; color:#64748b; font-size:.9rem; }
    @media (max-width:900px) { .event-media-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media (max-width:600px) { .event-media-toolbar { align-items:stretch; flex-direction:column; } .event-media-search { max-width:none; } .event-media-grid { grid-template-columns:1fr; } }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const grid = document.getElementById('event-media-grid');
        const search = document.getElementById('event-media-search');
        const count = document.getElementById('event-media-count');
        const noResults = document.getElementById('event-media-no-results');
        if (!grid) return;

        const choices = Array.from(grid.querySelectorAll('.event-media-choice'));
        const update = function () {
            const term = (search?.value || '').trim().toLowerCase();
            let visible = 0;
            choices.forEach(function (choice) {
                const matches = !term || (choice.dataset.mediaSearch || '').includes(term);
                choice.hidden = !matches;
                if (matches) visible++;
                const input = choice.querySelector('input');
                choice.classList.toggle('is-selected', !!input?.checked);
            });
            const selected = choices.filter(choice => choice.querySelector('input')?.checked).length;
            if (count) count.textContent = selected + (selected === 1 ? ' selected' : ' selected');
            if (noResults) noResults.hidden = visible !== 0;
        };

        choices.forEach(function (choice) {
            choice.addEventListener('click', function (event) {
                if (event.target.tagName.toLowerCase() !== 'input') {
                    const input = choice.querySelector('input');
                    input.checked = !input.checked;
                }
                update();
            });
        });
        search?.addEventListener('input', update);
        update();
    });
</script>
@endonce
