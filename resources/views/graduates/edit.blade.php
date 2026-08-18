<h1>Edit Graduate</h1>

<form method="POST" action="{{ route('graduates.update', $graduate->id) }}">
  @csrf
  @method('PUT')

  <label>Name</label>
  <input type="text" name="name" value="{{ $graduate->name }}">
  @error('name')
  <p style="color: red">{{ $message }}</p>
  @enderror

  <label>Student Reference</label>
  <input type="text" name="student_reference" value="{{ $graduate->student_reference }}">

  <label>School</label>
  <select name="school_id" id="school_id">
    @foreach ($schools as $school)
    <option value="{{ $school->id }}">{{ $school->name }}</option>
    @endforeach
  </select>
  @error('school_id')
  <p style="color: red">{{ $message }}</p>
  @enderror

  <label>Major</label>
  <select name="major_id" id="major_id">
    @foreach ($majors as $major)
    <option value="{{ $major->id }}" data-school-id="{{ $major->school_id }}">
      {{ $major->name }}
    </option>
    @endforeach
  </select>
  @error('major_id')
  <p style="color: red">{{ $message }}</p>
  @enderror

  <label>Campus</label>
  <select name="campus_id">
    @foreach ($campuses as $campus)
    <option value="{{ $campus->id }}" @selected($graduate->campus_id === $campus->id)>{{ $campus->name }}</option>
    @endforeach
  </select>
  @error('campus_id')
  <p style="color: red">{{ $message }}</p>
  @enderror

  <label>Graduation</label>
  <select name="graduation_id">
    @foreach ($graduations as $graduation)
    <option value="{{ $graduation->id }}" @selected($graduate->graduation_id === $graduation->id)>{{ $graduation->ceremony_date }} — {{ $graduation->venue }}</option>
    @endforeach
  </select>
  @error('graduation_id')
  <p style="color: red">{{ $message }}</p>
  @enderror

  <label>Profile Text</label>
  <textarea name="profile_text">{{ $graduate->profile_text }}</textarea>

  <label>Future Plans</label>
  <textarea name="future_plans">{{ $graduate->future_plans }}</textarea>

  <label>Quote</label>
  <input type="text" name="quote" value="{{ $graduate->quote }}">

  <label>Consent Status</label>
  <select name="consent_status">
    <option value="pending" @selected($graduate->consent_status === 'pending')>Pending</option>
    <option value="granted" @selected($graduate->consent_status === 'granted')>Granted</option>
    <option value="declined" @selected($graduate->consent_status === 'declined')>Declined</option>
  </select>

  <label>Publish Status</label>
  <select name="publish_status">
    <option value="draft" @selected($graduate->publish_status === 'draft')>Draft</option>
    <option value="reviewed" @selected($graduate->publish_status === 'reviewed')>Reviewed</option>
    <option value="approved" @selected($graduate->publish_status === 'approved')>Approved</option>
    <option value="published" @selected($graduate->publish_status === 'published')>Published</option>
    <option value="archived" @selected($graduate->publish_status === 'archived')>Archived</option>
  </select>

  <button type="submit">Update</button>
</form>

<script>
  const schoolSelect = document.getElementById('school_id');
  const majorSelect = document.getElementById('major_id');
  const allMajorOptions = Array.from(majorSelect.options);

  function filterMajors() {
    const selectedSchoolId = schoolSelect.value;

    majorSelect.innerHTML = '';

    allMajorOptions.forEach(function(option) {
      if (option.dataset.schoolId === selectedSchoolId) {
        majorSelect.appendChild(option);
      }
    });
  }

  schoolSelect.addEventListener('change', filterMajors);
  filterMajors();

</script>
