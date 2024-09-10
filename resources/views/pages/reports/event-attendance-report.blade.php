@extends('layouts.master')

@section('title')
    @lang('translation.attendance_report')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Attendance
        @endslot
        @slot('title')
            {{ $endPoint }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Event Details</h3>
                </div>
                <div class="card-body">
                    <img src="{{ URL::asset('uploads/' . $event->poster) }}" alt="" class="rounded mb-2"
                        style="width: 100%; max-height: 200px; object-fit: cover;">
                    <h3>{{ $event->title }}</h3>
                    <div class="flex gap-2">
                        <span class="bg-primary badge text-uppercase">Worldwide</span>
                        <span class="badge bg-primary text-uppercase"></span>
                    </div>
                    <div class="my-2">
                        {!! $event->description !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-xxl-6 col-md-6">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Attendance Report</h4>
                                    <div class="flex-shrink-0">
                                        
                                    </div>
                                </div><!-- end card header -->
                                <div class="card-body pb-0">
                                    <div id="sales-forecast-chart"
                                        data-colors='["--vz-primary", "--vz-success", "--vz-warning"]'
                                        data-colors-minimal='["--vz-primary-rgb, 0.75", "--vz-primary", "--vz-primary-rgb, 0.55"]'
                                        data-colors-creative='["--vz-primary", "--vz-secondary", "--vz-info"]'
                                        data-colors-corporate='["--vz-primary", "--vz-success", "--vz-secondary"]'
                                        data-colors-galaxy='["--vz-primary", "--vz-secondary", "--vz-info"]'
                                        data-colors-classic='["--vz-primary", "--vz-warning", "--vz-secondary"]'
                                        class="apex-charts" dir="ltr"></div>
                                </div>
                            </div><!-- end card -->
                        </div><!-- end col -->
                        <div class="col-xxl-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4 class="card-title">Total Users</h4>
                                        <button class="btn btn-primary btn-sm">Print List of Users <i class="ri-printer-line"></i></button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-responsive table-bordered">
                                        <thead>
                                            <tr>
                                                @foreach ($event_attendance as $attendance)
                                                    <th class="text-center">{{ Carbon::parse($attendance->attendance_date)->format('F d, Y') }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @foreach ($event_attendance as $attendance)
                                                    <td class="text-center">{{ $attendance->user_count }}</td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- apexcharts -->
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/dashboard-crm.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
