@php($isEdit = isset($graduate))
@php($isAdmin = Auth::user()->role?->role_name === 'admin')
@php($canManageAi = in_array(Auth::user()->role?->role_name, ['admin', 'editor']))
@php($availableAcademicYears = $academicYears ?? \App\Models\AcademicYear::orderByDesc('title')->get())
@php($activeAcademicYear = $activeAcademicYear ?? $availableAcademicYears->firstWhere('status', 'active'))
@php($approvedLinks = old('approved_links', $graduate->approved_links ?? []))

<x-app-layout>
    <x-slot name="header">
        <div class="dashboard-heading graduate-heading">
            <div><p class="eyebrow">Yearbook / people</p><h1>{{ $isEdit ? 'Edit graduate' : 'Add graduate' }}</h1></div>
            <a href="{{ route('graduates.index') }}" class="text-link">Back to profiles <span aria-hidden="true">←</span></a>
        </div>
    </x-slot>
    <div class="dashboard-wrap graduate-form-wrap">
        @if ($errors->any())<div class="notice notice-error"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @if ($isEdit && !$graduate->aiGenerations->count() && $canManageAi)<form id="generate-biography-form" method="POST" action="{{ route('graduates.generate-biography', $graduate) }}">@csrf</form>@endif
        <form method="POST" action="{{ $isEdit ? route('graduates.update', $graduate) : route('graduates.store') }}" enctype="multipart/form-data" class="graduate-form">
            @csrf @if ($isEdit) @method('PUT') @endif
            <div class="form-main">
                <section class="form-section">
                    <div class="form-section-heading"><p class="eyebrow">Identification</p><h2>{{ $isEdit ? 'Profile details' : 'Who is graduating?' }}</h2></div>
                    <div class="form-grid form-grid-two">
                        <label class="form-field form-field-wide"><span>Full name</span><input type="text" name="name" value="{{ old('name', $graduate->name ?? '') }}" required>@error('name')<small>{{ $message }}</small>@enderror</label>
                        <label class="form-field"><span>Student reference</span><input type="text" name="student_reference" value="{{ old('student_reference', $graduate->student_reference ?? '') }}"></label>
                        <label class="form-field"><span>School</span><select name="school_id" id="school_id" required>@foreach ($schools as $school)<option value="{{ $school->id }}" @selected(old('school_id', $graduate->school_id ?? '') == $school->id)>{{ $school->name }}</option>@endforeach</select>@error('school_id')<small>{{ $message }}</small>@enderror</label>
                        <label class="form-field"><span>Major</span><select name="major_id" id="major_id" required>@foreach ($majors as $major)<option value="{{ $major->id }}" data-school-id="{{ $major->school_id }}" @selected(old('major_id', $graduate->major_id ?? '') == $major->id)>{{ $major->name }}</option>@endforeach</select>@error('major_id')<small>{{ $message }}</small>@enderror</label>
                        <label class="form-field"><span>Degree level</span><select name="degree_level" required><option value="undergraduate" @selected(old('degree_level', $graduate->degree_level ?? 'undergraduate') === 'undergraduate')>Undergraduate (Bachelor)</option><option value="graduate" @selected(old('degree_level', $graduate->degree_level ?? '') === 'graduate')>Graduate (Master's)</option></select>@error('degree_level')<small>{{ $message }}</small>@enderror</label>
                        <label class="form-field"><span>GPA</span><input type="number" name="gpa" value="{{ old('gpa', $graduate->gpa ?? '') }}" min="0" max="4" step="0.01" placeholder="e.g. 3.72"><small>Fixed university scale: 4.00.</small>@error('gpa')<small>{{ $message }}</small>@enderror</label>
                        <label class="form-field"><span>Campus</span><select name="campus_id" required>@foreach ($campuses as $campus)<option value="{{ $campus->id }}" @selected(old('campus_id', $graduate->campus_id ?? '') == $campus->id)>{{ $campus->name }}</option>@endforeach</select></label>
                        <label class="form-field"><span>Graduation year</span><select name="academic_year_id" id="academic_year_id" required><option value="">Select academic year</option>@foreach ($availableAcademicYears as $academicYear)<option value="{{ $academicYear->id }}" @selected(old('academic_year_id', $graduate->academic_year_id ?? ($activeAcademicYear?->id ?? '')) == $academicYear->id)>{{ $academicYear->title }}{{ $academicYear->status === 'active' ? ' · Active' : ($academicYear->status === 'archived' ? ' · Archived' : '') }}</option>@endforeach</select>@error('academic_year_id')<small>{{ $message }}</small>@enderror</label>
                        <label class="form-field"><span>Ceremony attendance</span><select name="graduation_id" id="graduation_id"><option value="">Not attending a ceremony</option>@foreach ($graduations as $graduation)<option value="{{ $graduation->id }}" data-academic-year-id="{{ $graduation->academic_year_id }}" @selected(old('graduation_id', $graduate->graduation_id ?? '') == $graduation->id)>{{ $graduation->academicYear?->title ?? 'Edition' }} · {{ $graduation->venue ?: 'Ceremony' }}</option>@endforeach</select>@error('graduation_id')<small>{{ $message }}</small>@enderror</label>
                    </div>
                </section>

                <section class="form-section">
                    <div class="form-section-heading"><p class="eyebrow">Profile text</p><h2>Give the profile a voice.</h2></div>
                    <div class="form-grid">
                        <label class="form-field"><span>Approved biography / profile text</span><textarea name="profile_text" rows="6">{{ old('profile_text', $graduate->profile_text ?? '') }}</textarea><small>This may be entered by an authorized editor or produced as an AI-assisted draft for editorial review.</small></label>
                        <label class="form-field"><span>Future plans</span><textarea name="future_plans" rows="4">{{ old('future_plans', $graduate->future_plans ?? '') }}</textarea></label>
                        <label class="form-field"><span>Quote</span><input type="text" name="quote" value="{{ old('quote', $graduate->quote ?? '') }}" maxlength="255"></label>
                    </div>
                </section>

                <section class="form-section">
                    <div class="form-section-heading"><p class="eyebrow">Academic highlights</p><h2>Achievements and work.</h2></div>
                    <div class="form-grid">
                        <label class="form-field"><span>Academic achievements, awards & honors</span><textarea name="achievements" rows="5">{{ old('achievements', is_array($graduate->achievements ?? null) ? implode("\n", $graduate->achievements) : ($graduate->achievements ?? '')) }}</textarea><small>Use one achievement per line.</small></label>
                        <label class="form-field"><span>Projects & research</span><textarea name="projects" rows="5">{{ old('projects', is_array($graduate->projects ?? null) ? implode("\n", $graduate->projects) : ($graduate->projects ?? '')) }}</textarea><small>Use one project or research item per line.</small></label>
                    </div>
                </section>

                <section class="form-section">
                    <div class="form-section-heading"><p class="eyebrow">University engagement</p><h2>Life beyond the classroom.</h2></div>
                    <label class="form-field"><span>Clubs, volunteering, activities, leadership & community engagement</span><textarea name="activities" rows="6">{{ old('activities', is_array($graduate->activities ?? null) ? implode("\n", $graduate->activities) : ($graduate->activities ?? '')) }}</textarea><small>Use one activity or engagement item per line.</small></label>
                </section>

                <section class="form-section">
                    <div class="form-section-heading"><p class="eyebrow">Professional exposure</p><h2>Experience, training and credentials.</h2></div>
                    <div class="form-grid">
                        <label class="form-field"><span>Internships & selected work experience</span><textarea name="internships" rows="5">{{ old('internships', is_array($graduate->internships ?? null) ? implode("\n", $graduate->internships) : ($graduate->internships ?? '')) }}</textarea><small>Use one experience per line.</small></label>
                        <label class="form-field"><span>Certifications & training</span><textarea name="certifications_training" rows="5">{{ old('certifications_training', $graduate->certifications_training ?? '') }}</textarea><small>Include certification/training name, provider and year where appropriate.</small></label>
                        <label class="form-field"><span>Professional interests</span><textarea name="professional_interests" rows="4">{{ old('professional_interests', $graduate->professional_interests ?? '') }}</textarea><small>Optional areas of professional interest.</small></label>
                    </div>
                </section>

                <section class="form-section">
                    <div class="form-section-heading"><p class="eyebrow">Approved links</p><h2>Let the profile travel further.</h2></div>
                    <p class="upload-note">Only add links approved for public publication. These are shown only on a published profile with granted consent.</p>
                    <div class="form-grid">
                        @for ($i = 0; $i < 3; $i++)
                            <label class="form-field"><span>{{ ['Portfolio / website', 'LinkedIn / professional profile', 'GitHub / other approved link'][$i] }}</span><input type="url" name="approved_links[]" value="{{ old('approved_links.' . $i, $approvedLinks[$i] ?? '') }}" placeholder="https://"></label>
                        @endfor
                    </div>
                </section>
            </div>

            <aside class="form-aside">
                @if (session('success') || session('error'))<div class="notice {{ session('error') ? 'notice-error' : 'notice-success' }}">{{ session('error') ?? session('success') }}</div>@endif
                <section class="portrait-upload">
                    <p class="eyebrow">Profile media</p><h2>{{ $isEdit && $graduate->portraitMedia ? 'Update grad photo' : 'Add grad photo' }}</h2>
                    @if ($isEdit && $graduate->portraitMedia)<img class="portrait-preview" src="{{ asset('storage/' . $graduate->portraitMedia->path) }}" alt="{{ $graduate->portraitMedia->alt_text }}">@else<div class="portrait-placeholder">{{ collect(explode(' ', trim($graduate->name ?? 'GR')))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('') }}</div>@endif
                    <label class="upload-field"><span>Choose a new photo</span><input type="file" name="portrait" accept="image/jpeg,image/png,image/webp"><small>JPG, PNG, or WebP. Maximum 5 MB.</small></label>@error('portrait')<small class="form-error">{{ $message }}</small>@enderror
                    <p class="upload-note">Approved portraits are processed for consistent yearbook presentation.</p>
                </section>

                <section class="form-section form-section-compact">
                    <div class="form-section-heading"><p class="eyebrow">Professional profile</p><h2>{{ $isEdit && $graduate->resumeMedia ? 'Update resume / CV' : 'Add resume / CV' }}</h2></div>
                    @if ($isEdit && $graduate->resumeMedia)<div class="upload-note"><strong>{{ $graduate->resumeMedia->file_name }}</strong><br><span>PDF currently attached to this graduate.</span></div>@else<p class="upload-note">Upload the graduate's resume as a PDF. It is public only when the graduate has granted consent and the profile is published.</p>@endif
                    <label class="upload-field"><span>{{ $isEdit && $graduate->resumeMedia ? 'Choose a replacement PDF' : 'Choose a resume PDF' }}</span><input type="file" name="resume" accept="application/pdf"><small>PDF only. Maximum 10 MB.</small></label>@error('resume')<small class="form-error">{{ $message }}</small>@enderror
                </section>

                @if ($isEdit && !$graduate->aiGenerations->count() && $canManageAi)<section class="form-section form-section-compact"><div class="form-section-heading"><p class="eyebrow">Writing assistant</p><h2>Build the biography.</h2></div><p class="upload-note">Generate a draft biography from this profile's details for review.</p><button type="submit" form="generate-biography-form" class="button button-red">Generate biography <span aria-hidden="true">→</span></button></section>@elseif ($isEdit && $graduate->aiGenerations->count())<section class="form-section form-section-compact"><div class="form-section-heading"><p class="eyebrow">Writing assistant</p><h2>Biography draft ready.</h2></div><p class="upload-note">This generated biography is waiting in the editorial review queue.</p><a href="{{ route('ai-generations.index', ['type' => 'graduate_biography']) }}" class="text-link">Open biography review <span aria-hidden="true">→</span></a></section>@endif

                <section class="form-section form-section-compact">
                    <div class="form-section-heading"><p class="eyebrow">Privacy & publication</p><h2>Permissions</h2></div>
                    <p class="upload-note">Consent determines public detail exposure. Pending or declined graduates can be published as name-only directory entries; full profile details require granted consent.</p>
                    @if ($isAdmin)
                        <label class="form-field"><span>Consent status</span><select name="consent_status"><option value="pending" @selected(old('consent_status', $graduate->consent_status ?? 'pending') === 'pending')>Pending</option><option value="granted" @selected(old('consent_status', $graduate->consent_status ?? '') === 'granted')>Granted</option><option value="declined" @selected(old('consent_status', $graduate->consent_status ?? '') === 'declined')>Declined</option></select></label>
                        <label class="form-field"><span>Publish status</span><select name="publish_status"><option value="draft" @selected(old('publish_status', $graduate->publish_status ?? 'draft') === 'draft')>Draft</option><option value="reviewed" @selected(old('publish_status', $graduate->publish_status ?? '') === 'reviewed')>Submitted for review</option><option value="approved" @selected(old('publish_status', $graduate->publish_status ?? '') === 'approved')>Approved</option><option value="published" @selected(old('publish_status', $graduate->publish_status ?? '') === 'published')>Published</option><option value="rejected" @selected(old('publish_status', $graduate->publish_status ?? '') === 'rejected')>Changes requested</option><option value="archived" @selected(old('publish_status', $graduate->publish_status ?? '') === 'archived')>Archived</option></select></label>
                    @else
                        <label class="form-field"><span>Consent status</span><select disabled><option>{{ ucfirst($graduate->consent_status ?? 'pending') }}</option></select><small>Only an administrator can change permissions.</small></label>
                        <label class="form-field"><span>Publish status</span><select disabled><option>{{ $graduate->publish_status === 'reviewed' ? 'Submitted for review' : ($graduate->publish_status === 'rejected' ? 'Changes requested' : ucfirst($graduate->publish_status ?? 'draft')) }}</option></select></label>
                    @endif
                </section>
                <div class="form-actions"><a href="{{ route('graduates.index') }}" class="button button-muted">Cancel</a><button type="submit" class="button button-navy">{{ $isEdit ? 'Update graduate' : 'Save graduate' }} <span aria-hidden="true">→</span></button></div>
            </aside>
        </form>
    </div>
    <script>
        const schoolSelect = document.getElementById('school_id');
        const majorSelect = document.getElementById('major_id');
        const academicYearSelect = document.getElementById('academic_year_id');
        const graduationSelect = document.getElementById('graduation_id');
        const allMajorOptions = Array.from(majorSelect.options);
        const allGraduationOptions = Array.from(graduationSelect.options);
        function filterMajors(){const selectedSchoolId=schoolSelect.value;majorSelect.innerHTML='';allMajorOptions.forEach(option=>{if(option.dataset.schoolId===selectedSchoolId)majorSelect.appendChild(option);});}
        function filterGraduations(){const selectedYearId=academicYearSelect.value;const currentValue=graduationSelect.value;graduationSelect.innerHTML='';graduationSelect.appendChild(allGraduationOptions[0]);allGraduationOptions.slice(1).forEach(option=>{if(option.dataset.academicYearId===selectedYearId)graduationSelect.appendChild(option);});graduationSelect.value=Array.from(graduationSelect.options).some(option=>option.value===currentValue)?currentValue:'';}
        schoolSelect.addEventListener('change',filterMajors);academicYearSelect.addEventListener('change',filterGraduations);filterMajors();filterGraduations();
    </script>
</x-app-layout>
