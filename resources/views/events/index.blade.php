<h1>Events</h1>

<a href="{{ route('events.create') }}">Add Event</a>

<ul>
    @foreach ($events as $event)
        <li>
            {{ $event->title }} — {{ $event->event_date }} — {{ $event->status }}
            ({{ $event->academicYear->title }}, {{ $event->category->name }})

            <a href="{{ route('events.edit', $event->id) }}">Edit</a>

            <form method="POST" action="{{ route('events.destroy', $event->id) }}" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </li>
    @endforeach
</ul>