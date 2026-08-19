<h1>Add User</h1>

<form method="POST" action="{{ route('users.store') }}">
  @csrf

  <label>Name</label>
  <input type="text" name="name">
  @error('name') <p style="color:red">{{ $message }}</p> @enderror

  <label>Email</label>
  <input type="email" name="email">
  @error('email') <p style="color:red">{{ $message }}</p> @enderror

  <label>Password</label>
  <input type="password" name="password">
  @error('password') <p style="color:red">{{ $message }}</p> @enderror

  <label>Role</label>
  <select name="role_id">
    @foreach ($roles as $role)
    <option value="{{ $role->id }}">{{ $role->role_name }}</option>
    @endforeach
  </select>

  <label>Status</label>
  <select name="status">
    <option value="invited">Invited</option>
    <option value="active">Active</option>
  </select>

  <button type="submit">Save</button>
</form>
