<x-app-layout>
    <x-slot name="header">
        <div class="dashboard-heading event-heading">
            <div><p class="eyebrow">Yearbook office / stories</p><h1>Campus events</h1></div>
            @if (in_array(Auth::user()->role?->role_name, ['admin', 'editor']))<a href="{{ route('events.create') }}" class="button button-red"><span aria-hidden="true">+</span> Add event</a>@endif
        </div>
    </x-slot>

    @php $statusCounts = $events->groupBy('status')->map->count(); @endphp

    <div class="dashboard-wrap event-wrap" x-data="{ search: '', status: 'all', year: 'all', campus: 'all', school: 'all' }">
        <section class="event-intro">
            <div><p class="eyebrow eyebrow-light">The year in motion</p><h2>Capture campus<br>in the moment.</h2><p>Keep the stories, gatherings, and milestones that bring this year's yearbook to life.</p></div>
            <div class="event-intro-mark" aria-hidden="true">EVENTS<br><span>{{ now()->format('y') }}</span></div>
        </section>

        @if (session('success') || session('error'))<div class="notice {{ session('error') ? 'notice-error' : 'notice-success' }}">{{ session('error') ?? session('success') }}</div>@endif

        <section class="filter-ribbon" aria-label="Event filters">
            <div class="filter-ribbon-heading"><div><p class="eyebrow">Filter events</p><span>Focus the story calendar by edition or place.</span></div><button type="button" class="clear-filters" x-show="status !== 'all' || year !== 'all' || campus !== 'all' || school !== 'all'" @click="status = 'all'; year = 'all'; campus = 'all'; school = 'all'">Clear filters</button></div>
            <div class="event-filters">
                <button type="button" class="filter-button" :class="{ 'is-selected': status === 'all' }" @click="status = 'all'">All <span>{{ $events->count() }}</span></button>
                @foreach (['draft' => 'Draft', 'reviewed' => 'Review', 'approved' => 'Approved', 'published' => 'Published'] as $key => $label)
                    <button type="button" class="filter-button" :class="{ 'is-selected': status === '{{ $key }}' }" @click="status = '{{ $key }}'">{{ $label }} <span>{{ $statusCounts->get($key, 0) }}</span></button>
                @endforeach
            </div>
            <div class="ribbon-fields"><select class="directory-filter" x-model="year" aria-label="Filter by academic year"><option value="all">All years</option>@foreach ($academicYears as $academicYear)<option value="{{ $academicYear->id }}">{{ $academicYear->title }}</option>@endforeach</select><select class="directory-filter" x-model="campus" aria-label="Filter by campus"><option value="all">All campuses</option>@foreach ($campuses as $campus)<option value="{{ $campus->id }}">{{ $campus->name }}</option>@endforeach</select><select class="directory-filter" x-model="school" aria-label="Filter by school"><option value="all">All schools</option>@foreach ($schools as $school)<option value="{{ $school->id }}">{{ $school->name }}</option>@endforeach</select></div>
        </section>

        <section class="event-toolbar" aria-label="Event tools">
            <label class="search-field"><span aria-hidden="true">⌕</span><input type="search" x-model="search" placeholder="Search events" aria-label="Search events"></label>
        </section>

        <section class="event-section">
            <div class="section-heading"><div><p class="eyebrow">Story calendar</p><h2>Campus moments</h2></div><span class="panel-meta">{{ $events->count() }} records</span></div>
            <div class="event-grid">
                @forelse ($events as $event)
                    @php $searchText = strtolower($event->title . ' ' . ($event->category?->name ?? '') . ' ' . ($event->location ?? '') . ' ' . ($event->description ?? '')); @endphp
                    <article class="event-card" x-show="(status === 'all' || status === '{{ $event->status }}') && (year === 'all' || year === '{{ $event->academic_year_id }}') && (campus === 'all' || [{{ $event->campuses->pluck('id')->implode(',') }}].includes(Number(campus))) && (school === 'all' || [{{ $event->schools->pluck('id')->implode(',') }}].includes(Number(school))) && '{{ $searchText }}'.includes(search.toLowerCase())">
                        <div class="event-card-top"><span class="event-date"><strong>{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</strong><small>{{ \Carbon\Carbon::parse($event->event_date)->format('M Y') }}</small></span><span class="edition-status {{ $event->status === 'published' ? 'is-active' : 'is-archived' }}">{{ ucfirst($event->status) }}</span></div>
                        <p class="event-category">{{ $event->category?->name ?? 'Campus story' }}@if ($event->featured)<span>Featured</span>@endif</p>
                        <h3>{{ $event->title }}</h3>
                        <p class="event-location">{{ $event->location ?: 'Location to be announced' }} · {{ $event->academicYear?->title ?? 'No academic year' }}</p>
                        @if ($event->description)<p class="event-description">{{ \Illuminate\Support\Str::limit($event->description, 150) }}</p>@endif
                        @if ($event->aiGenerations->count())<div class="ai-summary"><span class="status-dot status-reviewed"></span> AI summary {{ ucfirst($event->aiGenerations->last()->status) }}</div>@endif
                        <div class="event-footer"><a href="{{ route('events.edit', $event) }}" class="text-link">Open event <span aria-hidden="true">→</span></a><form method="POST" action="{{ route('events.destroy', $event) }}">@csrf @method('DELETE')<button type="submit" class="text-button">Delete</button></form></div>
                        <div class="event-actions">
                            @if ($event->status === 'draft')<form method="POST" action="{{ route('events.submit', $event) }}">@csrf<button type="submit" class="action-button action-primary">Submit for review</button></form>
                            @elseif ($event->status === 'reviewed' && in_array(Auth::user()->role?->role_name, ['admin', 'reviewer']))<form method="POST" action="{{ route('events.approve', $event) }}">@csrf<button type="submit" class="action-button action-primary">Approve</button></form><form method="POST" action="{{ route('events.reject', $event) }}">@csrf<button type="submit" class="action-button">Reject</button></form>
                            @elseif ($event->status === 'approved' && in_array(Auth::user()->role?->role_name, ['admin', 'reviewer']))<form method="POST" action="{{ route('events.publish', $event) }}">@csrf<button type="submit" class="action-button action-primary">Publish event</button></form>@endif
                            @if (!$event->aiGenerations->count())<form method="POST" action="{{ route('events.generate-summary', $event) }}">@csrf<button type="submit" class="action-button">Generate summary</button></form>@endif
                        </div>
                    </article>
                @empty
                    <div class="empty-editions"><p class="eyebrow">No events yet</p><h3>Start the story calendar.</h3><p>Add a campus event to begin building this year's collection.</p><a href="{{ route('events.create') }}" class="button button-navy">Add event <span aria-hidden="true">→</span></a></div>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
