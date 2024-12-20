<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">

    <title>Admin Login</title>
</head>

<body>
    <div class="container" id="container">
        <div class="form-container sign-in">
            <form action="{{ route('admin.login.submit') }}" method="POST" class="user">
                @csrf

                <h1>Đăng nhập</h1>

                <input type="text" class="form-control form-control-user @error('login') is-invalid @enderror"
                    id="login" placeholder="Email hoặc Tên người dùng..." name="login" value="{{ old('login') }}"
                    autofocus>

                @error('login')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

                <input type="password" class="form-control form-control-user @error('password') is-invalid @enderror"
                    id="password" placeholder="Mật khẩu" name="password" value="{{ old('password') }}" autofocus>

                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

                <div class="form-group">
                    <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck" name="remember">
                        <label class="custom-control-label mx-2" for="customCheck">Lưu tài khoản</label>
                    </div>
                </div>

                <button>Đăng nhập</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-right">
                    <h2>Chào mừng bạn đến với Nega LMS Admin</h2>
                    <p>Đăng nhập để quản lý và khai thác tối đa các tính năng học tập trên Nega LMS</p>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>
