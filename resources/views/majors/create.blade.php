<h1>Add Major</h1>

<form method="POST" action="{{ route('majors.store') }}">
    @csrf

    <label>Name</label>
    <input type="text" name="name">

    <label>Code</label>
    <input type="text" name="code">

    <label>School</label>
    <select name="school_id">
        @foreach ($schools as $school)
            <option value="{{ $school->id }}">{{ $school->name }}</option>
        @endforeach
    </select>

    <button type="submit">Save</button>
</form>