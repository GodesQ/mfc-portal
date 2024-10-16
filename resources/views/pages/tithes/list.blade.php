@extends('layouts.master')
@section('title')
    @lang('translation.tithes')
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Tithes
        @endslot
        @slot('title')
            {{ $endPoint }}
        @endslot
    @endcomponent

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card-header border-0 mb-3">
                <div class="d-flex align-items-center justify-content-end">
                    <div class="flex-shrink-0">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="#" class="btn btn-primary add-btn text-capitalize" data-bs-toggle="modal"
                                data-bs-target="#add-tithe-form" onclick="handleOpenTitheForm('0')">
                                <i class="mdi mdi-plus fs-15"></i> Add Tithe</a>
                            <a href="#" class="btn btn-primary add-btn text-capitalize" data-bs-toggle="modal"
                                data-bs-target="#add-tithe-form" onclick="handleOpenTitheForm('1')">
                                <i class="mdi mdi-hand-coin fs-15"></i> Give Tithe</a>


                            <button class="btn btn-soft-danger" id="remove-actions"><i
                                    class="ri-delete-bin-2-line"></i></button>
                            {{-- <div id="tithe-form" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel"
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
                                                            <label for="amount" class="form-label">Tithe Amount</label>
                                                            <div class="form-icon">
                                                                <input type="text" oninput="validateDigit(this)"
                                                                    id="amount" class="form-control form-control-icon"
                                                                    name="amount" placeholder="" value="50"
                                                                    min="50">
                                                                <i class="fst-normal">₱</i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label for="amount" class="form-label">For the Month
                                                                Of</label>
                                                            <select name="for_the_month_of" id="month-of-field"
                                                                class="form-select">
                                                                <option {{ date('F') == 'January' ? 'selected' : null }}
                                                                    value="January">January</option>
                                                                <option {{ date('F') == 'February' ? 'selected' : null }}
                                                                    value="February">February</option>
                                                                <option {{ date('F') == 'March' ? 'selected' : null }}
                                                                    value="March">March</option>
                                                                <option {{ date('F') == 'April' ? 'selected' : null }}
                                                                    value="April">April</option>
                                                                <option {{ date('F') == 'May' ? 'selected' : null }}
                                                                    value="May">May</option>
                                                                <option {{ date('F') == 'June' ? 'selected' : null }}
                                                                    value="June">June</option>
                                                                <option {{ date('F') == 'July' ? 'selected' : null }}
                                                                    value="July">July</option>
                                                                <option {{ date('F') == 'August' ? 'selected' : null }}
                                                                    value="August">August</option>
                                                                <option {{ date('F') == 'September' ? 'selected' : null }}
                                                                    value="September">September</option>
                                                                <option {{ date('F') == 'October' ? 'selected' : null }}
                                                                    value="October">October</option>
                                                                <option {{ date('F') == 'November' ? 'selected' : null }}
                                                                    value="November">November</option>
                                                                <option {{ date('F') == 'December' ? 'selected' : null }}
                                                                    value="December">December</option>
                                                            </select>
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
                            </div><!-- /.modal --> --}}
                            <div id="add-tithe-form" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel"
                                aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="myModalLabel">Add Tithe</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('tithes.store') }}" id="tithe-form" method="post">
                                                @csrf
                                                <input type="hidden" id="is-payment-required" name="is_payment_required"
                                                    value="1">
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
                                                            <label for="amount" class="form-label">Tithe Amount</label>
                                                            <div class="form-icon">
                                                                <input type="text" oninput="validateDigit(this)"
                                                                    id="amount" class="form-control form-control-icon"
                                                                    name="amount" placeholder="" value="50"
                                                                    min="50">
                                                                <i class="fst-normal">₱</i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label for="amount" class="form-label">For the Month
                                                                Of</label>
                                                            <select name="for_the_month_of" id="month-of-field"
                                                                class="form-select">
                                                                <option {{ date('F') == 'January' ? 'selected' : null }}
                                                                    value="January">January</option>
                                                                <option {{ date('F') == 'February' ? 'selected' : null }}
                                                                    value="February">February</option>
                                                                <option {{ date('F') == 'March' ? 'selected' : null }}
                                                                    value="March">March</option>
                                                                <option {{ date('F') == 'April' ? 'selected' : null }}
                                                                    value="April">April</option>
                                                                <option {{ date('F') == 'May' ? 'selected' : null }}
                                                                    value="May">May</option>
                                                                <option {{ date('F') == 'June' ? 'selected' : null }}
                                                                    value="June">June</option>
                                                                <option {{ date('F') == 'July' ? 'selected' : null }}
                                                                    value="July">July</option>
                                                                <option {{ date('F') == 'August' ? 'selected' : null }}
                                                                    value="August">August</option>
                                                                <option {{ date('F') == 'September' ? 'selected' : null }}
                                                                    value="September">September</option>
                                                                <option {{ date('F') == 'October' ? 'selected' : null }}
                                                                    value="October">October</option>
                                                                <option {{ date('F') == 'November' ? 'selected' : null }}
                                                                    value="November">November</option>
                                                                <option {{ date('F') == 'December' ? 'selected' : null }}
                                                                    value="December">December</option>
                                                            </select>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                My Monthly Tithe Record
                            </h3>
                        </div>
                        <div class="card-body">
                            <div id="column_chart" class="apex-charts" dir="ltr"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card" id="ticketsList">
                        <div class="card-body">
                            <table id="tithes_datatable" class="table nowrap align-middle table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="" data-sort="id">ID</th>
                                        <th class="" data-sort="name">Name</th>
                                        <th class="" data-sort="amount">Amount</th>
                                        <th class="" data-sort="section">Section</th>
                                        <th class="" data-sort="month">Month</th>
                                        <th class="" data-sort="date_tithe">Date Tithe</th>
                                        <th class="" data-sort="status">Status</th>
                                        <th class="" data-sort="action">Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <!--end card-body-->
                    </div>
                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>

@section('script')
    <script src="{{ asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('#tithes_datatable').on('draw.dt', function() {

                $('[data-bs-toggle="tooltip"]').tooltip();

                $('.remove-btn').click(function() {
                    var id = $(this).attr('id');

                    showDeleteMessage({
                        message: '<strong class="text-danger">Removing this tithe</strong> will remove all of the information from our database.',
                        deleteFunction: function() {
                            $.ajax({
                                url: '/dashboard/tithes/' + id,
                                type: 'DELETE',
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                success: function(response) {
                                    showSuccessMessage(response.message);
                                    $('#tithes_datatable').DataTable().ajax
                                        .reload(null,
                                            false
                                        ); // false to keep the current page
                                },
                                error: function(xhr, response, error) {
                                    showErrorMessage(xhr.statusText);
                                }
                            });
                        }
                    });
                });
            });

            function initializeTables() {
                let columns = [{
                        data: "id",
                        name: "id",
                    },
                    {
                        data: 'user',
                        name: 'user',
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                    },
                    {
                        data: 'section',
                        name: 'section',
                    },
                    {
                        data: 'for_the_month_of',
                        name: 'for_the_month_of',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "actions",
                        name: "actions",
                        orderable: false,
                        searchable: false,
                    },
                ];

                let table = $("#tithes_datatable").DataTable({
                    processing: true,
                    pageLength: 10,
                    responsive: true,
                    serverSide: true,
                    ajax: {
                        url: "/dashboard/tithes/",
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    },
                    columns: columns,
                    language: {
                        emptyTable: `<div class="noresult">
                                        <div class="text-center">
                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" id="search-icon"
                                                colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                                            </lord-icon>
                                            <h5 class="mt-2">Sorry! This table is empty</h5>
                                            <p class="text-muted mb-0">No data available in table. Please add some records.</p>
                                        </div>
                                    </div>`,
                        zeroRecords: `<div class="noresult">
                                        <div class="text-center">
                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" id="search-icon"
                                                colors="primary:#b4b4b4,secondary:#08a88a" style="width:75px;height:75px">
                                            </lord-icon>
                                            <h5 class="mt-2">Sorry! No Result Found</h5>
                                            <p class="text-muted mb-0">We've searched all of our records, We did not find any data for you search.</p>
                                        </div>
                                    </div>`,
                    },
                    order: [
                        [0, "desc"],
                    ],
                });
            }

            const fetchTithesData = async () => {
                const response = await fetch('/dashboard/tithes/chart/user-monthly');
                const data = await response.json();
                let total_per_month = [];
                let months = [];
                if (data.tithes.length > 0) {
                    total_per_month = data.tithes.map(tithe => {
                        return tithe.total;
                    });
                    months = data.tithes.map(tithe => {
                        return tithe.for_the_month_of;
                    })
                }

                var options = {
                    series: [{
                        name: 'Total Tithe',
                        data: total_per_month
                    }],
                    chart: {
                        type: 'bar',
                        height: 350
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    xaxis: {
                        categories: months,
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return "₱ " + val
                            }
                        }
                    }
                };

                var chart = new ApexCharts(document.querySelector("#column_chart"), options);
                chart.render();
            }

            initializeTables();
            fetchTithesData();
        })
    </script>

    <script>
        function handleOpenTitheForm(value) {
            $('#is-payment-required').val(value);
        }
    </script>
@endsection
@endsection
