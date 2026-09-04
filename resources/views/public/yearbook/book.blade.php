@extends('public.layout')

@section('title', 'Yearbook ' . $yearbookYear)

@section('content')
<div class="hero">
    <div class="container">
        <h1>Yearbook {{ $yearbookYear }}</h1>
        <p>Everything from the {{ $yearbookYear - 1 }}–{{ $yearbookYear }} academic year</p>
    </div>
</div>

<div class="section">
    <div class="container">
        <h2 class="section-title">Events</h2>
        <div class="grid grid-3">
            @forelse ($events as $event)
            <a href="{{ route('public.event.detail', $event->id) }}" style="text-decoration:none;color:inherit;">
                <div class="card">
                    <div class="card-image">
                        @if($event->media->first())
                        <img src="{{ Storage::disk('public')->url($event->media->first()->path) }}" alt="{{ $event->title }}">
                        @else
                        <div style="width:100%;height:100%;background:linear-gradient(135deg,var(--ink) 0%,#1a3f7f 100%);"></div>
                        @endif
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">{{ $event->title }}</h3>
                        <p class="card-text">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</p>
                    </div>
                </div>
            </a>
            @empty
            <p>No published events for this yearbook yet.</p>
            @endforelse
        </div>

        <h2 class="section-title" style="margin-top:60px;">Graduations</h2>
        <div class="grid grid-2">
            @forelse ($graduations as $graduation)
            <a href="{{ route('public.graduation.detail', $graduation->id) }}" style="text-decoration:none;color:inherit;">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">{{ $graduation->venue ?: 'Graduation Ceremony' }}</h3>
                        <p class="card-text">{{ \Carbon\Carbon::parse($graduation->ceremony_date)->format('F d, Y') }}</p>
                    </div>
                </div>
            </a>
            @empty
            <p>No graduation ceremonies for this yearbook yet.</p>
            @endforelse
        </div>

        <h2 class="section-title" style="margin-top:60px;">Graduates</h2>
        <div class="grid grid-4">
            @forelse ($graduates as $graduate)
            <a href="{{ route('public.graduate.detail', $graduate->id) }}" style="text-decoration:none;color:inherit;">
                <div class="card" style="text-align:center;">
                    <div class="card-body">
                        <h3 class="card-title">{{ $graduate->name }}</h3>
                        <p class="card-text">{{ $graduate->major->name ?? '' }}</p>
                    </div>
                </div>
            </a>
            @empty
            <p>No published graduate profiles for this yearbook yet.</p>
            @endforelse
        </div>

        {{ $graduates->links() }}
    </div>
</div>
@endsection