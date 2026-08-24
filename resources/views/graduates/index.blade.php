<h1>Graduates</h1>

<a href="{{ route('graduates.create') }}">Add Graduate</a>

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

@forelse ($graduates as $graduate)

<h2>{{ $graduate->name }}</h2>

<p>
  Student Reference: {{ $graduate->student_reference }} <br>
  Major: {{ $graduate->major->name }} <br>
  School: {{ $graduate->school->name }} <br>
  Publish Status: {{ $graduate->publish_status }} <br>
  Consent Status: {{ $graduate->consent_status }}
</p>

@if ($graduate->profile_text)
<p>
  <strong>Profile:</strong><br>
  {{ $graduate->profile_text }}
</p>
@endif

@if ($graduate->future_plans)
<p>
  <strong>Future Plans:</strong><br>
  {{ $graduate->future_plans }}
</p>
@endif

@if ($graduate->quote)
<p>
  <strong>Quote:</strong><br>
  {{ $graduate->quote }}
</p>
@endif

<a href="{{ route('graduates.edit', $graduate->id) }}">Edit</a>

<form
  method="POST"
  action="{{ route('graduates.destroy', $graduate->id) }}"
  style="display:inline">
  @csrf
  @method('DELETE')
  <button type="submit">Delete</button>
</form>

<form
  method="POST"
  action="{{ route('graduates.submit', $graduate->id) }}"
  style="display:inline">
  @csrf
  <button type="submit">Submit for Review</button>
</form>

<form
  method="POST"
  action="{{ route('graduates.approve', $graduate->id) }}"
  style="display:inline">
  @csrf
  <button type="submit">Approve</button>
</form>

<form
  method="POST"
  action="{{ route('graduates.reject', $graduate->id) }}"
  style="display:inline">
  @csrf
  <button type="submit">Reject</button>
</form>

<form
  method="POST"
  action="{{ route('graduates.generate-biography', $graduate->id) }}"
  style="display:inline">
  @csrf
  <button type="submit">Generate AI Biography</button>
</form>

@if ($graduate->aiGenerations->count() > 0)

<h3>AI Generations</h3>

@foreach ($graduate->aiGenerations as $generation)

<p>
  <strong>Content Type:</strong>
  {{ $generation->content_type }}
</p>

<p>
  <strong>Status:</strong>
  {{ $generation->status }}
</p>

<p>
  <strong>Generated Biography:</strong><br>
  {{ $generation->generated_text }}
</p>

@if ($generation->reviewed_text)
<p>
  <strong>Reviewed Biography:</strong><br>
  {{ $generation->reviewed_text }}
</p>
@endif

<hr>

@endforeach

@else

<p>No AI biography generated yet.</p>

@endif

<hr>

@empty

<p>No graduates found.</p>

@endforelse