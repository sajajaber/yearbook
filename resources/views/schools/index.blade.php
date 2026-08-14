<h1>Schools</h1>

<a href="{{ route('schools.create') }}">Add School</a>

<ul>
    @foreach ($schools as $school)
        <li>
            {{ $school->name }} ({{ $school->code }})

            <a href="{{ route('schools.edit', $school->id) }}">Edit</a>

            <form method="POST" action="{{ route('schools.destroy', $school->id) }}" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </li>
    @endforeach
</ul>