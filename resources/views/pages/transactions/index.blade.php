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
            Transactions
        @endslot
    @endcomponent

    <div class="row my-3">
        <div class="col-lg-12">
            <div class="card" id="ticketsList">
                <div class="card-body">
                    <table id="transaction_datatables" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th class="sort" data-sort="id">ID</th>
                                <th class="sort" data-sort="transaction_code">Transaction Code</th>
                                <th class="sort" data-sort="reference_code">Reference Code</th>
                                <th class="sort" data-sort="payment_type">Payment Type</th>
                                <th class="sort" data-sort="total_amount">Total Amount</th>
                                <th class="sort" data-sort="status">Status</th>
                                <th class="sort" data-sort="actions">Actions</th>
                            </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // make sure that the table is loaded correctly
            $('#transaction_datatables').on('draw.dt', function() {
                $('[data-bs-toggle="tooltip"]').tooltip();

                $('.remove-btn').click(function() {
                    var id = $(this).attr('id');

                    showDeleteMessage({
                        message: 'Deleting this announcement will remove all of the information from our database.',
                        deleteFunction: function() {
                            $.ajax({
                                url: '/dashboard/announcements/' + id,
                                type: 'DELETE',
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                success: function(response) {
                                    showSuccessMessage(response.message);
                                    $('#announcement_datatables').DataTable().draw(false);
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
                        data: "transaction_code",
                        name: "transaction_code",
                    },
                    {
                        data: "reference_code",
                        name: "reference_code",
                    },
                    {
                        data: "payment_type",
                        name: "payment_type",
                        orderable: false,
                    },
                    {
                        data: "total_amount",
                        name: "total_amount",
                    },
                    {
                        data: "status",
                        name: "status",
                        orderable: false,
                    },
                    {
                        data: "actions",
                        name: "actions",
                        orderable: false,
                        searchable: false,
                    },
                ];

                $("#transaction_datatables").DataTable({
                    processing: true,
                    pageLength: 10,
                    responsive: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('transactions.index') }}",
                    },
                    columns: columns,
                    language: {
                        emptyTable: `<div class="noresult">
                        <div class="text-center">
                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                            </lord-icon>
                            <h5 class="mt-2">Sorry! This table is empty</h5>
                            <p class="text-muted mb-0">No data available in table. Please add some records.</p>
                        </div>
                    </div>`
                    },
                    order: [
                        [0, "desc"], // Sort by the first column (index 0) in descending order
                    ],
                });
            }

            initializeTables();
        })
    </script>
@endsection