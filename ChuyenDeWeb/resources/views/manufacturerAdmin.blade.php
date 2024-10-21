@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2 class="text-center">Manufacturer</h2>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <a href="{{ route('manufacturer.create') }}" class="btn btn-primary my-3 mx-auto">Add Manufacturer</a>
            <div class="col-md-10 justify-content-center mx-auto">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Manufacturer name</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>aaa</td>
                            <td><img src="{{ asset('img/manufacturer/logoapples.png') }}" alt="default" width="50"></td>
                            <td>
                            <a href="#" class="btn btn-primary">Edit</a>
                            <a href="#" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        @if(isset($manufacturers) && count($manufacturers) > 0)
                            @foreach($manufacturers as $manufacturer)
                                <tr>
                                    <td>{{ $manufacturer->manufacturer_id }}</td>
                                    <td>{{ $manufacturer->manufacturer_name }}</td>
                                    <td><img src="{{ asset('img/manufacturer/' . $manufacturer->image) }}"
                                            alt="{{ $manufacturer->manufacturer_name }}" width="50"></td>
                                    <td>
                                        <!-- Add action buttons here -->
                                        <a href="{{ route('manufacturer.edit', $manufacturer->manufacturer_id) }}" class="btn btn-primary">Edit</a>
                                        <a href="{{ route('manufacturer.delete', $manufacturer->manufacturer_id) }}" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">No manufacturers found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection