<h1>Users</h1>

<a href="{{ route('users.create') }}">Add User</a>

<ul>
  @foreach ($users as $user)
  <li>
    {{ $user->name }} — {{ $user->email }} — {{ $user->role->role_name }} — {{ $user->status }}

    <a href="{{ route('users.edit', $user->id) }}">Edit</a>

    <form method="POST" action="{{ route('users.destroy', $user->id) }}" style="display:inline">
      @csrf
      @method('DELETE')
      <button type="submit">Delete</button>
    </form>
  </li>
  @endforeach
</ul>
