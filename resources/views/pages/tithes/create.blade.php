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
            Give Tithe
        @endslot
    @endcomponent

    <div class="row mt-3">
        <div class="col-xl-4">

        </div>
        <div class="col-xl-4 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('tithes.store') }}" method="post">
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
                                    <label for="amount" class="form-label">Registration Fee</label>
                                    <div class="form-icon">
                                        <input type="text" oninput="validateDigit(this)" id="amount"
                                            class="form-control form-control-icon" name="amount"
                                            placeholder="" value="50" min="50">
                                        <i class="fst-normal">₱</i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">For the Month Of</label>
                                    <select name="for_the_month_of" id="month-of-field" class="form-select">
                                        <option {{ date('F') == "January" ? "selected" : null }} value="January">January</option>
                                        <option {{ date('F') == "February" ? "selected" : null }} value="February">February</option>
                                        <option {{ date('F') == "March" ? "selected" : null }} value="March">March</option>
                                        <option {{ date('F') == "April" ? "selected" : null }} value="April">April</option>
                                        <option {{ date('F') == "May" ? "selected" : null }} value="May">May</option>
                                        <option {{ date('F') == "June" ? "selected" : null }} value="June">June</option>
                                        <option {{ date('F') == "July" ? "selected" : null }} value="July">July</option>
                                        <option {{ date('F') == "August" ? "selected" : null }} value="August">August</option>
                                        <option {{ date('F') == "September" ? "selected" : null }} value="September">September</option>
                                        <option {{ date('F') == "October" ? "selected" : null }} value="October">October</option>
                                        <option {{ date('F') == "November" ? "selected" : null }} value="November">November</option>
                                        <option {{ date('F') == "December" ? "selected" : null }} value="December">December</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" style="width: 100%">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-4"></div>
    </div>
@endsection
