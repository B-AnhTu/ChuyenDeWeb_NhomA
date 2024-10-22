@extends('layouts.dashboard')
<style>
    .action-buttons {
        display: flex;
        gap: 5px;
        /* Adjust the gap between buttons as needed */
    }

    .action-buttons form {
        display: inline;
    }
</style>
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2 class="text-center">Manufacturer</h2>
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
                        @foreach ($manufacturers as $manufacturer)
                            <tr>
                                <td>{{ $manufacturer->manufacturer_id }}</td>
                                <td>{{ $manufacturer->manufacturer_name }}</td>
                                <td><img src="{{ asset('img/manufacturer/' . $manufacturer->image) }}"
                                        alt="{{ $manufacturer->manufacturer_name }}" width="50"></td>
                                <td>
                                    <!-- Add action buttons here -->
                                    <a href="{{ route('manufacturer.edit', $manufacturer->manufacturer_id) }}"
                                        class="btn btn-primary">Edit</a>
                                    <form action="{{ route('manufacturer.delete', $manufacturer->manufacturer_id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="col-md-12 d-flex justify-content-center">
                    {{ $manufacturers->links('pagination::bootstrap-4') }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection