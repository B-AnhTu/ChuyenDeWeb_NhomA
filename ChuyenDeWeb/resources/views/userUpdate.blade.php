@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h2 class="text-center">Add new user</h2>
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
                <form action="{{ route('userAdmin.update', $user->slug) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" value="{{ $user->user_id }}">
                    <div class="form-group">
                        <label for="fullname">Fullname</label>
                        <input type="text" name="fullname" id="fullname" class="form-control" value="{{$user->fullname}}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" class="form-control" value="{{$user->email}}">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="text" name="password" id="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{$user->phone}}">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea rows="4" name="address" id="address" class="form-control" >{{$user->address}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <img src="{{ asset('img/profile-picture/' . $user->image) }}"
                            alt="{{ $user->fullname }}" width="50">
                        <input type="file" name="image" id="image" class="form-control">
                    </div>
                    <div class="col-md-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary my-3 mx-auto">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
