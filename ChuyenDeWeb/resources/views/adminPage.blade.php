@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h2 class="text-center">Welcome to the Admin Dashboard</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-10 justify-content-center mx-auto">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>ID</th>
                                <th>Fullname</th>
                                <th>Image</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>John Doe</td>
                                <td><img src="{{ asset('img/profile-picture/user-default.jpg') }}" alt="Image" width="50"></td>
                                <td>
                                    <select class="form-control">
                                        <option>Admin</option>
                                        <option>User</option>
                                        <option>Editor</option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <a href=""><i class="fas fa-2x fa-eye"></i></a>
                                    <a href=""><i class="fas fa-2x fa-pencil-alt"></i></a>
                                    <a href=""><i class="fas fa-2x fa-trash"></i></a>
                                </td>
                            </tr>
                            <!-- More rows as needed -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
