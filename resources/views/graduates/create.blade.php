<h1>Add Graduate</h1>

<form method="POST" action="{{ route('graduates.store') }}">
  @csrf

  <label>Name</label>
  <input type="text" name="name">
  @error('name')
  <p style="color: red">{{ $message }}</p>
  @enderror

  <label>Student Reference</label>
  <input type="text" name="student_reference">

  <label>School</label>
  <select name="school_id">
    @foreach ($schools as $school)
    <option value="{{ $school->id }}">{{ $school->name }}</option>
    @endforeach
  </select>
  @error('school_id')
  <p style="color: red">{{ $message }}</p>
  @enderror

  <label>Major</label>
  <select name="major_id">
    @foreach ($majors as $major)
    <option value="{{ $major->id }}">{{ $major->name }}</option>
    @endforeach
  </select>
  @error('major_id')
  <p style="color: red">{{ $message }}</p>
  @enderror

  <label>Campus</label>
  <select name="campus_id">
    @foreach ($campuses as $campus)
    <option value="{{ $campus->id }}">{{ $campus->name }}</option>
    @endforeach
  </select>
  @error('campus_id')
  <p style="color: red">{{ $message }}</p>
  @enderror

  <label>Graduation</label>
  <select name="graduation_id">
    @foreach ($graduations as $graduation)
    <option value="{{ $graduation->id }}">{{ $graduation->ceremony_date }} — {{ $graduation->venue }}</option>
    @endforeach
  </select>
  @error('graduation_id')
  <p style="color: red">{{ $message }}</p>
  @enderror

  <label>Profile Text</label>
  <textarea name="profile_text"></textarea>

  <label>Future Plans</label>
  <textarea name="future_plans"></textarea>

  <label>Quote</label>
  <input type="text" name="quote">

  <label>Consent Status</label>
  <select name="consent_status">
    <option value="pending">Pending</option>
    <option value="granted">Granted</option>
    <option value="declined">Declined</option>
  </select>

  <label>Publish Status</label>
  <select name="publish_status">
    <option value="draft">Draft</option>
    <option value="reviewed">Reviewed</option>
    <option value="approved">Approved</option>
    <option value="published">Published</option>
    <option value="archived">Archived</option>
  </select>

  <button type="submit">Save</button>
</form>
