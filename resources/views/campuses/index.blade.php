<h1>Campuses</h1>

<a href="{{ route('campuses.create') }}">Add Campus</a>

<ul>
  @foreach ($campuses as $campus)
  <li>
    {{ $campus->name }} ({{ $campus->code }}) — {{ $campus->status }}

    <a href="{{ route('campuses.edit', $campus->id) }}">Edit</a>

    <form method="POST" action="{{ route('campuses.update', $campus->id) }}" style="display:inline">
      @csrf
      @method('PUT')
      <input type="hidden" name="name" value="{{ $campus->name }}">
      <input type="hidden" name="code" value="{{ $campus->code }}">
      <input type="hidden" name="status" value="{{ $campus->status === 'active' ? 'archived' : 'active' }}">
      <button type="submit">{{ $campus->status === 'active' ? 'Archive' : 'Unarchive' }}</button>
    </form>
  </li>
  @endforeach
</ul>
