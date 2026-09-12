<x-app-layout>
    <x-slot name="header">
        <div class="dashboard-heading media-heading">
            <div>
                <p class="eyebrow">Yearbook office / assets</p>
                <h1>Edit media</h1>
            </div>
            <a href="{{ route('media.index') }}" class="text-link">Back to media <span aria-hidden="true">←</span></a>
        </div>
    </x-slot>

    <div class="dashboard-wrap" style="max-width: 980px; margin: 0 auto;">
        <style>
            .media-edit-grid { display:grid; grid-template-columns:minmax(0,1fr) 340px; gap:24px; align-items:start; }
            .media-edit-card { padding:28px; border:1px solid #d8e3ef; border-radius:22px; background:#fff; box-shadow:0 10px 35px rgba(0,42,92,.06); }
            .media-edit-preview { min-height:360px; display:grid; place-items:center; overflow:hidden; border-radius:16px; background:#f1f6fb; }
            .media-edit-preview img { max-width:100%; max-height:520px; object-fit:contain; }
            .media-edit-preview video { width:100%; max-height:520px; }
            .media-edit-field { margin-bottom:20px; }
            .media-edit-field label, .media-edit-assignment-title { display:block; margin-bottom:8px; color:#002a5c; font-size:.78rem; font-weight:800; letter-spacing:.06em; text-transform:uppercase; }
            .media-edit-field input, .media-edit-field textarea, .media-edit-assignment select { width:100%; box-sizing:border-box; border:1px solid #cbd8e6; border-radius:11px; padding:11px 12px; color:#002a5c; background:#fff; }
            .media-edit-field textarea { resize:vertical; }
            .media-edit-field input:focus, .media-edit-field textarea:focus, .media-edit-assignment select:focus { outline:3px solid rgba(255,176,52,.18); border-color:#ffb034; }
            .media-edit-assignment { margin-top:24px; padding:20px; border:1px solid #d8e3ef; border-radius:16px; background:#f7fbff; }
            .media-edit-assignment select { min-height:190px; }
            .media-edit-assignment-note { margin:8px 0 0; color:#64748b; font-size:.78rem; line-height:1.5; }
            .media-edit-current { display:flex; flex-wrap:wrap; gap:6px; margin-top:12px; }
            .media-edit-tag { padding:5px 9px; border-radius:999px; background:#eaf2fb; color:#002a5c; font-size:.7rem; font-weight:700; }
            .media-edit-actions { display:flex; gap:10px; margin-top:24px; }
            .media-edit-actions > * { flex:1; }
            @media (max-width: 780px) { .media-edit-grid { grid-template-columns:1fr; } }
            @media (max-width: 520px) { .media-edit-actions { flex-direction:column; } }
        </style>

        @if ($errors->any())
            <div class="alert alert-error" role="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <div class="media-edit-grid">
            <section class="media-edit-card">
                <div class="media-edit-preview">
                    @if ($mediaItem->type === 'image')
                        <img src="{{ $mediaItem->thumbnailUrl() }}" alt="{{ $mediaItem->alt_text ?? $mediaItem->file_name }}">
                    @elseif ($mediaItem->type === 'video')
                        <video controls preload="metadata"><source src="{{ asset('storage/' . $mediaItem->path) }}">{{ __('Your browser does not support video playback.') }}</video>
                    @else
                        <a href="{{ asset('storage/' . $mediaItem->path) }}" target="_blank" rel="noopener noreferrer" class="text-link">Open {{ $mediaItem->file_name }} <span aria-hidden="true">↗</span></a>
                    @endif
                </div>
            </section>

            <section class="media-edit-card">
                <form action="{{ route('media.update', $mediaItem->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="media-edit-field">
                        <label for="caption">Caption</label>
                        <textarea id="caption" name="caption" rows="3" maxlength="255" placeholder="Enter a caption for this media">{{ old('caption', $mediaItem->caption) }}</textarea>
                        @error('caption')<small>{{ $message }}</small>@enderror
                    </div>

                    <div class="media-edit-field">
                        <label for="alt_text">Alternative text</label>
                        <textarea id="alt_text" name="alt_text" rows="3" maxlength="255" placeholder="Describe the image for accessibility and SEO">{{ old('alt_text', $mediaItem->alt_text) }}</textarea>
                        @error('alt_text')<small>{{ $message }}</small>@enderror
                    </div>

                    <div class="media-edit-field">
                        <label for="credit">Credit</label>
                        <input type="text" id="credit" name="credit" value="{{ old('credit', $mediaItem->credit) }}" maxlength="255" placeholder="Photographer, artist, or source">
                        @error('credit')<small>{{ $message }}</small>@enderror
                    </div>

                    <div class="media-edit-assignment">
                        <span class="media-edit-assignment-title">Assign this media to events</span>
                        <select name="event_ids[]" multiple aria-label="Events assigned to {{ $mediaItem->file_name }}">
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}" @selected($mediaItem->events->contains('id', $event->id))>
                                    {{ $event->title }}{{ $event->event_date ? ' · ' . \Carbon\Carbon::parse($event->event_date)->format('M d, Y') : '' }}
                                </option>
                            @endforeach
                        </select>
                        <p class="media-edit-assignment-note">Select one or more events. Hold Ctrl on Windows or Cmd on Mac for multiple selections. Saving with nothing selected removes all event assignments.</p>
                        @if ($mediaItem->events->isNotEmpty())
                            <div class="media-edit-current" aria-label="Currently assigned events">
                                @foreach ($mediaItem->events as $event)
                                    <span class="media-edit-tag">{{ $event->title }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="media-edit-actions">
                        <a href="{{ route('media.index') }}" class="button button-muted">Cancel</a>
                        <button type="submit" class="button button-navy">Save changes <span aria-hidden="true">→</span></button>
                    </div>
                </form>

                <form action="{{ route('media.destroy', $mediaItem->id) }}" method="POST" style="margin-top:10px;" onsubmit="return confirm('{{ __('Are you sure you want to delete this media?') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-button action-button-small" style="width:100%;">{{ __('Delete media') }}</button>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>
