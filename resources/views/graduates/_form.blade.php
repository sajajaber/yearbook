@php($isEdit = isset($graduate))
@php($isAdmin = Auth::user()->role?->role_name === 'admin')
@php($canManageAi = in_array(Auth::user()->role?->role_name, ['admin', 'editor']))

<x-app-layout>
    <x-slot name="header">
        <div class="dashboard-heading graduate-heading">
            <div>
                <p class="eyebrow">Yearbook/ people</p>
                <h1>{{ $isEdit ? 'Edit graduate' : 'Add graduate' }}</h1>
            </div>
            <a href="{{ route('graduates.index') }}" class="text-link">Back to profiles <span aria-hidden="true">←</span></a>
        </div>
    </x-slot>

    <div class="dashboard-wrap graduate-form-wrap">
        @if ($errors->any())
        <div class="notice notice-error">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST" action="{{ $isEdit ? route('graduates.update', $graduate) : route('graduates.store') }}" enctype="multipart/form-data" class="graduate-form">
            @csrf
            @if ($isEdit) @method('PUT') @endif
            <div class="form-main">
                <section class="form-section">
                    <div class="form-section-heading">
                        <p class="eyebrow">Identity</p>
                        <h2>{{ $isEdit ? 'Profile details' : 'Who is graduating?' }}</h2>
                    </div>
                    <div class="form-grid form-grid-two">
                        <label class="form-field form-field-wide"><span>Full name</span><input type="text" name="name" value="{{ old('name', $graduate->name ?? '') }}" required>@error('name')<small>{{ $message }}</small>@enderror</label>
                        <label class="form-field"><span>Student reference</span><input type="text" name="student_reference" value="{{ old('student_reference', $graduate->student_reference ?? '') }}"></label>
                        <label class="form-field"><span>School</span><select name="school_id" id="school_id" required>@foreach ($schools as $school)<option value="{{ $school->id }}" @selected(old('school_id', $graduate->school_id ?? '') == $school->id)>{{ $school->name }}</option>@endforeach</select>@error('school_id')<small>{{ $message }}</small>@enderror</label>
                        <label class="form-field"><span>Major</span><select name="major_id" id="major_id" required>@foreach ($majors as $major)<option value="{{ $major->id }}" data-school-id="{{ $major->school_id }}" @selected(old('major_id', $graduate->major_id ?? '') == $major->id)>{{ $major->name }}</option>@endforeach</select>@error('major_id')<small>{{ $message }}</small>@enderror</label>
                        <label class="form-field"><span>Degree level</span><select name="degree_level" required>
                                <option value="undergraduate" @selected(old('degree_level', $graduate->degree_level ?? 'undergraduate') === 'undergraduate')>Undergraduate (Bachelor)</option>
                                <option value="graduate" @selected(old('degree_level', $graduate->degree_level ?? '') === 'graduate')>Graduate (Master's)</option>
                            </select>@error('degree_level')<small>{{ $message }}</small>@enderror</label>
                        <label class="form-field"><span>Campus</span><select name="campus_id" required>@foreach ($campuses as $campus)<option value="{{ $campus->id }}" @selected(old('campus_id', $graduate->campus_id ?? '') == $campus->id)>{{ $campus->name }}</option>@endforeach</select></label>
                        <label class="form-field"><span>Graduation edition</span><select name="graduation_id" required>@foreach ($graduations as $graduation)<option value="{{ $graduation->id }}" @selected(old('graduation_id', $graduate->graduation_id ?? '') == $graduation->id)>{{ $graduation->academicYear?->title ?? 'Edition' }} &middot; {{ $graduation->venue ?: 'Ceremony' }}</option>@endforeach</select></label>
                    </div>
                </section>

                <section class="form-section">
                    <div class="form-section-heading">
                        <p class="eyebrow">The story</p>
                        <h2>Give the profile a voice.</h2>
                    </div>
                    <div class="form-grid"><label class="form-field"><span>Profile text</span><textarea name="profile_text" rows="5">{{ old('profile_text', $graduate->profile_text ?? '') }}</textarea></label><label class="form-field"><span>Future plans</span><textarea name="future_plans" rows="4">{{ old('future_plans', $graduate->future_plans ?? '') }}</textarea></label><label class="form-field"><span>Quote</span><input type="text" name="quote" value="{{ old('quote', $graduate->quote ?? '') }}"></label></div>
                </section>
            </div>

            <aside class="form-aside">
                @if (session('success') || session('error'))<div class="notice {{ session('error') ? 'notice-error' : 'notice-success' }}">{{ session('error') ?? session('success') }}</div>@endif
                <section class="portrait-upload">
                    <p class="eyebrow">Portrait</p>
                    <h2>{{ $isEdit && $graduate->portraitMedia ? 'Update grad photo' : 'Add grad photo' }}</h2>@if ($isEdit && $graduate->portraitMedia)<img class="portrait-preview" src="{{ asset('storage/' . $graduate->portraitMedia->path) }}" alt="{{ $graduate->portraitMedia->alt_text }}">@else<div class="portrait-placeholder">{{ collect(explode(' ', trim($graduate->name ?? 'GR')))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('') }}</div>@endif<label class="upload-field"><span>Choose a new photo</span><input type="file" name="portrait" accept="image/jpeg,image/png,image/webp"><small>JPG, PNG, or WebP. Maximum 5 MB.</small></label>@error('portrait')<small class="form-error">{{ $message }}</small>@enderror<p class="upload-note">The photo will be added to the media library and linked to this graduate's profile.</p>
                </section>
                @if ($isEdit && !$graduate->aiGenerations->count() && $canManageAi)
                    <section class="form-section form-section-compact">
                        <div class="form-section-heading">
                            <p class="eyebrow">Writing assistant</p>
                            <h2>Build the biography.</h2>
                        </div>
                        <p class="upload-note">Generate a draft biography from this profile's details for review.</p>
                        {{-- This button is inside the main graduate form, so a nested form would be invalid HTML and would submit to graduates.update. --}}
                        <button
                            type="submit"
                            class="button button-red"
                            formaction="{{ route('graduates.generate-biography', $graduate) }}"
                            formmethod="POST"
                        >
                            Generate biography <span aria-hidden="true">→</span>
                        </button>
                    </section>
                @elseif ($isEdit && $graduate->aiGenerations->count())
                    <section class="form-section form-section-compact">
                        <div class="form-section-heading">
                            <p class="eyebrow">Writing assistant</p>
                            <h2>Biography draft ready.</h2>
                        </div>
                        <p class="upload-note">This generated biography is waiting in the editorial review queue.</p><a href="{{ route('ai-generations.index', ['type' => 'graduate_biography']) }}" class="text-link">Open biography review <span aria-hidden="true">→</span></a>
                    </section>
                @endif
                <section class="form-section form-section-compact">
                    <div class="form-section-heading">
                        <p class="eyebrow">Publishing</p>
                        <h2>Permissions</h2>
                    </div>@if ($isAdmin)<label class="form-field"><span>Consent status</span><select name="consent_status">
                            <option value="pending" @selected(old('consent_status', $graduate->consent_status ?? 'pending') === 'pending')>Pending</option>
                            <option value="granted" @selected(old('consent_status', $graduate->consent_status ?? '') === 'granted')>Granted</option>
                            <option value="declined" @selected(old('consent_status', $graduate->consent_status ?? '') === 'declined')>Declined</option>
                        </select></label><label class="form-field"><span>Publish status</span><select name="publish_status">
                            <option value="draft" @selected(old('publish_status', $graduate->publish_status ?? 'draft') === 'draft')>Draft</option>
                            <option value="reviewed" @selected(old('publish_status', $graduate->publish_status ?? '') === 'reviewed')>Reviewed</option>
                            <option value="approved" @selected(old('publish_status', $graduate->publish_status ?? '') === 'approved')>Approved</option>
                            <option value="published" @selected(old('publish_status', $graduate->publish_status ?? '') === 'published')>Published</option>
                            <option value="rejected" @selected(old('publish_status', $graduate->publish_status ?? '') === 'rejected')>Rejected</option>
                            <option value="archived" @selected(old('publish_status', $graduate->publish_status ?? '') === 'archived')>Archived</option>
                        </select></label>@else<label class="form-field"><span>Consent status</span><select disabled>
                            <option>{{ ucfirst($graduate->consent_status ?? 'pending') }}</option>
                        </select><small>Only an administrator can change permissions.</small></label><label class="form-field"><span>Publish status</span><select disabled>
                            <option>{{ ucfirst($graduate->publish_status ?? 'draft') }}</option>
                        </select></label>@endif
                </section>
                <div class="form-actions"><a href="{{ route('graduates.index') }}" class="button button-muted">Cancel</a><button type="submit" class="button button-navy">{{ $isEdit ? 'Update graduate' : 'Save graduate' }} <span aria-hidden="true">→</span></button></div>
            </aside>
        </form>
    </div>

    <script>
        const schoolSelect = document.getElementById('school_id');
        const majorSelect = document.getElementById('major_id');
        const allMajorOptions = Array.from(majorSelect.options);

        function filterMajors() {
            const selectedSchoolId = schoolSelect.value;
            majorSelect.innerHTML = '';
            allMajorOptions.forEach(option => {
                if (option.dataset.schoolId === selectedSchoolId) majorSelect.appendChild(option);
            });
        }
        schoolSelect.addEventListener('change', filterMajors);
        filterMajors();
    </script>
</x-app-layout>
