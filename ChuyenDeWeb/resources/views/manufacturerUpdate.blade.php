@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h2 class="text-center">Update Manufacturer</h2>
                <form action="{{ route('manufacturer.update', $manufacturer->manufacturer_id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="manufacturer_id" value="{{ $manufacturer->manufacturer_id }}">
                    <div class="form-group">
                        <label for="manufacturer_name">Manufacturer Name</label>
                        <input type="text" name="manufacturer_name" id="manufacturer_name" class="form-control" value="{{ $manufacturer->manufacturer_name }}">
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" name="image" id="image" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary mx-auto">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
