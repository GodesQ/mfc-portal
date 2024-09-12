@extends('layouts.master')
@section('title')
    Dashboard
@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">
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

    <style>
        .timeline-2 .timeline-year p {
            border: 1px solid #405189;
        }

        .timeline-2:after {
            background: #405189;
        }

        #col-left-dashboard {
            border-radius: 5px;
            max-height: 650px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        #col-left-dashboard::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(78, 78, 78, 0.3);
            background-color: #f4f3f9;
            border-radius: 10px;
        }

        #col-left-dashboard::-webkit-scrollbar {
            width: 5px;
            background-color: #f4f3f9;
            border-radius: 10px;
        }

        #col-left-dashboard::-webkit-scrollbar-thumb {
            background-color: #405189;
            border-radius: 10px;
            border: none;
        }

        .gprev.gbtn {
            display: none !important;
        }

        .gnext.gbtn {
            display: none !important;
        }
    </style>

    <div class="row mb-3 pb-1">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-lg-row flex-column" style="justify-content: space-between;">
                <div class="col-lg-6">
                    <h4 class="fs-16 mb-1">Good Morning, {{ auth()->user()->first_name }}!</h4>
                    <p class="text-muted mb-0 fs-11">"I pray also that...you may know the hope to which he has called you,
                        the riches of his glorious inheritance in the saints, and his incomparably great power for us who
                        believe." - Ephesians 1:18-19</p>
                </div>
                <div class="mt-3 mt-lg-0">
                    <div class="row g-3 mb-0 align-items-center">
                        <div class="col-auto">
                            <button type="button" class="btn btn-disabled border material-shadow-none" disabled><i
                                    class="mdi mdi-hands-pray fs-15"></i> Send Prayer Intention</button>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('events.calendar') }}" class="btn btn-info material-shadow-none"><i
                                    class="mdi mdi-calendar-multiselect fs-15"></i> View Events</a>
                        </div>
                        <!--end col-->
                        <div class="col-auto">
                            <button type="button" class="btn btn-success material-shadow-none" data-bs-toggle="modal"
                                data-bs-target="#tithe-form">
                                <i class="mdi mdi-hand-coin fs-15"></i> Give Tithes
                            </button>
                            <div id="tithe-form" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel"
                                aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="myModalLabel">Give Tithe</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('tithes.store') }}" id="tithe-form" method="post">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        @component('components.input_fields.basic')
                                                            @slot('id')
                                                                mfc_user_id
                                                            @endslot
                                                            @slot('name')
                                                                mfc_user_id
                                                            @endslot
                                                            @slot('label')
                                                                MFC User ID
                                                            @endslot
                                                            @slot('placeholder')
                                                                MFC User ID
                                                            @endslot
                                                            @slot('feedback')
                                                                Invalid input. Minimum of 3 characters!
                                                            @endslot
                                                            @slot('value')
                                                                {{ auth()->user()->mfc_id_number }}
                                                            @endslot
                                                        @endcomponent
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label for="amount" class="form-label">Registration
                                                                Fee</label>
                                                            <div class="form-icon">
                                                                <input type="text" oninput="validateDigit(this)"
                                                                    id="amount" class="form-control form-control-icon"
                                                                    name="amount" placeholder="" value="50"
                                                                    min="50">
                                                                <i class="fst-normal">₱</i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary" id="tithe-form-btn"
                                                    style="width: 100%">
                                                    Submit
                                                </button>
                                            </form>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
            </div><!-- end card header -->
        </div>
        <!--end col-->
    </div>
    <div class="row">
        <div class="col-xl-7" id="col-left-dashboard">
            @role('super_admin')
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
                                            <td><img src="{{ URL::asset('images/' . $registration->user->avatar) }}"
                                                    alt="" class="avatar-xs rounded-circle me-2 material-shadow">
                                                <a href="#javascript: void(0);"
                                                    class="text-body fw-medium">{{ $registration->user->first_name . ' ' . $registration->user->last_name }}</a>
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
            @else
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <h2 class="card-title">Recent Announcements</h2>
                        </div>
                        <div class="card-body">
                            <div class="timeline-2">
                                @foreach ($recent_announcements as $month => $announcements)
                                    <div class="timeline-year">
                                        <p>{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('M Y') }}</p>
                                    </div>
                            
                                    @foreach ($announcements as $announcement)
                                        <div class="timeline-continue">
                                            <div class="row timeline-right">
                                                <div class="col-12">
                                                    <p class="timeline-date">
                                                        {{ \Carbon\Carbon::parse($announcement->created_at)->format('M d, Y') }}
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <div class="timeline-box">
                                                        <div class="timeline-text">
                                                            <div class="d-flex">
                                                                <div class="flex-grow-1 ms-3">
                                                                    <h5 class="mb-1">{{ $announcement->title }}</h5>
                                                                    <p class="text-muted mb-0">{!! $announcement->content !!}</p>
                                                                    <div class="row border border-dashed rounded gx-2 p-2">
                                                                        @forelse ($announcement->images as $image)
                                                                            <div class="col-3">
                                                                                <a href="{{ URL::asset('uploads/announcements/' . $announcement->id . '/' . $image->image_path) }}" 
                                                                                    data-glightbox="" data-gallery="lightbox">
                                                                                    <img src="{{ URL::asset('uploads/announcements/' . $announcement->id . '/' . $image->image_path) }}" 
                                                                                         alt="" class="img-fluid rounded">
                                                                                </a>
                                                                            </div>
                                                                        @empty
                                                                            <div class="col-12">No Images Found</div>
                                                                        @endforelse
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>                            
                        </div>
                    </div>
                </div>
            @endrole
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
                                        <a href="#"
                                            class="text-reset fs-14 mb-0">{{ Carbon::parse($tithe->created_at)->format('F d, Y') }}</a>
                                    </div>
                                    <div class="col-sm-auto">
                                        <button type="button" class="btn btn-sm btn-outline-primary" disabled>
                                            <i class="mdi mdi-note-edit-outline"></i> P
                                            {{ number_format($tithe->amount, 2) }}
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
                                                <h5 class="mb-0">{{ Carbon::parse($event->start_date)->format('d') }}
                                                </h5>
                                                <div class="text-muted">
                                                    {{ Carbon::parse($event->start_date)->format('M') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="text-muted mt-0 mb-1 fs-13">
                                            {{ Carbon::parse($event->time)->format('H:i A') }}</h5>
                                        <a href="#" class="text-reset fs-14 mb-0">{{ $event->title }}</a>
                                    </div>
                                    <div class="col-sm-auto">
                                        @if ($event->is_enable_event_registration)
                                            <a href="{{ route('events.register', $event->id) }}"
                                                class="btn btn-sm btn-outline-success">
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
        </div><!-- end col -->
    </div><!-- end row -->
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/dashboard-crm.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        const lightbox = GLightbox({
            selector: 'a[data-glightbox]',
            title: "",
            touchNavigation: false,
            loop: true,
            draggable: false,
        });
    </script>

@endsection
