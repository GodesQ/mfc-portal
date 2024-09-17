<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-wide-white.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img class="mt-3" src="{{ URL::asset('build/images/logo-wide-white.png') }}" alt=""
                    height="80">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
        <p id="mportal" class="text-white mt-1">Missionary Portal</p>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>@lang('translation.menu')</span></li>
                {{-- <li class="menu-title"><span>@lang('translation.menu')</span></li> --}}
                <li class="nav-item">
                    <a class="nav-link menu-link {{ preg_match('/^dashboards$/', Request::path()) ? 'active' : '' }}"
                        href="{{ route('dashboards.index') }}">
                        <i class="ri-dashboard-2-line"></i> <span>@lang('translation.dashboards')</span>
                    </a>
                </li>

                @role('super_admin')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ preg_match('/^dashboard\/announcement/', Request::path()) ? 'active' : '' }}"
                            href="{{ route('announcements.index') }}">
                            <i class="ri-megaphone-line"></i> <span>@lang('translation.announcements')</span>
                        </a>
                    </li>
                @endrole
                
                @role('super_admin')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ preg_match('/^dashboard\/directory/', Request::path()) ? 'active' : 'false' }}"
                            href="#directory" data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="directory">
                            <i class="ri-map-pin-user-line"></i><span>@lang('translation.directory')</span>
                        </a>
                        <div class="collapse menu-dropdown {{ preg_match('/^dashboard\/directory/', Request::path()) ? 'show' : '' }}"
                            id="directory">
                            <ul class="nav nav-sm flex-column">
                                <li
                                    class="nav-item {{ preg_match('/^dashboard\/directory\/all/', Request::path()) ? 'active' : '' }}">
                                    <a href="{{ route('users.index', ['section' => 'all']) }}"
                                        class="nav-link {{ preg_match('/^dashboard\/directory\/all/', Request::path()) ? 'active' : '' }}">@lang('translation.all')</a>
                                </li>
                                <li
                                    class="nav-item {{ preg_match('/^dashboard\/directory\/all/', Request::path()) ? 'active' : '' }}">
                                    <a href="{{ route('users.index', ['section' => 'kids']) }}"
                                        class="nav-link {{ preg_match('/^dashboard\/directory\/kids/', Request::path()) ? 'active' : '' }}">@lang('translation.kids')</a>
                                </li>
                                <li
                                    class="nav-item {{ preg_match('/^dashboard\/directory\/youth/', Request::path()) ? 'active' : '' }}">
                                    <a href="{{ route('users.index', ['section' => 'youth']) }}"
                                        class="nav-link {{ preg_match('/^dashboard\/directory\/youth/', Request::path()) ? 'active' : '' }}">@lang('translation.youth')</a>
                                </li>
                                <li
                                    class="nav-item {{ preg_match('/^dashboard\/directory\/singles/', Request::path()) ? 'active' : '' }}">
                                    <a href="{{ route('users.index', ['section' => 'singles']) }}"
                                        class="nav-link {{ preg_match('/^dashboard\/directory\/singles/', Request::path()) ? 'active' : '' }}">@lang('translation.singles')</a>
                                </li>
                                <li
                                    class="nav-item {{ preg_match('/^dashboard\/directory\/servants/', Request::path()) ? 'active' : '' }}">
                                    <a href="{{ route('users.index', ['section' => 'servants']) }}"
                                        class="nav-link {{ preg_match('/^dashboard\/directory\/servants/', Request::path()) ? 'active' : '' }}">@lang('translation.servants')</a>
                                </li>
                                <li
                                    class="nav-item {{ preg_match('/^dashboard\/directory\/handmaids/', Request::path()) ? 'active' : '' }}">
                                    <a href="{{ route('users.index', ['section' => 'handmaids']) }}"
                                        class="nav-link {{ preg_match('/^dashboard\/directory\/handmaids/', Request::path()) ? 'active' : '' }}">@lang('translation.handmaids')</a>
                                </li>
                                <li
                                    class="nav-item {{ preg_match('/^dashboard\/directory\/couples/', Request::path()) ? 'active' : '' }}">
                                    <a href="{{ route('users.index', ['section' => 'couples']) }}"
                                        class="nav-link {{ preg_match('/^dashboard\/directory\/couples/', Request::path()) ? 'active' : '' }}">@lang('translation.couples')</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endrole

                <li class="nav-item">
                    <a class="nav-link menu-link {{ preg_match('/^dashboard\/events/', Request::path()) ? 'active' : '' }}"
                        href="#events" data-bs-toggle="collapse" role="button" aria-expanded="false"
                        aria-controls="events">
                        <i class="ri-calendar-check-line"></i> <span>@lang('translation.event_management')</span>
                    </a>
                    <div class="collapse menu-dropdown {{ preg_match('/^dashboard\/events/', Request::path()) ? 'show' : '' }}"
                        id="events">
                        <ul class="nav nav-sm flex-column">
                            <li
                                class="nav-item {{ preg_match('/^dashboard\/events/', Request::path()) ? 'active' : '' }}">
                                <a href="{{ route('events.index') }}"
                                    class="nav-link {{ 'dashboard/events' === Request::path() ? 'active' : '' }}">@lang('translation.list')</a>
                            </li>
                            <li
                                class="nav-item {{ preg_match('/^dashboard\/events/', Request::path()) ? 'active' : '' }}">
                                <a href="{{ route('events.calendar') }}"
                                    class="nav-link {{ 'dashboard/events/calendar' === Request::path() ? 'active' : '' }}">@lang('translation.calendar')</a>
                            </li>
                            <li
                                class="nav-item {{ Request::is('dashboard/users/' . auth()->user()->id . "/events/registrations") ? 'active' : '' }}">
                                <a href="{{ route('users.events.registrations', auth()->user()->id) }}"
                                    class="nav-link {{ Request::is('dashboard/users/' . auth()->user()->id . "/events/registrations") ? 'active' : '' }}">
                                    My Registrations
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::is('dashboard/tithes') ? 'active' : '' }}"
                        href="{{ route('tithes.index') }}">
                        <i class="ri-bubble-chart-line"></i> <span>@lang('translation.tithes')</span>
                    </a>
                </li>

                @role('super_admin')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('dashboard/attendances') ? 'active' : '' }}"
                                 href="{{ route('attendances.index') }}">
                            <i class="ri-folder-user-line"></i> <span>@lang('translation.attendance')</span>
                        </a>
                    </li>
                @endrole

                @role('super_admin')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('dashboard/transactions') ? 'active' : '' }}"
                            href="{{ route('transactions.index') }}">
                            <i class="ri-wallet-3-line"></i> <span>@lang('translation.transactions')</span>
                        </a>
                    </li>
                @endrole
                
                @role('super_admin')
                    <li class="menu-title"><i class="ri-more-fill"></i> <span>@lang('translation.management')</span></li>
                @endrole

                @role('super_admin')
                    <li class="nav-item">
                        <a class="nav-link {{ preg_match('/^dashboard\/roles/', Request::path()) ? 'active' : '' }} menu-link" href="{{ route('roles.index') }}">
                            <i class="ri-shield-user-line"></i> <span>@lang('translation.roles')</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link" {{ preg_match('/^dashboard\/permissions/', Request::path()) ? 'active' : '' }} href="{{ route('permissions.index') }}">
                            <i class="ri-body-scan-line"></i> <span>@lang('translation.permissions')</span>
                        </a>
                    </li>
                @endrole
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
