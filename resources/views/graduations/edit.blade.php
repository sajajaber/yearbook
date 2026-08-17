<h1>Edit Graduation</h1>

<form method="POST" action="{{ route('graduations.update', $graduation->id) }}">
  @csrf
  @method('PUT')

  <label>Academic Year</label>
  <select name="academic_year_id">
    @foreach ($academicYears as $academicYear)
    <option value="{{ $academicYear->id }}" @selected($graduation->academic_year_id === $academicYear->id)>{{ $academicYear->title }}</option>
    @endforeach
  </select>
  @error('academic_year_id')
  <p style="color: red">{{ $message }}</p>
  @enderror

  <label>Ceremony Date</label>
  <input type="date" name="ceremony_date" value="{{ $graduation->ceremony_date }}">
  @error('ceremony_date')
  <p style="color: red">{{ $message }}</p>
  @enderror

  <label>Venue</label>
  <input type="text" name="venue" value="{{ $graduation->venue }}">

  <label>Description</label>
  <textarea name="description">{{ $graduation->description }}</textarea>

  <label>Campuses</label>
  @foreach ($campuses as $campus)
  <label>
    <input type="checkbox" name="campus_ids[]" value="{{ $campus->id }}" @checked($graduation->campuses->contains($campus->id))>
    {{ $campus->name }}
  </label>
  @endforeach

  <button type="submit">Update</button>
</form>
