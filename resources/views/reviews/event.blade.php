<x-app-layout>
    <x-slot name="header">
        <div class="dashboard-heading review-heading">
            <div><p class="eyebrow">Review / Event</p><h1>{{ $event->title }}</h1></div>
            <a href="{{ route('events.index') }}" class="button button-navy">← Back to events</a>
        </div>
    </x-slot>

    <div class="review-page">
        <main class="review-main review-event-main">
            <section class="review-card review-overview">
                <div class="review-card-heading">
                    <div><p class="eyebrow">Event content</p><h2>{{ $event->title }}</h2><p class="review-subtitle">{{ $event->category?->name ?? 'Campus story' }} · {{ $event->academicYear?->title ?? 'No academic year' }}</p></div>
                    <span class="review-badge">Read only</span>
                </div>
                <dl class="review-details review-event-details">
                    <div><dt>Date</dt><dd>{{ $event->event_date?->format('F j, Y') ?? '—' }}</dd></div>
                    <div><dt>Location</dt><dd>{{ $event->location ?: '—' }}</dd></div>
                    <div><dt>Featured</dt><dd>{{ $event->featured ? 'Yes' : 'No' }}</dd></div>
                    <div><dt>Status</dt><dd>{{ ucfirst($event->status) }}</dd></div>
                </dl>
            </section>

            @if ($event->description)
                <section class="review-card review-content-card"><p class="eyebrow">Description</p><div class="review-content">{{ $event->description }}</div></section>
            @endif

            @if ($event->reviewFeedback->count())
                <section class="review-card feedback-history">
                    <div class="review-card-heading"><div><p class="eyebrow">Review history</p><h3>Feedback and decisions</h3></div></div>
                    @foreach ($event->reviewFeedback as $feedback)
                        <div class="feedback-entry"><div class="feedback-meta"><strong>{{ ucfirst($feedback->status) }}</strong><span>{{ $feedback->reviewer?->name ?? 'Reviewer' }}</span></div><p>{{ $feedback->message }}</p></div>
                    @endforeach
                </section>
            @endif

            @if (in_array(Auth::user()->role?->role_name, ['admin', 'reviewer']) && $event->status === 'reviewed')
                <section class="review-decision-card">
                    <div class="decision-intro">
                        <span class="decision-icon" aria-hidden="true">✓</span>
                        <div><p class="eyebrow eyebrow-light">Reviewer decision</p><h2>Is this event ready?</h2><p>Approve the event or send clear feedback to the editor. Reviewers do not edit event content.</p></div>
                    </div>
                    <div class="decision-actions">
                        <form method="POST" action="{{ route('events.approve', $event) }}">@csrf<button type="submit" class="action-button action-primary">Approve event <span aria-hidden="true">→</span></button></form>
                        <div class="feedback-composer">
                            <div class="composer-heading"><span class="composer-icon" aria-hidden="true">✎</span><div><strong>Request changes</strong><span>Tell the editor exactly what needs attention.</span></div></div>
                            <form method="POST" action="{{ route('events.request-changes', $event) }}" class="feedback-form">
                                @csrf
                                <label for="event-review-note">Review note</label>
                                <textarea id="event-review-note" name="message" required maxlength="5000" rows="5" placeholder="Example: Please correct the event date and add the approved campus name to the description."></textarea>
                                <div class="feedback-form-footer"><span>Maximum 5,000 characters</span><button type="submit" class="action-button">Send to editor <span aria-hidden="true">→</span></button></div>
                            </form>
                        </div>
                    </div>
                </section>
            @endif
        </main>
    </div>

    <style>
        .review-page{max-width:1160px;margin:0 auto;padding:36px 24px 80px}.review-main{display:grid;gap:16px;max-width:900px;margin:0 auto}.review-card{background:#fff;border:1px solid var(--line,#d8e3ef);border-radius:18px;padding:25px;box-shadow:0 5px 18px rgba(0,42,92,.035)}.review-card-heading{display:flex;justify-content:space-between;align-items:flex-start;gap:16px}.review-card h2{margin:0 0 6px;color:var(--ink,#002a5c)}.review-card h3{margin:0;color:var(--ink,#002a5c)}.review-subtitle{margin:4px 0 0;color:#64748b;font-size:13px}.review-badge{padding:6px 10px;border-radius:999px;background:#f1f5f9;color:#64748b;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px}.review-details{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px;margin:20px 0 0}.review-event-details{grid-template-columns:repeat(4,minmax(0,1fr))}.review-details dt{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#94a3b8}.review-details dd{margin:5px 0 0;color:#1e293b;font-size:14px;font-weight:600}.review-content-card{padding:24px}.review-content{white-space:pre-line;line-height:1.8;color:#334155;font-size:14px}.feedback-history{background:#fffaf3;border-color:#f0dcc1}.feedback-entry{padding:15px 0;border-top:1px solid #eadbc8}.feedback-entry:first-of-type{margin-top:10px}.feedback-meta{display:flex;gap:10px;align-items:center}.feedback-meta strong{color:#8a5b13;font-size:12px}.feedback-meta span{color:#94a3b8;font-size:11px}.feedback-entry p{margin:7px 0 0;color:#475569;white-space:pre-line;line-height:1.65;font-size:13px}.review-decision-card{padding:26px;border-radius:20px;background:linear-gradient(135deg,#002a5c 0%,#073972 100%);color:#fff;box-shadow:0 18px 45px rgba(0,42,92,.16)}.decision-intro{display:flex;gap:14px;align-items:flex-start}.decision-intro h2{margin:0;color:#fff}.decision-intro p:not(.eyebrow){margin:5px 0 0;color:rgba(255,255,255,.72);font-size:13px;line-height:1.6}.eyebrow-light{color:rgba(255,255,255,.6)}.decision-icon,.composer-icon{display:grid;place-items:center;width:38px;height:38px;flex:0 0 38px;border-radius:11px;background:rgba(255,176,52,.16);color:#ffb034;font-weight:800}.decision-actions{display:grid;grid-template-columns:auto minmax(0,1fr);gap:18px;align-items:start;margin-top:24px}.feedback-composer{padding:18px;border:1px solid rgba(255,255,255,.14);border-radius:16px;background:rgba(255,255,255,.07)}.composer-heading{display:flex;gap:10px;align-items:center;margin-bottom:14px}.composer-heading strong{display:block;font-size:13px}.composer-heading span:not(.composer-icon){display:block;margin-top:2px;color:rgba(255,255,255,.62);font-size:11px}.feedback-form label{display:block;margin-bottom:7px;color:rgba(255,255,255,.78);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px}.feedback-form textarea{display:block;width:100%;min-height:128px;resize:vertical;padding:13px 14px;border:1px solid rgba(255,255,255,.2);border-radius:12px;background:#fff;color:#1e293b;font:14px/1.6 Inter,sans-serif;box-shadow:0 5px 15px rgba(0,0,0,.08);outline:none;transition:border-color .2s,box-shadow .2s}.feedback-form textarea::placeholder{color:#94a3b8}.feedback-form textarea:focus{border-color:#ffb034;box-shadow:0 0 0 3px rgba(255,176,52,.2)}.feedback-form-footer{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-top:10px}.feedback-form-footer>span{font-size:10px;color:rgba(255,255,255,.48)}.action-button{border:1px solid rgba(255,255,255,.22);border-radius:10px;padding:10px 14px;background:rgba(255,255,255,.08);color:#fff;font:700 12px Inter,sans-serif;cursor:pointer;transition:transform .2s,background .2s}.action-button:hover{transform:translateY(-1px);background:rgba(255,255,255,.14)}.action-button.action-primary{background:#ffb034;border-color:#ffb034;color:#002a5c}.action-button.action-primary:hover{background:#ffc15c}.button{display:inline-flex;align-items:center;gap:8px;text-decoration:none}.button-navy{background:#002a5c;color:#fff;padding:10px 14px;border-radius:10px}.review-heading{gap:20px}@media(max-width:800px){.review-event-details{grid-template-columns:1fr 1fr}.decision-actions{grid-template-columns:1fr}}@media(max-width:560px){.review-page{padding:24px 16px 60px}.review-details,.review-event-details{grid-template-columns:1fr}.feedback-form-footer{align-items:flex-start;flex-direction:column}.feedback-form-footer .action-button{width:100%}}
    </style>
</x-app-layout>
