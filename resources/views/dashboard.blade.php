<x-app-layout>
    <x-slot name="header">
        <div class="dashboard-heading">
            <div>
                <p class="eyebrow">Yearbook office / {{ now()->format('Y') }}</p>
                <h1>Hello, {{ Str::before(Auth::user()->name, ' ') }}.</h1>
            </div>
            @if (in_array(Auth::user()->role?->role_name, ['admin', 'editor']))<a href="{{ route('graduates.create') }}" class="button button-red"><span aria-hidden="true">+</span> Add graduate</a>@endif
        </div>
    </x-slot>

    @php
    $eventStatuses = $eventsByStatus->keyBy('status');
    $publishStatuses = $graduatesByPublishStatus->keyBy('publish_status');
    $statusLabels = ['draft' => 'Draft', 'reviewed' => 'In review', 'approved' => 'Approved', 'published' => 'Published'];
    @endphp

    <div class="dashboard-wrap">
        <section class="welcome-banner" aria-labelledby="dashboard-intro">
            <div>
                <p class="eyebrow eyebrow-light">Class of {{ now()->year }}</p>
                <h2 id="dashboard-intro">Shape the yearbook story.</h2>
                <p>Keep campus moments, graduate profiles, and final approvals moving in one place.</p>
            </div>
            <div class="banner-mark" aria-hidden="true">YB<br><span>{{ now()->format('y') }}</span></div>
        </section>

        <section class="stat-grid" aria-label="Yearbook totals">
            <a href="{{ route('graduates.index') }}" class="stat-card stat-card-featured"><span class="stat-label">Graduate profiles</span><strong>{{ number_format($totalGraduates) }}</strong><span class="stat-link">Manage profiles <span aria-hidden="true">→</span></span></a>
            <a href="{{ route('events.index') }}" class="stat-card"><span class="stat-label">Campus stories</span><strong>{{ number_format($totalEvents) }}</strong><span class="stat-link">Browse events <span aria-hidden="true">→</span></span></a>
            @if (in_array(Auth::user()->role?->role_name, ['admin', 'editor']))<a href="{{ route('graduations.index') }}" class="stat-card"><span class="stat-label">Graduations</span><strong>{{ number_format($totalGraduations) }}</strong><span class="stat-link">View editions <span aria-hidden="true">→</span></span></a><a href="{{ route('media.index') }}" class="stat-card"><span class="stat-label">Media assets</span><strong>{{ number_format($totalMedia) }}</strong><span class="stat-link">Open library <span aria-hidden="true">→</span></span></a>@else<div class="stat-card"><span class="stat-label">Graduations</span><strong>{{ number_format($totalGraduations) }}</strong></div><div class="stat-card"><span class="stat-label">Media assets</span><strong>{{ number_format($totalMedia) }}</strong></div>@endif
        </section>

        <section class="content-grid">
            <div class="panel panel-wide">
                <div class="panel-header">
                    <div>
                        <p class="eyebrow">Editorial workflow</p>
                        <h2>Graduate profiles</h2>
                    </div><a href="{{ route('graduates.index') }}" class="text-link">View all <span aria-hidden="true">→</span></a>
                </div>
                <div class="workflow-list">
                    @foreach ($statusLabels as $status => $label)
                    @php $count = $publishStatuses->get($status)?->total ?? 0; @endphp
                    <div class="workflow-row">
                        <div class="workflow-name"><span class="status-dot status-{{ $status }}"></span>{{ $label }}</div>
                        <div class="workflow-track"><span style="width: {{ $totalGraduates ? min(100, ($count / $totalGraduates) * 100) : 0 }}%"></span></div><strong>{{ $count }}</strong>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="panel panel-accent">
                <p class="eyebrow">Next up</p>
                <h2>Review queue</h2>
                <p class="queue-number">{{ $publishStatuses->get('reviewed')?->total ?? 0 }}</p>
                <p class="panel-note">profiles are waiting for an editorial decision.</p><a href="{{ route('graduates.index', ['publish_status' => 'reviewed']) }}" class="button button-navy">Open queue <span aria-hidden="true">→</span></a>
            </div>

            <div class="panel panel-wide">
                <div class="panel-header">
                    <div>
                        <p class="eyebrow">By school</p>
                        <h2>Representation across campus</h2>
                    </div><span class="panel-meta">{{ $graduatesBySchool->count() }} schools</span>
                </div>
                <div class="school-grid">
                    @forelse ($graduatesBySchool->sortByDesc('total')->take(6) as $school)
                    <div class="school-item"><span>{{ $school->school?->name ?? 'Unassigned' }}</span><strong>{{ $school->total }}</strong></div>
                    @empty
                    <p class="empty-state">No school data has been added yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="panel panel-dark">
                <p class="eyebrow eyebrow-light">Campus stories</p>
                <h2>Event pipeline</h2>
                <div class="mini-stats">
                    <div><strong>{{ $eventStatuses->get('draft')?->total ?? 0 }}</strong><span>Draft</span></div>
                    <div><strong>{{ $eventStatuses->get('reviewed')?->total ?? 0 }}</strong><span>Review</span></div>
                    <div><strong>{{ $eventStatuses->get('published')?->total ?? 0 }}</strong><span>Live</span></div>
                </div>@if (in_array(Auth::user()->role?->role_name, ['admin', 'editor']))<a href="{{ route('events.create') }}" class="button button-outline">Add an event <span aria-hidden="true">→</span></a>@endif
            </div>
        </section>
    </div>
</x-app-layout>