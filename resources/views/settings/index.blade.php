<style>
    .settings-wrap {
        padding-top: 0;
    }

    .settings-intro {
        background: linear-gradient(135deg, #002a5c 0%, #073972 100%);
        color: var(--white);
        min-height: 210px;
        padding: 38px 48px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .settings-intro::after {
        content: "";
        width: 320px;
        height: 320px;
        border: 1px solid rgba(255, 176, 52, 0.35);
        border-radius: 50%;
        position: absolute;
        right: -90px;
        bottom: -170px;
    }

    .settings-intro h2 {
        color: var(--white);
        font-size: clamp(22px, 2.6vw, 32px);
        line-height: 1.25;
        margin: 0 0 12px;
    }

    .settings-intro p:not(.eyebrow) {
        color: #d8e3ef;
        max-width: 460px;
        margin: 0;
        font-size: 13px;
    }

    .settings-intro-stats {
        color: #ffce6b;
        text-align: right;
        z-index: 1;
    }

    .settings-intro-stats strong {
        display: block;
        font:
            700 44px/1 "Merriweather",
            serif;
    }

    .settings-intro-stats span {
        color: #d8e3ef;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.4px;
        text-transform: uppercase;
    }

    .settings-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        background: var(--white);
        border: 1px solid var(--line);
        padding: 10px;
        margin-bottom: 20px;
    }

    .settings-panel {
        background: var(--white);
        border: 1px solid var(--line);
        border-top: 4px solid var(--ink);
        padding: 28px;
    }

    .settings-panel-heading {
        border-bottom: 1px solid var(--line);
        padding-bottom: 16px;
        margin-bottom: 20px;
    }

    .settings-panel-heading h2 {
        color: var(--ink);
        font-size: 21px;
        margin: 4px 0 0;
    }

    .settings-add-form {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        background: #f7fbff;
        border: 1px dashed #8ca9c3;
        padding: 16px 18px;
        margin-bottom: 22px;
    }

    .settings-add-form input,
    .settings-add-form select {
        flex: 1;
        min-width: 150px;
        padding: 10px 11px;
        border: 1px solid var(--line);
        background: var(--white);
        font:
            12px "Inter",
            sans-serif;
    }

    .settings-add-form .button {
        white-space: nowrap;
    }

    .settings-list {
        display: grid;
        gap: 8px;
    }

    .settings-row {
        border: 1px solid var(--line);
        transition: border-color 0.15s ease;
    }

    .settings-row:has(.settings-row-display:hover) {
        border-color: #8ca9c3;
    }

    .settings-row-display,
    .settings-row-edit {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 12px 16px;
    }

    .settings-row-avatar {
        width: 38px;
        height: 38px;
        flex: 0 0 auto;
        display: grid;
        place-items: center;
        background: #e7f0fa;
        color: var(--ink);
        font:
            700 11px "Merriweather",
            serif;
        text-transform: uppercase;
    }

    .settings-row-main {
        display: flex;
        flex-direction: column;
        gap: 2px;
        flex: 1;
        min-width: 0;
    }

    .settings-row-name {
        color: var(--ink);
        font-weight: 700;
        font-size: 13px;
    }

    .settings-row-meta {
        color: var(--ink-soft);
        font-size: 11px;
    }

    .settings-row-actions {
        display: flex;
        gap: 14px;
        flex: 0 0 auto;
    }

    .text-button-danger:hover {
        color: #c0522a;
    }

    .settings-row-edit {
        flex-wrap: wrap;
    }

    .settings-row-edit input,
    .settings-row-edit select {
        flex: 1;
        min-width: 120px;
        padding: 9px 10px;
        border: 1px solid var(--line);
        font:
            12px "Inter",
            sans-serif;
    }

    .settings-empty {
        border: 1px dashed var(--line);
        padding: 32px;
        text-align: center;
    }

    .settings-empty h3 {
        color: var(--ink);
        font:
            700 17px "Merriweather",
            serif;
        margin: 6px 0 6px;
    }

    .settings-empty p:not(.eyebrow) {
        color: var(--ink-soft);
        font-size: 12px;
        margin: 0;
    }


    /* Majors */
    .majors-add-form {
        margin-bottom: 26px;
    }

    .majors-groups {
        display: grid;
        gap: 20px;
    }

    .majors-group {
        border: 1px solid var(--line);
        background: var(--white);
        overflow: hidden;
    }

    .majors-group-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 20px;
        margin: 0;
        background: #f7fbff;
        border-bottom: 1px solid var(--line);
    }

    .majors-group-heading-main {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .majors-group-icon {
        width: 38px;
        height: 38px;
        flex: 0 0 auto;
        display: grid;
        place-items: center;
        background: #e7f0fa;
        color: var(--ink);
        font: 700 11px "Merriweather", serif;
        text-transform: uppercase;
    }

    .majors-group-heading h3 {
        color: var(--ink);
        font: 700 15px "Merriweather", serif;
        margin: 0;
    }

    .majors-group-subtitle {
        display: block;
        margin-top: 3px;
        color: var(--ink-soft);
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .majors-group-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 30px;
        height: 26px;
        padding: 0 9px;
        border-radius: 20px;
        background: #e7f0fa;
        color: var(--ink);
        font: 700 11px "Inter", sans-serif;
        flex: 0 0 auto;
    }

    .majors-list {
        display: grid;
    }

    .major-row {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 14px 20px;
        border-bottom: 1px solid var(--line);
        transition: background 0.15s ease;
    }

    .major-row:last-child {
        border-bottom: 0;
    }

    .major-row:hover {
        background: #fafcff;
    }

    .major-code {
        width: 44px;
        height: 44px;
        flex: 0 0 auto;
        display: grid;
        place-items: center;
        background: #eef4fa;
        color: var(--ink);
        font: 700 11px "Inter", sans-serif;
        letter-spacing: .5px;
        text-transform: uppercase;
    }

    .major-info {
        flex: 1;
        min-width: 0;
    }

    .major-name {
        display: block;
        color: var(--ink);
        font-size: 13px;
        font-weight: 700;
        line-height: 1.35;
    }

    .major-code-label {
        display: block;
        margin-top: 3px;
        color: var(--ink-soft);
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .8px;
    }

    .major-actions {
        display: flex;
        align-items: center;
        gap: 14px;
        flex: 0 0 auto;
    }

    .major-edit-form {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 4px 0;
    }

    .major-edit-form input,
    .major-edit-form select {
        flex: 1;
        min-width: 140px;
        padding: 9px 10px;
        border: 1px solid var(--line);
        background: var(--white);
        font: 12px "Inter", sans-serif;
    }

    @media (max-width: 700px) {
        .majors-group-heading {
            align-items: flex-start;
        }

        .major-row {
            align-items: flex-start;
        }

        .major-actions {
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
        }

        .major-edit-form {
            flex-direction: column;
            align-items: stretch;
        }
    }

    @media (max-width: 700px) {
        .settings-intro {
            flex-direction: column;
            align-items: start;
            gap: 20px;
            padding: 28px 24px;
        }

        .settings-intro-stats {
            text-align: left;
        }

        .settings-add-form,
        .settings-row-display,
        .settings-row-edit {
            flex-direction: column;
            align-items: stretch;
        }

        .settings-row-actions {
            justify-content: flex-end;
        }
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <div class="dashboard-heading">
            <div>
                <p class="eyebrow">Yearbook office / configuration</p>
                <h1>Settings</h1>
            </div>
        </div>
    </x-slot>

    @php
    $totalItems = $academicYears->count() + $campuses->count() + $schools->count() + $majors->count() + $eventCategories->count();
    @endphp

    <div class="dashboard-wrap settings-wrap" x-data="{ tab: '{{ session('active_tab', 'academic-years') }}' }">
        <section class="settings-intro">
            <div>
                <p class="eyebrow eyebrow-light">The building blocks</p>
                <h2>Configure the reference data<br>everything else is built on.</h2>
                <p>Academic years, campuses, schools, majors, and event categories — the shared vocabulary behind every record in the yearbook.</p>
            </div>
        </section>

        @if (session('success') || session('error'))
        <div class="notice {{ session('error') ? 'notice-error' : 'notice-success' }}">{{ session('error') ?? session('success') }}</div>
        @endif
        @if (session('generated_password'))
        <div class="notice notice-success">New user created. Temporary password: <strong>{{ session('generated_password') }}</strong> — share this securely; it will not be shown again.</div>
        @endif
        @if ($errors->any())
        <div class="notice notice-error"><strong>Please review the highlighted fields.</strong>
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <div class="settings-tabs">
            <button type="button" class="filter-button" :class="{ 'is-selected': tab === 'academic-years' }" @click="tab = 'academic-years'">Academic Years <span>{{ $academicYears->count() }}</span></button>
            <button type="button" class="filter-button" :class="{ 'is-selected': tab === 'campuses' }" @click="tab = 'campuses'">Campuses <span>{{ $campuses->count() }}</span></button>
            <button type="button" class="filter-button" :class="{ 'is-selected': tab === 'schools' }" @click="tab = 'schools'">Schools <span>{{ $schools->count() }}</span></button>
            <button type="button" class="filter-button" :class="{ 'is-selected': tab === 'majors' }" @click="tab = 'majors'">Majors <span>{{ $majors->count() }}</span></button>
            <button type="button" class="filter-button" :class="{ 'is-selected': tab === 'categories' }" @click="tab = 'categories'">Event Categories <span>{{ $eventCategories->count() }}</span></button>
            <button type="button" class="filter-button" :class="{ 'is-selected': tab === 'users' }" @click="tab = 'users'">Users <span>{{ $users->count() }}</span></button>
        </div>

        {{-- ACADEMIC YEARS --}}
        <section class="settings-panel" x-show="tab === 'academic-years'" x-cloak>
            <div class="settings-panel-heading">
                <div>
                    <p class="eyebrow">Editions</p>
                    <h2>Academic years</h2>
                </div>
            </div>

            <form method="POST" action="{{ route('academic-years.store') }}" class="settings-add-form">
                @csrf
                <input type="text" name="title" placeholder="Title, e.g. 2026-2027" required>
                <input type="date" name="start_date" required aria-label="Start date">
                <input type="date" name="end_date" required aria-label="End date">
                <textarea name="dedication" placeholder="Dedication text for this yearbook edition" rows="2">{{ old('dedication') }}</textarea>
                <select name="status">
                    <option value="draft">Draft</option>
                    <option value="active">Active</option>
                    <option value="archived">Archived</option>
                </select>
                <button type="submit" class="button button-navy">Add year</button>
            </form>

            <div class="settings-list">
                @forelse ($academicYears as $year)
                <div class="settings-row" x-data="{ editing: false }">
                    <div class="settings-row-display" x-show="!editing" x-transition.opacity>
                        <div class="settings-row-avatar">{{ Str::substr($year->title, 0, 2) }}</div>
                        <div class="settings-row-main">
                            <span class="settings-row-name">{{ $year->title }}</span>
                            <span class="settings-row-meta">{{ \Carbon\Carbon::parse($year->start_date)->format('M Y') }} → {{ \Carbon\Carbon::parse($year->end_date)->format('M Y') }}</span>
                        </div>
                        <span class="edition-status {{ $year->status === 'active' ? 'is-active' : 'is-archived' }}">{{ ucfirst($year->status) }}</span>
                        <div class="settings-row-actions">
                            <button type="button" class="text-button" @click="editing = true">Edit</button>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('academic-years.update', $year->id) }}" class="settings-row-edit" x-show="editing" x-cloak x-transition.opacity>
                        @csrf @method('PUT')
                        <input type="text" name="title" value="{{ $year->title }}" required>
                        <input type="date" name="start_date" value="{{ $year->start_date }}" required>
                        <input type="date" name="end_date" value="{{ $year->end_date }}" required>
                        <select name="status">
                            <option value="draft" @selected($year->status === 'draft')>Draft</option>
                            <option value="active" @selected($year->status === 'active')>Active</option>
                            <option value="archived" @selected($year->status === 'archived')>Archived</option>
                        </select>
                        <button type="submit" class="button button-navy">Save</button>
                        <button type="button" class="button button-muted" @click="editing = false">Cancel</button>
                    </form>
                </div>
                @empty
                <div class="settings-empty">
                    <p class="eyebrow">No academic years yet</p>
                    <h3>Add the first edition.</h3>
                    <p>Academic years anchor every event and graduation to a point in time.</p>
                </div>
                @endforelse
            </div>
        </section>

        {{-- CAMPUSES --}}
        <section class="settings-panel" x-show="tab === 'campuses'" x-cloak>
            <div class="settings-panel-heading">
                <div>
                    <p class="eyebrow">Locations</p>
                    <h2>Campuses</h2>
                </div>
            </div>

            <form method="POST" action="{{ route('campuses.store') }}" class="settings-add-form">
                @csrf
                <input type="hidden" name="tab" value="campuses">
                <input type="text" name="name" placeholder="Campus name" required>
                <input type="text" name="code" placeholder="Code, e.g. BEY" required>
                <select name="status">
                    <option value="active">Active</option>
                    <option value="archived">Archived</option>
                </select>
                <button type="submit" class="button button-navy">Add campus</button>
            </form>

            <div class="settings-list">
                @forelse ($campuses as $campus)
                <div class="settings-row" x-data="{ editing: false }">
                    <div class="settings-row-display" x-show="!editing" x-transition.opacity>
                        <div class="settings-row-avatar">{{ Str::substr($campus->code, 0, 2) }}</div>
                        <div class="settings-row-main">
                            <span class="settings-row-name">{{ $campus->name }}</span>
                            <span class="settings-row-meta">{{ $campus->code }}</span>
                        </div>
                        <span class="edition-status {{ $campus->status === 'active' ? 'is-active' : 'is-archived' }}">{{ ucfirst($campus->status) }}</span>
                        <div class="settings-row-actions">
                            <button type="button" class="text-button" @click="editing = true">Edit</button>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('campuses.update', $campus->id) }}" class="settings-row-edit" x-show="editing" x-cloak x-transition.opacity>
                        @csrf @method('PUT')
                        <input type="hidden" name="tab" value="campuses">
                        <input type="text" name="name" value="{{ $campus->name }}" required>
                        <input type="text" name="code" value="{{ $campus->code }}" required>
                        <select name="status">
                            <option value="active" @selected($campus->status === 'active')>Active</option>
                            <option value="archived" @selected($campus->status === 'archived')>Archived</option>
                        </select>
                        <button type="submit" class="button button-navy">Save</button>
                        <button type="button" class="button button-muted" @click="editing = false">Cancel</button>
                    </form>
                </div>
                @empty
                <div class="settings-empty">
                    <p class="eyebrow">No campuses yet</p>
                    <h3>Add the first campus.</h3>
                    <p>Campuses tie events, graduations, and graduates to a place.</p>
                </div>
                @endforelse
            </div>
        </section>

        {{-- SCHOOLS --}}
        <section class="settings-panel" x-show="tab === 'schools'" x-cloak>
            <div class="settings-panel-heading">
                <div>
                    <p class="eyebrow">Academic units</p>
                    <h2>Schools</h2>
                </div>
            </div>

            <form method="POST" action="{{ route('schools.store') }}" class="settings-add-form">
                @csrf
                <input type="text" name="name" placeholder="School name" required>
                <input type="text" name="code" placeholder="Code, e.g. ART" required>
                <select name="status">
                    <option value="active">Active</option>
                    <option value="archived">Archived</option>
                </select>
                <button type="submit" class="button button-navy">Add school</button>
            </form>

            <div class="settings-list">
                @forelse ($schools as $school)
                <div class="settings-row" x-data="{ editing: false }">
                    <div class="settings-row-display" x-show="!editing" x-transition.opacity>
                        <div class="settings-row-avatar">{{ Str::substr($school->code, 0, 2) }}</div>
                        <div class="settings-row-main">
                            <span class="settings-row-name">{{ $school->name }}</span>
                            <span class="settings-row-meta">{{ $school->code }}</span>
                        </div>
                        <span class="edition-status {{ $school->status === 'active' ? 'is-active' : 'is-archived' }}">{{ ucfirst($school->status) }}</span>
                        <div class="settings-row-actions">
                            <button type="button" class="text-button" @click="editing = true">Edit</button>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('schools.update', $school->id) }}" class="settings-row-edit" x-show="editing" x-cloak x-transition.opacity>
                        @csrf @method('PUT')
                        <input type="text" name="name" value="{{ $school->name }}" required>
                        <input type="text" name="code" value="{{ $school->code }}" required>
                        <select name="status">
                            <option value="active" @selected($school->status === 'active')>Active</option>
                            <option value="archived" @selected($school->status === 'archived')>Archived</option>
                        </select>
                        <button type="submit" class="button button-navy">Save</button>
                        <button type="button" class="button button-muted" @click="editing = false">Cancel</button>
                    </form>
                </div>
                @empty
                <div class="settings-empty">
                    <p class="eyebrow">No schools yet</p>
                    <h3>Add the first school.</h3>
                    <p>Schools group majors and give graduates their academic home.</p>
                </div>
                @endforelse
            </div>
        </section>

        {{-- MAJORS --}}
        <section class="settings-panel" x-show="tab === 'majors'" x-cloak>
            <div class="settings-panel-heading">
                <div>
                    <h2>Majors</h2>
                </div>
            </div>

            <form method="POST" action="{{ route('majors.store') }}" class="settings-add-form majors-add-form">
                @csrf
                <input type="hidden" name="tab" value="majors">
                <select name="school_id" required>
                    <option value="">Choose school</option>
                    @foreach ($schools as $school)
                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                    @endforeach
                </select>
                <input type="text" name="name" placeholder="Major name" required>
                <input type="text" name="code" placeholder="Code, e.g. CSCI" required>
                <button type="submit" class="button button-navy">Add major</button>
            </form>

            @php
            $majorsBySchool = $majors->groupBy(fn ($major) => $major->school?->name ?? 'Unassigned');
            @endphp

            <div class="majors-groups">
                @forelse ($majorsBySchool as $schoolName => $schoolMajors)
                <div class="majors-group">
                    <div class="majors-group-heading">
                        <div class="majors-group-heading-main">
                            <div class="majors-group-icon">
                                {{ Str::substr($schoolName, 0, 2) }}
                            </div>
                            <div>
                                <h3>{{ $schoolName }}</h3>
                            </div>
                        </div>

                        <span class="majors-group-count">
                            {{ $schoolMajors->count() }}
                        </span>
                    </div>

                    <div class="majors-list">
                        @foreach ($schoolMajors as $major)
                        <div x-data="{ editing: false }">
                            <div class="major-row" x-show="!editing" x-transition.opacity>
                                <div class="major-code">
                                    {{ Str::substr($major->code, 0, 2) }}
                                </div>

                                <div class="major-info">
                                    <span class="major-name">{{ $major->name }}</span>
                                    <span class="major-code-label">{{ $major->code }}</span>
                                </div>

                                <div class="major-actions">
                                    <button type="button" class="text-button" @click="editing = true">
                                        Edit
                                    </button>

                                </div>
                            </div>

                            <form
                                method="POST"
                                action="{{ route('majors.update', $major->id) }}"
                                class="major-row major-edit-form"
                                x-show="editing"
                                x-cloak
                                x-transition.opacity>
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="tab" value="majors">

                                <input
                                    type="text"
                                    name="name"
                                    value="{{ $major->name }}"
                                    required>

                                <input
                                    type="text"
                                    name="code"
                                    value="{{ $major->code }}"
                                    required>

                                <select name="school_id" required>
                                    @foreach ($schools as $school)
                                    <option
                                        value="{{ $school->id }}"
                                        @selected($major->school_id === $school->id)
                                        >
                                        {{ $school->name }}
                                    </option>
                                    @endforeach
                                </select>

                                <button type="submit" class="button button-navy">
                                    Save
                                </button>

                                <button
                                    type="button"
                                    class="button button-muted"
                                    @click="editing = false">
                                    Cancel
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
                @empty
                <div class="settings-empty">
                    <p class="eyebrow">No majors yet</p>
                    <h3>Add the first major.</h3>
                    <p>Majors connect graduates to a specific field within their school.</p>
                </div>
                @endforelse
            </div>
        </section>

        {{-- EVENT CATEGORIES --}}
        <section class="settings-panel" x-show="tab === 'categories'" x-cloak>
            <div class="settings-panel-heading">
                <div>
                    <p class="eyebrow">Coverage types</p>
                    <h2>Event categories</h2>
                </div>
            </div>

            <form method="POST" action="{{ route('event-categories.store') }}" class="settings-add-form">
                @csrf
                <input type="text" name="name" placeholder="Category name" required>
                <input type="text" name="description" placeholder="Description, optional">
                <button type="submit" class="button button-navy">Add category</button>
            </form>

            <div class="settings-list">
                @forelse ($eventCategories as $category)
                <div class="settings-row" x-data="{ editing: false }">
                    <div class="settings-row-display" x-show="!editing" x-transition.opacity>
                        <div class="settings-row-avatar">{{ Str::substr($category->name, 0, 2) }}</div>
                        <div class="settings-row-main">
                            <span class="settings-row-name">{{ $category->name }}</span>
                            <span class="settings-row-meta">{{ $category->description ?: 'No description' }}</span>
                        </div>
                        <div class="settings-row-actions">
                            <button type="button" class="text-button" @click="editing = true">Edit</button>
                            <form method="POST" action="{{ route('event-categories.destroy', $category->id) }}" onsubmit="return confirm('Delete this category?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-button text-button-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('event-categories.update', $category->id) }}" class="settings-row-edit" x-show="editing" x-cloak x-transition.opacity>
                        @csrf @method('PUT')
                        <input type="text" name="name" value="{{ $category->name }}" required>
                        <input type="text" name="description" value="{{ $category->description }}">
                        <button type="submit" class="button button-navy">Save</button>
                        <button type="button" class="button button-muted" @click="editing = false">Cancel</button>
                    </form>
                </div>
                @empty
                <div class="settings-empty">
                    <p class="eyebrow">No categories yet</p>
                    <h3>Add the first category.</h3>
                    <p>Categories group events so visitors can browse by type.</p>
                </div>
                @endforelse
            </div>
        </section>

        {{-- USERS --}}
        <section class="settings-panel" x-show="tab === 'users'" x-cloak x-data="{ showAddUser: false }">
            <div class="settings-panel-heading">
                <div>
                    <p class="eyebrow">User accounts</p>
                    <h2>Users</h2>
                </div>
            </div>

            <button type="button" class="button button-navy" style="margin-bottom: 18px;" @click="showAddUser = !showAddUser">
                <span aria-hidden="true">+</span> Add user
            </button>

            <form method="POST" action="{{ route('users.store') }}" class="settings-add-form" x-show="showAddUser" x-cloak x-transition.opacity>
                @csrf
                <input type="text" name="name" placeholder="Full name" required>
                <input type="email" name="email" placeholder="Email address" required>
                <input type="password" name="password" placeholder="Password" required minlength="8">
                <select name="role_id" required>
                    <option value="">Choose role</option>
                    @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ ucfirst($role->role_name) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="button button-navy">Create user</button>
            </form>

            <div class="settings-list">
                @forelse ($users as $user)
                <div class="settings-row" x-data="{ editing: false }">
                    <div class="settings-row-display" x-show="!editing" x-transition.opacity>
                        <div class="settings-row-avatar">{{ Str::substr($user->name, 0, 2) }}</div>
                        <div class="settings-row-main">
                            <span class="settings-row-name">{{ $user->name }}</span>
                            <span class="settings-row-meta">{{ $user->email }} · {{ ucfirst($user->role?->role_name ?? 'No role') }}</span>
                        </div>
                        <span class="edition-status {{ $user->status === 'active' ? 'is-active' : 'is-archived' }}">{{ ucfirst($user->status) }}</span>
                        <div class="settings-row-actions">
                            <button type="button" class="text-button" @click="editing = true">Edit</button>
                            <form method="POST" action="{{ route('users.destroy', $user->id) }}" onsubmit="return confirm('Delete this user?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-button text-button-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('users.update', $user->id) }}" class="settings-row-edit" x-show="editing" x-cloak x-transition.opacity>
                        @csrf @method('PUT')
                        <input type="text" name="name" value="{{ $user->name }}" required>
                        <input type="email" name="email" value="{{ $user->email }}" required>
                        <input type="password" name="password" placeholder="Leave blank to keep current password" minlength="8">
                        <select name="role_id" required>
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @selected($user->role_id === $role->id)>{{ ucfirst($role->role_name) }}</option>
                            @endforeach
                        </select>
                        <select name="status" required>
                            <option value="active" @selected($user->status === 'active')>Active</option>
                            <option value="suspended" @selected($user->status === 'suspended')>Suspended</option>
                        </select>
                        <button type="submit" class="button button-navy">Save</button>
                        <button type="button" class="button button-muted" @click="editing = false">Cancel</button>
                    </form>
                </div>
                @empty
                <div class="settings-empty">
                    <p class="eyebrow">No users yet</p>
                    <h3>Add the first user.</h3>
                    <p>Users can log in to manage the yearbook content.</p>
                </div>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>