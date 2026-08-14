<h1>Majors</h1>

<a href="{{ route('majors.create') }}">Add Major</a>

<ul>
    @foreach ($majors as $major)
        <li>
            {{ $major->name }} ({{ $major->code }}) — {{ $major->school->name }}

            <a href="{{ route('majors.edit', $major->id) }}">Edit</a>

            <form method="POST" action="{{ route('majors.destroy', $major->id) }}" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </li>
    @endforeach
</ul>