<div class="school-block">
    <div class="school-block-heading">
        <h3>{{ $schoolName }}</h3>
        <span>{{ $group['visible']->count() }} students</span>
    </div>

    @if ($group['visible']->isNotEmpty())
    <div class="student-photo-grid">
        @foreach ($group['visible'] as $student)
        @php
            $portrait = $student->portraitMedia ?? $student->media->firstWhere('type', 'image');
        @endphp
        @if ($student->student_reference)
        <a href="{{ route('public.graduate.detail', ['student_reference' => $student->student_reference]) }}" class="student-photo-card">
        @else
        <div class="student-photo-card">
        @endif
            <div class="student-photo-frame">
                @if ($portrait)
                <img src="{{ $portrait->thumbnailUrl() }}" alt="{{ $portrait->alt_text ?? $student->name }}">
                @else
                <span class="initial">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                @endif
            </div>
            <div class="student-name">{{ $student->name }}</div>
            <div class="student-major">{{ $student->major?->name }}</div>
        @if ($student->student_reference)
        </a>
        @else
        </div>
        @endif
        @endforeach
    </div>
    @endif
</div>
