<x-app-layout>
  <x-slot name="header">
    <div class="dashboard-heading graduation-heading">
      <div>
        <p class="eyebrow">Yearbook office / editions</p>
        <h1>Add graduation</h1>
      </div>
      <a href="{{ route('graduations.index') }}" class="text-link">Back to graduations <span aria-hidden="true">←</span></a>
    </div>
  </x-slot>

  <div class="dashboard-wrap graduation-form-wrap">
    @if ($errors->any())
      <div class="notice notice-error"><strong>Please review the highlighted fields.</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('graduations.store') }}" class="graduation-form">
      @csrf
      <div class="form-main">
        <section class="form-section">
          <div class="form-section-heading">
            <p class="eyebrow">Ceremony details</p>
            <h2>Set the occasion.</h2>
          </div>
          <div class="form-grid form-grid-two">
            <label class="form-field"><span>Academic year</span><select name="academic_year_id" required><option value="">Choose an academic year</option>@foreach ($academicYears as $academicYear)<option value="{{ $academicYear->id }}" @selected(old('academic_year_id') == $academicYear->id)>{{ $academicYear->title }}</option>@endforeach</select>@error('academic_year_id')<small>{{ $message }}</small>@enderror</label>
            <label class="form-field"><span>Ceremony date</span><input type="date" name="ceremony_date" value="{{ old('ceremony_date') }}" required>@error('ceremony_date')<small>{{ $message }}</small>@enderror</label>
            <label class="form-field form-field-wide"><span>Venue</span><input type="text" name="venue" value="{{ old('venue') }}" placeholder="Where will the ceremony take place?"></label>
            <label class="form-field form-field-wide"><span>Description</span><textarea name="description" rows="5" placeholder="Add a short note about this graduation edition.">{{ old('description') }}</textarea></label>
          </div>
        </section>

        <section class="form-section">
          <div class="form-section-heading">
            <p class="eyebrow">Coverage</p>
            <h2>Choose who and where.</h2>
          </div>
          <div class="selection-block">
            <div class="selection-heading"><span class="form-field-label">Campuses</span><label class="select-all"><input type="checkbox" data-select-all="graduation-create-campuses" @checked(count(old('campus_ids', [])) === $campuses->count() && $campuses->count() > 0)><span>Select all campuses</span></label><small>Select the campuses included in this ceremony.</small></div>
            <div class="choice-grid" data-select-group="graduation-create-campuses">
              @foreach ($campuses as $campus)
                <label class="choice-item"><input type="checkbox" name="campus_ids[]" value="{{ $campus->id }}" @checked(in_array($campus->id, old('campus_ids', [])))><span>{{ $campus->name }}</span></label>
              @endforeach
            </div>
          </div>
          <div class="selection-block">
            <div class="selection-heading"><span class="form-field-label">Schools</span><label class="select-all"><input type="checkbox" data-select-all="graduation-create-schools" checked><span>Select all schools</span></label><small>All active schools are included by default.</small></div>
            <div class="choice-grid" data-select-group="graduation-create-schools">
              @foreach ($schools as $school)
                <label class="choice-item"><input type="checkbox" name="school_ids[]" value="{{ $school->id }}" @checked(in_array($school->id, old('school_ids', $schools->pluck('id')->all())))><span>{{ $school->name }}</span></label>
              @endforeach
              <script>document.querySelectorAll('[data-select-all]').forEach(selectAll => { const group = document.querySelector(`[data-select-group="${selectAll.dataset.selectAll}"]`); if (!group) return; const choices = group.querySelectorAll('input[type="checkbox"]'); selectAll.addEventListener('change', () => choices.forEach(choice => choice.checked = selectAll.checked)); choices.forEach(choice => choice.addEventListener('change', () => { selectAll.checked = Array.from(choices).every(item => item.checked); })); });</script>
            </div>
          </div>
        </section>
      </div>

      <aside class="form-aside">
        <section class="portrait-upload graduation-note">
          <p class="eyebrow">Edition setup</p>
          <h2>Ready for the big day.</h2>
          <p>Set the ceremony details, then connect the campuses and schools that should appear in this graduation edition.</p>
        </section>
        <div class="form-actions"><a href="{{ route('graduations.index') }}" class="button button-muted">Cancel</a><button type="submit" class="button button-navy">Save graduation <span aria-hidden="true">→</span></button></div>
      </aside>
    </form>
  </div>
</x-app-layout>