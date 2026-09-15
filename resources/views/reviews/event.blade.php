<x-app-layout>
    <x-slot name="header">
        <div class="dashboard-heading">
            <div><p class="eyebrow">Review / Event</p><h1>{{ $event->title }}</h1></div>
            <a href="{{ route('events.index') }}" class="button button-navy">← Back to events</a>
        </div>
    </x-slot>

    <div class="dashboard-wrap" style="max-width:1100px;margin:0 auto;padding:36px 24px 80px;">
        <main style="display:grid;gap:18px;">
            <section style="background:#fff;border:1px solid #d8e3ef;border-radius:18px;padding:28px;">
                <div style="display:flex;justify-content:space-between;gap:20px;align-items:start;">
                    <div><p class="eyebrow">Event content</p><h2 style="margin:0 0 10px;">{{ $event->title }}</h2><p style="color:#64748b;">{{ $event->category?->name ?? 'Campus story' }} · {{ $event->academicYear?->title ?? 'No academic year' }}</p></div>
                    <strong>{{ ucfirst($event->status) }}</strong>
                </div>
                <dl style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px;margin-top:24px;">
                    <div><dt style="font-size:11px;text-transform:uppercase;color:#64748b;">Date</dt><dd>{{ $event->event_date?->format('F j, Y') ?? '—' }}</dd></div>
                    <div><dt style="font-size:11px;text-transform:uppercase;color:#64748b;">Location</dt><dd>{{ $event->location ?: '—' }}</dd></div>
                    <div><dt style="font-size:11px;text-transform:uppercase;color:#64748b;">Featured</dt><dd>{{ $event->featured ? 'Yes' : 'No' }}</dd></div>
                </dl>
            </section>

            @if ($event->description)
                <section style="background:#fff;border:1px solid #d8e3ef;border-radius:18px;padding:24px;"><p class="eyebrow">Description</p><div style="white-space:pre-line;line-height:1.75;">{{ $event->description }}</div></section>
            @endif

            @if ($event->reviewFeedback->count())
                <section style="background:#fff8f0;border:1px solid #f1d6b2;border-radius:18px;padding:24px;">
                    <p class="eyebrow">Review history</p>
                    @foreach ($event->reviewFeedback as $feedback)
                        <div style="padding:14px 0;border-top:1px solid #ead8c2;"><strong>{{ ucfirst($feedback->status) }}</strong> · {{ $feedback->reviewer?->name ?? 'Reviewer' }}<p style="margin:7px 0 0;white-space:pre-line;">{{ $feedback->message }}</p></div>
                    @endforeach
                </section>
            @endif

            @if (in_array(Auth::user()->role?->role_name, ['admin', 'reviewer']) && $event->status === 'reviewed')
                <section style="background:#002a5c;color:#fff;border-radius:18px;padding:26px;">
                    <p style="margin:0 0 8px;text-transform:uppercase;letter-spacing:1.5px;font-size:11px;opacity:.7;">Reviewer decision</p>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        <form method="POST" action="{{ route('events.approve', $event) }}">@csrf<button type="submit" class="action-button action-primary">Approve event</button></form>
                        <form method="POST" action="{{ route('events.request-changes', $event) }}" style="display:flex;gap:8px;flex:1;min-width:300px;">
                            @csrf
                            <input name="message" required maxlength="5000" placeholder="Explain what the editor needs to fix…" style="flex:1;border:0;border-radius:8px;padding:10px 12px;">
                            <button type="submit" class="action-button">Request changes</button>
                        </form>
                    </div>
                </section>
            @endif
        </main>
    </div>
</x-app-layout>
