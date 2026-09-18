@extends('public.layout')

@section('title', 'Graduates | LIU Digital Yearbook')

@section('extra-css')
    @vite('resources/css/pages/graduates.css')
@endsection

@section('content')
@php
$visibleGrouped = $graduates
->groupBy(fn($graduate) => $graduate->school?->name ?? 'Unassigned')
->sortKeys();

$namedGrouped = $namedOnly
->groupBy(fn($graduate) => $graduate->school?->name ?? 'Unassigned')
->sortKeys();

$schoolNames = $visibleGrouped->keys()
->merge($namedGrouped->keys())
->unique()
->sort()
->values();

$directoryTotal = $graduates->count() + $namedOnly->count();
$schoolCount = $schoolNames->count();

$visibleUndergraduates = $graduates->where('degree_level', 'undergraduate')->count();
$visibleGraduates = $graduates->where('degree_level', 'graduate')->count();
$namedUndergraduates = $namedOnly->where('degree_level', 'undergraduate')->count();
$namedGraduates = $namedOnly->where('degree_level', 'graduate')->count();
@endphp

<div class="graduates-page">
    <section class="graduates-hero">
        <div class="graduates-hero-inner">
            <div class="graduates-kicker">The graduating community</div>
            <h1>Graduates <em>A record of achievement, ambition, and the people who shaped each class.</em></h1>
            <div class="graduates-hero-bottom">
                <p class="graduates-hero-copy">Explore the LIU Digital Yearbook graduate directory across schools, degrees, campuses, and academic years.</p>
                <div class="graduates-hero-stat">
                    <div class="hero-stat"><strong>{{ $directoryTotal }}</strong><span>Directory entries</span></div>
                    <div class="hero-stat"><strong>{{ $schoolCount }}</strong><span>Schools</span></div>
                </div>
            </div>
        </div>
    </section>

    <main class="container">
        <section class="graduates-discovery" aria-label="Graduate filters">
            <div class="graduates-filter-shell">
                <form method="GET" action="{{ route('public.graduates') }}" class="graduates-filter-form">
                    <div class="graduates-filter-field">
                        <input type="search" name="search" value="{{ $search }}" placeholder="Search graduates…" aria-label="Search graduates">
                    </div>

                    <div class="graduates-filter-field">
                        <select name="year" aria-label="Academic year">
                            <option value="">All academic years</option>
                            @foreach($years as $academicYear)
                            <option value="{{ $academicYear->id }}" @selected((string) $year==(string) $academicYear->id)>
                                {{ $academicYear->title }}{{ $academicYear->status === 'active' ? ' · Current' : '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="graduates-filter-field">
                        <select name="degree" aria-label="Degree level">
                            <option value="">All degrees</option>
                            <option value="undergraduate" @selected($degree==='undergraduate' )>Undergraduate</option>
                            <option value="graduate" @selected($degree==='graduate' )>Graduate</option>
                        </select>
                    </div>

                    <div class="graduates-filter-field">
                        <select name="school" aria-label="School">
                            <option value="">All schools</option>
                            @foreach($schools as $item)
                            <option value="{{ $item->id }}" @selected((string) $school===(string) $item->id)>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="graduates-filter-field">
                        <select name="campus" aria-label="Campus">
                            <option value="">All campuses</option>
                            @foreach($campuses as $item)
                            <option value="{{ $item->id }}" @selected((string) $campus===(string) $item->id)>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="graduates-filter-field">
                        <select name="sort" aria-label="Sort graduates">
                            <option value="name" @selected($sort==='name' )>Name A–Z</option>
                            <option value="name_desc" @selected($sort==='name_desc' )>Name Z–A</option>
                            <option value="latest" @selected($sort==='latest' )>Newest first</option>
                            <option value="oldest" @selected($sort==='oldest' )>Oldest first</option>
                        </select>
                    </div>

                    <button class="graduates-filter-button" type="submit">Filter</button>
                </form>
            </div>
        </section>

        <section class="graduates-overview" aria-label="Directory overview">
            <div class="directory-intro">
                <small>Digital yearbook directory</small>
                <strong>Every school. Every class. One graduating community.</strong>
            </div>

            <div class="directory-stat">
                <small>Profiles</small>
                <strong>{{ $graduates->count() }}</strong>
                <span>public profiles</span>
            </div>

            <div class="directory-stat">
                <small>Undergraduate</small>
                <strong>{{ $visibleUndergraduates + $namedUndergraduates }}</strong>
                <span>directory entries</span>
            </div>

            <div class="directory-stat">
                <small>Graduate</small>
                <strong>{{ $visibleGraduates + $namedGraduates }}</strong>
                <span>directory entries</span>
            </div>
        </section>

        @if($directoryTotal > 0)
        <section class="school-directory">
            <div class="school-directory-heading">
                <div>
                    <p class="directory-kicker">The directory</p>
                    <h2>Browse by school</h2>
                </div>
                <span class="directory-result-count">{{ $directoryTotal }} {{ \Illuminate\Support\Str::plural('entry', $directoryTotal) }}</span>
            </div>

            @foreach($schoolNames as $schoolName)
            @php
            $schoolVisible = $visibleGrouped->get($schoolName, collect());
            $schoolNamed = $namedGrouped->get($schoolName, collect());
            $schoolTotal = $schoolVisible->count() + $schoolNamed->count();
            $degreeGroups = collect(['undergraduate', 'graduate'])->mapWithKeys(function ($level) use ($schoolVisible, $schoolNamed) {
            return [$level => [
            'visible' => $schoolVisible->where('degree_level', $level)->values(),
            'named' => $schoolNamed->where('degree_level', $level)->values(),
            ]];
            });
            @endphp

            <section class="school-block">
                <div class="school-block-heading">
                    <h3>{{ $schoolName }}</h3>
                    <span class="school-total">{{ $schoolTotal }} {{ Str::plural('graduate', $schoolTotal) }}</span>
                </div>

                @foreach($degreeGroups as $degreeLevel => $group)
                @if($group['visible']->isNotEmpty() || $group['named']->isNotEmpty())
                @php $degreeTotal = $group['visible']->count() + $group['named']->count(); @endphp
                <div class="school-degree-section">
                    <div class="school-degree-label">
                        <strong>{{ $degreeLevel === 'graduate' ? 'Graduate' : 'Undergraduate' }}</strong>
                        <span>{{ $degreeTotal }} {{ Str::plural('entry', $degreeTotal) }}</span>
                    </div>

                    @if($group['visible']->isNotEmpty())
                    <div class="graduates-grid">
                        @foreach($group['visible'] as $graduate)
                        @php
                        $portrait = $graduate->portraitMedia ?? $graduate->media->firstWhere('type', 'image');
                        $graduateYear = $graduate->academicYear?->title ?? $graduate->graduation?->academicYear?->title;
                        @endphp

                        <a class="graduate-link" href="{{ route('public.graduate.detail', ['student_reference' => $graduate->student_reference]) }}">
                            <article class="graduate-card">
                                <div class="graduate-portrait">
                                    @if($portrait)
                                    <img src="{{ $portrait->thumbnailUrl() }}" alt="{{ $graduate->name }}" loading="lazy">
                                    @else
                                    <span class="graduate-placeholder">{{ mb_strtoupper(mb_substr($graduate->name, 0, 1)) }}</span>
                                    @endif

                                    <span class="graduate-badge graduate-degree-badge">{{ $degreeLevel === 'graduate' ? 'Graduate' : 'Undergraduate' }}</span>
                                    @if($graduateYear)
                                    <span class="graduate-badge graduate-year-badge">{{ $graduateYear }}</span>
                                    @endif
                                </div>

                                <div class="graduate-card-body">
                                    <h4 class="graduate-name">{{ $graduate->name }}</h4>
                                    @if($graduate->major)
                                    <p class="graduate-major">{{ $graduate->major->name }}</p>
                                    @endif
                                    <div class="graduate-card-footer">
                                        <span class="graduate-view">View profile →</span>
                                    </div>
                                </div>
                            </article>
                        </a>
                        @endforeach
                    </div>
                    @endif

                    @if($group['named']->isNotEmpty())
                    <div class="name-only-wrap">
                        <p class="name-only-note">Additional graduates listed by name only.</p>
                        <div class="graduate-name-only-list" aria-label="Graduates listed by name only">
                            @foreach($group['named'] as $graduate)
                            <span class="graduate-name-only">{{ $graduate->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endif
                @endforeach
            </section>
            @endforeach
        </section>
        @else
        <section class="graduates-empty">
            <h3>No graduates found</h3>
            <p>Try changing your search or filter selections.</p>
            <a class="graduates-reset" href="{{ route('public.graduates') }}">Reset filters</a>
        </section>
        @endif

        @if(method_exists($graduates, 'links'))
        <div class="graduates-pagination">{{ $graduates->links() }}</div>
        @endif
    </main>
</div>
@endsection