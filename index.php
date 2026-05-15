<?php include("./backend/config/condb.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ข้าวมันไก่น้องนัน</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="./plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="./dist/css/adminlte.min.css">
    
    <!-- jQuery -->
    <script src="./plugins/jquery/jquery.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<style>
    .login-page {
        background-image: url('rice-steamed-with-chicken-breast-2-1024x609.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 100vh;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .login-box {
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 15px;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.5);
        padding: 20px;
    }

    .login-logo p {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
    }

    .login-box-msg {
        font-size: 1.2rem;
        color: #666;
    }

    .form-control {
        border-radius: 30px;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        border-radius: 30px;
        padding: 10px 0;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .input-group-text {
        border-radius: 30px 0 0 30px;
    }

    a.text-center {
        font-size: 0.9rem;
        color: #007bff;
    }

    a.text-center:hover {
        text-decoration: underline;
    }
</style>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card align-content-center justify-content-center">
            <div class="card-body login-card-body">
                <div class="login-logo">
                    <p class="name">ข้าวมันไก่น้องนัน</p>
                </div>
                <p class="login-box-msg">ล็อกอินเข้าใช้งาน</p>

                <form action="./backend/auth/login.php" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="u_username" id="u_username" placeholder="ชื่อผู้ใช้งาน" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="u_password" id="u_password" placeholder="รหัสผ่าน" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">เข้าสู่ระบบ</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <p class="mb-0 text-center">
                    <a href="register.php" class="text-center">สมัครเข้าใช้งาน</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->
    <!-- Bootstrap 4 -->
    <script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="./dist/js/adminlte.min.js"></script>
</body>

</html>
