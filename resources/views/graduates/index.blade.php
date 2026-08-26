<x-app-layout>
    <x-slot name="header">
        <div class="dashboard-heading graduate-heading">
            <div><p class="eyebrow">Yearbook office / people</p><h1>Graduate profiles</h1></div>
            <a href="{{ route('graduates.create') }}" class="button button-red"><span aria-hidden="true">+</span> Add graduate</a>
        </div>
    </x-slot>

    @php
        $statusCounts = $graduates->groupBy('publish_status')->map->count();
        $consentGranted = $graduates->where('consent_status', 'granted')->count();
    @endphp

    <div class="dashboard-wrap graduate-wrap" x-data="{ search: '', status: 'all' }">
        <section class="graduate-intro">
            <div><p class="eyebrow eyebrow-light">The people behind the year</p><h2>Make every<br>story count.</h2><p>Review, refine, and publish the voices that will define this year's yearbook.</p></div>
            <div class="graduate-intro-stats"><div><strong>{{ $graduates->count() }}</strong><span>profiles</span></div><div><strong>{{ $consentGranted }}</strong><span>consents</span></div></div>
        </section>

        @if (session('success') || session('error'))<div class="notice {{ session('error') ? 'notice-error' : 'notice-success' }}">{{ session('error') ?? session('success') }}</div>@endif

        <section class="graduate-toolbar" aria-label="Graduate profile controls">
            <div class="graduate-filters">
                <button type="button" class="filter-button" :class="{ 'is-selected': status === 'all' }" @click="status = 'all'">All <span>{{ $graduates->count() }}</span></button>
                @foreach (['draft' => 'Draft', 'reviewed' => 'Review', 'approved' => 'Approved', 'published' => 'Published'] as $key => $label)
                    <button type="button" class="filter-button" :class="{ 'is-selected': status === '{{ $key }}' }" @click="status = '{{ $key }}'">{{ $label }} <span>{{ $statusCounts->get($key, 0) }}</span></button>
                @endforeach
            </div>
            <label class="search-field"><span aria-hidden="true">⌕</span><input type="search" x-model="search" placeholder="Search by name, school, or major" aria-label="Search graduate profiles"></label>
        </section>

        <section class="graduate-section">
            <div class="section-heading"><div><p class="eyebrow">Profile directory</p><h2>Class of graduates</h2></div><span class="panel-meta">{{ $graduates->count() }} records</span></div>
            <div class="graduate-grid">
                @forelse ($graduates as $graduate)
                    @php
                        $searchText = strtolower($graduate->name . ' ' . ($graduate->school?->name ?? '') . ' ' . ($graduate->major?->name ?? '') . ' ' . ($graduate->student_reference ?? ''));
                        $initials = collect(explode(' ', trim($graduate->name)))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('');
                    @endphp
                    <article class="graduate-card" x-show="(status === 'all' || status === '{{ $graduate->publish_status }}') && '{{ $searchText }}'.includes(search.toLowerCase())">
                        <div class="graduate-card-top"><div class="graduate-avatar">{{ $initials }}</div><span class="edition-status {{ $graduate->publish_status === 'published' ? 'is-active' : 'is-archived' }}">{{ ucfirst($graduate->publish_status) }}</span></div>
                        <h3>{{ $graduate->name }}</h3><p class="graduate-reference">{{ $graduate->student_reference ?: 'No student reference' }}</p>
                        <dl class="graduate-details"><div><dt>School</dt><dd>{{ $graduate->school?->name ?? 'Unassigned' }}</dd></div><div><dt>Major</dt><dd>{{ $graduate->major?->name ?? 'Unassigned' }}</dd></div><div><dt>Campus</dt><dd>{{ $graduate->campus?->name ?? 'Unassigned' }}</dd></div></dl>
                        <div class="graduate-footer"><span class="consent-label"><span class="status-dot {{ $graduate->consent_status === 'granted' ? 'status-approved' : 'status-reviewed' }}"></span>{{ ucfirst($graduate->consent_status ?: 'pending') }} consent</span><a href="{{ route('graduates.edit', $graduate) }}" class="text-link">Open profile <span aria-hidden="true">→</span></a></div>
                        <div class="graduate-actions">
                            @if ($graduate->publish_status === 'draft')<form method="POST" action="{{ route('graduates.submit', $graduate) }}">@csrf<button type="submit" class="action-button action-primary">Submit for review</button></form>
                            @elseif ($graduate->publish_status === 'reviewed')<form method="POST" action="{{ route('graduates.approve', $graduate) }}">@csrf<button type="submit" class="action-button action-primary">Approve</button></form><form method="POST" action="{{ route('graduates.reject', $graduate) }}">@csrf<button type="submit" class="action-button">Reject</button></form>
                            @elseif ($graduate->publish_status === 'approved' && $graduate->consent_status === 'granted')<form method="POST" action="{{ route('graduates.publish', $graduate) }}">@csrf<button type="submit" class="action-button action-primary">Publish profile</button></form>@endif
                            @if (!$graduate->aiGenerations->count())<form method="POST" action="{{ route('graduates.generate-biography', $graduate) }}">@csrf<button type="submit" class="action-button">Generate biography</button></form>@endif
                        </div>
                    </article>
                @empty
                    <div class="empty-editions"><p class="eyebrow">No profiles yet</p><h3>Start the class directory.</h3><p>Add a graduate to begin shaping this year's collection.</p><a href="{{ route('graduates.create') }}" class="button button-navy">Add graduate <span aria-hidden="true">→</span></a></div>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>