@php($isAdmin = Auth::user()->role?->role_name === 'admin')
@php($canManageMedia = in_array(Auth::user()->role?->role_name, ['admin', 'editor'], true))
<x-app-layout>
    <x-slot name="header">
        <div class="dashboard-heading event-heading">
            <div>
                <p class="eyebrow">Yearbook office / stories</p>
                <h1>Edit event</h1>
            </div><a href="{{ route('events.index') }}" class="text-link">Back to events <span aria-hidden="true">←</span></a>
        </div>
    </x-slot>
    <div class="dashboard-wrap event-form-wrap">
        @if ($errors->any())<div class="notice notice-error"><strong>Please review the highlighted fields.</strong>
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>@endif
        <form method="POST" action="{{ route('events.update', $event) }}" class="event-form">@csrf @method('PUT')
            <div class="form-main">
                <section class="form-section">
                    <div class="form-section-heading">
                        <p class="eyebrow">Event details</p>
                        <h2>Refine the moment.</h2>
                    </div>
                    <div class="form-grid form-grid-two">
                        <label class="form-field form-field-wide"><span>Event title</span><input type="text" name="title" value="{{ old('title', $event->title) }}" required>@error('title')<small>{{ $message }}</small>@enderror</label>
                        <label class="form-field"><span>Academic year</span><select name="academic_year_id" required>@foreach ($academicYears as $academicYear)<option value="{{ $academicYear->id }}" @selected(old('academic_year_id', $event->academic_year_id) == $academicYear->id)>{{ $academicYear->title }}</option>@endforeach</select></label>
                        <label class="form-field"><span>Category</span><select name="category_id" required>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $event->category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select></label>
                        <label class="form-field"><span>Event date</span><input
                                type="date"
                                name="event_date"
                                value="{{ old('event_date', optional($event->event_date)->format('Y-m-d') ?? $event->event_date) }}"
                                required></label>
                        <label class="form-field"><span>Location</span><input type="text" name="location" value="{{ old('location', $event->location) }}" placeholder="Where will it happen?"></label>
                        <label class="form-field form-field-wide"><span>Description</span><textarea name="description" rows="6" placeholder="Add the context readers will need.">{{ old('description', $event->description) }}</textarea></label>
                    </div>
                </section>
                @include('events._coverage', ['selectedCampuses' => $event->campuses->pluck('id')->all(), 'selectedSchools' => $event->schools->pluck('id')->all()])
                @if ($canManageMedia)
                    @include('events._media-picker', ['selectedMediaIds' => old('media_ids', $event->media->pluck('id')->all())])
                @else
                    <section class="form-section event-media-section">
                        <div class="form-section-heading"><p class="eyebrow">Event media</p><h2>Attached visual story.</h2></div>
                        <p class="upload-note">Only administrators and editors can change event media assignments.</p>
                        @if ($event->media->isNotEmpty())
                            <div class="event-media-grid">
                                @foreach ($event->media as $mediaItem)
                                    <div class="event-media-choice is-selected">
                                        <span class="event-media-thumb">@if($mediaItem->type === 'image')<img src="{{ $mediaItem->thumbnailUrl() }}" alt="{{ $mediaItem->alt_text ?? $mediaItem->file_name }}">@else<span class="event-media-video-icon">▶</span>@endif</span>
                                        <span class="event-media-info"><strong>{{ $mediaItem->file_name }}</strong><small>{{ ucfirst($mediaItem->type) }}</small></span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="upload-note">No media is currently assigned to this event.</p>
                        @endif
                    </section>
                @endif
            </div>
            <aside class="form-aside">
                <section class="portrait-upload event-note">
                    <p class="eyebrow">Story setup</p>
                    <h2>Keep it current.</h2>
                    <p>Update the details your yearbook team will use to find, review, and publish this campus story.</p>
                </section>@if ($event->aiGenerations->count())<section class="form-section form-section-compact">
                    <div class="form-section-heading">
                        <p class="eyebrow">Writing assistant</p>
                        <h2>Summary draft ready.</h2>
                    </div>
                    <p class="upload-note">This generated summary is waiting in the editorial review queue.</p><a href="{{ route('ai-generations.index', ['type' => 'event_summary']) }}" class="text-link">Open summary review <span aria-hidden="true">→</span></a>
                </section>@endif<section class="form-section form-section-compact">
                    <div class="form-section-heading">
                        <p class="eyebrow">Publishing</p>
                        <h2>Set visibility.</h2>
                    </div>@if ($isAdmin)<label class="form-field"><span>Status</span><select name="status">
                            <option value="draft" @selected(old('status', $event->status) === 'draft')>Draft</option>
                            <option value="reviewed" @selected(old('status', $event->status) === 'reviewed')>Reviewed</option>
                            <option value="approved" @selected(old('status', $event->status) === 'approved')>Approved</option>
                            <option value="published" @selected(old('status', $event->status) === 'published')>Published</option>
                            <option value="archived" @selected(old('status', $event->status) === 'archived')>Archived</option>
                        </select></label>@else<label class="form-field"><span>Status</span><select disabled>
                            <option>{{ ucfirst($event->status) }}</option>
                        </select><small>Only an administrator can change publishing status.</small></label>@endif<label class="choice-item event-featured"><input type="checkbox" name="featured" value="1" @checked(old('featured', $event->featured))><span>Feature this event</span></label>
                </section>
                <div class="form-actions"><a href="{{ route('events.index') }}" class="button button-muted">Cancel</a><button type="submit" class="button button-navy">Update event <span aria-hidden="true">→</span></button></div>
            </aside>
        </form>
    </div>
</x-app-layout>