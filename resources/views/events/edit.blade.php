<h1>Edit Event</h1>

<form method="POST" action="{{ route('events.update', $event->id) }}">
  @csrf
  @method('PUT')

  <label>Title</label>
  <input type="text" name="title" value="{{ $event->title }}">

  <label>Academic Year</label>
  <select name="academic_year_id">
    @foreach ($academicYears as $academicYear)
    <option value="{{ $academicYear->id }}" @selected($event->academic_year_id === $academicYear->id)>{{ $academicYear->title }}</option>
    @endforeach
  </select>

  <label>Campuses</label>
  @foreach ($campuses as $campus)
  <label>
    <input type="checkbox" name="campus_ids[]" value="{{ $campus->id }}" @checked($event->campuses->contains($campus->id))>
    {{ $campus->name }}
  </label>
  @endforeach

  <label>Schools</label>
  @foreach ($schools as $school)
  <label>
    <input type="checkbox" name="school_ids[]" value="{{ $school->id }}" @checked($event->schools->contains($school->id))>
    {{ $school->name }}
  </label>
  @endforeach

  <label>Category</label>
  <select name="category_id">
    @foreach ($categories as $category)
    <option value="{{ $category->id }}" @selected($event->category_id === $category->id)>{{ $category->name }}</option>
    @endforeach
  </select>

  <label>Event Date</label>
  <input type="date" name="event_date" value="{{ $event->event_date }}">

  <label>Description</label>
  <textarea name="description">{{ $event->description }}</textarea>

  <label>Location</label>
  <input type="text" name="location" value="{{ $event->location }}">

  <label>Status</label>
  <select name="status">
    <option value="draft" @selected($event->status === 'draft')>Draft</option>
    <option value="reviewed" @selected($event->status === 'reviewed')>Reviewed</option>
    <option value="approved" @selected($event->status === 'approved')>Approved</option>
    <option value="published" @selected($event->status === 'published')>Published</option>
    <option value="archived" @selected($event->status === 'archived')>Archived</option>
  </select>

  <label>
    <input type="checkbox" name="featured" value="1" @checked($event->featured)>
    Featured
  </label>

  <button type="submit">Update</button>
</form>
