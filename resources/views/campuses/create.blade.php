<h1>Add Campus</h1>

<form method="POST" action="{{ route('campuses.store') }}">
  @csrf

  @error('code')
  <p style="color: red">{{ $message }}</p>
  @enderror

  <label>Name</label>
  <input type="text" name="name">

  <label>Code</label>
  <input type="text" name="code">

  <label>Status</label>
  <select name="status">
    <option value="active">Active</option>
    <option value="archived">Archived</option>
  </select>

  <button type="submit">Save</button>
</form>
