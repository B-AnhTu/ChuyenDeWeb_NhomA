@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h2 class="text-center mb-5">Admin Page - Manage User Permissions</h2>
    <div id="alert-container" class="my-3"></div> <!-- Container for alerts -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Image</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td> <!-- Sequential number -->
                <td>{{ $user->fullname }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <img src="{{ asset('img/profile-picture/' . $user->image) }}" alt="User Image" class="img-fluid" style="width: 100px; height: 100px;">
                </td>
                <td>
                    <form action="{{ route('userAdmin.updatePermissions', $user->user_id) }}" method="POST" class="d-flex align-items-center">
                        @csrf
                        @method('PUT')
                        <div class="input-group">
                            <select class="form-control role-select mr-2" name="role" data-user-id="{{ $user->user_id }}">
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="editor" {{ $user->role == 'editor' ? 'selected' : '' }}>Editor</option>
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>
                        <button onclick="return confirm('Bạn có chắc chắn muốn cập nhật quyền hạn cho người dùng này?')" type="submit" class="btn btn-primary update-permissions" data-user-id="{{ $user->user_id }}">Update</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="col-md-12 text-center">
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