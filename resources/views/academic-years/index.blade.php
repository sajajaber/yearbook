<h1>Academic Years</h1>

<ul>
    @foreach ($academicYears as $year)
        <li>
            {{ $year->title }} ({{ $year->status }})

            <a href="{{ route('academic-years.edit', $year->id) }}">Edit</a>

            <form method="POST" action="{{ route('academic-years.destroy', $year->id) }}" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </li>
    @endforeach
</ul>