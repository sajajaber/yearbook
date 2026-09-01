@extends('public.layout')

@section('title', 'Graduates')

@section('extra-css')
<style>
    .graduates-filters {
        background: var(--white);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--ink);
    }

    .filter-group input,
    .filter-group select {
        padding: 10px 12px;
        border: 1px solid var(--line);
        border-radius: 6px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: var(--red);
        box-shadow: 0 0 0 3px rgba(255, 176, 52, 0.1);
    }

    .graduate-card {
        text-align: center;
        transition: all 0.3s ease;
    }

    .graduate-card .card-image {
        position: relative;
        overflow: hidden;
    }

    .graduate-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: var(--red);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .graduate-card .card-body {
        text-align: center;
    }

    .graduate-info {
        font-size: 0.9rem;
        color: var(--ink-soft);
        margin-bottom: 8px;
    }
</style>
@endsection

@section('content')
<!-- Hero -->
<div class="hero">
    <div class="container">
        <h1>Graduates Directory</h1>
        <p>Meet the accomplished graduates from this year</p>
    </div>
</div>

<!-- Filters -->
<div class="section">
    <div class="container">
        <div class="graduates-filters">
            <form method="GET" action="{{ route('public.graduates') }}" id="graduatesForm">
                <div class="filter-row">
                    <div class="filter-group">
                        <label>Search Name</label>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Graduate name...">
                    </div>
                    <div class="filter-group">
                        <label>School</label>
                        <select name="school">
                            <option value="">All Schools</option>
                            @foreach($schools as $s)
                            <option value="{{ $s->id }}" @if($s->id == $school) selected @endif>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Campus</label>
                        <select name="campus">
                            <option value="">All Campuses</option>
                            @foreach($campuses as $c)
                            <option value="{{ $c->id }}" @if($c->id == $campus) selected @endif>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Sort By</label>
                        <select name="sort">
                            <option value="name" @if($sort === 'name') selected @endif>Name (A-Z)</option>
                            <option value="latest" @if($sort === 'latest') selected @endif>Latest</option>
                            <option value="oldest" @if($sort === 'oldest') selected @endif>Oldest</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary">Search</button>
                    @if($search || $school || $campus)
                    <a href="{{ route('public.graduates') }}" class="btn btn-secondary">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        @if($graduates->count() > 0)
            <p style="color: var(--ink-soft); font-size: 0.95rem; margin-bottom: 20px;">
                Showing {{ $graduates->from() }}-{{ $graduates->to() }} of {{ $graduates->total() }} graduates
            </p>

            <div class="grid grid-4">
                @foreach($graduates as $graduate)
                <a href="{{ route('public.graduate.detail', $graduate->id) }}" style="text-decoration: none; color: inherit;">
                    <div class="card graduate-card">
                        @php
                            $portrait = $graduate->media->first();
                        @endphp
                        <div class="card-image">
                            @if($portrait)
                                <img src="{{ Storage::disk('public')->url($portrait->path) }}" alt="{{ $graduate->name }}">
                            @else
                                <div style="width: 100%; height: 250px; background: linear-gradient(135deg, var(--ink) 0%, #1a3f7f 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 4rem; font-weight: 700;">{{ strtoupper(substr($graduate->name, 0, 1)) }}</div>
                            @endif
                            @if($graduate->graduation)
                            <span class="graduate-badge">{{ $graduate->graduation->ceremony_date ? \Carbon\Carbon::parse($graduate->graduation->ceremony_date)->format('Y') : 'Class' }}</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">{{ $graduate->name }}</h3>
                            <div class="graduate-info">
                                {{ $graduate->major->name ?? 'Major' }}
                            </div>
                            <div class="graduate-info">
                                {{ $graduate->school->name ?? 'School' }}
                            </div>
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
            <div style="text-align: center; padding: 60px 20px;">
                <p style="font-size: 1.1rem; color: var(--ink-soft); margin-bottom: 20px;">No graduates found matching your filters.</p>
                <a href="{{ route('public.graduates') }}" class="btn btn-primary">Browse All Graduates</a>
            </div>
        @endif
    </div>
</div>
@endsection
