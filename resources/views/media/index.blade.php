<x-app-layout>
  <x-slot name="header">
    <div class="dashboard-heading media-heading">
      <div>
        <p class="eyebrow">Yearbook office / assets</p>
        <h1>Media library</h1>
        <p class="media-count">{{ $totalMedia }} {{ Str::plural('item', $totalMedia) }}</p>
      </div>
      @if (in_array(Auth::user()->role?->role_name, ['admin', 'editor']))
      <a href="{{ route('media.create') }}" class="button button-red" aria-label="Upload new media file">
        <span aria-hidden="true">+</span> Upload media
      </a>
      @endif
    </div>
  </x-slot>

  <div class="dashboard-wrap media-wrap">
    <!-- Alerts -->
    @if ($errors->any())
    <div class="alert alert-error" role="alert">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    @if (session('success'))
    <div class="alert alert-success" role="status">
      {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-error" role="alert">
      {{ session('error') }}
    </div>
    @endif

    <!-- Toolbar with Search, Filter, and Sort -->
    <div class="media-toolbar">
      <div>
        <p class="eyebrow">Organized archive</p>
        <h2>Every image, ready when you need it.</h2>
      </div>
      
      <div class="media-controls">
        <!-- Search Form -->
        <form method="GET" action="{{ route('media.index') }}" class="media-search-form" role="search">
          <input type="hidden" name="filter" value="{{ $filter }}">
          <input type="hidden" name="sort" value="{{ $sortBy }}">
        </form>

        <!-- Filters -->
        <div class="media-filters">
          <a href="{{ route('media.index', array_merge(request()->query(), ['filter' => 'all'])) }}" 
             class="filter-button {{ $filter === 'all' ? 'is-selected' : '' }}"
             aria-current="{{ $filter === 'all' ? 'page' : 'false' }}">
            All
          </a>
          <a href="{{ route('media.index', array_merge(request()->query(), ['filter' => 'graduate-portraits'])) }}" 
             class="filter-button {{ $filter === 'graduate-portraits' ? 'is-selected' : '' }}"
             aria-current="{{ $filter === 'graduate-portraits' ? 'page' : 'false' }}">
            Graduate profile photos
          </a>
        </div>

        <!-- Sort Options -->
        <form method="GET" action="{{ route('media.index') }}" class="media-sort-form"> 
          <input type="hidden" name="filter" value="{{ $filter }}">
          <input type="hidden" name="search" value="{{ $search }}">
        </form>
      </div>
    </div>

    <!-- Media Grid or Empty State -->
    @if ($mediaItems->isEmpty())
    <div class="media-empty-state">
      <p>No media found</p>
      @if (in_array(Auth::user()->role?->role_name, ['admin', 'editor']))
      <a href="{{ route('media.create') }}" class="button button-red button-small">
        <span aria-hidden="true">+</span> Upload media
      </a>
      @endif
    </div>
    @else
    <div class="media-grid" role="list">
      @foreach ($mediaItems as $mediaItem)
      <div class="media-card" role="listitem">
        <div class="media-preview" aria-label="Preview of {{ $mediaItem->file_name }}">
          @if ($mediaItem->type === 'image')
          <img 
            src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 200'%3E%3Crect fill='%23e7f0fa' width='300' height='200'/%3E%3C/svg%3E"
            data-src="{{ asset('storage/' . $mediaItem->path) }}" 
            alt="{{ $mediaItem->alt_text ?? $mediaItem->file_name }}"
            class="media-img lazy"
            loading="lazy"
          >
          @elseif ($mediaItem->type === 'video')
          <div class="media-video-placeholder">
            <svg class="video-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M8 5v14l11-7z"/>
            </svg>
            <p>Video</p>
          </div>
          <video controls class="media-video" style="display: none;" preload="metadata">
            <source src="{{ asset('storage/' . $mediaItem->path) }}">
            {{ __('Your browser does not support video playback.') }}
          </video>
          @elseif ($mediaItem->type === 'document')
          <a href="{{ asset('storage/' . $mediaItem->path) }}" target="_blank" rel="noopener noreferrer" class="media-document-link" aria-label="Open document: {{ $mediaItem->file_name }}">
            <svg class="document-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-8-6z"/>
            </svg>
            <p>{{ strtoupper(pathinfo($mediaItem->file_name, PATHINFO_EXTENSION)) }}</p>
          </a>
          @endif
        </div>

        <div class="media-card-body">
          <p class="media-file-name" title="{{ $mediaItem->file_name }}">
            {{ $mediaItem->file_name }}
          </p>
          
          @if ($mediaItem->caption)
          <p class="media-caption">{{ $mediaItem->caption }}</p>
          @endif

          <div class="media-metadata">
            @if ($mediaItem->credit)
            <span class="media-meta">
              <span class="meta-label">Credit:</span> {{ $mediaItem->credit }}
            </span>
            @endif
            <span class="media-meta">
              <span class="meta-label">Uploaded:</span> {{ $mediaItem->created_at->format('M d, Y') }}
            </span>
            @php
              $fileSize = $mediaItem->type === 'document' ? null : \Storage::disk('public')->size($mediaItem->path);
            @endphp
            @if ($fileSize)
            <span class="media-meta">
              <span class="meta-label">Size:</span> {{ number_format($fileSize / 1024, 0) }}KB
            </span>
            @endif
            @if ($mediaItem->uploader)
            <span class="media-meta">
              <span class="meta-label">By:</span> {{ $mediaItem->uploader->name }}
            </span>
            @endif
          </div>

          @if ($mediaItem->portraitGraduates->isNotEmpty())
          <div class="portrait-links" role="group" aria-label="Associated graduates">
            <span class="portrait-links-title">Graduate profile</span>
            @foreach ($mediaItem->portraitGraduates as $graduate)
            <a href="{{ route('graduates.edit', $graduate) }}" class="portrait-link">
              {{ $graduate->name }} <span aria-hidden="true">→</span>
            </a>
            @endforeach
          </div>
          @endif

          @if (in_array(Auth::user()->role?->role_name, ['admin', 'editor']))
          <div class="media-actions" role="group" aria-label="Media actions">
            <a href="{{ route('media.edit', $mediaItem->id) }}" class="button button-navy button-small" aria-label="Edit {{ $mediaItem->file_name }}">
              Edit
            </a>
            <form action="{{ route('media.destroy', $mediaItem->id) }}" method="POST" class="media-delete-form" onsubmit="return confirm('Are you sure you want to delete this media?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="action-button action-button-small" aria-label="Delete {{ $mediaItem->file_name }}">
                Delete
              </button>
            </form>
          </div>
          @endif
        </div>
      </div>
      @endforeach
    </div>

    <!-- Pagination -->
    <div class="media-pagination">
      {{ $mediaItems->appends(request()->query())->render() }}
    </div>
    @endif
  </div>

  <script>
    // Lazy loading for images
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
        // Fallback for browsers that don't support IntersectionObserver
        images.forEach(img => {
          img.src = img.dataset.src;
          img.classList.add('loaded');
        });
      }
    });

    // Toggle video on click
    document.querySelectorAll('.media-video-placeholder').forEach(placeholder => {
      placeholder.addEventListener('click', function() {
        const video = this.parentElement.querySelector('.media-video');
        if (video) {
          this.style.display = 'none';
          video.style.display = 'block';
          video.play().catch(() => {
            // Handle autoplay restrictions
          });
        }
      });
    });
  </script>
</x-app-layout>