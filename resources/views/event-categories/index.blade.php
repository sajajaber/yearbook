<h1>Event Categories</h1>

<a href="{{ route('event-categories.create') }}">Add Event Category</a>

<ul>
    @foreach ($eventCategories as $eventCategory)
        <li>
            {{ $eventCategory->name }} — {{ $eventCategory->description }}

            <a href="{{ route('event-categories.edit', $eventCategory->id) }}">Edit</a>

            <form method="POST" action="{{ route('event-categories.destroy', $eventCategory->id) }}" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </li>
    @endforeach
</ul>