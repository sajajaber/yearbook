@extends('public.layout')

@section('title', 'Graduates')

@section('extra-css')
<style>
/* Keep the existing graduates design; add comfortable page gutters and clean Cards/List controls. */
.graduates-page > main.container { max-width:1240px; padding-left:32px; padding-right:32px; }
.graduates-view-tools { display:flex; align-items:center; gap:4px; }
.graduates-view-button { min-width:70px; }
@media(max-width:850px){.graduates-page > main.container{padding-left:24px;padding-right:24px;}}
@media(max-width:650px){.graduates-page > main.container{padding-left:18px;padding-right:18px;}}
</style>
@endsection

@section('content')
<div class="graduates-page">
    <section class="graduates-hero">
        <div class="graduates-hero-grid"></div>
        <div class="graduates-hero-glow"></div>
        <div class="graduates-hero-inner">
            <div class="graduates-hero-content">
                <div class="graduates-kicker">Faces of our year</div>
                <h1>Graduates<em>the people behind the story.</em></h1>
                <div class="graduates-hero-bottom">
                    <p class="graduates-hero-copy">Explore the graduating community by academic year, degree level, school and campus. Every profile is part of the yearbook record.</p>
                    <div class="graduates-hero-count"><strong>{{ $graduates->total() }}</strong><span>{{ Str::plural('graduate', $graduates->total()) }}</span></div>
                </div>
            </div>
        </div>
    </section>

    <main class="container">
        <section class="graduates-discovery">
            <div class="graduates-filter-shell">
                <form method="GET" action="{{ route('public.graduates') }}" class="graduates-filter-form">
                    <div class="graduates-filter-field"><input type="search" name="search" value="{{ $search }}" placeholder="Search by name..."></div>
                    <div class="graduates-filter-field"><select name="year"><option value="">All academic years</option>@foreach($years as $academicYear)<option value="{{ $academicYear->id }}" @selected((string)$year === (string)$academicYear->id)>{{ $academicYear->title }}{{ $academicYear->status === 'active' ? ' · Current' : '' }}</option>@endforeach</select></div>
                    <div class="graduates-filter-field"><select name="degree"><option value="">All degree levels</option><option value="undergraduate" @selected($degree === 'undergraduate')>Undergraduate</option><option value="graduate" @selected($degree === 'graduate')>Graduate</option></select></div>
                    <div class="graduates-filter-field"><select name="school"><option value="">All schools</option>@foreach($schools as $item)<option value="{{ $item->id }}" @selected((string)$school === (string)$item->id)>{{ $item->name }}</option>@endforeach</select></div>
                    <div class="graduates-filter-field"><select name="campus"><option value="">All campuses</option>@foreach($campuses as $item)<option value="{{ $item->id }}" @selected((string)$campus === (string)$item->id)>{{ $item->name }}</option>@endforeach</select></div>
                    <button class="graduates-filter-button" type="submit">Filter</button>
                </form>
            </div>
        </section>

        <section>
            <div class="graduates-header">
                <div><div class="graduates-kicker-small">The directory</div><h2>Graduates</h2></div>
                <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;justify-content:flex-end;">
                    <span class="graduates-count">{{ $graduates->total() }} {{ Str::plural('profile', $graduates->total()) }}</span>
                    <div class="graduates-view-tools" aria-label="Choose graduate view">
                        <button type="button" class="graduates-view-button is-active" data-graduates-view="cards">Cards</button>
                        <button type="button" class="graduates-view-button" data-graduates-view="list">List</button>
                    </div>
                </div>
            </div>

            @if($graduates->count())
                <div class="graduates-year-group">
                    <div class="graduates-year-heading"><span class="graduates-year-number">{{ $year ? optional($years->firstWhere('id', $year))->title : 'Selected year' }}</span><span class="graduates-year-line"></span></div>
                    <div class="graduates-grid" id="graduates-grid">
                        @foreach($graduates as $graduate)
                            @php($portrait = $graduate->portraitMedia ?? $graduate->media->firstWhere('type', 'image'))
                            @php($graduateYear = $graduate->academicYear?->title ?? $graduate->graduation?->academicYear?->title)
                            <a class="graduate-link" href="{{ route('public.graduate.detail', $graduate->id) }}">
                                <article class="graduate-card">
                                    <div class="graduate-portrait">
                                        @if($portrait)<img src="{{ $portrait->thumbnailUrl() }}" alt="{{ $graduate->name }}" loading="lazy">@else<span class="graduate-placeholder">{{ mb_strtoupper(mb_substr($graduate->name,0,1)) }}</span>@endif
                                        <span class="graduate-badge graduate-degree-badge">{{ $graduate->degree_level === 'graduate' ? 'Graduate' : 'Undergraduate' }}</span>
                                        @if($graduateYear)<span class="graduate-badge graduate-year-badge">{{ $graduateYear }}</span>@endif
                                    </div>
                                    <div class="graduate-card-body">
                                        <h3 class="graduate-name">{{ $graduate->name }}</h3>
                                        @if($graduate->major)<p class="graduate-major">{{ $graduate->major->name }}</p>@endif
                                        @if($graduate->school)<p class="graduate-school">{{ $graduate->school->name }}</p>@endif
                                        <div class="graduate-card-footer"><span class="graduate-view">View profile →</span></div>
                                    </div>
                                </article>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="graduates-pagination">{!! $graduates->onEachSide(1)->links('pagination::simple-tailwind') !!}</div>
            @else
                <div class="graduates-empty"><h3>No graduate profiles found</h3><p>Try another academic year, degree level, school, campus or search term.</p><a class="graduate-view" href="{{ route('public.graduates') }}">Reset filters →</a></div>
            @endif

            @if($namedOnly->count())
                <div class="graduates-named-only"><strong>Additional graduates</strong>{{ $namedOnly->implode(' · ') }}</div>
            @endif
        </section>
    </main>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){const grid=document.getElementById('graduates-grid');if(!grid)return;const buttons=document.querySelectorAll('[data-graduates-view]');const key='yearbook-graduates-view';const apply=mode=>{grid.classList.toggle('view-list',mode==='list');buttons.forEach(b=>b.classList.toggle('is-active',b.dataset.graduatesView===mode));try{localStorage.setItem(key,mode)}catch(e){}};let mode='cards';try{mode=localStorage.getItem(key)==='list'?'list':'cards'}catch(e){}buttons.forEach(b=>b.addEventListener('click',()=>apply(b.dataset.graduatesView)));apply(mode);});
</script>
@endpush
