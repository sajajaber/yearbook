<h1>Add Academic Year</h1>

<form method="POST" action="{{ route('academic-years.store') }}">
    @csrf //inserts a hidden security token into the form
    // this is a defense against CSRF attacks (a malicious site tricking your browser into submitting a form to your app without you knowing)

    <label>Title</label>
    <input type="text" name="title">

    <label>Start Date</label>
    <input type="date" name="start_date">

    <label>End Date</label>
    <input type="date" name="end_date">

    <label>Status</label>
    <select name="status">
        <option value="draft">Draft</option>
        <option value="active">Active</option>
        <option value="archived">Archived</option>
    </select>

    <button type="submit">Save</button>
</form>