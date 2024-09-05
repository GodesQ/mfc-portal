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

    {{-- <div class="dropdown sidebar-user m-1 rounded">
        <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <span class="d-flex align-items-center gap-2">
                <img class="rounded header-profile-user"
                    src="@if (Auth::user()->avatar != '') {{ URL::asset('build/images/users/' . Auth::user()->avatar) }}@else{{ URL::asset('build/images/users/avatar-1.jpg') }} @endif"
                    alt="Header Avatar">
                <span class="text-start">
                    <span class="d-block fw-medium sidebar-user-name-text">{{ Auth::user()->name }}</span>
                    <span class="d-block fs-14 sidebar-user-name-sub-text"><i
                            class="ri ri-circle-fill fs-10 text-success align-baseline"></i> <span
                            class="align-middle">Online</span></span>
                </span>
            </span>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            <!-- item-->
            <h6 class="dropdown-header">Welcome {{ Auth::user()->name }}!</h6>
            <a class="dropdown-item" href="pages-profile"><i
                    class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Profile</span></a>
            <a class="dropdown-item" href="apps-chat"><i
                    class="mdi mdi-message-text-outline text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Messages</span></a>
            <a class="dropdown-item" href="apps-tasks-kanban"><i
                    class="mdi mdi-calendar-check-outline text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Taskboard</span></a>
            <a class="dropdown-item" href="pages-faqs"><i
                    class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Help</span></a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="pages-profile"><i
                    class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Balance :
                    <b>$5971.67</b></span></a>
            <a class="dropdown-item" href="pages-profile-settings"><span
                    class="badge bg-success-subtle text-success mt-1 float-end">New</span><i
                    class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Settings</span></a>
            <a class="dropdown-item" href="auth-lockscreen-basic"><i
                    class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Lock
                    screen</span></a>

            <a class="dropdown-item " href="javascript:void();"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                    class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span
                    key="t-logout">@lang('translation.logout')</span></a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div> --}}

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

                <li class="nav-item">
                    <a class="nav-link menu-link {{ preg_match('/^dashboard\/announcement/', Request::path()) ? 'active' : '' }}"
                        href="{{ route('announcements.index') }}">
                        <i class="ri-megaphone-line"></i> <span>@lang('translation.announcements')</span>
                    </a>
                </li>

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
                                class="nav-item {{ preg_match('/^dashboard\/directory\/kids/', Request::path()) ? 'active' : '' }}">
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
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ preg_match('/^dashboard\/tithes/', Request::path()) ? 'active' : '' }}"
                        href="{{ route('tithes.index') }}">
                        <i class="ri-bubble-chart-line"></i> <span>@lang('translation.tithes')</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('attendances.index') }}">
                        <i class="ri-folder-user-line"></i> <span>@lang('translation.attendance')</span>
                    </a>
                </li>

                <li class="menu-title"><i class="ri-more-fill"></i> <span>@lang('translation.management')</span></li>

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
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
