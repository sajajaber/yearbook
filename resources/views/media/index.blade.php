<x-app-layout>
  <x-slot name="header"><div class="dashboard-heading media-heading"><div><p class="eyebrow">Yearbook office / assets</p><h1>Media library</h1></div><a href="{{ route('media.create') }}" class="button button-red"><span aria-hidden="true">+</span> Upload media</a></div></x-slot>

  <div class="dashboard-wrap media-wrap">
    <div class="media-toolbar"><div><p class="eyebrow">Organized archive</p><h2>Every image, ready when you need it.</h2></div><div class="media-filters"><a href="{{ route('media.index') }}" class="filter-button {{ $filter === 'all' ? 'is-selected' : '' }}">All</a><a href="{{ route('media.index', ['filter' => 'graduate-portraits']) }}" class="filter-button {{ $filter === 'graduate-portraits' ? 'is-selected' : '' }}">Graduate profile photos</a></div></div>
    @if ($errors->any())
    <div style="margin-bottom: 20px; padding: 15px; background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; border-radius: 4px;">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    @if (session('success'))
    <div style="margin-bottom: 20px; padding: 15px; background: #d4edda; border: 1px solid #c3e6cb; color: #155724; border-radius: 4px;">
      {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div style="margin-bottom: 20px; padding: 15px; background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; border-radius: 4px;">
      {{ session('error') }}
    </div>
    @endif

    @if ($mediaItems->isEmpty())
    <div style="text-align: center; padding: 40px;">
      <p>{{ __('No media items yet. Start by uploading your first media.') }}</p>
    </div>
    @else
    <div class="media-grid">
      @foreach ($mediaItems as $mediaItem)
      <div class="media-card">
        <div class="media-preview">
          @if ($mediaItem->type === 'image')
          <img src="{{ asset('storage/' . $mediaItem->path) }}" alt="{{ $mediaItem->alt_text ?? $mediaItem->file_name }}">
          @elseif ($mediaItem->type === 'video')
          <video controls style="max-width: 100%; max-height: 100%;">
            <source src="{{ asset('storage/' . $mediaItem->path) }}">
            {{ __('Your browser does not support video playback.') }}
          </video>
          @elseif ($mediaItem->type === 'document')
          <a href="{{ asset('storage/' . $mediaItem->path) }}" target="_blank" style="text-decoration: none; color: #333; text-align: center;">
            <div style="font-size: 48px;">📄</div>
            <div style="font-size: 12px; margin-top: 8px;">{{ __('View document') }}</div>
          </a>
          @endif
        </div>
        <div class="media-card-body">
          <p class="media-file-name">
            {{ $mediaItem->file_name }}
          </p>
          @if ($mediaItem->caption)
          <p class="media-caption">
            {{ $mediaItem->caption }}
          </p>
          @endif
          @if ($mediaItem->credit)
          <p class="media-meta">
            {{ __('Credit') }}: {{ $mediaItem->credit }}
          </p>
          @endif
          <p class="media-meta">
            {{ __('Uploaded') }}: {{ $mediaItem->created_at->format('M d, Y') }}
          </p>
          @if ($mediaItem->portraitGraduates->isNotEmpty())<div class="portrait-links"><span>Graduate profile</span>@foreach ($mediaItem->portraitGraduates as $graduate)<a href="{{ route('graduates.edit', $graduate) }}">{{ $graduate->name }} <span aria-hidden="true">→</span></a>@endforeach</div>@endif
          <div class="media-actions">
            <a href="{{ route('media.edit', $mediaItem->id) }}" class="button button-navy">
              {{ __('Edit') }}
            </a>
            <form action="{{ route('media.destroy', $mediaItem->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="action-button">
                {{ __('Delete') }}
              </button>
            </form>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    @endif
  </div>
</x-app-layout> 