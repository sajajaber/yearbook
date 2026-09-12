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
        border: 1px dashed #cbd8e6;
        border-radius: 24px;
        background: linear-gradient(180deg, #fff 0%, #f7fbff 100%);
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
        color: #002a5c;
      }
      .media-empty-state h3 {
        margin: 0;
        color: #002a5c;
        font-size: 1.25rem;
        font-weight: 800;
      }
      .media-empty-state p {
        max-width: 430px;
        margin: 0 0 12px;
        color: #64748b;
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
        transition: transform .45s cubic-bezier(.16,1,.3,1), opacity .3s ease;
      }
      .media-card:hover .media-preview .media-img {
        transform: scale(1.035);
      }
      .media-event-assignment {
        margin-top: 16px;
        padding: 14px;
        border: 1px solid #d8e3ef;
        border-radius: 14px;
        background: #f7fbff;
      }
      .media-event-assignment-label {
        display: block;
        margin-bottom: 8px;
        color: #002a5c;
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .06em;
        text-transform: uppercase;
      }
      .media-event-assignment select {
        width: 100%;
        min-height: 82px;
        padding: 8px 10px;
        border: 1px solid #cbd8e6;
        border-radius: 10px;
        background: #fff;
        color: #002a5c;
        font-size: .82rem;
      }
      .media-event-assignment select:focus {
        outline: 3px solid rgba(255,176,52,.18);
        border-color: #ffb034;
      }
      .media-event-assignment-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 10px;
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
        color: #002a5c;
        font-size: .68rem;
        font-weight: 700;
      }
      @media (max-width: 520px) {
        .media-event-assignment-actions {
          align-items: stretch;
          flex-direction: column;
        }
      }
    </style>

    @if ($errors->any())
    <div class="alert alert-error" role="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="alert alert-error" role="alert">{{ session('error') }}</div>@endif

    <div class="media-toolbar">
      <div><p class="eyebrow">Organized archive</p><h2>Every image, ready when you need it.</h2></div>
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
          <select name="sort" onchange="this.form.submit()" aria-label="Sort media"><option value="latest" @selected($sortBy === 'latest')>Newest</option><option value="oldest" @selected($sortBy === 'oldest')>Oldest</option><option value="name" @selected($sortBy === 'name')>Name</option></select>
        </form>
      </div>
    </div>

    @if ($mediaItems->isEmpty())
    <div class="media-empty-state">
      <div class="media-empty-icon" aria-hidden="true">
        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-4.5-4.5L8 19"/></svg>
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
          <div class="media-video-placeholder"><svg class="video-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg><p>Video</p></div><video controls class="media-video" style="display: none;" preload="metadata"><source src="{{ asset('storage/' . $mediaItem->path) }}">{{ __('Your browser does not support video playback.') }}</video>
          @elseif ($mediaItem->type === 'document')
          <a href="{{ asset('storage/' . $mediaItem->path) }}" target="_blank" rel="noopener noreferrer" class="media-document-link" aria-label="Open document: {{ $mediaItem->file_name }}"><svg class="document-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-8-6z"/></svg><p>{{ strtoupper(pathinfo($mediaItem->file_name, PATHINFO_EXTENSION)) }}</p></a>
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

          @if (in_array(Auth::user()->role?->role_name, ['admin', 'editor']))
          <form action="{{ route('media.update', $mediaItem->id) }}" method="POST" class="media-event-assignment">
            @csrf
            @method('PUT')
            <span class="media-event-assignment-label">Assign to event</span>
            <select name="event_ids[]" multiple aria-label="Events assigned to {{ $mediaItem->file_name }}">
              @foreach ($events as $event)
                <option value="{{ $event->id }}" @selected($mediaItem->events->contains('id', $event->id))>
                  {{ $event->title }}{{ $event->event_date ? ' · ' . \Carbon\Carbon::parse($event->event_date)->format('M d, Y') : '' }}
                </option>
              @endforeach
            </select>
            <div class="media-event-assignment-actions">
              <span class="media-meta">Hold Ctrl/Cmd to select multiple.</span>
              <button type="submit" class="button button-navy button-small">Save events</button>
            </div>
          </form>
          @endif

          @if ($mediaItem->portraitGraduates->isNotEmpty())
          <div class="portrait-links" role="group" aria-label="Associated graduates"><span class="portrait-links-title">Graduate profile</span>@foreach ($mediaItem->portraitGraduates as $graduate)<a href="{{ route('graduates.edit', $graduate) }}" class="portrait-link">{{ $graduate->name }} <span aria-hidden="true">→</span></a>@endforeach</div>
          @endif
          @if (in_array(Auth::user()->role?->role_name, ['admin', 'editor']))
          <div class="media-actions" role="group" aria-label="Media actions"><a href="{{ route('media.edit', $mediaItem->id) }}" class="button button-navy button-small">Edit</a><form action="{{ route('media.destroy', $mediaItem->id) }}" method="POST" class="media-delete-form" onsubmit="return confirm('Are you sure you want to delete this media?');">@csrf @method('DELETE')<button type="submit" class="action-button action-button-small">Delete</button></form></div>
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
          entries.forEach(entry => { if (entry.isIntersecting) { const img = entry.target; img.src = img.dataset.src; img.classList.remove('lazy'); img.classList.add('loaded'); observer.unobserve(img); } });
        });
        images.forEach(img => imageObserver.observe(img));
      } else { images.forEach(img => { img.src = img.dataset.src; img.classList.add('loaded'); }); }
    });
    document.querySelectorAll('.media-video-placeholder').forEach(placeholder => {
      placeholder.addEventListener('click', function() { const video = this.parentElement.querySelector('.media-video'); if (video) { this.style.display = 'none'; video.style.display = 'block'; video.play().catch(() => {}); } });
    });
  </script>
</x-app-layout>
