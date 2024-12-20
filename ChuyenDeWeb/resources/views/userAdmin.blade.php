@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2 class="text-center">User Management</h2>
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
                        <form action="{{ route('userAdmin.index') }}" method="get">
                            <select class="form-control me-2" name="sort_by" onchange="this.form.submit()">
                                <option value="" disabled selected>Sắp xếp theo</option>
                                <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Tên (Từ
                                    A - Z)</option>
                                <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Tên
                                    (Từ Z - A)</option>
                                <option value="role_asc" {{ request('sort_by') == 'role_asc' ? 'selected' : '' }}>Quyền
                                    (Tăng dần)
                                </option>
                                <option value="role_desc" {{ request('sort_by') == 'role_desc' ? 'selected' : '' }}>Quyền
                                    (Giảm
                                    dần)</option>
                                <option value="created_at_asc" {{ request('sort_by') == 'created_at_asc' ? 'selected' : '' }}>Ngày tạo (Tăng dần)</option>
                                <option value="created_at_desc" {{ request('sort_by') == 'created_at_desc' ? 'selected' : '' }}>Ngày tạo (Giảm dần)</option>
                            </select>
                        </form>
                    </div>
                    <div class="col-md-6 mx-auto">
                        <form class="d-flex" action="{{ route('userAdmin.index') }}" method="GET">
                            @csrf
                            <input name="query" class="form-control me-2" type="text" placeholder="Search"
                                aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row my-3 d-flex align-items-center">
                    <div class="col-sm-3 col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Total Users</p>
                                            <h4 class="card-title">{{ $totalUsers }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-info bubble-shadow-small">
                                            <i class="fas fa-user-check"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Online Users</p>
                                            <h4 class="card-title">{{ $onlineUsers }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-2">
                        <div class="head-content text-center bg-white p-2">
                            <p>Online Users: <span class="badge bg-primary">{{ $onlineUsers }}</span></p>
                        </div>
                    </div> -->
                    <div class="col-md-6 d-flex justify-content-end">
                        <a href="{{ route('userAdmin.create') }}" class="btn btn-primary">Add User</a>
                    </div>
                </div>
            </div>
            <div class="col-md-12 justify-content-center mx-auto">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
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
                                <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                <!-- Sequential number -->
                                <td>{{ $user->fullname }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>
                                @if (!empty($user->image))
                                    <img src="{{ asset('img/profile-picture/' . $user->image) }}"
                                        alt="{{ $user->fullname }}" width="100">
                                @else
                                    <img src="{{ asset('img/profile-picture/user-default.jpg') }}" alt="Default Image"
                                        width="100">
                                @endif
                                </td>
                                <td>
                                    <!-- Add action buttons here -->
                                    <a href="{{ route('userAdmin.show', $user->slug) }}" class="btn btn-primary"><i
                                            class="fas fa-eye"></i></a>
                                    <a href="{{ route('userAdmin.edit', $user->slug) }}" class="btn btn-primary"><i
                                            class="fas fa-pencil-alt"></i></a>
                                    <form action="{{ route('userAdmin.delete', $user->slug) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Are you sure you want to delete this user?')"
                                            type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
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