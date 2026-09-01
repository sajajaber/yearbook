<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Search the yearbook. Try a name, event, school, or a general question — e.g. "graduation events in Beirut" or "students in computer science".') }}
    </div>

    <form method="POST" action="{{ route('search.perform') }}">
        @csrf
        <div class="flex gap-3">
            <x-text-input
                type="text"
                name="query"
                class="block mt-1 w-full"
                placeholder="{{ __('Search the yearbook…') }}"
                value="{{ $query ?? '' }}"
                required
                autofocus />
            <x-primary-button>{{ __('Search') }}</x-primary-button>
        </div>
    </form>

    @if ($error ?? null)
    <div class="mt-4 text-sm text-red-600 dark:text-red-400">
        {{ $error }}
    </div>
    @endif

    @if ($query && ! ($error ?? null))
    <div class="mt-6">
        @if (($results ?? collect())->isEmpty())
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('No matches found for ":query".', ['query' => $query]) }}
        </p>
        @else
        <ul class="space-y-4">
            @foreach ($results as $result)
            <li class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <span class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    {{ $result['type'] === 'event' ? __('Event') : __('Graduate') }}
                </span>
                <h3 class="font-semibold text-gray-900 dark:text-gray-100">
                    {{ $result['title'] }}
                </h3>
                @if (!empty($result['excerpt']))
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $result['excerpt'] }}</p>
                @endif
                @if (!empty($result['reason']))
                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $result['reason'] }}</p>
                @endif
            </li>
            @endforeach
        </ul>
        @endif
    </div>
    @endif
</x-guest-layout>
