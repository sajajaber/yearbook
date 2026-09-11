<x-app-layout>
    <x-slot name="header">
        <div class="dashboard-heading">
            <div>
                <p class="eyebrow">Yearbook settings</p>
                <h1>Edit Academic Year</h1>
            </div>
        </div>
    </x-slot>

    <div class="dashboard-wrap">
        <div class="panel academic-year-form-card">
            <div class="panel-header">
                <div>
                    <p class="eyebrow">Edition details</p>
                    <h2>Refine the academic year</h2>
                    <p class="panel-meta">Update the edition details first, then refine the dedication that appears in the exported yearbook.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('academic-years.update', $academicYear->id) }}" class="academic-year-form">
                @csrf
                @method('PUT')

                <div class="academic-year-grid">
                    <label class="academic-field">
                        <span>Title</span>
                        <input type="text" name="title" value="{{ old('title', $academicYear->title) }}" required>
                        @error('title') <small class="form-error">{{ $message }}</small> @enderror
                    </label>

                    <label class="academic-field">
                        <span>Status</span>
                        <select name="status">
                            <option value="draft" @selected(old('status', $academicYear->status) === 'draft')>Draft</option>
                            <option value="active" @selected(old('status', $academicYear->status) === 'active')>Active</option>
                            <option value="archived" @selected(old('status', $academicYear->status) === 'archived')>Archived</option>
                        </select>
                    </label>

                    <label class="academic-field">
                        <span>Start Date</span>
                        <input type="date" name="start_date" value="{{ old('start_date', $academicYear->start_date) }}">
                        @error('start_date') <small class="form-error">{{ $message }}</small> @enderror
                    </label>

                    <label class="academic-field">
                        <span>End Date</span>
                        <input type="date" name="end_date" value="{{ old('end_date', $academicYear->end_date) }}">
                        @error('end_date') <small class="form-error">{{ $message }}</small> @enderror
                    </label>
                </div>

                <div class="dedication-section">
                    <div class="dedication-heading">
                        <div class="dedication-mark" aria-hidden="true">“</div>
                        <div>
                            <span class="dedication-label">Dedication</span>
                            <h3>A few words worth remembering</h3>
                            <p>Write a short message that introduces this edition. It will be presented as a featured page in the exported yearbook.</p>
                        </div>
                    </div>

                    <label class="dedication-field">
                        <span class="sr-only">Dedication message</span>
                        <textarea name="dedication" rows="7" maxlength="2000" placeholder="Dedicated to the students, memories, and moments that made this year unforgettable...">{{ old('dedication', $academicYear->dedication) }}</textarea>
                        <span class="dedication-footer">
                            <small>A warm, meaningful message works best.</small>
                            <small>Maximum 2,000 characters</small>
                        </span>
                        @error('dedication') <small class="form-error">{{ $message }}</small> @enderror
                    </label>
                </div>

                <div class="academic-form-actions">
                    <a href="{{ url()->previous() }}" class="button button-outline-dark">Cancel</a>
                    <button type="submit" class="button button-navy">Update Academic Year</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .academic-year-form-card { max-width: 900px; margin: 0 auto; }
        .academic-year-form .panel-header { margin-bottom: 30px; }
        .academic-year-form .panel-header h2 { color: var(--ink); margin: 0 0 7px; }
        .academic-year-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:22px; }
        .academic-field { display:flex; flex-direction:column; gap:8px; color:var(--ink); font-size:11px; font-weight:700; letter-spacing:.8px; text-transform:uppercase; }
        .academic-field input, .academic-field select { width:100%; min-height:48px; border:1px solid var(--line); border-radius:2px; background:#fff; color:var(--ink); padding:12px 14px; font:500 13px/1.5 Inter,sans-serif; outline:none; transition:border-color .2s ease,box-shadow .2s ease; }
        .academic-field input:hover, .academic-field select:hover { border-color:#a9bfd4; }
        .academic-field input:focus, .academic-field select:focus { border-color:var(--ink); box-shadow:0 0 0 3px rgba(0,42,92,.08); }

        .dedication-section { position:relative; margin-top:30px; padding:28px; overflow:hidden; border:1px solid #d8e3ef; background:radial-gradient(circle at 100% 0%,rgba(255,176,52,.13),transparent 34%),linear-gradient(135deg,#f9fcff 0%,#f2f7fc 100%); }
        .dedication-section::before { content:""; position:absolute; left:0; top:0; bottom:0; width:4px; background:#ffb034; }
        .dedication-heading { display:flex; align-items:flex-start; gap:16px; margin-bottom:20px; }
        .dedication-mark { flex:0 0 auto; width:48px; height:48px; display:grid; place-items:center; margin-top:2px; background:var(--ink); color:#ffb034; font:700 32px/1 Merriweather,Georgia,serif; }
        .dedication-label { display:block; margin-bottom:5px; color:#b87900; font-size:10px; font-weight:800; letter-spacing:1.5px; text-transform:uppercase; }
        .dedication-heading h3 { margin:0 0 5px; color:var(--ink); font:700 18px/1.35 Merriweather,Georgia,serif; }
        .dedication-heading p { max-width:650px; margin:0; color:var(--ink-soft); font-size:12px; line-height:1.6; }
        .dedication-field { display:block; }
        .dedication-field textarea { display:block; width:100%; min-height:170px; resize:vertical; border:1px solid #c7d6e4; border-radius:2px; background:rgba(255,255,255,.92); color:var(--ink); padding:18px 20px; font:italic 15px/1.8 Merriweather,Georgia,serif; outline:none; box-shadow:0 8px 24px rgba(0,42,92,.04); transition:border-color .2s ease,box-shadow .2s ease,background .2s ease; }
        .dedication-field textarea::placeholder { color:#94a7ba; opacity:1; }
        .dedication-field textarea:hover { border-color:#a9bfd4; }
        .dedication-field textarea:focus { border-color:#ffb034; background:#fff; box-shadow:0 0 0 3px rgba(255,176,52,.14),0 10px 28px rgba(0,42,92,.06); }
        .dedication-footer { display:flex; justify-content:space-between; gap:12px; margin-top:8px; color:var(--ink-soft); font-size:10px; line-height:1.5; }
        .form-error { display:block; color:#b42318; font-size:10px; font-weight:500; letter-spacing:0; text-transform:none; }
        .academic-form-actions { display:flex; justify-content:flex-end; gap:10px; margin-top:30px; padding-top:22px; border-top:1px solid var(--line); }
        .button-outline-dark { border:1px solid var(--line); color:var(--ink); background:#fff; }
        .button-outline-dark:hover { border-color:var(--ink); }
        .sr-only { position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0; }
        @media(max-width:640px){ .academic-year-grid{grid-template-columns:1fr;} .dedication-section{padding:22px 20px;} .dedication-heading{gap:12px;} .dedication-mark{width:42px;height:42px;font-size:28px;} .dedication-footer{flex-direction:column;gap:2px;} .academic-form-actions{flex-direction:column-reverse;} .academic-form-actions .button{width:100%;} }
    </style>
</x-app-layout>
