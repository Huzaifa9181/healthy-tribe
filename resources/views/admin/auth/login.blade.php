<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <!-- Google Font: Source Sans Pro -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{url('admin/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{url('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{url('admin/css/adminlte.min.css')}}">
    <style>
        .login-box, .register-box {
            margin: 7% auto;
            width: 360px;
        }
    </style>
</head>
<body class="hold-transition login-page" style="height: auto;">
<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            @if(session()->has('error'))
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h6><i class="icon fas fa-exclamation-triangle"></i> Alert!</h6>
                    {{ session('error') }}
                </div>
            @else
                <span> Enter your e-mail and password </span>
            @endif

            @if(session()->has('success'))

                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h6><i class="icon fas fa-check"></i> {{ trans('admin.success') }}</h6>
                    {{ session('success') }}
                </div>
            @endif
        </div>
        <div class="card-body">
            <p class="login-box-msg">Administrators login</p>
            <form action="{{ url('admin/login') }}" method="post">
                {!! csrf_field() !!}
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" name="password" placeholder="password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="rememberme" value="1" >
                            <label for="remember">Remember me</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-dark btn-block btn-flat">
                            <i class="fas fa-sign-in-alt"></i> login
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <hr>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->
<!-- jQuery -->
<script src="{{url('admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{url('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{url('admin/js/adminlte.min.js')}}"></script>
</body>
</html>
