@extends('layouts.master')

@section('title')
    User Registrations
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Registrations
        @endslot
        @slot('title')
            User Registrations
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            
        </div>
    </div>

@endsection