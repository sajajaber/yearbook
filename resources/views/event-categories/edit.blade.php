<h1>Edit Event Category</h1>

<form method="POST" action="{{ route('event-categories.update', $eventCategory->id) }}">
  @csrf
  @method('PUT')

  <label>Name</label>
  <input type="text" name="name" value="{{ $eventCategory->name }}">

  @error('name')
  <p style="color: red">{{ $message }}</p>
  @enderror

  <label>Description</label>
  <textarea name="description">{{ $eventCategory->description }}</textarea>

  <button type="submit">Update</button>
</form>
