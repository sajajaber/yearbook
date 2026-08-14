<h1>Edit Major</h1>

<form method="POST" action="{{ route('majors.update', $major->id) }}">
    @csrf
    @method('PUT')

    <label>Name</label>
    <input type="text" name="name" value="{{ $major->name }}">

    <label>Code</label>
    <input type="text" name="code" value="{{ $major->code }}">

    <label>School</label>
    <select name="school_id">
        @foreach ($schools as $school)
            <option value="{{ $school->id }}" @selected($major->school_id === $school->id)>{{ $school->name }}</option>
        @endforeach
    </select>

    <button type="submit">Update</button>
</form>