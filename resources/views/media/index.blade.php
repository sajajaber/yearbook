<x-app-layout>
  <x-slot name="header">
    <div class="dashboard-heading media-heading">
      <div>
        <p class="eyebrow">Yearbook office / assets</p>
        <h1>Media library</h1>
        <p class="media-count">{{ $totalMedia }} {{ Str::plural('item', $totalMedia) }}</p>
      </div>
      @if (in_array(Auth::user()->role?->role_name, ['admin', 'editor']))
      <a href="{{ route('media.create') }}" class="button button-red" aria-label="Upload new media file"><span aria-hidden="true">+</span> Upload media</a>
      @endif
    </div>
  </x-slot>

  <div class="dashboard-wrap media-wrap">
    <style>
      .media-empty-state {
        min-height: 360px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 56px 24px;
        border: 1px dashed var(--line);
        border-radius: 24px;
        background: linear-gradient(180deg, var(--white) 0%, var(--paper) 100%);
        text-align: center;
      }

      .media-empty-icon {
        width: 72px;
        height: 72px;
        display: grid;
        place-items: center;
        margin-bottom: 8px;
        border-radius: 20px;
        background: #eaf2fb;
        color: var(--ink);
      }

      .media-empty-state h3 {
        margin: 0;
        color: var(--ink);
        font-size: 1.25rem;
        font-weight: 800;
      }

      .media-empty-state p {
        max-width: 430px;
        margin: 0 0 12px;
        color: var(--ink-soft);
        line-height: 1.7;
      }

      .media-preview {
        overflow: hidden;
        aspect-ratio: 4 / 3;
      }

      .media-preview .media-img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        transition: transform .45s cubic-bezier(.16, 1, .3, 1), opacity .3s ease;
      }

      .media-card:hover .media-preview .media-img {
        transform: scale(1.035);
      }

      .media-event-current {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-top: 8px;
      }

      .media-event-tag {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 999px;
        background: #eaf2fb;
        color: var(--ink);
        font-size: .68rem;
        font-weight: 700;
      }

      .media-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 14px;
        border-top: 1px solid var(--line);
        padding-top: 14px;
      }

      .media-actions form {
        display: contents;
      }

      .button.button-small {
        padding: 7px 12px;
        font-size: 9px;
      }

      .action-button.action-button-small {
        padding: 6px 10px;
        font-size: 8.5px;
      }

      .media-actions .action-button {
        border-color: #f3c6c6;
        color: #b3261e;
      }

      .media-actions .action-button:hover {
        background: #fdf0f0;
        border-color: #e39a9a;
        color: #8c1d17;
      }

      /* Toolbar: search, filter pills, sort */

      .media-controls {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
      }

      .media-search-form {
        position: relative;
      }

      .media-search-form input[type="search"] {
        width: 220px;
        max-width: 100%;
        box-sizing: border-box;
        border: 1px solid var(--line);
        border-radius: 999px;
        padding: 9px 16px 9px 34px;
        background: var(--white) url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2364748b%22 stroke-width=%222%22%3E%3Ccircle cx=%2211%22 cy=%2211%22 r=%227%22/%3E%3Cpath d=%22m21 21-4.35-4.35%22/%3E%3C/svg%3E') no-repeat 12px center;
        background-size: 14px;
        color: var(--ink);
        font-size: .82rem;
        transition: border-color .15s ease, box-shadow .15s ease;
      }

      .media-search-form input[type="search"]:focus {
        outline: none;
        border-color: var(--red);
        box-shadow: 0 0 0 3px rgba(255, 176, 52, .22);
      }

      .media-sort-form select {
        border: 1px solid var(--line);
        border-radius: 999px;
        padding: 9px 30px 9px 14px;
        background: var(--white);
        color: var(--ink);
        font-size: .78rem;
        font-weight: 600;
        cursor: pointer;
        appearance: none;
        background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2364748b%22 stroke-width=%222%22 stroke-linecap=%22round%22%3E%3Cpath d=%22m6 9 6 6 6-6%22/%3E%3C/svg%3E');
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 12px;
      }

      .media-sort-form select:focus {
        outline: none;
        border-color: var(--red);
        box-shadow: 0 0 0 3px rgba(255, 176, 52, .22);
      }

      @media (max-width: 600px) {
        .media-controls {
          flex-direction: column;
          align-items: stretch;
        }

        .media-search-form input[type="search"] {
          width: 100%;
        }
      }

      /* Edit / assign-to-event modals */

      .media-modal-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding: 24px 28px 20px;
        background: var(--white);
        border-bottom: 1px solid var(--line);
      }

      .media-modal-header h3 {
        margin: 0;
        color: var(--ink);
        font-size: 1.1rem;
        font-weight: 800;
      }

      .media-modal-header p {
        margin: 4px 0 0;
        color: var(--ink-soft);
        font-size: .82rem;
      }

      .media-modal-close {
        flex-shrink: 0;
        display: grid;
        place-items: center;
        width: 30px;
        height: 30px;
        border: none;
        border-radius: 999px;
        background: var(--paper);
        color: var(--ink-soft);
        cursor: pointer;
        transition: background .15s ease, color .15s ease;
      }

      .media-modal-close:hover {
        background: var(--line);
        color: var(--ink);
      }

      .media-modal-body {
        padding: 24px 28px 28px;
        background: var(--white);
        color: var(--ink);
      }

      .media-edit-field {
        margin-bottom: 18px;
      }

      .media-edit-field label {
        display: block;
        margin-bottom: 8px;
        color: var(--ink);
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .06em;
        text-transform: uppercase;
      }

      .media-edit-field input,
      .media-edit-field textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid var(--line);
        border-radius: 11px;
        padding: 10px 12px;
        color: var(--ink);
        background: var(--white);
        font: inherit;
        font-size: .88rem;
        transition: border-color .15s ease, box-shadow .15s ease;
      }

      .media-edit-field textarea {
        resize: vertical;
      }

      .media-edit-field input:focus,
      .media-edit-field textarea:focus {
        outline: none;
        border-color: var(--red);
        box-shadow: 0 0 0 3px rgba(255, 176, 52, .22);
      }

      .media-event-checklist {
        max-height: 260px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 4px;
        padding-right: 4px;
      }

      .media-event-option {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 10px;
        border-radius: 9px;
        border: 1px solid transparent;
        font-size: .85rem;
        color: var(--ink);
        transition: background .12s ease, border-color .12s ease;
      }

      .media-event-option:hover {
        background: #eaf2fb;
      }

      .media-event-option:has(input:checked) {
        background: var(--paper);
        border-color: var(--line);
        font-weight: 600;
      }

      .media-event-option input {
        accent-color: var(--red);
      }

      .media-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 22px;
        padding-top: 18px;
        border-top: 1px solid var(--line);
      }
    </style>

    @if ($errors->any())
    <div class="alert alert-error" role="alert">
      <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif
    @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="alert alert-error" role="alert">{{ session('error') }}</div>@endif

    <div class="media-toolbar">
      <div>
        <p class="eyebrow">Organized archive</p>
        <h2>Every image, ready when you need it.</h2>
      </div>
      <div class="media-controls">
        <form method="GET" action="{{ route('media.index') }}" class="media-search-form" role="search">
          <input type="search" name="search" value="{{ $search }}" placeholder="Search media" aria-label="Search media">
          <input type="hidden" name="filter" value="{{ $filter }}">
          <input type="hidden" name="sort" value="{{ $sortBy }}">
        </form>
        <div class="media-filters">
          <a href="{{ route('media.index', array_merge(request()->query(), ['filter' => 'all', 'page' => 1])) }}" class="filter-button {{ $filter === 'all' ? 'is-selected' : '' }}" aria-current="{{ $filter === 'all' ? 'page' : 'false' }}">All</a>
          <a href="{{ route('media.index', array_merge(request()->query(), ['filter' => 'graduate-portraits', 'page' => 1])) }}" class="filter-button {{ $filter === 'graduate-portraits' ? 'is-selected' : '' }}" aria-current="{{ $filter === 'graduate-portraits' ? 'page' : 'false' }}">Graduate profile photos</a>
        </div>
        <form method="GET" action="{{ route('media.index') }}" class="media-sort-form">
          <input type="hidden" name="filter" value="{{ $filter }}"><input type="hidden" name="search" value="{{ $search }}">
          <select name="sort" onchange="this.form.submit()" aria-label="Sort media">
            <option value="latest" @selected($sortBy==='latest' )>Newest</option>
            <option value="oldest" @selected($sortBy==='oldest' )>Oldest</option>
            <option value="name" @selected($sortBy==='name' )>Name</option>
          </select>
        </form>
      </div>
    </div>

    @if ($mediaItems->isEmpty())
    <div class="media-empty-state">
      <div class="media-empty-icon" aria-hidden="true">
        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
          <rect x="3" y="3" width="18" height="18" rx="3" />
          <circle cx="8.5" cy="8.5" r="1.5" />
          <path d="m21 15-4.5-4.5L8 19" />
        </svg>
      </div>
      <h3>No media found</h3>
      <p>There are no media assets matching the current filter or search. Upload a photo to start building the yearbook archive.</p>
      @if (in_array(Auth::user()->role?->role_name, ['admin', 'editor']))
      <a href="{{ route('media.create') }}" class="button button-red button-small"><span aria-hidden="true">+</span> Upload media</a>
      @endif
    </div>
    @else
    <div class="media-grid" role="list">
      @foreach ($mediaItems as $mediaItem)
      <div class="media-card" role="listitem">
        <div class="media-preview" aria-label="Preview of {{ $mediaItem->file_name }}">
          @if ($mediaItem->type === 'image')
          <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 200'%3E%3Crect fill='%23e7f0fa' width='300' height='200'/%3E%3C/svg%3E" data-src="{{ $mediaItem->thumbnailUrl() }}" alt="{{ $mediaItem->alt_text ?? $mediaItem->file_name }}" class="media-img lazy" loading="lazy">
          @elseif ($mediaItem->type === 'video')
          <div class="media-video-placeholder"><svg class="video-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M8 5v14l11-7z" />
            </svg>
            <p>Video</p>
          </div><video controls class="media-video" style="display: none;" preload="metadata">
            <source src="{{ asset('storage/' . $mediaItem->path) }}">{{ __('Your browser does not support video playback.') }}
          </video>
          @elseif ($mediaItem->type === 'document')
          <a href="{{ asset('storage/' . $mediaItem->path) }}" target="_blank" rel="noopener noreferrer" class="media-document-link" aria-label="Open document: {{ $mediaItem->file_name }}"><svg class="document-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-8-6z" />
            </svg>
            <p>{{ strtoupper(pathinfo($mediaItem->file_name, PATHINFO_EXTENSION)) }}</p>
          </a>
          @endif
        </div>
        <div class="media-card-body">
          <p class="media-file-name" title="{{ $mediaItem->file_name }}">{{ $mediaItem->file_name }}</p>
          @if ($mediaItem->caption)<p class="media-caption">{{ $mediaItem->caption }}</p>@endif
          <div class="media-metadata">
            @if ($mediaItem->credit)<span class="media-meta"><span class="meta-label">Credit:</span> {{ $mediaItem->credit }}</span>@endif
            <span class="media-meta"><span class="meta-label">Uploaded:</span> {{ $mediaItem->created_at->format('M d, Y') }}</span>
            @php $fileSize = $mediaItem->type === 'document' ? null : \Storage::disk('public')->size($mediaItem->path); @endphp
            @if ($fileSize)<span class="media-meta"><span class="meta-label">Size:</span> {{ number_format($fileSize / 1024, 0) }}KB</span>@endif
            @if ($mediaItem->uploader)<span class="media-meta"><span class="meta-label">By:</span> {{ $mediaItem->uploader->name }}</span>@endif
          </div>

          @if ($mediaItem->events->isNotEmpty())
          <div class="media-event-current" aria-label="Events using this media">
            @foreach ($mediaItem->events as $event)
            <span class="media-event-tag">{{ $event->title }}</span>
            @endforeach
          </div>
          @endif

          @if ($mediaItem->portraitGraduates->isNotEmpty())
          <div class="portrait-links" role="group" aria-label="Associated graduates"><span class="portrait-links-title">Graduate profile</span>@foreach ($mediaItem->portraitGraduates as $graduate)<a href="{{ route('graduates.edit', $graduate) }}" class="portrait-link">{{ $graduate->name }} <span aria-hidden="true">→</span></a>@endforeach</div>
          @endif

          @if (in_array(Auth::user()->role?->role_name, ['admin', 'editor']))
          <div class="media-actions" role="group" aria-label="Media actions">
            <button type="button" class="button button-navy button-small" x-data x-on:click="$dispatch('open-modal', 'edit-media-{{ $mediaItem->id }}')">Edit</button>
            <button type="button" class="button button-navy button-small" x-data x-on:click="$dispatch('open-modal', 'assign-media-{{ $mediaItem->id }}')">Assign to event</button>
            <form action="{{ route('media.destroy', $mediaItem->id) }}" method="POST" class="media-delete-form" onsubmit="return confirm('Are you sure you want to delete this media?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="action-button action-button-small">Delete</button>
            </form>
          </div>

          <x-modal name="edit-media-{{ $mediaItem->id }}" maxWidth="md">
            <div class="media-modal-header">
              <div>
                <h3>Edit media details</h3>
                <p>{{ $mediaItem->file_name }}</p>
              </div>
              <button type="button" class="media-modal-close" x-on:click="$dispatch('close-modal', 'edit-media-{{ $mediaItem->id }}')" aria-label="Close">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                  <path d="M6 6l12 12M18 6L6 18" />
                </svg>
              </button>
            </div>
            <div class="media-modal-body">
              <form action="{{ route('media.update', $mediaItem->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="media-edit-field">
                  <label for="caption-{{ $mediaItem->id }}">Caption</label>
                  <textarea id="caption-{{ $mediaItem->id }}" name="caption" rows="3" maxlength="255" placeholder="Enter a caption for this media">{{ old('caption', $mediaItem->caption) }}</textarea>
                </div>
                <div class="media-edit-field">
                  <label for="alt_text-{{ $mediaItem->id }}">Alternative text</label>
                  <textarea id="alt_text-{{ $mediaItem->id }}" name="alt_text" rows="3" maxlength="255" placeholder="Describe the image for accessibility and SEO">{{ old('alt_text', $mediaItem->alt_text) }}</textarea>
                </div>
                <div class="media-edit-field">
                  <label for="credit-{{ $mediaItem->id }}">Credit</label>
                  <input type="text" id="credit-{{ $mediaItem->id }}" name="credit" value="{{ old('credit', $mediaItem->credit) }}" maxlength="255" placeholder="Photographer, artist, or source">
                </div>
                <div class="media-modal-actions">
                  <button type="button" class="button button-muted button-small" x-on:click="$dispatch('close-modal', 'edit-media-{{ $mediaItem->id }}')">Cancel</button>
                  <button type="submit" class="button button-navy button-small">Save changes</button>
                </div>
              </form>
            </div>
          </x-modal>

          <x-modal name="assign-media-{{ $mediaItem->id }}" maxWidth="md">
            <div class="media-modal-header">
              <div>
                <h3>Assign to events</h3>
                <p>{{ $mediaItem->file_name }}</p>
              </div>
              <button type="button" class="media-modal-close" x-on:click="$dispatch('close-modal', 'assign-media-{{ $mediaItem->id }}')" aria-label="Close">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                  <path d="M6 6l12 12M18 6L6 18" />
                </svg>
              </button>
            </div>
            <div class="media-modal-body">
              <form action="{{ route('media.update', $mediaItem->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="media-event-checklist" role="group" aria-label="Events assigned to {{ $mediaItem->file_name }}">
                  @forelse ($events as $event)
                  <label class="media-event-option">
                    <input type="checkbox" name="event_ids[]" value="{{ $event->id }}" @checked($mediaItem->events->contains('id', $event->id))>
                    <span>{{ $event->title }}{{ $event->event_date ? ' · ' . \Carbon\Carbon::parse($event->event_date)->format('M d, Y') : '' }}</span>
                  </label>
                  @empty
                  <p class="media-meta">No events have been created yet.</p>
                  @endforelse
                </div>
                <div class="media-modal-actions">
                  <button type="button" class="button button-muted button-small" x-on:click="$dispatch('close-modal', 'assign-media-{{ $mediaItem->id }}')">Cancel</button>
                  <button type="submit" class="button button-navy button-small">Save events</button>
                </div>
              </form>
            </div>
          </x-modal>
          @endif
        </div>
      </div>
      @endforeach
    </div>

    @if ($mediaItems->hasPages())
    <div class="liu-pagination" aria-label="Media pagination">
      <div class="liu-pagination-summary">Showing <strong>{{ $mediaItems->firstItem() }}</strong>–<strong>{{ $mediaItems->lastItem() }}</strong> of <strong>{{ $mediaItems->total() }}</strong></div>
      <div class="liu-pagination-controls">
        @if ($mediaItems->onFirstPage())<span class="liu-page-arrow is-disabled" aria-disabled="true">←</span>@else<a class="liu-page-arrow" href="{{ $mediaItems->previousPageUrl() }}" rel="prev" aria-label="Previous page">←</a>@endif
        @foreach ($mediaItems->getUrlRange(max(1, $mediaItems->currentPage() - 1), min($mediaItems->lastPage(), $mediaItems->currentPage() + 1)) as $page => $url)<a href="{{ $url }}" class="liu-page-number {{ $page == $mediaItems->currentPage() ? 'is-current' : '' }}" aria-current="{{ $page == $mediaItems->currentPage() ? 'page' : 'false' }}">{{ $page }}</a>@endforeach
        @if ($mediaItems->hasMorePages())<a class="liu-page-arrow" href="{{ $mediaItems->nextPageUrl() }}" rel="next" aria-label="Next page">→</a>@else<span class="liu-page-arrow is-disabled" aria-disabled="true">→</span>@endif
      </div>
    </div>
    @endif
    @endif
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const images = document.querySelectorAll('img.lazy');
      if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              const img = entry.target;
              img.src = img.dataset.src;
              img.classList.remove('lazy');
              img.classList.add('loaded');
              observer.unobserve(img);
            }
          });
        });
        images.forEach(img => imageObserver.observe(img));
      } else {
        images.forEach(img => {
          img.src = img.dataset.src;
          img.classList.add('loaded');
        });
      }
    });
    document.querySelectorAll('.media-video-placeholder').forEach(placeholder => {
      placeholder.addEventListener('click', function() {
        const video = this.parentElement.querySelector('.media-video');
        if (video) {
          this.style.display = 'none';
          video.style.display = 'block';
          video.play().catch(() => {});
        }
      });
    });
  </script>
</x-app-layout>