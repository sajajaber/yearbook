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
            <div class="form-field form-field-wide"><span>Description</span><x-rich-text-editor name="description" :value="old('description')" rows="5" placeholder="Add a short note about this graduation edition." />@error('description')<small>{{ $message }}</small>@enderror</div>
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
            </div>
          </div>
        </section>

        <section class="form-section">
          <div class="form-section-heading">
            <p class="eyebrow">Ceremony participants</p>
            <h2>Recognize the people on stage.</h2>
          </div>

          <div class="selection-block">
            <div class="selection-heading">
              <span class="form-field-label">Speakers</span>
              <small>Add the names of speakers who should appear on the public graduation page.</small>
            </div>
            <div class="repeatable-list" data-repeatable="speakers">
              <div class="repeatable-items">
                @foreach(old('speakers', []) as $speaker)
                  <div class="repeatable-row">
                    <input type="text" name="speakers[]" value="{{ $speaker }}" placeholder="Speaker name">
                    <button type="button" class="text-link repeatable-remove">Remove</button>
                  </div>
                @endforeach
              </div>
              <button type="button" class="button button-muted repeatable-add">+ Add speaker</button>
            </div>
          </div>

          <div class="selection-block">
            <div class="selection-heading">
              <span class="form-field-label">Award recipients</span>
              <small>Add each recipient together with the award they received.</small>
            </div>
            <div class="repeatable-list" data-repeatable="awards">
              <div class="repeatable-items">
                @foreach(old('award_recipients', []) as $recipient)
                  <div class="repeatable-row repeatable-row-award">
                    <input type="text" name="award_recipients[][name]" value="{{ $recipient['name'] ?? '' }}" placeholder="Recipient name">
                    <input type="text" name="award_recipients[][award]" value="{{ $recipient['award'] ?? '' }}" placeholder="Award">
                    <button type="button" class="text-link repeatable-remove">Remove</button>
                  </div>
                @endforeach
              </div>
              <button type="button" class="button button-muted repeatable-add">+ Add award recipient</button>
            </div>
          </div>
        </section>

        @include('graduations._media', ['selectedMediaIds' => old('media_ids', [])])
      </div>

      <aside class="form-aside">
        <section class="portrait-upload graduation-note">
          <p class="eyebrow">Edition setup</p>
          <h2>Ready for the big day.</h2>
          <p>Set the ceremony details, connect the campuses and schools, and select the media that should appear with this graduation edition.</p>
        </section>
        <div class="form-actions"><a href="{{ route('graduations.index') }}" class="button button-muted">Cancel</a><button type="submit" class="button button-navy">Save graduation <span aria-hidden="true">→</span></button></div>
      </aside>
    </form>
  </div>

  <script>
    document.querySelectorAll('[data-repeatable]').forEach(list => {
      const items = list.querySelector('.repeatable-items');
      const add = list.querySelector('.repeatable-add');
      const isAward = list.dataset.repeatable === 'awards';

      add?.addEventListener('click', () => {
        const row = document.createElement('div');
        row.className = isAward ? 'repeatable-row repeatable-row-award' : 'repeatable-row';
        row.innerHTML = isAward
          ? '<input type="text" name="award_recipients[][name]" placeholder="Recipient name"><input type="text" name="award_recipients[][award]" placeholder="Award"><button type="button" class="text-link repeatable-remove">Remove</button>'
          : '<input type="text" name="speakers[]" placeholder="Speaker name"><button type="button" class="text-link repeatable-remove">Remove</button>';
        items.appendChild(row);
      });

      list.addEventListener('click', event => {
        if (event.target.classList.contains('repeatable-remove')) {
          event.target.closest('.repeatable-row')?.remove();
        }
      });
    });

    document.querySelectorAll('[data-select-all]').forEach(selectAll => {
      const group = document.querySelector(`[data-select-group="${selectAll.dataset.selectAll}"]`);
      if (!group) return;
      const choices = group.querySelectorAll('input[type="checkbox"]');
      selectAll.addEventListener('change', () => choices.forEach(choice => choice.checked = selectAll.checked));
      choices.forEach(choice => choice.addEventListener('change', () => {
        selectAll.checked = Array.from(choices).every(item => item.checked);
      }));
    });
  </script>
</x-app-layout>