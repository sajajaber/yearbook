<h1>Events</h1>

<a href="{{ route('events.create') }}">Add Event</a>

@if (session('success'))
<p>{{ session('success') }}</p>
@endif

@if ($errors->any())
<ul>
  @foreach ($errors->all() as $error)
  <li>{{ $error }}</li>
  @endforeach
</ul>
@endif

<hr>

@forelse ($events as $event)

<h2>{{ $event->title }}</h2>

<p>
  Date: {{ $event->event_date }} <br>
  Status: {{ $event->status }} <br>
  Academic Year: {{ $event->academicYear->title }} <br>
  Category: {{ $event->category->name }}
</p>

@if ($event->description)
<p>
  Description: {{ $event->description }}
</p>
@endif

<a href="{{ route('events.edit', $event->id) }}">Edit</a>

<form method="POST"
  action="{{ route('events.destroy', $event->id) }}"
  style="display:inline">
  @csrf
  @method('DELETE')
  <button type="submit">Delete</button>
</form>

<form method="POST"
  action="{{ route('events.submit', $event->id) }}"
  style="display:inline">
  @csrf
  <button type="submit">Submit for Review</button>
</form>

<form method="POST"
  action="{{ route('events.approve', $event->id) }}"
  style="display:inline">
  @csrf
  <button type="submit">Approve</button>
</form>

<form method="POST"
  action="{{ route('events.reject', $event->id) }}"
  style="display:inline">
  @csrf
  <button type="submit">Reject</button>
</form>

<form method="POST"
  action="{{ route('events.generate-summary', $event->id) }}"
  style="display:inline">
  @csrf
  <button type="submit">Generate AI Summary</button>
</form>

@if ($event->aiGenerations->count())

<h3>AI Generations</h3>

@foreach ($event->aiGenerations as $generation)

<p>
  <strong>Status:</strong>
  {{ $generation->status }}
</p>

<p>
  <strong>Generated Text:</strong><br>
  {{ $generation->generated_text }}
</p>

@if ($generation->reviewed_text)
<p>
  <strong>Reviewed Text:</strong><br>
  {{ $generation->reviewed_text }}
</p>
@endif

<hr>

@endforeach
@else
<p>No AI summary generated yet.</p>
@endif

<hr>
@empty

<p>No events found.</p>
@endforelse