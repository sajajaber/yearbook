<x-app-layout>
    @php
    $roleName = Auth::user()->role?->role_name;
    $isReviewer = $roleName === 'reviewer';
    $canEdit = in_array($roleName, ['admin', 'editor'], true);
    $canReview = in_array($roleName, ['admin', 'reviewer'], true);
    $publishLabels = [
    'draft' => 'Draft',
    'reviewed' => 'Submitted for review',
    'approved' => 'Approved',
    'published' => 'Published',
    'rejected' => 'Changes requested',
    ];
    @endphp

    <x-slot name="header">
        <div class="dashboard-heading graduate-heading">
            <div>
                <p class="eyebrow">Yearbook office / people</p>
                <h1>Graduate profiles</h1>
            </div>
            @if ($canEdit)
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <a href="{{ route('graduates.import') }}" class="button button-navy"><span aria-hidden="true">↥</span> Import graduates</a>
                <a href="{{ route('graduates.create') }}" class="button button-red"><span aria-hidden="true">+</span> Add graduate</a>
            </div>
            @endif
        </div>
    </x-slot>

    <div class="dashboard-wrap graduate-wrap"
        x-data="{
             search: @js(request('search', '')),
             status: @js(request('status', 'all')),
             year: @js(request('year', 'all')),
             campus: @js(request('campus', 'all')),
             school: @js(request('school', 'all')),
             major: @js(request('major', 'all')),
             degreeLevel: @js($degreeLevel),
             go() {
                 const params = new URLSearchParams({
                     status: this.status,
                     year: this.year,
                     campus: this.campus,
                     school: this.school,
                     major: this.major,
                     degree_level: this.degreeLevel,
                     search: this.search,
                     sort: @js($sortBy)
                 });
                 window.location = @js(route('graduates.index')) + '?' + params.toString();
             },
             matches(r) {
                 return (this.status === 'all' || this.status === r.status)
                     && (this.year === 'all' || r.years.includes(this.year))
                     && (this.campus === 'all' || this.campus === r.campus)
                     && (this.school === 'all' || this.school === r.school)
                     && (this.major === 'all' || this.major === r.major)
                     && (this.degreeLevel === 'all' || this.degreeLevel === r.degreeLevel)
                     && r.text.includes(this.search.toLowerCase());
             }
         }">

        <section class="graduate-intro">
            <div>
                <p class="eyebrow eyebrow-light">The people behind the year</p>
                <h2>Make every<br>story count.</h2>
                <p>Review, refine, and publish the voices that will define this year's yearbook.</p>
            </div>
            <div class="graduate-intro-stats">
                <div><strong>{{ $graduates->total() }}</strong><span>profiles</span></div>
                <div><strong>{{ $consentGranted }}</strong><span>consents</span></div>
            </div>
        </section>

        @if (session('success') || session('error'))
        <div class="notice {{ session('error') ? 'notice-error' : 'notice-success' }}">{{ session('error') ?? session('success') }}</div>
        @endif

        <section class="filter-ribbon" aria-label="Graduate profile filters">
            <div class="filter-ribbon-heading">
                <div>
                    <p class="eyebrow">Filter profiles</p>
                    <span>Focus the directory by edition, place, or discipline.</span>
                </div>
                <a href="{{ route('graduates.index') }}" class="clear-filters">Clear filters</a>
            </div>

            <div class="graduate-filters">
                <a href="{{ request()->fullUrlWithQuery(['status' => null, 'page' => 1]) }}" class="filter-button {{ request('status', 'all') === 'all' ? 'is-selected' : '' }}">All <span>{{ $statusCounts->sum() }}</span></a>
                @foreach ($publishLabels as $key => $label)
                <a href="{{ request()->fullUrlWithQuery(['status' => $key, 'page' => 1]) }}" class="filter-button {{ request('status') === $key ? 'is-selected' : '' }}">{{ $label }} <span>{{ $statusCounts->get($key, 0) }}</span></a>
                @endforeach
            </div>

            <div class="ribbon-fields">
                <select class="directory-filter" x-model="year" name="year" @change="go()">
                    <option value="all">All years</option>
                    @foreach ($academicYears as $yearOption)
                    <option value="{{ $yearOption->id }}">{{ $yearOption->title }}</option>
                    @endforeach
                </select>
                <select class="directory-filter" x-model="campus" name="campus" @change="go()">
                    <option value="all">All campuses</option>
                    @foreach ($campuses as $campusOption)
                    <option value="{{ $campusOption->id }}">{{ $campusOption->name }}</option>
                    @endforeach
                </select>
                <select class="directory-filter" x-model="school" name="school" @change="go()">
                    <option value="all">All schools</option>
                    @foreach ($schools as $schoolOption)
                    <option value="{{ $schoolOption->id }}">{{ $schoolOption->name }}</option>
                    @endforeach
                </select>
                <select class="directory-filter" x-model="major" name="major" @change="go()">
                    <option value="all">All majors</option>
                    @foreach ($majors as $majorOption)
                    <option value="{{ $majorOption->id }}">{{ $majorOption->name }}</option>
                    @endforeach
                </select>
                <select class="directory-filter" x-model="degreeLevel" name="degree_level" @change="go()">
                    <option value="all">All degree levels</option>
                    <option value="undergraduate">Undergraduates</option>
                    <option value="graduate">Graduates</option>
                </select>
            </div>
        </section>

        <section class="graduate-toolbar">
            <form method="GET" action="{{ route('graduates.index') }}" class="graduate-search-form">
                <input type="hidden" name="status" x-model="status">
                <input type="hidden" name="year" x-model="year">
                <input type="hidden" name="campus" x-model="campus">
                <input type="hidden" name="school" x-model="school">
                <input type="hidden" name="major" x-model="major">
                <input type="hidden" name="degree_level" x-model="degreeLevel">
                <input type="hidden" name="sort" value="{{ $sortBy }}">
                <label class="search-field"><span aria-hidden="true">⌕</span><input type="search" name="search" x-model="search" value="{{ request('search', '') }}" placeholder="Search by name, school, or major"></label>
            </form>

            <form method="GET" action="{{ route('graduates.index') }}" class="admin-sort-form">
                <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                <input type="hidden" name="year" value="{{ request('year', 'all') }}">
                <input type="hidden" name="campus" value="{{ request('campus', 'all') }}">
                <input type="hidden" name="school" value="{{ request('school', 'all') }}">
                <input type="hidden" name="major" value="{{ request('major', 'all') }}">
                <input type="hidden" name="degree_level" value="{{ $degreeLevel }}">
                <input type="hidden" name="search" value="{{ request('search', '') }}">
                <label for="graduate-sort" class="sr-only">Sort graduates</label>
                <select id="graduate-sort" class="directory-filter" name="sort" onchange="this.form.submit()" aria-label="Sort graduates">
                    <option value="name" @selected($sortBy==='name' )>Name A–Z</option>
                    <option value="name_desc" @selected($sortBy==='name_desc' )>Name Z–A</option>
                    <option value="latest" @selected($sortBy==='latest' )>Newest added</option>
                    <option value="oldest" @selected($sortBy==='oldest' )>Oldest added</option>
                </select>
            </form>
        </section>

        <section class="graduate-section">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Profile directory</p>
                    <h2>Class of graduates</h2>
                </div>
                <span class="panel-meta">{{ $graduates->total() }} records</span>
            </div>

            <div class="graduate-grid">
                @forelse ($graduates as $graduate)
                @php
                $row = [
                'status' => (string) $graduate->publish_status,
                'years' => collect([$graduate->academic_year_id, $graduate->graduation?->academic_year_id])
                ->filter()
                ->map(fn ($id) => (string) $id)
                ->values()
                ->all(),
                'campus' => (string) $graduate->campus_id,
                'school' => (string) $graduate->school_id,
                'major' => (string) $graduate->major_id,
                'degreeLevel' => (string) $graduate->degree_level,
                'text' => mb_strtolower(
                $graduate->name . ' '
                . ($graduate->school?->name ?? '') . ' '
                . ($graduate->major?->name ?? '') . ' '
                . ($graduate->student_reference ?? '')
                ),
                ];
                $initials = collect(explode(' ', trim($graduate->name)))
                ->filter()
                ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                ->take(2)
                ->implode('');
                @endphp

                <article class="graduate-card" x-show="matches(@js($row))">
                    <div class="graduate-card-top">
                        @if ($graduate->portraitMedia)
                        <img class="graduate-avatar graduate-avatar-photo" src="{{ Storage::disk('public')->url($graduate->portraitMedia->path) }}" alt="{{ $graduate->portraitMedia->alt_text ?? $graduate->name }}">
                        @else
                        <div class="graduate-avatar">{{ $initials }}</div>
                        @endif
                        <span class="edition-status {{ $graduate->publish_status === 'published' ? 'is-active' : 'is-archived' }}">{{ $publishLabels[$graduate->publish_status] ?? ucfirst($graduate->publish_status) }}</span>
                    </div>

                    <h3>{{ $graduate->name }}</h3>
                    <p class="graduate-reference">{{ $graduate->student_reference ?: 'No student reference' }}</p>

                    <dl class="graduate-details">
                        <div>
                            <dt>School</dt>
                            <dd>{{ $graduate->school?->name ?? 'Unassigned' }}</dd>
                        </div>
                        <div>
                            <dt>Major</dt>
                            <dd>{{ $graduate->major?->name ?? 'Unassigned' }}</dd>
                        </div>
                        <div>
                            <dt>Campus</dt>
                            <dd>{{ $graduate->campus?->name ?? 'Unassigned' }}</dd>
                        </div>
                    </dl>

                    @if ($graduate->reviewFeedback->count())
                    <div style="margin:12px 0;padding:10px;background:#fff8f0;border:1px solid #f1d6b2;border-radius:9px;font-size:12px;">
                        <strong>{{ $graduate->reviewFeedback->count() }} open review note{{ $graduate->reviewFeedback->count() > 1 ? 's' : '' }}</strong><br>
                        <span style="color:#64748b;">{{ \Illuminate\Support\Str::limit($graduate->reviewFeedback->first()->message, 90) }}</span>
                    </div>
                    @endif

                    <div class="graduate-footer">
                        <span class="consent-label"><span class="status-dot {{ $graduate->consent_status === 'granted' ? 'status-approved' : 'status-reviewed' }}"></span>{{ ucfirst($graduate->consent_status ?: 'pending') }} consent</span>
                        <a href="{{ $isReviewer ? route('reviews.graduates.show', $graduate) : route('graduates.edit', $graduate) }}" class="text-link">{{ $isReviewer ? 'Review profile' : 'Open profile' }} <span aria-hidden="true">→</span></a>
                    </div>

                    <div class="graduate-actions">
                        @if ($graduate->publish_status === 'draft' && ! $isReviewer)
                        <form method="POST" action="{{ route('graduates.submit', $graduate) }}">@csrf<button type="submit" class="action-button action-primary">Submit for review</button></form>
                        @elseif ($graduate->publish_status === 'reviewed' && $canReview)
                        <a href="{{ route('reviews.graduates.show', $graduate) }}" class="action-button action-primary">Review profile</a>
                        @elseif ($graduate->publish_status === 'approved' && $canReview)
                        <form method="POST" action="{{ route('graduates.publish', $graduate) }}">@csrf<button type="submit" class="action-button action-primary">Publish profile</button></form>
                        @elseif ($graduate->publish_status === 'rejected' && ! $isReviewer)
                        <form method="POST" action="{{ route('graduates.submit', $graduate) }}">@csrf<button type="submit" class="action-button action-primary">Resubmit for review</button></form>
                        @endif
                    </div>
                </article>
                @empty
                <div class="empty-editions">
                    <p class="eyebrow">No profiles yet</p>
                    <h3>Start the class directory.</h3>
                    <p>Add a graduate to begin shaping this year's collection.</p>
                    @if (! $isReviewer)
                    <a href="{{ route('graduates.create') }}" class="button button-navy">Add graduate →</a>
                    @endif
                </div>
                @endforelse
            </div>

            @if ($graduates->hasPages())
            <div class="liu-pagination" aria-label="Graduate pagination">
                <div class="liu-pagination-summary">Showing <strong>{{ $graduates->firstItem() }}</strong>–<strong>{{ $graduates->lastItem() }}</strong> of <strong>{{ $graduates->total() }}</strong></div>
                <div class="liu-pagination-controls">
                    @if ($graduates->onFirstPage())
                    <span class="liu-page-arrow is-disabled">←</span>
                    @else
                    <a class="liu-page-arrow" href="{{ $graduates->previousPageUrl() }}">←</a>
                    @endif

                    @foreach ($graduates->getUrlRange(max(1, $graduates->currentPage() - 1), min($graduates->lastPage(), $graduates->currentPage() + 1)) as $page => $url)
                    <a href="{{ $url }}" class="liu-page-number {{ $page == $graduates->currentPage() ? 'is-current' : '' }}">{{ $page }}</a>
                    @endforeach

                    @if ($graduates->hasMorePages())
                    <a class="liu-page-arrow" href="{{ $graduates->nextPageUrl() }}">→</a>
                    @else
                    <span class="liu-page-arrow is-disabled">→</span>
                    @endif
                </div>
            </div>
            @endif
        </section>
    </div>
</x-app-layout>