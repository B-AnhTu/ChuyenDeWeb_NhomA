@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center py-3">
                <h2>Manufacturer Details</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <img src="{{ asset('img/manufacturer/' . $manufacturer->image) }}" alt="{{ $manufacturer->manufacturer_name }}" width="80%">
            </div>
            <div class="col-md-6">
                <h5>Tên nhà sản xuất: {{ $manufacturer->manufacturer_name }}</h5>
            </div>
        </div>
    </div>
@endsection
