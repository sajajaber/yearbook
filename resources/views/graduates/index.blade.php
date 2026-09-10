<x-app-layout>
    <x-slot name="header">
        <div class="dashboard-heading graduate-heading">
            <div><p class="eyebrow">Yearbook office / people</p><h1>Graduate profiles</h1></div>
            @if (in_array(Auth::user()->role?->role_name, ['admin', 'editor']))<a href="{{ route('graduates.create') }}" class="button button-red"><span aria-hidden="true">+</span> Add graduate</a>@endif
        </div>
    </x-slot>

    @php
        $statusCounts = $graduates->groupBy('publish_status')->map->count();
        $consentGranted = $graduates->where('consent_status', 'granted')->count();
    @endphp

    <div class="dashboard-wrap graduate-wrap" x-data="{ search: '', status: 'all', year: 'all', campus: 'all', school: 'all', major: 'all', view: 'grid' }">
        <section class="graduate-intro">
            <div><p class="eyebrow eyebrow-light">The people behind the year</p><h2>Make every<br>story count.</h2><p>Review, refine, and publish the voices that will define this year's yearbook.</p></div>
            <div class="graduate-intro-stats"><div><strong>{{ $graduates->total() }}</strong><span>profiles</span></div><div><strong>{{ $consentGranted }}</strong><span>consents</span></div></div>
        </section>

        @if (session('success') || session('error'))<div class="notice {{ session('error') ? 'notice-error' : 'notice-success' }}">{{ session('error') ?? session('success') }}</div>@endif

        <section class="filter-ribbon" aria-label="Graduate profile filters">
            <div class="filter-ribbon-heading"><div><p class="eyebrow">Filter profiles</p><span>Focus the directory by edition, place, or discipline.</span></div><button type="button" class="clear-filters" x-show="status !== 'all' || year !== 'all' || campus !== 'all' || school !== 'all' || major !== 'all'" @click="status = 'all'; year = 'all'; campus = 'all'; school = 'all'; major = 'all'">Clear filters</button></div>
            <div class="graduate-filters">
                <button type="button" class="filter-button" :class="{ 'is-selected': status === 'all' }" @click="status = 'all'">All <span>{{ $graduates->total() }}</span></button>
                @foreach (['draft' => 'Draft', 'reviewed' => 'Review', 'approved' => 'Approved', 'published' => 'Published', 'rejected' => 'Rejected'] as $key => $label)
                    <button type="button" class="filter-button" :class="{ 'is-selected': status === '{{ $key }}' }" @click="status = '{{ $key }}'">{{ $label }} <span>{{ $statusCounts->get($key, 0) }}</span></button>
                @endforeach
            </div>
            <div class="ribbon-fields">
                <select class="directory-filter" x-model="year" aria-label="Filter by academic year"><option value="all">All years</option>@foreach ($academicYears as $academicYear)<option value="{{ $academicYear->id }}">{{ $academicYear->title }}</option>@endforeach</select>
                <select class="directory-filter" x-model="campus" aria-label="Filter by campus"><option value="all">All campuses</option>@foreach ($campuses as $campus)<option value="{{ $campus->id }}">{{ $campus->name }}</option>@endforeach</select>
                <select class="directory-filter" x-model="school" aria-label="Filter by school"><option value="all">All schools</option>@foreach ($schools as $school)<option value="{{ $school->id }}">{{ $school->name }}</option>@endforeach</select>
                <select class="directory-filter" x-model="major" aria-label="Filter by major"><option value="all">All majors</option>@foreach ($majors as $major)<option value="{{ $major->id }}">{{ $major->name }}</option>@endforeach</select>
            </div>
        </section>

        <section class="graduate-toolbar" aria-label="Graduate profile tools">
            <label class="search-field"><span aria-hidden="true">⌕</span><input type="search" x-model="search" placeholder="Search by name, school, or major" aria-label="Search graduate profiles"></label>
            <div class="graduate-toolbar-tools"><div class="view-switcher" aria-label="Profile view"><button type="button" class="view-button" :class="{ 'is-selected': view === 'grid' }" @click="view = 'grid'" aria-label="Grid view" title="Grid view"><span aria-hidden="true">▦</span></button><button type="button" class="view-button" :class="{ 'is-selected': view === 'list' }" @click="view = 'list'" aria-label="List view" title="List view"><span aria-hidden="true">☷</span></button></div></div>
        </section>

        <section class="graduate-section">
            <div class="section-heading"><div><p class="eyebrow">Profile directory</p><h2>Class of graduates</h2></div><span class="panel-meta">{{ $graduates->total() }} records</span></div>
            <div class="graduate-grid" :class="{ 'graduate-list': view === 'list' }">
                @forelse ($graduates as $graduate)
                    @php
                        $searchText = strtolower($graduate->name . ' ' . ($graduate->school?->name ?? '') . ' ' . ($graduate->major?->name ?? '') . ' ' . ($graduate->student_reference ?? ''));
                        $initials = collect(explode(' ', trim($graduate->name)))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('');
                    @endphp
                    <article class="graduate-card" x-show="(status === 'all' || status === '{{ $graduate->publish_status }}') && (year === 'all' || year === '{{ $graduate->graduation?->academic_year_id }}') && (campus === 'all' || campus === '{{ $graduate->campus_id }}') && (school === 'all' || school === '{{ $graduate->school_id }}') && (major === 'all' || major === '{{ $graduate->major_id }}') && @js($searchText).includes(search.toLowerCase())">
                        <div class="graduate-card-top"><div class="graduate-avatar">{{ $initials }}</div><span class="edition-status {{ $graduate->publish_status === 'published' ? 'is-active' : 'is-archived' }}">{{ ucfirst($graduate->publish_status) }}</span></div>
                        <h3>{{ $graduate->name }}</h3><p class="graduate-reference">{{ $graduate->student_reference ?: 'No student reference' }}</p>
                        <dl class="graduate-details"><div><dt>School</dt><dd>{{ $graduate->school?->name ?? 'Unassigned' }}</dd></div><div><dt>Major</dt><dd>{{ $graduate->major?->name ?? 'Unassigned' }}</dd></div><div><dt>Campus</dt><dd>{{ $graduate->campus?->name ?? 'Unassigned' }}</dd></div></dl>
                        <div class="graduate-footer"><span class="consent-label"><span class="status-dot {{ $graduate->consent_status === 'granted' ? 'status-approved' : 'status-reviewed' }}"></span>{{ ucfirst($graduate->consent_status ?: 'pending') }} consent</span><a href="{{ route('graduates.edit', $graduate) }}" class="text-link">Open profile <span aria-hidden="true">→</span></a></div>
                        <div class="graduate-actions">
                            @if ($graduate->publish_status === 'draft')<form method="POST" action="{{ route('graduates.submit', $graduate) }}">@csrf<button type="submit" class="action-button action-primary">Submit for review</button></form>
                            @elseif ($graduate->publish_status === 'reviewed' && in_array(Auth::user()->role?->role_name, ['admin', 'reviewer']))<form method="POST" action="{{ route('graduates.approve', $graduate) }}">@csrf<button type="submit" class="action-button action-primary">Approve</button></form><form method="POST" action="{{ route('graduates.reject', $graduate) }}">@csrf<button type="submit" class="action-button">Reject</button></form>
                            @elseif ($graduate->publish_status === 'approved' && $graduate->consent_status === 'granted' && in_array(Auth::user()->role?->role_name, ['admin', 'reviewer']))<form method="POST" action="{{ route('graduates.publish', $graduate) }}">@csrf<button type="submit" class="action-button action-primary">Publish profile</button></form>
                            @elseif ($graduate->publish_status === 'rejected')<form method="POST" action="{{ route('graduates.submit', $graduate) }}">@csrf<button type="submit" class="action-button action-primary">Resubmit for review</button></form>@endif
                        </div>
                    </article>
                @empty
                    <div class="empty-editions"><p class="eyebrow">No profiles yet</p><h3>Start the class directory.</h3><p>Add a graduate to begin shaping this year's collection.</p><a href="{{ route('graduates.create') }}" class="button button-navy">Add graduate <span aria-hidden="true">→</span></a></div>
                @endforelse
            </div>

            @if ($graduates->hasPages())
                <div class="liu-pagination" aria-label="Graduate pagination">
                    <div class="liu-pagination-summary">Showing <strong>{{ $graduates->firstItem() }}</strong>–<strong>{{ $graduates->lastItem() }}</strong> of <strong>{{ $graduates->total() }}</strong></div>
                    <div class="liu-pagination-controls">
                        @if ($graduates->onFirstPage())
                            <span class="liu-page-arrow is-disabled" aria-disabled="true">←</span>
                        @else
                            <a class="liu-page-arrow" href="{{ $graduates->previousPageUrl() }}" rel="prev" aria-label="Previous page">←</a>
                        @endif

                        @foreach ($graduates->getUrlRange(max(1, $graduates->currentPage() - 1), min($graduates->lastPage(), $graduates->currentPage() + 1)) as $page => $url)
                            <a href="{{ $url }}" class="liu-page-number {{ $page == $graduates->currentPage() ? 'is-current' : '' }}" aria-current="{{ $page == $graduates->currentPage() ? 'page' : 'false' }}">{{ $page }}</a>
                        @endforeach

                        @if ($graduates->hasMorePages())
                            <a class="liu-page-arrow" href="{{ $graduates->nextPageUrl() }}" rel="next" aria-label="Next page">→</a>
                        @else
                            <span class="liu-page-arrow is-disabled" aria-disabled="true">→</span>
                        @endif
                    </div>
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
