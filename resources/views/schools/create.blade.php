<h1>Add School</h1>

<form method="POST" action="{{ route('schools.store') }}">
    @csrf

    <label>Name</label>
    <input type="text" name="name">

    <label>Code</label>
    <input type="text" name="code">

    <button type="submit">Save</button>
</form>