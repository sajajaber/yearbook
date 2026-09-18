@extends('public.layout')

@section('title', 'Search the Yearbook')

@section('extra-css')
    @vite('resources/css/pages/search.css')
@endsection

@section('content')

<div class="search-page">

    <div class="search-container">


        {{-- =====================================================
         SEARCH INTRO
    ====================================================== --}}

        <section class="search-intro">

            <div class="search-eyebrow">
                LIU · Digital Yearbook
            </div>

            <h1 class="search-title">
                Search the memories.
            </h1>

            <p class="search-description">
                Find graduates, events, schools, campuses, and stories
                preserved throughout the digital yearbook archive.
            </p>

        </section>


        {{-- =====================================================
         SEARCH FORM
    ====================================================== --}}

        <div class="search-box">

            <form
                method="POST"
                action="{{ route('search.perform') }}"
                class="search-form">

                @csrf

                <div class="search-input-wrapper">

                    <svg
                        class="search-icon"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m20 20-4-4"></path>
                    </svg>

                    <input
                        type="text"
                        name="query"
                        class="search-input"
                        placeholder="Search a name, event, school, campus..."
                        value="{{ $query ?? '' }}"
                        required
                        autofocus>

                </div>

                <button
                    type="submit"
                    class="search-button">
                    Search archive
                </button>

            </form>

        </div>


        {{-- =====================================================
         ERROR
    ====================================================== --}}

        @if ($error ?? null)

        <div class="search-error">
            {{ $error }}
        </div>

        @endif


        {{-- =====================================================
         RESULTS
    ====================================================== --}}

        @if ($query && !($error ?? null))

        @if (($results ?? collect())->isEmpty())

        <section class="search-empty">

            <div class="empty-mark">
                0
            </div>

            <h2>
                No memories found.
            </h2>

            <p>
                Nothing in the yearbook matched
                “{{ $query }}”.
                Try searching for a person's name,
                an event, school, or campus.
            </p>

            <div class="search-hints">

                <span class="search-hint">
                    Graduate names
                </span>

                <span class="search-hint">
                    Events
                </span>

                <span class="search-hint">
                    Schools
                </span>

                <span class="search-hint">
                    Campuses
                </span>

            </div>

        </section>

        @else

        <section class="results-section">

            <div class="results-header">

                <div>

                    <div class="results-label">
                        Archive search
                    </div>

                    <h2 class="results-title">
                        Results for “{{ $query }}”
                    </h2>

                </div>

                <div class="results-count">
                    {{ $results->count() }}
                    {{ $results->count() === 1 ? 'match' : 'matches' }}
                </div>

            </div>


            <div class="search-results">

                @foreach ($results as $result)

                <a href="{{ $result['url'] ?? '#' }}" class="search-result" @if(empty($result['url'])) aria-disabled="true" @endif>

                    {{-- Result type --}}

                    <div class="result-type">

                        {{ $result['type'] === 'event'
                                    ? __('Event')
                                    : __('Graduate')
                                }}

                    </div>


                    {{-- Result content --}}

                    <div class="result-content">

                        <h3>
                            {{ $result['title'] }}
                        </h3>

                        @if (!empty($result['excerpt']))

                        <p class="result-excerpt">
                            {{ $result['excerpt'] }}
                        </p>

                        @endif

                        @if (!empty($result['reason']))

                        <p class="result-reason">
                            {{ $result['reason'] }}
                        </p>

                        @endif

                    </div>


                    {{-- Arrow --}}

                    <div class="result-arrow">
                        →
                    </div>

                </a>

                @endforeach

            </div>

        </section>

        @endif

        @else

        {{-- =================================================
             INITIAL STATE
        ================================================== --}}

        <section class="search-empty">

            <div class="empty-mark">
                ?
            </div>

            <h2>
                What do you remember?
            </h2>

            <p>
                Search the archive using a name, event,
                school, campus, or even a natural-language
                question.
            </p>

            <div class="search-hints">

                <span class="search-hint">
                    “Graduation events”
                </span>

                <span class="search-hint">
                    “Computer Science”
                </span>

                <span class="search-hint">
                    “Students in Beirut”
                </span>

            </div>

        </section>

        @endif

    </div>

</div>

@endsection