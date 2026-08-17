<h1>Graduates</h1>

<a href="{{ route('graduates.create') }}">Add Graduate</a>

<ul>
  @foreach ($graduates as $graduate)
  <li>
    {{ $graduate->name }} — {{ $graduate->major->name }}, {{ $graduate->school->name }}
    ({{ $graduate->consent_status }} / {{ $graduate->publish_status }})

    <a href="{{ route('graduates.edit', $graduate->id) }}">Edit</a>

    <form method="POST" action="{{ route('graduates.destroy', $graduate->id) }}" style="display:inline">
      @csrf
      @method('DELETE')
      <button type="submit">Delete</button>
    </form>
  </li>
  @endforeach
</ul>
