<x-app-layout>
  <x-slot name="header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
      <h2>{{ __('Media Library') }}</h2>
      <a href="{{ route('media.create') }}" style="padding: 8px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;">
        {{ __('Upload New Media') }}
      </a>
    </div>
  </x-slot>

  <div style="padding: 20px;">
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
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
      @foreach ($mediaItems as $mediaItem)
      <div style="border: 1px solid #ddd; border-radius: 4px; overflow: hidden;">
        <div style="background: #f5f5f5; display: flex; align-items: center; justify-content: center; min-height: 250px;">
          @if ($mediaItem->type === 'image')
          <img src="{{ asset('storage/' . $mediaItem->path) }}" alt="{{ $mediaItem->alt_text ?? $mediaItem->file_name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
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
        <div style="padding: 15px;">
          <p style="margin: 0 0 10px 0; font-size: 12px; color: #666;">
            {{ $mediaItem->file_name }}
          </p>
          @if ($mediaItem->caption)
          <p style="margin: 10px 0; font-weight: bold;">
            {{ $mediaItem->caption }}
          </p>
          @endif
          @if ($mediaItem->credit)
          <p style="margin: 10px 0; font-size: 14px; color: #666;">
            {{ __('Credit') }}: {{ $mediaItem->credit }}
          </p>
          @endif
          <p style="margin: 10px 0; font-size: 12px; color: #999;">
            {{ __('Uploaded') }}: {{ $mediaItem->created_at->format('M d, Y') }}
          </p>
          <div style="margin-top: 15px; display: flex; gap: 10px;">
            <a href="{{ route('media.edit', $mediaItem->id) }}" style="flex: 1; padding: 8px; background: #007bff; color: white; text-decoration: none; text-align: center; border-radius: 4px; font-size: 14px;">
              {{ __('Edit') }}
            </a>
            <form action="{{ route('media.destroy', $mediaItem->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('{{ __('Are you sure?') }}');">
              @csrf
              @method('DELETE')
              <button type="submit" style="width: 100%; padding: 8px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">
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