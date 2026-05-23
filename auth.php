<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="vendor/alertify/css/alertify.css">
    <link rel="stylesheet" href="vendor/alertify/css/themes/bootstrap.css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: "Noto Sans Thai", serif;
            font-style: normal;
            font-weight: 400;
        }
    </style>
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col col-lg-6">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">ล็อกอินเข้าสู่ระบบ</h1>
                                    </div>
                                    <form class="user" id="form-login">
                                        <div class="form-group">
                                            <input type="text" name="username" class="form-control form-control-user"
                                                placeholder="Username" id="txt-username">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control form-control-user"
                                                id="txt-password" placeholder="Password">
                                        </div>
                                        <a href="#" class="btn btn-primary btn-user btn-block" id="btn-login">
                                            Login
                                        </a>
                                        <hr>
                                        <input type="hidden" name="type" value="login">
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.php">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/alertify/alertify.min.js"></script>
    <script type="text/javascript">
        alertify.defaults.theme.ok = "btn btn-primary";
        alertify.defaults.theme.cancel = "btn btn-danger";
        alertify.defaults.theme.input = "form-control";
    </script>
    <script>
        $(document).ready(function() {
            // Init Ready Page Loaded
            $("#btn-login").on('click', function(e) {
                let formData = $("#form-login").serializeArray();
                $.post("controller/auth.controller.php", formData, (response) => {
                    if (response.message == 'success') {
                        window.location.href = `index.php?r=dashboard`;
                    } else {
                        alertify.error("Username or password wrong");
                    }
                });
            });
            $("#txt-username").keypress(function(key) {
                if (key.keyCode === 13) {
                    $("#txt-password").focus();
                }
            });
            $("#txt-password").keypress(function(key) {
                if (key.keyCode === 13) {
                    $("#btn-login").click();
                }
            });
            // Init Ready Page Loaded
        });
    </script>
</body>

</html>