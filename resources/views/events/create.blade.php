<h1>Add Event</h1>

<form method="POST" action="{{ route('events.store') }}">
    @csrf

    <label>Title</label>
    <input type="text" name="title">

    <label>Academic Year</label>
    <select name="academic_year_id">
        @foreach ($academicYears as $academicYear)
            <option value="{{ $academicYear->id }}">{{ $academicYear->title }}</option>
        @endforeach
    </select>

    <label>Campuses</label>
    @foreach ($campuses as $campus)
        <label>
            <input type="checkbox" name="campus_ids[]" value="{{ $campus->id }}">
            {{ $campus->name }}
        </label>
    @endforeach

    <label>Schools</label>
    @foreach ($schools as $school)
        <label>
            <input type="checkbox" name="school_ids[]" value="{{ $school->id }}">
            {{ $school->name }}
        </label>
    @endforeach

    <label>Category</label>
    <select name="category_id">
        @foreach ($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>

    <label>Event Date</label>
    <input type="date" name="event_date">

    <label>Description</label>
    <textarea name="description"></textarea>

    <label>Location</label>
    <input type="text" name="location">

    <label>Status</label>
    <select name="status">
        <option value="draft">Draft</option>
        <option value="reviewed">Reviewed</option>
        <option value="approved">Approved</option>
        <option value="published">Published</option>
        <option value="archived">Archived</option>
    </select>

    <label>
        <input type="checkbox" name="featured" value="1">
        Featured
    </label>

    <button type="submit">Save</button>
</form>