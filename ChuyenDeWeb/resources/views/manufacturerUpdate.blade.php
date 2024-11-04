@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h2 class="text-center">Update Manufacturer</h2>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <form action="{{ route('manufacturer.update', $manufacturer->slug) }}" method="post" enctype="multipart/form-data">
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
                    <div class="col-md-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary my-3 mx-auto">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
