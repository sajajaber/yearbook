<h1>Edit School</h1>

<form method="POST" action="{{ route('schools.update', $school->id) }}">
  @csrf
  @method('PUT')

  <label>Name</label>
  <input type="text" name="name" value="{{ $school->name }}">

  <label>Code</label>
  <input type="text" name="code" value="{{ $school->code }}">

  <label>Status</label>
  <select name="status">
    <option value="active" @selected($school->status === 'active')>Active</option>
    <option value="archived" @selected($school->status === 'archived')>Archived</option>
  </select>

  <button type="submit">Update</button>
</form>
