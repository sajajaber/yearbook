<x-app-layout>
    <x-slot name="header">
        <div class="dashboard-heading review-heading">
            <div><p class="eyebrow">Review / Graduate profile</p><h1>{{ $graduate->name }}</h1></div>
            <a href="{{ route('graduates.index') }}" class="button button-navy">← Back to graduates</a>
        </div>
    </x-slot>

    <div class="review-page">
        <div class="review-layout">
            <aside class="review-sidebar">
                @if ($graduate->portraitMedia)
                    <div class="review-portrait-wrap">
                        <img src="{{ Storage::disk('public')->url($graduate->portraitMedia->path) }}" alt="{{ $graduate->portraitMedia->alt_text ?? $graduate->name }}" class="review-portrait">
                    </div>
                @endif
                <div class="review-status-card">
                    <span class="review-status-label">Current status</span>
                    <strong class="review-status">{{ $graduate->publish_status === 'reviewed' ? 'Submitted for review' : ($graduate->publish_status === 'rejected' ? 'Changes requested' : ucfirst($graduate->publish_status)) }}</strong>
                    <p>{{ $graduate->school?->name ?? 'No school' }}<br>{{ $graduate->major?->name ?? 'No major' }}</p>
                </div>
            </aside>

            <main class="review-main">
                <section class="review-card review-overview">
                    <div class="review-card-heading">
                        <div><p class="eyebrow">Profile content</p><h2>{{ $graduate->name }}</h2></div>
                        <span class="review-badge">Read only</span>
                    </div>
                    <dl class="review-details">
                        <div><dt>Student reference</dt><dd>{{ $graduate->student_reference ?: '—' }}</dd></div>
                        <div><dt>Degree level</dt><dd>{{ ucfirst($graduate->degree_level) }}</dd></div>
                        <div><dt>GPA</dt><dd>{{ $graduate->gpa !== null ? number_format((float) $graduate->gpa, 2) . ' / 4.00' : '—' }}</dd></div>
                        <div><dt>Campus</dt><dd>{{ $graduate->campus?->name ?? '—' }}</dd></div>
                        <div><dt>Consent</dt><dd>{{ ucfirst($graduate->consent_status ?: 'pending') }}</dd></div>
                    </dl>
                </section>

                @foreach ([['Biography', $graduate->profile_text], ['Achievements', is_array($graduate->achievements) ? implode(' • ', $graduate->achievements) : $graduate->achievements], ['Activities', is_array($graduate->activities) ? implode(' • ', $graduate->activities) : $graduate->activities], ['Projects', is_array($graduate->projects) ? implode(' • ', $graduate->projects) : $graduate->projects], ['Internships', is_array($graduate->internships) ? implode(' • ', $graduate->internships) : $graduate->internships], ['Future plans', $graduate->future_plans], ['Quote', $graduate->quote]] as [$label, $value])
                    @if ($value)
                        <section class="review-card review-content-card"><p class="eyebrow">{{ $label }}</p><div class="review-content">{{ $value }}</div></section>
                    @endif
                @endforeach

                @if ($graduate->resumeMedia)
                    <section class="review-card review-content-card"><p class="eyebrow">Resume / CV</p><div class="review-file">{{ $graduate->resumeMedia->file_name }}</div></section>
                @endif

                @if ($graduate->reviewFeedback->count())
                    <section class="review-card feedback-history">
                        <div class="review-card-heading"><div><p class="eyebrow">Review history</p><h3>Feedback and decisions</h3></div></div>
                        @foreach ($graduate->reviewFeedback as $feedback)
                            <div class="feedback-entry">
                                <div class="feedback-meta"><strong>{{ ucfirst($feedback->status) }}</strong><span>{{ $feedback->reviewer?->name ?? 'Reviewer' }}</span></div>
                                <p>{{ $feedback->message }}</p>
                            </div>
                        @endforeach
                    </section>
                @endif

                @if (in_array(Auth::user()->role?->role_name, ['admin', 'reviewer']) && $graduate->publish_status === 'reviewed')
                    <section class="review-decision-card">
                        <div class="decision-intro">
                            <div class="decision-icon" aria-hidden="true">✓</div>
                            <div><p class="eyebrow eyebrow-accent">Reviewer decision</p><h2>Is this profile ready?</h2><p>Approve the profile or send clear feedback to the editor. Reviewers do not edit profile content.</p></div>
                        </div>
                        <div class="decision-actions">
                            <form method="POST" action="{{ route('graduates.approve', $graduate) }}">@csrf<button type="submit" class="action-button action-primary">Approve profile <span aria-hidden="true">→</span></button></form>
                            <div class="feedback-composer">
                                <div class="composer-heading"><span class="composer-icon" aria-hidden="true">✎</span><div><strong>Request changes</strong><span>Tell the editor exactly what needs attention.</span></div></div>
                                <form method="POST" action="{{ route('graduates.request-changes', $graduate) }}" class="feedback-form">
                                    @csrf
                                    <label for="graduate-review-note">Review note</label>
                                    <textarea id="graduate-review-note" name="message" required maxlength="5000" rows="5" placeholder="Example: Please verify the graduation year in the biography and replace the current profile photo with the approved portrait."></textarea>
                                    <div class="feedback-form-footer"><span>Maximum 5,000 characters</span><button type="submit" class="action-button action-secondary">Send to editor <span aria-hidden="true">→</span></button></div>
                                </form>
                            </div>
                        </div>
                    </section>
                @endif
            </main>
        </div>
    </div>

    <style>
        .review-page{max-width:1160px;margin:0 auto;padding:36px 24px 80px}.review-layout{display:grid;grid-template-columns:250px minmax(0,1fr);gap:28px;align-items:start}.review-sidebar{position:sticky;top:88px}.review-portrait-wrap{overflow:hidden;border-radius:20px;background:#eef4f9;border:1px solid var(--line,#d8e3ef);box-shadow:0 14px 35px rgba(0,42,92,.08)}.review-portrait{display:block;width:100%;aspect-ratio:1;object-fit:cover}.review-status-card{margin-top:14px;padding:18px;border:1px solid var(--line,#d8e3ef);border-radius:16px;background:#fff}.review-status-label{display:block;font-size:10px;font-weight:700;letter-spacing:1.3px;text-transform:uppercase;color:#94a3b8}.review-status{display:block;margin-top:6px;color:var(--ink,#002a5c);font-size:16px}.review-status-card p{margin:7px 0 0;color:#64748b;font-size:12px;line-height:1.6}.review-main{display:grid;gap:16px}.review-card{background:#fff;border:1px solid var(--line,#d8e3ef);border-radius:18px;padding:25px;box-shadow:0 5px 18px rgba(0,42,92,.035)}.review-card-heading{display:flex;justify-content:space-between;align-items:flex-start;gap:16px}.review-card h2{margin:0 0 6px;color:var(--ink,#002a5c)}.review-card h3{margin:0;color:var(--ink,#002a5c)}.review-badge{padding:6px 10px;border-radius:999px;background:#f1f5f9;color:#64748b;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px}.review-details{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px;margin:20px 0 0}.review-details dt{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#94a3b8}.review-details dd{margin:5px 0 0;color:#1e293b;font-size:14px;font-weight:600}.review-content{white-space:pre-line;line-height:1.8;color:#334155;font-size:14px}.review-file{display:inline-flex;padding:10px 13px;border:1px solid var(--line,#d8e3ef);border-radius:10px;background:#f7fbff;color:var(--ink,#002a5c);font-size:13px;font-weight:600}.feedback-history{background:#fffaf3;border-color:#f0dcc1}.feedback-entry{padding:15px 0;border-top:1px solid #eadbc8}.feedback-entry:first-of-type{margin-top:10px}.feedback-meta{display:flex;gap:10px;align-items:center}.feedback-meta strong{color:#8a5b13;font-size:12px}.feedback-meta span{color:#94a3b8;font-size:11px}.feedback-entry p{margin:7px 0 0;color:#475569;white-space:pre-line;line-height:1.65;font-size:13px}.review-decision-card{padding:24px;background:#f7fbff;border:1px solid #d8e3ef;border-radius:18px;color:#1e293b;box-shadow:0 8px 24px rgba(0,42,92,.055);position:relative;overflow:hidden}.review-decision-card:before{content:"";position:absolute;left:0;top:0;bottom:0;width:4px;background:#ffb034}.decision-intro{display:flex;gap:14px;align-items:flex-start}.decision-intro h2{margin:0;color:var(--ink,#002a5c);font-size:22px}.decision-intro p:not(.eyebrow){margin:5px 0 0;color:#64748b;font-size:13px;line-height:1.6}.eyebrow-accent{color:#b7791f}.decision-icon,.composer-icon{display:grid;place-items:center;width:38px;height:38px;flex:0 0 38px;border-radius:11px;background:#fff1d6;color:#c47a00;font-weight:800}.decision-actions{display:grid;grid-template-columns:auto minmax(0,1fr);gap:18px;align-items:start;margin-top:20px}.feedback-composer{padding:18px;border:1px solid #d8e3ef;border-radius:14px;background:#fff}.composer-heading{display:flex;gap:10px;align-items:center;margin-bottom:14px}.composer-heading strong{display:block;font-size:13px;color:#002a5c}.composer-heading span:not(.composer-icon){display:block;margin-top:2px;color:#64748b;font-size:11px}.feedback-form label{display:block;margin-bottom:7px;color:#64748b;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px}.feedback-form textarea{display:block;width:100%;min-height:128px;resize:vertical;padding:13px 14px;border:1px solid #d8e3ef;border-radius:11px;background:#f8fafc;color:#1e293b;font:14px/1.6 Inter,sans-serif;box-shadow:none;outline:none;transition:border-color .2s,box-shadow .2s,background .2s}.feedback-form textarea::placeholder{color:#94a3b8}.feedback-form textarea:focus{border-color:#ffb034;background:#fff;box-shadow:0 0 0 3px rgba(255,176,52,.14)}.feedback-form-footer{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-top:10px}.feedback-form-footer>span{font-size:10px;color:#94a3b8}.action-button{border:1px solid #d8e3ef;border-radius:10px;padding:10px 14px;background:#fff;color:#002a5c;font:700 12px Inter,sans-serif;cursor:pointer;transition:transform .2s,background .2s,border-color .2s}.action-button:hover{transform:translateY(-1px);background:#f7fbff;border-color:#b8c9dc}.action-button.action-primary{background:#ffb034;border-color:#ffb034;color:#002a5c}.action-button.action-primary:hover{background:#ffc15c;border-color:#ffc15c}.action-button.action-secondary{background:#002a5c;border-color:#002a5c;color:#fff}.action-button.action-secondary:hover{background:#073972;border-color:#073972}.button{display:inline-flex;align-items:center;gap:8px;text-decoration:none}.button-navy{background:#002a5c;color:#fff;padding:10px 14px;border-radius:10px}.review-heading{gap:20px}@media(max-width:800px){.review-layout{grid-template-columns:1fr}.review-sidebar{position:static;display:grid;grid-template-columns:140px 1fr;gap:16px}.review-status-card{margin:0}.decision-actions{grid-template-columns:1fr}.review-details{grid-template-columns:1fr 1fr}}@media(max-width:560px){.review-page{padding:24px 16px 60px}.review-sidebar{grid-template-columns:1fr}.review-portrait-wrap{max-width:180px}.review-details{grid-template-columns:1fr}.feedback-form-footer{align-items:flex-start;flex-direction:column}.feedback-form-footer .action-button{width:100%}}
    </style>
</x-app-layout>
