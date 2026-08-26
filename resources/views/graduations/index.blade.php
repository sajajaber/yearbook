<x-app-layout>
    <x-slot name="header">
      <div class="dashboard-heading graduation-heading">
        <div>
          <p class="eyebrow">Yearbook office / editions</p>
          <h1>Graduation editions</h1>
        </div>
        <a href="{{ route('graduations.create') }}" class="button button-red"><span aria-hidden="true">+</span> Add graduation</a>
      </div>
    </x-slot>

    @php
    $activeGraduations = $graduations->where('status', 'active');
    $archivedGraduations = $graduations->where('status', 'archived');
    $upcomingGraduation = $activeGraduations->filter(fn ($graduation) => $graduation->ceremony_date >= now()->toDateString())->sortBy('ceremony_date')->first();
    @endphp

    <div class="dashboard-wrap graduation-wrap" x-data="{ search: '' }">
      <section class="graduation-intro">
        <div>
          <p class="eyebrow eyebrow-light">The annual record</p>
          <h2>Every class deserves<br>its own chapter.</h2>
          <p>Organize ceremonies, campus coverage, and the people who make each edition memorable.</p>
        </div>
        <div class="edition-count" aria-label="Active graduation editions">
          <strong>{{ $activeGraduations->count() }}</strong>
          <span>active<br>editions</span>
        </div>
      </section>

      <section class="graduation-toolbar" aria-label="Graduation edition controls">
        <div class="graduation-summary"><span class="status-dot status-published"></span>{{ $activeGraduations->count() }} active <span class="toolbar-divider">/</span> {{ $archivedGraduations->count() }} archived</div>
        <label class="search-field">
          <span aria-hidden="true">⌕</span>
          <input type="search" x-model="search" placeholder="Search editions" aria-label="Search graduation editions">
        </label>
      </section>

      @if ($upcomingGraduation)
      <section class="featured-edition">
        <div class="featured-date"><span>{{ $upcomingGraduation->academicYear?->title ?? 'Upcoming' }}</span><strong>{{ \Carbon\Carbon::parse($upcomingGraduation->ceremony_date)->format('d') }}</strong><small>{{ \Carbon\Carbon::parse($upcomingGraduation->ceremony_date)->format('M Y') }}</small></div>
        <div class="featured-copy">
          <p class="eyebrow">Next ceremony</p>
          <h2>{{ $upcomingGraduation->venue ?: 'Graduation ceremony' }}</h2>
          <p>{{ $upcomingGraduation->description ?: 'This edition is ready for its ceremony details and yearbook coverage.' }}</p>
          <div class="featured-meta">{{ $upcomingGraduation->campuses->count() }} campuses <span>/</span> {{ $upcomingGraduation->schools->count() }} schools <span>/</span> {{ $upcomingGraduation->media->count() }} media assets</div>
        </div>
        <a href="{{ route('graduations.edit', $upcomingGraduation) }}" class="button button-navy">Edit edition <span aria-hidden="true">→</span></a>
      </section>
      @endif

      <section class="edition-section">
        <div class="section-heading">
          <div>
            <p class="eyebrow">Edition archive</p>
            <h2>All graduations</h2>
          </div><span class="panel-meta">{{ $graduations->count() }} total</span>
        </div>
        <div class="edition-grid">
          @forelse ($graduations->sortByDesc('ceremony_date') as $graduation)
          <article class="edition-card" x-show="'{{ strtolower($graduation->academicYear?->title . ' ' . $graduation->venue) }}'.includes(search.toLowerCase())">
            <div class="edition-card-top"><span class="edition-year">{{ $graduation->academicYear?->title ?? 'No academic year' }}</span><span class="edition-status {{ $graduation->status === 'active' ? 'is-active' : 'is-archived' }}">{{ ucfirst($graduation->status) }}</span></div>
            <h3>{{ $graduation->venue ?: 'Graduation ceremony' }}</h3>
            <p class="edition-date">{{ \Carbon\Carbon::parse($graduation->ceremony_date)->format('l, F j, Y') }}</p>
            <div class="edition-card-meta"><span>{{ $graduation->campuses->count() }} campuses</span><span>{{ $graduation->media->count() }} media</span></div>
            <div class="edition-actions"><a href="{{ route('graduations.edit', $graduation) }}" class="text-link">Edit <span aria-hidden="true">→</span></a>
              <form method="POST" action="{{ $graduation->status === 'active' ? route('graduations.destroy', $graduation) : route('graduations.unarchive', $graduation) }}">@csrf @if ($graduation->status === 'active') @method('DELETE') @endif<button type="submit" class="archive-button">{{ $graduation->status === 'active' ? 'Archive' : 'Restore' }}</button></form>
            </div>
          </article>
          @empty
          <div class="empty-editions">
            <p class="eyebrow">No editions yet</p>
            <h3>Start the first chapter.</h3>
            <p>Create a graduation edition to begin organizing the yearbook.</p><a href="{{ route('graduations.create') }}" class="button button-navy">Add graduation <span aria-hidden="true">→</span></a>
          </div>
          @endforelse
        </div>
      </section>
    </div>
  </x-app-layout>