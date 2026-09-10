@extends('public.layout')

@section('title', 'Search the Yearbook')

@section('extra-css')

<style>
    /* =========================================================
       SEARCH PAGE
       ========================================================= */

    .search-page {
        min-height: 75vh;
        background: var(--paper);
        margin: 0 -32px;
        padding-bottom: 100px;
    }

    .search-container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 40px;
    }

    /* =========================================================
       INTRO
       ========================================================= */

    .search-intro {
        padding: 80px 0 55px;
    }

    .search-eyebrow {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--red);
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        margin-bottom: 20px;
    }

    .search-eyebrow::before {
        content: "";
        width: 28px;
        height: 2px;
        background: var(--red);
    }

    .search-title {
        max-width: 750px;
        margin: 0;
        color: var(--ink);
        font-family: "Merriweather", serif;
        font-size: clamp(46px, 7vw, 76px);
        font-weight: 400;
        line-height: 1;
        letter-spacing: -3px;
    }

    .search-description {
        max-width: 600px;
        margin: 22px 0 0;
        color: var(--ink-soft);
        font-size: 14px;
        line-height: 1.8;
    }


    /* =========================================================
       SEARCH BOX
       ========================================================= */

    .search-box {
        max-width: 900px;
        position: relative;
        margin-bottom: 65px;
    }

    .search-form {
        display: flex;
        background: var(--white);
        border: 1px solid var(--line);
        box-shadow: 8px 8px 0 rgba(0, 42, 92, .06);
        transition: box-shadow .3s ease, border-color .3s ease;
    }

    .search-form:focus-within {
        border-color: var(--ink);
        box-shadow: 8px 8px 0 rgba(255, 176, 52, .18);
    }

    .search-input-wrapper {
        position: relative;
        flex: 1;
    }

    .search-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        width: 19px;
        height: 19px;
        transform: translateY(-50%);
        color: var(--ink-soft);
        pointer-events: none;
    }

    .search-input {
        width: 100%;
        height: 66px;
        border: none;
        outline: none;
        padding: 0 20px 0 56px;
        background: transparent;
        color: var(--ink);
        font-family: inherit;
        font-size: 15px;
    }

    .search-input::placeholder {
        color: #9aa8b7;
    }

    .search-button {
        height: 66px;
        padding: 0 30px;
        border: none;
        background: var(--ink);
        color: var(--white);
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        cursor: pointer;
        transition: background .25s ease, color .25s ease;
    }

    .search-button:hover {
        background: var(--red);
        color: var(--ink);
    }


    /* =========================================================
       ERROR
       ========================================================= */

    .search-error {
        margin-top: -40px;
        margin-bottom: 40px;
        padding: 15px 18px;
        border-left: 3px solid #c62828;
        background: #fff5f5;
        color: #a61b1b;
        font-size: 12px;
    }


    /* =========================================================
       RESULTS HEADER
       ========================================================= */

    .results-section {
        border-top: 1px solid var(--line);
        padding-top: 38px;
    }

    .results-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 28px;
        gap: 20px;
    }

    .results-label {
        color: var(--red);
        font-size: 8px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 7px;
    }

    .results-title {
        margin: 0;
        color: var(--ink);
        font-family: "Merriweather", serif;
        font-size: 30px;
        font-weight: 400;
        line-height: 1.2;
    }

    .results-count {
        color: var(--ink-soft);
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        white-space: nowrap;
    }


    /* =========================================================
       RESULT LIST
       ========================================================= */

    .search-results {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .search-result {
        position: relative;
        display: grid;
        grid-template-columns: 100px 1fr 30px;
        align-items: center;
        gap: 24px;
        padding: 23px 25px;
        background: var(--white);
        border: 1px solid var(--line);
        text-decoration: none;
        color: inherit;
        overflow: hidden;
        transition:
            transform .25s ease,
            box-shadow .25s ease,
            border-color .25s ease;
    }

    .search-result::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: var(--red);
        transform: scaleY(0);
        transform-origin: bottom;
        transition: transform .3s ease;
    }

    .search-result:hover {
        transform: translateX(5px);
        border-color: rgba(0, 42, 92, .18);
        box-shadow: 0 10px 25px rgba(19, 42, 58, .07);
    }

    .search-result:hover::before {
        transform: scaleY(1);
    }


    /* =========================================================
       RESULT TYPE
       ========================================================= */

    .result-type {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--ink-soft);
        font-size: 8px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    .result-type::before {
        content: "";
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--red);
    }


    /* =========================================================
       RESULT CONTENT
       ========================================================= */

    .result-content {
        min-width: 0;
    }

    .result-content h3 {
        margin: 0 0 6px;
        color: var(--ink);
        font-family: "Merriweather", serif;
        font-size: 18px;
        font-weight: 400;
        line-height: 1.35;
    }

    .result-excerpt {
        margin: 0;
        color: var(--ink-soft);
        font-size: 11px;
        line-height: 1.65;
    }

    .result-reason {
        margin-top: 7px;
        color: #8c98a5;
        font-size: 9px;
        font-style: italic;
    }


    /* =========================================================
       ARROW
       ========================================================= */

    .result-arrow {
        color: var(--ink);
        font-size: 20px;
        transition: transform .25s ease;
    }

    .search-result:hover .result-arrow {
        transform: translateX(5px);
    }


    /* =========================================================
       EMPTY SEARCH STATE
       ========================================================= */

    .search-empty {
        padding: 80px 30px;
        border-top: 1px solid var(--line);
        text-align: center;
    }

    .empty-mark {
        margin-bottom: 15px;
        color: rgba(0, 42, 92, .12);
        font-family: "Merriweather", serif;
        font-size: 70px;
        line-height: 1;
    }

    .search-empty h2 {
        margin: 0 0 10px;
        color: var(--ink);
        font-family: "Merriweather", serif;
        font-size: 28px;
        font-weight: 400;
    }

    .search-empty p {
        max-width: 500px;
        margin: 0 auto;
        color: var(--ink-soft);
        font-size: 12px;
        line-height: 1.8;
    }


    /* =========================================================
       SEARCH HINTS
       ========================================================= */

    .search-hints {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-top: 25px;
    }

    .search-hint {
        padding: 8px 13px;
        border: 1px solid var(--line);
        background: var(--white);
        color: var(--ink);
        font-size: 8px;
        font-weight: 700;
        letter-spacing: .8px;
        text-transform: uppercase;
    }


    /* =========================================================
       ENTRANCE ANIMATION
       ========================================================= */

    .search-intro,
    .search-box,
    .results-section,
    .search-empty {
        animation: searchReveal .7s cubic-bezier(.2, .8, .2, 1) both;
    }

    .search-box {
        animation-delay: .08s;
    }

    .results-section,
    .search-empty {
        animation-delay: .16s;
    }

    @keyframes searchReveal {
        from {
            opacity: 0;
            transform: translateY(18px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }


    /* =========================================================
       MOBILE
       ========================================================= */

    @media (max-width: 700px) {

        .search-page {
            margin: 0 -20px;
        }

        .search-container {
            padding: 0 22px;
        }

        .search-intro {
            padding: 60px 0 40px;
        }

        .search-title {
            font-size: 48px;
            letter-spacing: -2px;
        }

        .search-description {
            font-size: 13px;
        }

        .search-form {
            display: block;
        }

        .search-input {
            height: 60px;
        }

        .search-button {
            width: 100%;
            height: 54px;
        }

        .search-box {
            margin-bottom: 50px;
        }

        .results-header {
            display: block;
        }

        .results-count {
            display: block;
            margin-top: 10px;
        }

        .search-result {
            grid-template-columns: 75px 1fr 20px;
            gap: 15px;
            padding: 20px 17px;
        }

        .result-type {
            font-size: 7px;
        }

        .result-content h3 {
            font-size: 16px;
        }

        .result-excerpt {
            font-size: 10px;
        }

        .result-arrow {
            font-size: 17px;
        }
    }
</style>

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

                <div class="search-result">

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

                </div>

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