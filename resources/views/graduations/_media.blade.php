<section class="form-section graduation-media-section">
    <div class="form-section-heading">
        <p class="eyebrow">Media</p>
        <h2>Build the ceremony gallery.</h2>
        <p class="upload-note">Select the photos, videos, or documents that belong to this graduation ceremony.</p>
    </div>

    @php
        $selectedMedia = collect(old('media_ids', $selectedMediaIds ?? []))->map(fn ($id) => (string) $id)->all();
    @endphp

    @if ($mediaItems->isEmpty())
        <div class="graduation-media-empty">
            <strong>No media has been uploaded yet.</strong>
            <span>Upload assets from the Media Library first, then return here to attach them to this graduation ceremony.</span>
            <a href="{{ route('media.create') }}" class="text-link">Open Media Library <span aria-hidden="true">→</span></a>
        </div>
    @else
        <div class="graduation-media-picker">
            @foreach ($mediaItems as $mediaItem)
                <label class="graduation-media-option">
                    <input type="checkbox" name="media_ids[]" value="{{ $mediaItem->id }}" @checked(in_array((string) $mediaItem->id, $selectedMedia, true))>
                    <span class="graduation-media-thumb">
                        @if ($mediaItem->type === 'image')
                            <img src="{{ $mediaItem->thumbnailUrl() }}" alt="" loading="lazy">
                        @elseif ($mediaItem->type === 'video')
                            <span class="graduation-media-type-icon" aria-hidden="true">▶</span>
                        @else
                            <span class="graduation-media-type-icon" aria-hidden="true">DOC</span>
                        @endif
                    </span>
                    <span class="graduation-media-copy">
                        <strong title="{{ $mediaItem->file_name }}">{{ $mediaItem->file_name }}</strong>
                        <small>{{ ucfirst($mediaItem->type) }} · {{ $mediaItem->created_at->format('M d, Y') }}</small>
                    </span>
                    <span class="graduation-media-check" aria-hidden="true">✓</span>
                </label>
            @endforeach
        </div>
    @endif
</section>

<style>
    .graduation-media-section { margin-top: 24px; }
    .graduation-media-picker { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; max-height: 520px; overflow-y: auto; padding: 2px; }
    .graduation-media-option { position: relative; display: grid; grid-template-columns: 72px minmax(0, 1fr) 26px; align-items: center; gap: 13px; min-width: 0; padding: 10px; border: 1px solid #d8e3ef; border-radius: 16px; background: #fff; cursor: pointer; transition: transform .25s ease, border-color .25s ease, box-shadow .25s ease, background .25s ease; }
    .graduation-media-option:hover { transform: translateY(-2px); border-color: #a9bfd6; box-shadow: 0 10px 24px rgba(0, 42, 92, .08); }
    .graduation-media-option:has(input:checked) { border-color: #ffb034; background: #fffaf0; box-shadow: 0 8px 22px rgba(255, 176, 52, .12); }
    .graduation-media-option input { position: absolute; width: 1px; height: 1px; opacity: 0; pointer-events: none; }
    .graduation-media-thumb { width: 72px; height: 58px; display: grid; place-items: center; overflow: hidden; border-radius: 11px; background: #eaf2fb; color: #002a5c; font-size: .65rem; font-weight: 800; }
    .graduation-media-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .graduation-media-type-icon { display: grid; place-items: center; width: 34px; height: 34px; border-radius: 10px; background: rgba(0, 42, 92, .08); }
    .graduation-media-copy { min-width: 0; display: flex; flex-direction: column; gap: 4px; }
    .graduation-media-copy strong { overflow: hidden; color: #002a5c; font-size: .84rem; line-height: 1.35; text-overflow: ellipsis; white-space: nowrap; }
    .graduation-media-copy small { color: #64748b; font-size: .72rem; }
    .graduation-media-check { display: grid; place-items: center; width: 25px; height: 25px; border: 1px solid #cbd8e6; border-radius: 50%; color: transparent; font-size: .72rem; font-weight: 900; transition: all .25s ease; }
    .graduation-media-option:has(input:checked) .graduation-media-check { border-color: #ffb034; background: #ffb034; color: #002a5c; }
    .graduation-media-empty { display: flex; flex-direction: column; gap: 7px; padding: 24px; border: 1px dashed #cbd8e6; border-radius: 16px; background: #f7fbff; }
    .graduation-media-empty strong { color: #002a5c; }
    .graduation-media-empty span { color: #64748b; line-height: 1.6; }
    @media (max-width: 760px) { .graduation-media-picker { grid-template-columns: 1fr; } }
</style>
