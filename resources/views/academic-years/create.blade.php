<x-app-layout>
    <x-slot name="header">
        <div class="dashboard-heading">
            <div>
                <p class="eyebrow">Yearbook settings</p>
                <h1>Add Academic Year</h1>
            </div>
        </div>
    </x-slot>

    <div class="dashboard-wrap">
        <div class="panel academic-year-form-card">
            <div class="panel-header">
                <div>
                    <p class="eyebrow">New edition</p>
                    <h2>Set up the academic year</h2>
                    <p class="panel-meta">The dedication will appear as a featured page in the exported yearbook.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('academic-years.store') }}" class="academic-year-form">
                @csrf

                <div class="academic-year-grid">
                    <label class="academic-field">
                        <span>Title</span>
                        <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. 2025–2027" required>
                        @error('title') <small class="form-error">{{ $message }}</small> @enderror
                    </label>

                    <label class="academic-field">
                        <span>Status</span>
                        <select name="status">
                            <option value="draft" @selected(old('status', 'draft') === 'draft')>Draft</option>
                            <option value="active" @selected(old('status') === 'active')>Active</option>
                            <option value="archived" @selected(old('status') === 'archived')>Archived</option>
                        </select>
                    </label>

                    <label class="academic-field">
                        <span>Start Date</span>
                        <input type="date" name="start_date" value="{{ old('start_date') }}">
                        @error('start_date') <small class="form-error">{{ $message }}</small> @enderror
                    </label>

                    <label class="academic-field">
                        <span>End Date</span>
                        <input type="date" name="end_date" value="{{ old('end_date') }}">
                        @error('end_date') <small class="form-error">{{ $message }}</small> @enderror
                    </label>

                    <label class="academic-field academic-field-full">
                        <span>Dedication</span>
                        <small class="academic-help">A short message that introduces the edition. Keep it warm, meaningful, and suitable for print.</small>
                        <textarea name="dedication" rows="6" maxlength="2000" placeholder="Dedicated to the students, memories, and moments that made this year unforgettable...">{{ old('dedication') }}</textarea>
                        @error('dedication') <small class="form-error">{{ $message }}</small> @enderror
                    </label>
                </div>

                <div class="academic-form-actions">
                    <a href="{{ url()->previous() }}" class="button button-outline-dark">Cancel</a>
                    <button type="submit" class="button button-navy">Save Academic Year</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .academic-year-form-card { max-width: 900px; margin: 0 auto; }
        .academic-year-form .panel-header { margin-bottom: 28px; }
        .academic-year-form .panel-header h2 { color: var(--ink); margin: 0 0 7px; }
        .academic-year-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:22px; }
        .academic-field { display:flex; flex-direction:column; gap:7px; color:var(--ink); font-size:11px; font-weight:700; letter-spacing:.8px; text-transform:uppercase; }
        .academic-field input, .academic-field textarea { width:100%; border:1px solid var(--line); border-radius:0; background:#fff; color:var(--ink); padding:12px 13px; font:500 13px/1.5 Inter,sans-serif; outline:none; }
        .academic-field input:focus, .academic-field textarea:focus { border-color:var(--ink); box-shadow:0 0 0 2px rgba(0,42,92,.07); }
        .academic-field textarea { resize:vertical; min-height:150px; }
        .academic-field-full { grid-column:1/-1; }
        .academic-help { color:var(--ink-soft); font-size:11px; font-weight:400; line-height:1.5; letter-spacing:0; text-transform:none; }
        .form-error { color:#b42318; font-size:10px; font-weight:500; letter-spacing:0; text-transform:none; }
        .academic-form-actions { display:flex; justify-content:flex-end; gap:10px; margin-top:28px; padding-top:22px; border-top:1px solid var(--line); }
        .button-outline-dark { border:1px solid var(--line); color:var(--ink); background:#fff; }
        .button-outline-dark:hover { border-color:var(--ink); }
        @media(max-width:640px){ .academic-year-grid{grid-template-columns:1fr;} .academic-field-full{grid-column:auto;} .academic-form-actions{flex-direction:column-reverse;} .academic-form-actions .button{width:100%;} }
    </style>
</x-app-layout>
