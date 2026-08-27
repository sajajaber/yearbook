@php($isAdmin = Auth::user()->role?->role_name === 'admin')
<x-app-layout>
    <x-slot name="header"><div class="dashboard-heading event-heading"><div><p class="eyebrow">Yearbook office / stories</p><h1>Add event</h1></div><a href="{{ route('events.index') }}" class="text-link">Back to events <span aria-hidden="true">←</span></a></div></x-slot>
    <div class="dashboard-wrap event-form-wrap">
        @if ($errors->any())<div class="notice notice-error"><strong>Please review the highlighted fields.</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form method="POST" action="{{ route('events.store') }}" class="event-form">@csrf
            <div class="form-main">
                <section class="form-section"><div class="form-section-heading"><p class="eyebrow">Event details</p><h2>Give the moment a shape.</h2></div><div class="form-grid form-grid-two">
                    <label class="form-field form-field-wide"><span>Event title</span><input type="text" name="title" value="{{ old('title') }}" placeholder="What is happening?" required>@error('title')<small>{{ $message }}</small>@enderror</label>
                    <label class="form-field"><span>Academic year</span><select name="academic_year_id" required><option value="">Choose an academic year</option>@foreach ($academicYears as $academicYear)<option value="{{ $academicYear->id }}" @selected(old('academic_year_id') == $academicYear->id)>{{ $academicYear->title }}</option>@endforeach</select></label>
                    <label class="form-field"><span>Category</span><select name="category_id" required><option value="">Choose a category</option>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>@endforeach</select></label>
                    <label class="form-field"><span>Event date</span><input type="date" name="event_date" value="{{ old('event_date') }}" required></label>
                    <label class="form-field"><span>Location</span><input type="text" name="location" value="{{ old('location') }}" placeholder="Where will it happen?"></label>
                    <label class="form-field form-field-wide"><span>Description</span><textarea name="description" rows="6" placeholder="Add the context readers will need.">{{ old('description') }}</textarea></label>
                </div></section>
                @include('events._coverage')
            </div>
            <aside class="form-aside"><section class="portrait-upload event-note"><p class="eyebrow">Story setup</p><h2>Make it memorable.</h2><p>Capture the details your yearbook team will need to find, review, and publish this campus story.</p></section><section class="form-section form-section-compact"><div class="form-section-heading"><p class="eyebrow">Publishing</p><h2>Set visibility.</h2></div>@if ($isAdmin)<label class="form-field"><span>Status</span><select name="status"><option value="draft" @selected(old('status', 'draft') === 'draft')>Draft</option><option value="reviewed" @selected(old('status') === 'reviewed')>Reviewed</option><option value="approved" @selected(old('status') === 'approved')>Approved</option><option value="published" @selected(old('status') === 'published')>Published</option><option value="archived" @selected(old('status') === 'archived')>Archived</option></select></label>@else<p class="upload-note">New events are saved as drafts. An administrator manages publishing permissions.</p>@endif<label class="choice-item event-featured"><input type="checkbox" name="featured" value="1" @checked(old('featured'))><span>Feature this event</span></label></section><div class="form-actions"><a href="{{ route('events.index') }}" class="button button-muted">Cancel</a><button type="submit" class="button button-navy">Save event <span aria-hidden="true">→</span></button></div></aside>
        </form>
    </div>
</x-app-layout>
