@extends('app')
@section('content')

    @if ($errors->has('error'))
        <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
    @endif


    <div class="container mt-5 py-5">
        <div class="row">
            <div class="col-md-3 text-center">
                <!-- Ảnh đại diện -->
                <a href="#" data-bs-toggle="modal" data-bs-target="#changeProfilePictureModal">
                    @if (auth()->user()->image)
                        <img id="previewImage" src="{{ asset('img/profile-picture/' . Auth::user()->image) }}"
                            alt="{{Auth::user()->fullname}}" class="img-thumbnail rounded-circle">
                    @else
                        <img id="previewImage" src="{{ asset('img/profile-picture/user-default.jpg') }}" alt="user-default"
                            class="img-thumbnail rounded-circle">
                    @endif
                </a>
                <!-- Modal -->
                <div class="modal fade" id="changeProfilePictureModal" tabindex="-1"
                    aria-labelledby="changeProfilePictureModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="changeProfilePictureModalLabel">Thay đổi ảnh đại diện</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <!-- Input file bên trái -->
                                    <div class="col-md-6">
                                        <form id="uploadForm" enctype="multipart/form-data">
                                            @csrf
                                            <label for="profileImage">Chọn ảnh đại diện:</label>
                                            <input type="file" id="profileImage" name="profileImage" accept="image/*"
                                                class="form-control">
                                            <button type="submit" class="btn btn-primary mt-3">Lưu</button>
                                        </form>
                                    </div>
                                    <!-- Hiển thị ảnh bên phải -->
                                    <div class="col-md-6">
                                        <img id="modalPreviewImage"
                                            src="{{ auth()->user()->image ? asset('img/profile-picture/' . auth()->user()->image) : asset('img/profile-picture/user-default.jpg') }}"
                                            alt="Current Profile Picture" class="img-thumbnail rounded-circle"
                                            style="width: 150px; height: 150px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h5 class="mt-2">{{ auth()->user()->fullname }}</h5>
                <p>{{ auth()->user()->email }}</p>
            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h4>Thông tin cá nhân</h4>
                    </div>
                    <div class="card-body">
                        <!-- Hiển thị thông báo lỗi hoặc thành công -->
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

                        <!-- Form cập nhật thông tin cá nhân -->
                        <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Họ và tên:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="fullname"
                                        value="{{ old('fullname', auth()->user()->fullname) }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Email:</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" name="email"
                                        value="{{ old('email', auth()->user()->email) }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Địa chỉ:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="address"
                                        value="{{ old('address', auth()->user()->address) }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Số điện thoại:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="phone"
                                        value="{{ old('phone', auth()->user()->phone) }}">
                                </div>
                            </div>

                            <!-- Nút cập nhật -->
                            <div class="row mb-3">
                                <div class="col-sm-12 text-end">
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('profileImage').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                // Cập nhật ảnh bên phải modal với ảnh đã chọn
                document.getElementById('modalPreviewImage').src = e.target.result;
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        });

        // Xử lý form upload ảnh
        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch('/Profile-user/upload-profile-image', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Đóng modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById(
                            'changeProfilePictureModal'));
                        modal.hide();

                        // Cập nhật ảnh đại diện trên trang
                        document.getElementById('previewImage').src = data.newImageUrl;
                        document.querySelector('.col-md-3 img').src = data.newImageUrl;

                        // Hiển thị thông báo thành công bằng SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: 'Cập nhật ảnh đại diện thành công',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {

                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: data.message, 
                            confirmButtonText: 'Đóng'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Đã xảy ra lỗi không xác định',
                        confirmButtonText: 'Đóng'
                    });
                });
        });
    </script>
@endsection
