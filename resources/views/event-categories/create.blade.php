<h1>Add Event Category</h1>

<form method="POST" action="{{ route('event-categories.store') }}">
  @csrf

  <label>Name</label>
  <input type="text" name="name">

  @error('name')
  <p style="color: red">{{ $message }}</p>
  @enderror

  <label>Description</label>
  <textarea name="description"></textarea>

  <button type="submit">Save</button>
</form>
