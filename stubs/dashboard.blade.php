@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<div class="container-fluid">

    <h6 class="mb-3 fw-semibold">Dashboard</h6>

    {{-- Each section loads independently with its own loader --}}
    <div class="row g-3">

        <div class="col-md-4">
            <x-spa-target
                id="widget-revenue"
                url="{{ route('dashboard.revenue') }}"
                auto-load="true"
                loader-type="card"
                loader-rows="3"
            />
        </div>

        <div class="col-md-4">
            <x-spa-target
                id="widget-attendance"
                url="{{ route('dashboard.attendance') }}"
                auto-load="true"
                loader-type="skeleton"
                loader-rows="4"
            />
        </div>

        <div class="col-md-4">
            <x-spa-target
                id="widget-notices"
                url="{{ route('dashboard.notices') }}"
                auto-load="true"
                loader-type="spinner"
            />
        </div>

    </div>

    {{-- Large data table section --}}
    <div class="row mt-3">
        <div class="col-12">
            <x-spa-target
                id="recent-activity"
                url="{{ route('dashboard.activity') }}"
                auto-load="true"
                loader-type="table"
                loader-rows="8"
                loader-cols="5"
            />
        </div>
    </div>

</div>

@endsection
