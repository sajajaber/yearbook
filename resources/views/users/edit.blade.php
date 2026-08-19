<h1>Edit User</h1>

<form method="POST" action="{{ route('users.update', $user->id) }}">
  @csrf
  @method('PUT')

  <label>Name</label>
  <input type="text" name="name" value="{{ $user->name }}">
  @error('name') <p style="color:red">{{ $message }}</p> @enderror

  <label>Email</label>
  <input type="email" name="email" value="{{ $user->email }}">
  @error('email') <p style="color:red">{{ $message }}</p> @enderror

  <label>Password (leave blank to keep current password)</label>
  <input type="password" name="password">
  @error('password') <p style="color:red">{{ $message }}</p> @enderror

  <label>Role</label>
  <select name="role_id">
    @foreach ($roles as $role)
    <option value="{{ $role->id }}" @selected($user->role_id === $role->id)>{{ $role->role_name }}</option>
    @endforeach
  </select>

  <label>Status</label>
  <select name="status">
    <option value="invited" @selected($user->status === 'invited')>Invited</option>
    <option value="active" @selected($user->status === 'active')>Active</option>

  </select>

  <button type="submit">Update</button>
</form>
