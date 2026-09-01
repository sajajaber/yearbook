@extends('public.layout')

@section('title', 'Graduation Ceremony')

@section('extra-css')
<style>
    .graduation-hero {
        position: relative;
        height: 400px;
        background: linear-gradient(135deg, rgba(0, 42, 92, 0.7) 0%, rgba(26, 63, 127, 0.7) 100%);
        display: flex;
        align-items: flex-end;
        overflow: hidden;
    }

    .graduation-hero-image {
        position: absolute;
        inset: 0;
        z-index: -1;
    }

    .graduation-hero-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .graduation-hero-content {
        position: relative;
        color: white;
        padding: 40px 20px 20px;
        width: 100%;
    }

    .graduation-hero-content h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 15px;
    }
</style>
@endsection

@section('content')
<!-- Hero -->
<div class="graduation-hero">
    @php
        $image = $graduation->media->first();
    @endphp
    @if($image)
    <div class="graduation-hero-image">
        <img src="{{ Storage::disk('public')->url($image->path) }}" alt="Graduation">
    </div>
    @endif
    <div class="graduation-hero-content">
        <h1>Graduation Ceremony</h1>
        <p style="font-size: 1.1rem; opacity: 0.9;">{{ \Carbon\Carbon::parse($graduation->ceremony_date)->format('F d, Y') }}</p>
    </div>
</div>

<!-- Content -->
<div class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 300px; gap: 40px; margin-bottom: 60px;">
            <div style="background: var(--white); padding: 30px; border-radius: 12px;">
                <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 15px; color: var(--ink);">Ceremony Details</h2>
                <p style="color: var(--ink-soft); line-height: 1.8;">{{ $graduation->description }}</p>
                
                @if($graduation->media->count() > 1)
                <h2 style="font-size: 1.5rem; font-weight: 700; margin: 30px 0 15px; color: var(--ink);">Ceremony Photos</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    @foreach($graduation->media as $media)
                    <img src="{{ Storage::disk('public')->url($media->path) }}" alt="Ceremony" style="border-radius: 12px; width: 100%; height: 200px; object-fit: cover;">
                    @endforeach
                </div>
                @endif
            </div>

            <div>
                <div style="background: var(--white); padding: 20px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                    <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 15px; color: var(--ink);">📋 Details</h3>
                    <p style="font-size: 0.95rem; color: var(--ink-soft); margin-bottom: 10px;">
                        <strong>Date:</strong><br>
                        {{ \Carbon\Carbon::parse($graduation->ceremony_date)->format('F d, Y') }}
                    </p>
                    <p style="font-size: 0.95rem; color: var(--ink-soft);">
                        <strong>Venue:</strong><br>
                        {{ $graduation->venue }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Graduates Section -->
        <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 30px; color: var(--ink);">🎓 Graduates ({{ $graduates->total() }})</h2>
        
        @if($graduates->count() > 0)
            <div class="grid grid-4">
                @foreach($graduates as $graduate)
                <a href="{{ route('public.graduate.detail', $graduate->id) }}" style="text-decoration: none; color: inherit;">
                    <div class="card">
                        @php
                            $portrait = $graduate->media->first();
                        @endphp
                        <div class="card-image" style="height: 200px;">
                            @if($portrait)
                                <img src="{{ Storage::disk('public')->url($portrait->path) }}" alt="{{ $graduate->name }}">
                            @else
                                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--ink) 0%, #1a3f7f 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">{{ strtoupper(substr($graduate->name, 0, 1)) }}</div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">{{ $graduate->name }}</h3>
                            <p class="card-text" style="font-size: 0.9rem;">
                                {{ $graduate->major->name ?? 'Major' }}<br>
                                {{ $graduate->school->name ?? 'School' }}
                            </p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($graduates->hasPages())
            <div class="pagination" style="margin-top: 40px;">
                @if($graduates->onFirstPage())
                    <span>←</span>
                @else
                    <a href="{{ $graduates->previousPageUrl() }}">←</a>
                @endif

                @foreach($graduates->getUrlRange(1, $graduates->lastPage()) as $page => $url)
                    @if($page == $graduates->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if($graduates->hasMorePages())
                    <a href="{{ $graduates->nextPageUrl() }}">→</a>
                @else
                    <span>→</span>
                @endif
            </div>
            @endif
        @else
            <p style="text-align: center; color: var(--ink-soft); padding: 40px;">No graduate profiles available for this graduation.</p>
        @endif
    </div>
</div>
@endsection
