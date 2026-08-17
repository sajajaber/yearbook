<h1>Edit Campus</h1>

<form method="POST" action="{{ route('campuses.update', $campus->id) }}">
  @csrf
  @method('PUT')

  @error('code')
  <p style="color: red">{{ $message }}</p>
  @enderror

  <label>Name</label>
  <input type="text" name="name" value="{{ $campus->name }}">

  <label>Code</label>
  <input type="text" name="code" value="{{ $campus->code }}">

  <label>Status</label>
  <select name="status">
    <option value="active" @selected($campus->status === 'active')>Active</option>
    <option value="archived" @selected($campus->status === 'archived')>Archived</option>
  </select>

  <button type="submit">Update</button>
</form>
