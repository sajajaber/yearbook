<x-app-layout>
    <x-slot name="header">
        <div class="dashboard-heading ai-heading">
            <div><p class="eyebrow">Yearbook office / editorial</p><h1>AI review queue</h1></div>
            <span class="panel-meta">{{ $pending->count() }} pending</span>
        </div>
    </x-slot>

    <div class="dashboard-wrap ai-wrap">
        <section class="ai-intro"><div><p class="eyebrow eyebrow-light">Drafts from the writing assistant</p><h2>Give every generated story a human edit.</h2><p>Review the generated text before it becomes part of a graduate profile or campus event.</p></div><div class="ai-intro-count"><strong>{{ $pending->count() }}</strong><span>awaiting review</span></div></section>

        @if (session('success') || session('error'))<div class="notice {{ session('error') ? 'notice-error' : 'notice-success' }}">{{ session('error') ?? session('success') }}</div>@endif

        <section class="ai-toolbar" aria-label="AI review filters">
            <div class="ai-filters">
                <a href="{{ route('ai-generations.index') }}" class="filter-button {{ $contentType === 'all' ? 'is-selected' : '' }}">All</a>
                <a href="{{ route('ai-generations.index', ['type' => 'graduate_biography']) }}" class="filter-button {{ $contentType === 'graduate_biography' ? 'is-selected' : '' }}">Biographies</a>
                <a href="{{ route('ai-generations.index', ['type' => 'event_summary']) }}" class="filter-button {{ $contentType === 'event_summary' ? 'is-selected' : '' }}">Event summaries</a>
            </div>
        </section>

        <section class="ai-section">
            <div class="section-heading"><div><p class="eyebrow">Pending drafts</p><h2>Review generated content</h2></div><span class="panel-meta">{{ $pending->count() }} records</span></div>
            <div class="ai-grid">
                @forelse ($pending as $generation)
                    @php $source = $generation->sourceRecord(); @endphp
                    <article class="ai-card">
                        <div class="ai-card-top"><span class="ai-type">{{ $generation->content_type === 'graduate_biography' ? 'Graduate biography' : 'Event summary' }}</span><span class="status-dot status-reviewed"></span></div>
                        <h3>{{ $source?->name ?? $source?->title ?? 'Source unavailable' }}</h3>
                        <p class="ai-source">Generated {{ $generation->created_at?->format('M j, Y') }}</p>
                        <label class="form-field"><span>Generated text</span><textarea readonly rows="8">{{ $generation->generated_text }}</textarea></label>
                        <form method="POST" action="{{ route('ai-generations.review', $generation) }}" class="ai-review-form">
                            @csrf
                            <label class="form-field"><span>Reviewed text</span><textarea name="reviewed_text" rows="8" required>{{ $generation->generated_text }}</textarea></label>
                            <div class="ai-actions"><button type="submit" name="action" value="reject" class="action-button">Reject</button><button type="submit" name="action" value="approve" class="action-button action-primary">Approve and publish <span aria-hidden="true">→</span></button></div>
                        </form>
                    </article>
                @empty
                    <div class="empty-editions"><p class="eyebrow">Queue clear</p><h3>No AI drafts need review.</h3><p>New biographies and event summaries will appear here after generation.</p></div>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
