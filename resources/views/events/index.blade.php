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

    <form method="POST" action="{{ route('events.submit', $event->id) }}" style="display:inline">
      @csrf
      <button type="submit">Submit for Review</button>
    </form>
    <form method="POST" action="{{ route('events.approve', $event->id) }}" style="display:inline">
      @csrf
      <button type="submit">Approve</button>
    </form>
    <form method="POST" action="{{ route('events.reject', $event->id) }}" style="display:inline">
      @csrf
      <button type="submit">Reject</button>
    </form>
  </li>
  @endforeach
</ul>
