<nav x-data="{ open: false }" class="site-nav">
    <div class="nav-inner">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="brand-lockup"><span class="brand-mark">LIU</span><span class="brand-copy">Yearbook<br><small>Office</small></span></a>
                </div>
                <div class="nav-links hidden sm:flex">
                    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">Overview</a>
                    <a href="{{ route('graduates.index') }}" class="nav-item {{ request()->routeIs('graduates.*') ? 'is-active' : '' }}">Graduates</a>
                    <a href="{{ route('events.index') }}" class="nav-item {{ request()->routeIs('events.*') ? 'is-active' : '' }}">Events</a>
                    <a href="{{ route('media.index') }}" class="nav-item {{ request()->routeIs('media.*') ? 'is-active' : '' }}">Media</a>
                    @if (in_array(Auth::user()?->role?->role_name, ['admin', 'reviewer']))<a href="{{ route('ai-generations.index') }}" class="nav-item {{ request()->routeIs('ai-generations.*') ? 'is-active' : '' }}">AI review</a>@endif
                    @if (Auth::user()?->role?->role_name === 'admin')
                        <a href="{{ route('settings.index') }}" class="nav-item {{ request()->routeIs('settings.*') ? 'is-active' : '' }}">Settings</a>
                        <a href="{{ route('audit-logs.index') }}" class="nav-item {{ request()->routeIs('audit-logs.*') ? 'is-active' : '' }}">Audit Log</a>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6" style="gap:10px;">
                @php $unreadNotifications = Auth::user()->unreadNotifications; @endphp
                <div class="notification-menu" x-data="{ notificationsOpen: false }">
                    <button type="button" class="notification-trigger {{ $unreadNotifications->count() ? 'has-unread' : '' }}" @click="notificationsOpen = !notificationsOpen" :aria-expanded="notificationsOpen.toString()" aria-label="Notifications">
                        <svg class="notification-bell" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/><path d="M10 21h4"/></svg>
                        @if ($unreadNotifications->count())<span class="notification-count">{{ $unreadNotifications->count() > 9 ? '9+' : $unreadNotifications->count() }}</span>@endif
                    </button>
                    <div x-show="notificationsOpen" @click.outside="notificationsOpen = false" x-transition.origin.top.right x-cloak class="notification-panel">
                        <div class="notification-panel-head"><div><strong>Notifications</strong><small>Yearbook office updates</small></div><span>{{ $unreadNotifications->count() }} unread</span></div>
                        <div class="notification-list">
                            @forelse (Auth::user()->notifications->take(6) as $notification)
                                <a href="{{ route('notifications.read', $notification->id) }}" class="notification-item {{ $notification->read_at ? '' : 'is-unread' }}">
                                    <span class="notification-item-icon {{ ($notification->data['event'] ?? '') === 'changes_requested' ? 'is-action' : 'is-update' }}" aria-hidden="true">
                                        @if (($notification->data['event'] ?? '') === 'changes_requested')<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 6v6l4 2"/><circle cx="12" cy="12" r="9"/></svg>@else<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>@endif
                                    </span>
                                    <span class="notification-copy"><strong>{{ $notification->data['title'] ?? 'Yearbook update' }}</strong><small>{{ $notification->data['message'] ?? '' }}</small><em>{{ $notification->created_at->diffForHumans() }}</em></span>
                                    @if (!$notification->read_at)<span class="notification-unread-dot" aria-label="Unread"></span>@endif
                                </a>
                            @empty
                                <div class="notification-empty"><span class="notification-empty-icon" aria-hidden="true">✓</span><strong>You're all caught up</strong><small>No new review updates right now.</small></div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger"><button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"><div class="user-name">{{ Auth::user()?->name }} @if (Auth::user()?->role)<small>{{ ucfirst(Auth::user()?->role->role_name) }}</small>@endif</div><div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div></button></x-slot>
                    <x-slot name="content"><x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link><form method="POST" action="{{ route('logout') }}">@csrf<x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link></form></x-slot>
                </x-dropdown>
            </div>

            <div class="mobile-toggle sm:hidden"><button @click="open = ! open" aria-label="Toggle navigation" :aria-expanded="open.toString()" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none transition duration-150 ease-in-out"><svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path :class="{'hidden': open, 'inline-flex': ! open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /><path :class="{'hidden': ! open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button></div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="mobile-menu hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1"><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'is-active' : '' }}">Overview</a><a href="{{ route('graduates.index') }}" class="{{ request()->routeIs('graduates.*') ? 'is-active' : '' }}">Graduates</a><a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.*') ? 'is-active' : '' }}">Events</a><a href="{{ route('media.index') }}" class="{{ request()->routeIs('media.*') ? 'is-active' : '' }}">Media</a>@if (in_array(Auth::user()?->role?->role_name, ['admin', 'reviewer']))<a href="{{ route('ai-generations.index') }}" class="{{ request()->routeIs('ai-generations.*') ? 'is-active' : '' }}">AI review</a>@endif @if (Auth::user()?->role?->role_name === 'admin')<a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'is-active' : '' }}">Settings</a><a href="{{ route('audit-logs.index') }}" class="{{ request()->routeIs('audit-logs.*') ? 'is-active' : '' }}">Audit Log</a>@endif</div>
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600"><div class="px-4"><div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()?->name }}</div><div class="font-medium text-sm text-gray-500">{{ Auth::user()?->email }}</div>@if (Auth::user()?->role)<div class="mobile-role">{{ ucfirst(Auth::user()->role->role_name) }}</div>@endif</div><div class="mt-3 space-y-1"><x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link><form method="POST" action="{{ route('logout') }}">@csrf<x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-responsive-nav-link></form></div></div>
    </div>

    <style>
        [x-cloak]{display:none!important}.notification-menu{position:relative}.notification-trigger{position:relative;display:grid;place-items:center;width:42px;height:42px;border:1px solid var(--line,#d8e3ef);border-radius:12px;background:linear-gradient(180deg,#fff,#f7fbff);color:var(--ink,#002a5c);cursor:pointer;box-shadow:0 4px 12px rgba(0,42,92,.05);transition:transform .2s ease,border-color .2s ease,box-shadow .2s ease}.notification-trigger:hover,.notification-trigger:focus-visible{transform:translateY(-1px);border-color:#b9cde0;box-shadow:0 8px 20px rgba(0,42,92,.1);outline:none}.notification-trigger.has-unread{border-color:#e6c477}.notification-bell{width:19px;height:19px}.notification-count{position:absolute;top:-7px;right:-7px;display:grid;place-items:center;min-width:20px;height:20px;padding:0 5px;border:2px solid #fff;border-radius:999px;background:#ffb034;color:#002a5c;font:800 10px/1 Inter,sans-serif;box-shadow:0 3px 8px rgba(0,42,92,.16)}.notification-panel{position:absolute;right:0;top:52px;width:390px;background:#fff;border:1px solid #d8e3ef;border-radius:18px;box-shadow:0 22px 55px rgba(0,42,92,.18);overflow:hidden;z-index:50}.notification-panel:before{content:'';position:absolute;right:15px;top:-6px;width:11px;height:11px;background:#fff;border-left:1px solid #d8e3ef;border-top:1px solid #d8e3ef;transform:rotate(45deg)}.notification-panel-head{position:relative;display:flex;align-items:center;justify-content:space-between;padding:17px 18px;border-bottom:1px solid #e7edf4;background:#fbfdff}.notification-panel-head strong{display:block;color:#002a5c;font-size:14px}.notification-panel-head small{display:block;margin-top:3px;color:#94a3b8;font-size:10px}.notification-panel-head span{padding:5px 8px;border-radius:999px;background:#eef5fb;color:#64748b;font-size:10px;font-weight:700}.notification-list{max-height:420px;overflow-y:auto}.notification-item{position:relative;display:flex;gap:11px;padding:14px 16px;text-decoration:none;color:#002a5c;border-bottom:1px solid #eef2f6;transition:background .18s ease}.notification-item:hover{background:#f7fbff}.notification-item.is-unread{background:#fffaf0}.notification-item-icon{display:grid;place-items:center;width:34px;height:34px;flex:0 0 34px;border-radius:10px}.notification-item-icon svg{width:16px;height:16px}.notification-item-icon.is-action{background:#fff1dc;color:#a66a08}.notification-item-icon.is-update{background:#eaf3fb;color:#0a568d}.notification-copy{display:flex;min-width:0;flex:1;flex-direction:column;gap:3px}.notification-copy strong{font-size:12px;line-height:1.35}.notification-copy small{color:#64748b;line-height:1.45;font-size:11px}.notification-copy em{color:#94a3b8;font-size:10px;font-style:normal;margin-top:2px}.notification-unread-dot{width:7px;height:7px;flex:0 0 7px;margin-top:5px;border-radius:50%;background:#ffb034;box-shadow:0 0 0 3px rgba(255,176,52,.12)}.notification-empty{display:flex;align-items:center;flex-direction:column;gap:5px;padding:34px 20px;color:#64748b;text-align:center}.notification-empty-icon{display:grid;place-items:center;width:34px;height:34px;margin-bottom:3px;border-radius:50%;background:#edf7f1;color:#27805a;font-weight:800}.notification-empty strong{color:#334155;font-size:12px}.notification-empty small{font-size:11px}.mobile-menu a.is-active{color:var(--ink);background:#f7fbff;border-left:3px solid var(--red);padding-left:12px}.mobile-role{color:#0a66c2;font-size:10px;font-weight:700;letter-spacing:1.2px;margin-top:5px;text-transform:uppercase}@media(max-width:639px){.site-nav .flex.justify-between.h-16{width:100%;height:auto;min-height:58px}.mobile-toggle{display:flex;align-items:center}.mobile-menu{max-height:calc(100vh - 58px);overflow-y:auto}}
    </style>
</nav>
