<h1>Edit Academic Year</h1>

<form method="POST" action="{{ route('academic-years.update', $academicYear->id) }}">
  @csrf
  @method('PUT')

  <label>Title</label>
  <input type="text" name="title" value="{{ $academicYear->title }}">

  <label>Start Date</label>
  <input type="date" name="start_date" value="{{ $academicYear->start_date }}">

  <label>End Date</label>
  <input type="date" name="end_date" value="{{ $academicYear->end_date }}">

  @error('title')
  <p style="color: red">{{ $message }}</p>
  @enderror

  @error('start_date')
  <p style="color: red">{{ $message }}</p>
  @enderror

  @error('end_date')
  <p style="color: red">{{ $message }}</p>
  @enderror
  
  <label>Status</label>
  <select name="status">
    <option value="draft" @selected($academicYear->status === 'draft')>Draft</option>
    <option value="active" @selected($academicYear->status === 'active')>Active</option>
    <option value="archived" @selected($academicYear->status === 'archived')>Archived</option>
  </select>

  <button type="submit">Update</button>
</form>
