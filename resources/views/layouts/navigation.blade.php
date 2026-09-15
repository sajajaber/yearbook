<nav x-data="{ open: false }" class="site-nav">
    <div class="nav-inner">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="brand-lockup">
                        <span class="brand-mark">LIU</span>
                        <span class="brand-copy">Yearbook<br><small>Office</small></span>
                    </a>
                </div>

                <div class="nav-links hidden sm:flex">
                    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">Overview</a>
                    <a href="{{ route('graduates.index') }}" class="nav-item {{ request()->routeIs('graduates.*') ? 'is-active' : '' }}">Graduates</a>
                    <a href="{{ route('events.index') }}" class="nav-item {{ request()->routeIs('events.*') ? 'is-active' : '' }}">Events</a>
                    <a href="{{ route('media.index') }}" class="nav-item {{ request()->routeIs('media.*') ? 'is-active' : '' }}">Media</a>
                    @if (in_array(Auth::user()?->role?->role_name, ['admin', 'reviewer']))
                        <a href="{{ route('ai-generations.index') }}" class="nav-item {{ request()->routeIs('ai-generations.*') ? 'is-active' : '' }}">AI review</a>
                    @endif
                    @if (Auth::user()?->role?->role_name === 'admin')
                        <a href="{{ route('settings.index') }}" class="nav-item {{ request()->routeIs('settings.*') ? 'is-active' : '' }}">Settings</a>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6" style="gap:10px;">
                <div class="notification-menu" x-data="{ notificationsOpen: false }">
                    <button type="button" class="notification-trigger" @click="notificationsOpen = !notificationsOpen" :aria-expanded="notificationsOpen.toString()" aria-label="Notifications">
                        <span aria-hidden="true">♢</span>
                        @if (Auth::user()->unreadNotifications->count())<b>{{ Auth::user()->unreadNotifications->count() > 9 ? '9+' : Auth::user()->unreadNotifications->count() }}</b>@endif
                    </button>
                    <div x-show="notificationsOpen" @click.outside="notificationsOpen = false" x-cloak class="notification-panel">
                        <div class="notification-panel-head"><strong>Notifications</strong><span>{{ Auth::user()->unreadNotifications->count() }} unread</span></div>
                        @forelse (Auth::user()->notifications->take(6) as $notification)
                            <a href="{{ route('notifications.read', $notification->id) }}" class="notification-item {{ $notification->read_at ? '' : 'is-unread' }}">
                                <span class="notification-dot"></span>
                                <span><strong>{{ $notification->data['title'] ?? 'Yearbook update' }}</strong><small>{{ $notification->data['message'] ?? '' }}</small><em>{{ $notification->created_at->diffForHumans() }}</em></span>
                            </a>
                        @empty
                            <div class="notification-empty">You're all caught up.</div>
                        @endforelse
                    </div>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div class="user-name">
                                {{ Auth::user()?->name }}
                                @if (Auth::user()?->role)<small>{{ ucfirst(Auth::user()?->role->role_name) }}</small>@endif
                            </div>
                            <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">@csrf<x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link></form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="mobile-toggle sm:hidden"><button @click="open = ! open" aria-label="Toggle navigation" :aria-expanded="open.toString()" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none transition duration-150 ease-in-out"><svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path :class="{'hidden': open, 'inline-flex': ! open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /><path :class="{'hidden': ! open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button></div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="mobile-menu hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'is-active' : '' }}">Overview</a>
            <a href="{{ route('graduates.index') }}" class="{{ request()->routeIs('graduates.*') ? 'is-active' : '' }}">Graduates</a>
            <a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.*') ? 'is-active' : '' }}">Events</a>
            <a href="{{ route('media.index') }}" class="{{ request()->routeIs('media.*') ? 'is-active' : '' }}">Media</a>
            @if (in_array(Auth::user()?->role?->role_name, ['admin', 'reviewer']))<a href="{{ route('ai-generations.index') }}" class="{{ request()->routeIs('ai-generations.*') ? 'is-active' : '' }}">AI review</a>@endif
            @if (Auth::user()?->role?->role_name === 'admin')<a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'is-active' : '' }}">Settings</a>@endif
        </div>
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4"><div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()?->name }}</div><div class="font-medium text-sm text-gray-500">{{ Auth::user()?->email }}</div>@if (Auth::user()?->role)<div class="mobile-role">{{ ucfirst(Auth::user()->role->role_name) }}</div>@endif</div>
            <div class="mt-3 space-y-1"><x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link><form method="POST" action="{{ route('logout') }}">@csrf<x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-responsive-nav-link></form></div>
        </div>
    </div>

    <style>
        [x-cloak] { display:none!important; }
        .notification-menu { position:relative; }
        .notification-trigger { position:relative; width:40px; height:40px; border:1px solid var(--line,#d8e3ef); border-radius:10px; background:#fff; color:var(--ink,#002a5c); font-size:21px; cursor:pointer; }
        .notification-trigger b { position:absolute; top:-5px; right:-5px; min-width:18px; height:18px; padding:0 4px; border-radius:20px; background:#ffb034; color:#002a5c; font:700 10px/18px Inter,sans-serif; }
        .notification-panel { position:absolute; right:0; top:48px; width:360px; background:#fff; border:1px solid #d8e3ef; border-radius:14px; box-shadow:0 18px 45px rgba(0,42,92,.15); overflow:hidden; z-index:50; }
        .notification-panel-head { display:flex; justify-content:space-between; padding:15px 17px; border-bottom:1px solid #e7edf4; color:#002a5c; }
        .notification-panel-head span { font-size:11px; color:#64748b; }
        .notification-item { display:flex; gap:11px; padding:13px 16px; text-decoration:none; color:#002a5c; border-bottom:1px solid #eef2f6; }
        .notification-item:hover { background:#f7fbff; }
        .notification-item.is-unread { background:#fffaf0; }
        .notification-item span:nth-child(2) { display:flex; flex-direction:column; gap:3px; }
        .notification-item strong { font-size:13px; }
        .notification-item small { color:#64748b; line-height:1.4; }
        .notification-item em { color:#94a3b8; font-size:10px; font-style:normal; }
        .notification-dot { width:7px; height:7px; margin-top:5px; border-radius:50%; background:#ffb034; opacity:0; }
        .is-unread .notification-dot { opacity:1; }
        .notification-empty { padding:24px 18px; text-align:center; color:#64748b; font-size:13px; }
        .mobile-menu a.is-active { color:var(--ink); background:#f7fbff; border-left:3px solid var(--red); padding-left:12px; }
        .mobile-role { color:#0a66c2; font-size:10px; font-weight:700; letter-spacing:1.2px; margin-top:5px; text-transform:uppercase; }
        @media (max-width:639px) { .site-nav .flex.justify-between.h-16 { width:100%; height:auto; min-height:58px; } .mobile-toggle{display:flex;align-items:center;} .mobile-menu{max-height:calc(100vh - 58px);overflow-y:auto;} }
    </style>
</nav>
