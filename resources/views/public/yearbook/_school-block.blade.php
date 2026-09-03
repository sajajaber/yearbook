<div class="school-block">
    <div class="school-block-heading">
        <h3>{{ $schoolName }}</h3>
        <span>{{ $group['visible']->count() + $group['named']->count() }} students</span>
    </div>

    @if ($group['visible']->isNotEmpty())
    <div class="student-photo-grid">
        @foreach ($group['visible'] as $student)
        <a href="{{ route('public.graduate.detail', $student->id) }}" class="student-photo-card">
            <div class="student-photo-frame">
                @if ($student->media->first())
                <img src="{{ Storage::disk('public')->url($student->media->first()->path) }}" alt="{{ $student->name }}">
                @else
                <span class="initial">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                @endif
            </div>
            <div class="student-name">{{ $student->name }}</div>
            <div class="student-major">{{ $student->major?->name }}</div>
        </a>
        @endforeach
    </div>
    @endif

    @if ($group['named']->isNotEmpty())
    <div class="named-only-list">
        <strong>Also graduating</strong>
        {{ $group['named']->pluck('name')->implode(' &nbsp;&middot;&nbsp; ') }}
    </div>
    @endif
</div>