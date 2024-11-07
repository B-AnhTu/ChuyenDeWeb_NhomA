@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h2 class="text-center mb-5">Admin Page - Manage User Permissions</h2>
    <div class="col-md-12 mt-3">
        <div class="row mb-3 d-flex align-items-center">
            <div class="col-md-3">
                <form action="{{ route('sortAdmin') }}" method="get">
                    <select class="form-control me-2" name="sort_by" onchange="this.form.submit()">
                        <option value="" disabled selected>Sắp xếp theo</option>
                        <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Tên (Từ A - Z)
                        </option>
                        <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Tên (Từ Z - A)
                        </option>
                        <option value="role_asc" {{ request('sort_by') == 'role_asc' ? 'selected' : '' }}>Quyền (Tăng dần)
                        </option>
                        <option value="role_desc" {{ request('sort_by') == 'role_desc' ? 'selected' : '' }}>Quyền (Giảm
                            dần)</option>
                        <option value="created_at_asc" {{ request('sort_by') == 'created_at_asc' ? 'selected' : '' }}>Ngày
                            tạo (Tăng dần)</option>
                        <option value="created_at_desc" {{ request('sort_by') == 'created_at_desc' ? 'selected' : '' }}>
                            Ngày tạo (Giảm dần)</option>
                    </select>
                </form>
            </div>
            <div class="col-md-6 mx-center">
                <form class="d-flex" action="{{ route('searchPage') }}" method="GET">
                    @csrf
                    <input name="query" class="form-control me-2" type="text" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
            <div class="col-md-3 d-flex justify-content-end">
                <a href="{{ route('userAdmin.create') }}" class="btn btn-primary">Add User</a>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col ">
            </div>

        </div>
    </div>
    <div id="alert-container" class="my-3"></div> <!-- Container for alerts -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Image</th>
                <th>Role</th>
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
                    <td>
                        <img src="{{ asset('img/profile-picture/' . $user->image) }}" alt="User Image" class="img-fluid"
                            style="width: 100px; height: 100px;">
                    </td>
                    <td>
                        <form action="{{ route('userAdmin.updatePermissions', $user->slug) }}" method="POST"
                            class="d-flex align-items-center">
                            @csrf
                            @method('PUT')
                            <div class="input-group">
                                <select class="form-control role-select mr-2" name="role"
                                    data-user-id="{{ $user->user_id }}">
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="editor" {{ $user->role == 'editor' ? 'selected' : '' }}>Editor</option>
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                </select>
                            </div>
                            <button onclick="return confirm('Bạn có chắc chắn muốn cập nhật quyền hạn cho người dùng này?')"
                                type="submit" class="btn btn-primary update-permissions"
                                data-user-id="{{ $user->user_id }}">Update</button>
                        </form>
                    </td>
                    <td>
                        <!-- Add action buttons here -->
                        <a href="{{ route('userAdmin.show', $user->slug) }}" class="btn btn-primary"><i
                                class="fas fa-eye"></i></a>
                        <a href="{{ route('userAdmin.edit', $user->slug) }}" class="btn btn-primary"><i
                                class="fas fa-pencil-alt"></i></a>
                        <form action="{{ route('userAdmin.delete', $user->slug) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Are you sure you want to delete this user?')" type="submit"
                                class="btn btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="col-md-12 d-flex justify-content-center mb-5">
        {{ $users->links('pagination::bootstrap-4') }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.update-permissions').forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent form submission

                const userId = this.getAttribute('data-user-id');
                const role = document.querySelector(`.role-select[data-user-id="${userId}"]`).value;

                fetch(`/userAdmin/${userId}/update-permissions`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ role })
                })
                    .then(response => response.json())
                    .then(data => {
                        const alertContainer = document.getElementById('alert-container');
                        alertContainer.innerHTML = ''; // Clear previous alerts

                        const alertBox = document.createElement('div');
                        alertBox.className = `alert ${data.success ? 'alert-success' : 'alert-danger'}`;
                        alertBox.textContent = data.message;
                        alertContainer.appendChild(alertBox);

                        // Remove the alert after a few seconds
                        setTimeout(() => alertBox.remove(), 5000);
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    });
</script>
@endsection