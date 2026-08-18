<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Edit Media') }}</h2>
    </x-slot>

    <div style="padding: 20px; max-width: 600px; margin: 0 auto;">
        @if ($errors->any())
            <div style="margin-bottom: 20px; padding: 15px; background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; border-radius: 4px;">
                <p style="font-weight: bold; margin: 0 0 10px 0;">{{ __('Please fix the following errors:') }}</p>
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Media Preview -->
        <div style="margin-bottom: 20px; border: 1px solid #ddd; padding: 15px; border-radius: 4px;">
            <label style="display: block; margin-bottom: 10px; font-weight: bold;">
                {{ __('Media Preview') }}
            </label>
            <div style="background: #f5f5f5; display: flex; align-items: center; justify-content: center; min-height: 400px;">
                <img src="{{ asset('storage/' . $mediaItem->path) }}" alt="{{ $mediaItem->alt_text ?? $mediaItem->file_name }}" style="max-width: 100%; max-height: 100%; object-fit: contain; padding: 10px;">
            </div>
        </div>

        <form action="{{ route('media.update', $mediaItem->id) }}" method="POST" style="border: 1px solid #ddd; padding: 20px; border-radius: 4px;">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 20px;">
                <label for="caption" style="display: block; margin-bottom: 8px; font-weight: bold;">
                    {{ __('Caption') }}
                </label>
                <textarea id="caption" name="caption" rows="3" placeholder="{{ __('Enter a caption for this media') }}" maxlength="255" style="display: block; width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">{{ old('caption', $mediaItem->caption) }}</textarea>
                @error('caption')
                    <p style="color: red; margin: 5px 0 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="alt_text" style="display: block; margin-bottom: 8px; font-weight: bold;">
                    {{ __('Alternative Text') }}
                </label>
                <textarea id="alt_text" name="alt_text" rows="3" placeholder="{{ __('Describe the image for accessibility and SEO') }}" maxlength="255" style="display: block; width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">{{ old('alt_text', $mediaItem->alt_text) }}</textarea>
                @error('alt_text')
                    <p style="color: red; margin: 5px 0 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="credit" style="display: block; margin-bottom: 8px; font-weight: bold;">
                    {{ __('Credit') }}
                </label>
                <input type="text" id="credit" name="credit" value="{{ old('credit', $mediaItem->credit) }}" placeholder="{{ __('Photographer, artist, or source') }}" maxlength="255" style="display: block; width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;" />
                @error('credit')
                    <p style="color: red; margin: 5px 0 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Media Info -->
            <div style="margin-bottom: 20px; background: #f5f5f5; padding: 15px; border-radius: 4px;">
                <h3 style="font-weight: bold; margin: 0 0 15px 0;">{{ __('Media Information') }}</h3>
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold; width: 50%;">{{ __('File Name') }}</td>
                        <td style="padding: 8px 0;">{{ $mediaItem->file_name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">{{ __('File Type') }}</td>
                        <td style="padding: 8px 0;">{{ $mediaItem->type }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">{{ __('Uploaded By') }}</td>
                        <td style="padding: 8px 0;">@if ($mediaItem->user) {{ $mediaItem->user->name }} @else {{ __('Unknown') }} @endif</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">{{ __('Uploaded Date') }}</td>
                        <td style="padding: 8px 0;">{{ $mediaItem->created_at->format('M d, Y \a\t H:i') }}</td>
                    </tr>
                </table>
            </div>

            <div style="display: flex; justify-content: space-between; gap: 10px;">
                <a href="{{ route('media.index') }}" style="flex: 1; padding: 10px; background: #f5f5f5; color: #333; text-decoration: none; text-align: center; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;">
                    {{ __('Cancel') }}
                </a>
                <form action="{{ route('media.destroy', $mediaItem->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('{{ __('Are you sure you want to delete this media?') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="width: 100%; padding: 10px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                        {{ __('Delete') }}
                    </button>
                </form>
                <button type="submit" style="flex: 1; padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                    {{ __('Save Changes') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
