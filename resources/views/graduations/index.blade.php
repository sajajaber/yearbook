<h1>Graduations</h1>

<a href="{{ route('graduations.create') }}">Add Graduation</a>

<ul>
  @foreach ($graduations as $graduation)
  <li>
    {{ $graduation->ceremony_date }} — {{ $graduation->venue }}
    ({{ $graduation->academicYear->title }})

    <a href="{{ route('graduations.edit', $graduation->id) }}">Edit</a>

    <form method="POST" action="{{ route('graduations.destroy', $graduation->id) }}" style="display:inline">
      @csrf
      @method('DELETE')
      <button type="submit">Delete</button>
    </form>
  </li>
  @endforeach
</ul>
