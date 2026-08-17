<h1>Schools</h1>

<a href="{{ route('schools.create') }}">Add School</a>

<ul>
  @foreach ($schools as $school)
  <li>
    {{ $school->name }} ({{ $school->code }}) — {{ $school->status }}

    <a href="{{ route('schools.edit', $school->id) }}">Edit</a>

    <form method="POST" action="{{ route('schools.update', $school->id) }}" style="display:inline">
      @csrf
      @method('PUT')
      <input type="hidden" name="name" value="{{ $school->name }}">
      <input type="hidden" name="code" value="{{ $school->code }}">
      <input type="hidden" name="status" value="{{ $school->status === 'active' ? 'archived' : 'active' }}">
      <button type="submit">{{ $school->status === 'active' ? 'Archive' : 'Unarchive' }}</button>
    </form>
  </li>
  @endforeach
</ul>
