<x-app-layout>
    <x-slot name="header">
        <div class="dashboard-heading">
            <div><p class="eyebrow">Review / Graduate profile</p><h1>{{ $graduate->name }}</h1></div>
            <a href="{{ route('graduates.index') }}" class="button button-navy">← Back to graduates</a>
        </div>
    </x-slot>

    <div class="dashboard-wrap" style="max-width:1100px;margin:0 auto;padding:36px 24px 80px;">
        <div style="display:grid;grid-template-columns:260px 1fr;gap:32px;align-items:start;">
            <aside style="position:sticky;top:90px;">
                @if ($graduate->portraitMedia)
                    <img src="{{ Storage::disk('public')->url($graduate->portraitMedia->path) }}" alt="{{ $graduate->portraitMedia->alt_text ?? $graduate->name }}" style="width:100%;aspect-ratio:1;object-fit:cover;border-radius:18px;">
                @endif
                <div style="margin-top:16px;padding:18px;border:1px solid #d8e3ef;border-radius:14px;background:#fff;">
                    <strong>{{ ucfirst($graduate->publish_status) }}</strong>
                    <p style="margin:6px 0 0;color:#64748b;">{{ $graduate->school?->name ?? 'No school' }} · {{ $graduate->major?->name ?? 'No major' }}</p>
                </div>
            </aside>

            <main style="display:grid;gap:18px;">
                <section style="background:#fff;border:1px solid #d8e3ef;border-radius:18px;padding:28px;">
                    <p class="eyebrow">Profile content</p>
                    <h2 style="margin:0 0 18px;">{{ $graduate->name }}</h2>
                    <dl style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px;margin:0;">
                        <div><dt style="font-size:11px;text-transform:uppercase;color:#64748b;">Student reference</dt><dd>{{ $graduate->student_reference ?: '—' }}</dd></div>
                        <div><dt style="font-size:11px;text-transform:uppercase;color:#64748b;">Degree level</dt><dd>{{ ucfirst($graduate->degree_level) }}</dd></div>
                        <div><dt style="font-size:11px;text-transform:uppercase;color:#64748b;">Campus</dt><dd>{{ $graduate->campus?->name ?? '—' }}</dd></div>
                        <div><dt style="font-size:11px;text-transform:uppercase;color:#64748b;">Consent</dt><dd>{{ ucfirst($graduate->consent_status ?: 'pending') }}</dd></div>
                    </dl>
                </section>

                @foreach ([['Biography', $graduate->profile_text], ['Achievements', is_array($graduate->achievements) ? implode(' • ', $graduate->achievements) : $graduate->achievements], ['Activities', is_array($graduate->activities) ? implode(' • ', $graduate->activities) : $graduate->activities], ['Projects', is_array($graduate->projects) ? implode(' • ', $graduate->projects) : $graduate->projects], ['Internships', is_array($graduate->internships) ? implode(' • ', $graduate->internships) : $graduate->internships], ['Future plans', $graduate->future_plans], ['Quote', $graduate->quote]] as [$label, $value])
                    @if ($value)
                        <section style="background:#fff;border:1px solid #d8e3ef;border-radius:18px;padding:24px;"><p class="eyebrow">{{ $label }}</p><div style="white-space:pre-line;line-height:1.75;">{{ $value }}</div></section>
                    @endif
                @endforeach

                @if ($graduate->resumeMedia)
                    <section style="background:#fff;border:1px solid #d8e3ef;border-radius:18px;padding:24px;"><p class="eyebrow">Resume / CV</p><p>{{ $graduate->resumeMedia->file_name }}</p></section>
                @endif

                @if ($graduate->reviewFeedback->count())
                    <section style="background:#fff8f0;border:1px solid #f1d6b2;border-radius:18px;padding:24px;">
                        <p class="eyebrow">Review history</p>
                        @foreach ($graduate->reviewFeedback as $feedback)
                            <div style="padding:14px 0;border-top:1px solid #ead8c2;">
                                <strong>{{ ucfirst($feedback->status) }}</strong> · {{ $feedback->reviewer?->name ?? 'Reviewer' }}
                                <p style="margin:7px 0 0;white-space:pre-line;">{{ $feedback->message }}</p>
                            </div>
                        @endforeach
                    </section>
                @endif

                @if (in_array(Auth::user()->role?->role_name, ['admin', 'reviewer']) && $graduate->publish_status === 'reviewed')
                    <section style="background:#002a5c;color:#fff;border-radius:18px;padding:26px;">
                        <p style="margin:0 0 8px;text-transform:uppercase;letter-spacing:1.5px;font-size:11px;opacity:.7;">Reviewer decision</p>
                        <div style="display:flex;gap:10px;flex-wrap:wrap;">
                            <form method="POST" action="{{ route('graduates.approve', $graduate) }}">@csrf<button type="submit" class="action-button action-primary">Approve profile</button></form>
                            <form method="POST" action="{{ route('graduates.request-changes', $graduate) }}" style="display:flex;gap:8px;flex:1;min-width:300px;">
                                @csrf
                                <input name="message" required maxlength="5000" placeholder="Explain what the editor needs to fix…" style="flex:1;border:0;border-radius:8px;padding:10px 12px;">
                                <button type="submit" class="action-button">Request changes</button>
                            </form>
                        </div>
                    </section>
                @endif
            </main>
        </div>
    </div>
</x-app-layout>
