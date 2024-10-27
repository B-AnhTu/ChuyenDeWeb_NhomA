@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2 class="text-center">Product</h2>
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
            <div class="col-md-12">
                <div class="row mb-3 d-flex align-items-center">
                    <div class="col-md-3">
                        <select class="form-control" name="filter" id="filter">
                            <option value="" disabled selected>Sắp xếp</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                    <div class="col-md-6 mx-auto">
                        <form class="d-flex">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row mb-3 d-flex align-items-center">
                    <div class="col-md-2">
                        <div class="head-content text-center bg-white p-2">
                            <p>Total Users: <span class="badge bg-primary">{{ $totalUsers }}</span></p>
                        </div>    
                    </div>
                    <div class="col-md-2">
                        <div class="head-content text-center bg-white p-2">
                            <p>Online Users: <span class="badge bg-primary">{{ $onlineUsers }}</span></p>
                        </div>    
                    </div>
                    <div class="col-md-8 d-flex justify-content-end">
                        <a href="{{ route('userAdmin.create') }}" class="btn btn-primary">Add User</a>
                    </div>
                </div>
            </div>
            <div class="col-md-12 justify-content-center mx-auto">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fullname</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->user_id }}</td>
                                <td>{{ $user->fullname }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td><img src="{{ asset('img/profile-picture/' . $user->image) }}"
                                        alt="{{ $user->fullname }}" width="50"></td>
                                <td>
                                    <!-- Add action buttons here -->
                                    <a href="{{ route('userAdmin.show', $user->user_id) }}"
                                        class="btn btn-primary"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('userAdmin.edit', $user->user_id) }}"
                                        class="btn btn-primary"><i class="fas fa-pencil-alt"></i></a>
                                    <form action="{{ route('userAdmin.delete', $user->user_id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Are you sure you want to delete this user?')" type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="col-md-12 d-flex justify-content-center">
                    {{ $users->links('pagination::bootstrap-4') }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection