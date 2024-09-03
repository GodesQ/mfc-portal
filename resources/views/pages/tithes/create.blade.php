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
