@extends('layouts.master')
@section('title')
    Dashboard
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.dashboards')
        @endslot
        @slot('li_1')
            Home
        @endslot
    @endcomponent
    <div class="row mb-3 pb-1">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-lg-row flex-column" style="justify-content: space-between;">
                <div class="col-lg-6">
                    <h4 class="fs-16 mb-1">Good Morning, {{ auth()->user()->first_name }}!</h4>
                    <p class="text-muted mb-0 fs-11">"I pray also that...you may know the hope to which he has called you, the riches of his glorious inheritance in the saints, and his incomparably great power for us who believe." - Ephesians 1:18-19</p>
                </div>
                <div class="mt-3 mt-lg-0">
                    <form action="javascript:void(0);">
                        <div class="row g-3 mb-0 align-items-center">
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary material-shadow-none"><i class="mdi mdi-hands-pray fs-15"></i> Send Prayer Intention</button>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('events.calendar') }}" class="btn btn-info material-shadow-none"><i class="mdi mdi-calendar-multiselect fs-15"></i> View Events</a>
                            </div>
                            <!--end col-->
                             <div class="col-auto">
                                <button type="button" class="btn btn-success material-shadow-none"><i class="mdi mdi-hand-coin fs-15"></i> Give Tithes</button>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>
            </div><!-- end card header -->
        </div>
        <!--end col-->
    </div>
    <div class="row">
        <div class="col-xl-7">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Recent Event Registration</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-borderless table-hover table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-muted">
                                    <th scope="col">Name</th>
                                    <th scope="col">Event</th>
                                    <th scope="col">Date Registered</th>
                                    <th scope="col">Fee</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($recent_event_registrations as $registration)
                                    <tr>
                                        <td><img src="{{ URL::asset('images/' . $registration->user->avatar)}}" alt="" class="avatar-xs rounded-circle me-2 material-shadow">
                                            <a href="#javascript: void(0);" class="text-body fw-medium">{{ $registration->user->first_name . ' ' . $registration->user->last_name }}</a>
                                        <td>{{ $registration->event->title }}</td>
                                        </td>
                                        <!-- <td><span class="badge bg-success-subtle text-success p-2">Deal Won</span></td> -->
                                        <td>{{ Carbon::parse($registration->created_at)->format('M d, Y') }}</td>
                                        <td>
                                            <div class="text-nowrap">P {{ number_format($registration->amount, 2) }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                                
                            </tbody><!-- end tbody -->
                        </table><!-- end table -->
                    </div><!-- end table responsive -->
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-5">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Latest Tithe</h4>
                    <div class="flex-shrink-0">
                        <div class="dropdown card-header-dropdown">
                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <span class="text-muted fs-18"><i class="mdi mdi-dots-vertical"></i></span>
                            </a>
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body pt-0">
                    <ul class="list-group list-group-flush border-dashed">
                        @forelse ($latest_tithes as $tithe)
                            <li class="list-group-item ps-0">
                                <div class="row align-items-center g-3">
                                    <div class="col">
                                        <a href="#" class="text-reset fs-14 mb-0">{{ Carbon::parse($tithe->created_at)->format('F d, Y') }}</a>
                                    </div>
                                    <div class="col-sm-auto">
                                        <button type="button" class="btn btn-sm btn-outline-primary" disabled>
                                            <i class="mdi mdi-note-edit-outline"></i> P {{ number_format($tithe->amount, 2) }}
                                        </button>
                                    </div>
                                </div>
                                <!-- end row -->
                            </li><!-- end -->
                        @empty
                            <li class="list-group-item ps-0">
                                <h6 class="text-center">No Record Found</h6>
                                <!-- end row -->
                            </li><!-- end -->
                        @endforelse
                    </ul><!-- end -->
                </div><!-- end card body -->
            </div><!-- end card -->
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Upcoming Events</h4>
                    <div class="flex-shrink-0">
                        <div class="dropdown card-header-dropdown">
                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <span class="text-muted fs-18"><i class="mdi mdi-dots-vertical"></i></span>
                            </a>
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body pt-0">
                    <ul class="list-group list-group-flush border-dashed">
                        @forelse ($upcoming_events as $event)
                            <li class="list-group-item ps-0">
                                <div class="row align-items-center g-3">
                                    <div class="col-auto">
                                        <div class="avatar-sm p-1 py-2 h-auto bg-light rounded-3 material-shadow">
                                            <div class="text-center">
                                                <h5 class="mb-0">{{ Carbon::parse($event->start_date)->format('d') }}</h5>
                                                <div class="text-muted">{{ Carbon::parse($event->start_date)->format('M') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="text-muted mt-0 mb-1 fs-13">{{ Carbon::parse($event->time)->format('H:i A') }}</h5>
                                        <a href="#" class="text-reset fs-14 mb-0">{{ $event->title }}</a>
                                    </div>
                                    <div class="col-sm-auto">
                                        @if($event->is_enable_event_registration)
                                            <a href="{{ route('events.register', $event->id) }}" class="btn btn-sm btn-outline-success">
                                                <i class="mdi mdi-note-edit-outline"></i> Register
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <!-- end row -->
                            </li><!-- end -->
                        @empty
                            <li class="list-group-item ps-0">
                                <h6 class="text-center">No Upcoming Events</h6>                                
                            </li><!-- end -->
                        @endforelse
                    </ul><!-- end -->
                </div><!-- end card body -->
            </div><!-- end card -->
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Recent Announcements</h4>
                </div><!-- end card header -->

                <div class="card-body p-0">
                    <div data-simplebar>
                        <ul class="list-group list-group-flush border-dashed px-3">
                            <li class="list-group-item ps-0">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <label class="form-check-label mb-0 ps-2" for="task_one">Due to inclement weather condition, we are going to work from home</label>
                                    </div>
                                    <div class="flex-shrink-0 ms-2">
                                        <p class="text-muted fs-12 mb-0">15 Sep, 2021</p>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item ps-0">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <label class="form-check-label mb-0 ps-2" for="task_two">Send meeting invites for sales upcampaign</label>
                                    </div>
                                    <div class="flex-shrink-0 ms-2">
                                        <p class="text-muted fs-12 mb-0">20 Sep, 2021</p>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item ps-0">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <label class="form-check-label mb-0 ps-2" for="task_three">Weekly closed sales won checking with sales team</label>
                                    </div>
                                    <div class="flex-shrink-0 ms-2">
                                        <p class="text-muted fs-12 mb-0">24 Sep, 2021</p>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item ps-0">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <label class="form-check-label mb-0 ps-2" for="task_four">Add notes that can be viewed from the individual view</label>
                                    </div>
                                    <div class="flex-shrink-0 ms-2">
                                        <p class="text-muted fs-12 mb-0">27 Sep, 2021</p>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item ps-0">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <label class="form-check-label mb-0 ps-2" for="task_five">Move stuff to another page</label>
                                    </div>
                                    <div class="flex-shrink-0 ms-2">
                                        <p class="text-muted fs-12 mb-0">27 Sep, 2021</p>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item ps-0">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <label class="form-check-label mb-0 ps-2" for="task_six">Styling wireframe design and documentation for velzon admin</label>
                                    </div>
                                    <div class="flex-shrink-0 ms-2">
                                        <p class="text-muted fs-12 mb-0">27 Sep, 2021</p>
                                    </div>
                                </div>
                            </li>
                        </ul><!-- end ul -->
                    </div>
                    <div class="p-3 pt-2">
                        <a href="javascript:void(0);" class="text-muted text-decoration-underline">Show more...</a>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
@endsection
@section('script')
    <!-- apexcharts -->
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/dashboard-crm.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
