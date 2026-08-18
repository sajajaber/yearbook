<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Upload New Media') }}</h2>
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

        <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data" style="border: 1px solid #ddd; padding: 20px; border-radius: 4px;">
            @csrf

            <div style="margin-bottom: 20px;">
                <label for="file" style="display: block; margin-bottom: 8px; font-weight: bold;">
                    {{ __('Image File') }} <span style="color: red;">*</span>
                </label>
                <input type="file" id="file" name="file" required accept="image/jpeg,image/jpg,image/png,image/webp" style="display: block; width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" />
                <small style="color: #666; display: block; margin-top: 5px;">{{ __('PNG, JPG, JPEG or WEBP up to 5MB') }}</small>
                @error('file')
                    <p style="color: red; margin: 5px 0 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="caption" style="display: block; margin-bottom: 8px; font-weight: bold;">
                    {{ __('Caption') }}
                </label>
                <input type="text" id="caption" name="caption" value="{{ old('caption') }}" placeholder="{{ __('Enter a caption for this media') }}" maxlength="255" style="display: block; width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;" />
                @error('caption')
                    <p style="color: red; margin: 5px 0 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="alt_text" style="display: block; margin-bottom: 8px; font-weight: bold;">
                    {{ __('Alternative Text') }}
                </label>
                <input type="text" id="alt_text" name="alt_text" value="{{ old('alt_text') }}" placeholder="{{ __('Describe the image for accessibility') }}" maxlength="255" style="display: block; width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;" />
                @error('alt_text')
                    <p style="color: red; margin: 5px 0 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="credit" style="display: block; margin-bottom: 8px; font-weight: bold;">
                    {{ __('Credit') }}
                </label>
                <input type="text" id="credit" name="credit" value="{{ old('credit') }}" placeholder="{{ __('Photographer or source') }}" maxlength="255" style="display: block; width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;" />
                @error('credit')
                    <p style="color: red; margin: 5px 0 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <div style="display: flex; justify-content: space-between; gap: 10px;">
                <a href="{{ route('media.index') }}" style="flex: 1; padding: 10px; background: #f5f5f5; color: #333; text-decoration: none; text-align: center; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" style="flex: 1; padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                    {{ __('Upload Media') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
