<!DOCTYPE html>
<html lang = "vi">
<head>
    <meta charset="UTF-8">
    <meta name = "viewport" content = "width = device-width, initial-scale = 1.0">
    <title>Note</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        *{
            box-sizing: border-box;
            margin:0;
            padding:0;
        }
        .form-box{
            width: 100%;
            max-width: 400px;
            display:none;
        }
        form{
            width:100%;
        }

        .form-box.active{
            display: block;
        }
    </style>
</head>
<body>
    <div class = "container-fluid d-flex justify-content-center align-items-center vh-100">
        <div id = "login-form"class = "form-box active">
            <form class = "border rounded border-dark p-3" action = "login_register.php" method = "POST">
                    <p class = "text-center"><strong>Login</strong></p>
                    <div class="form-group">
                        <label>Email</label>
                        <input type = "text" name = "email" class = "form-control" placeholder="Email" required>
                    </div>
                    <div class = "form-group">
                        <label>Password</label>
                        <input type = "password" name = "password" class = "form-control" placeholder="Password" required>
                    </div>
                    <button name = "login"class = "btn btn-primary btn-block">Login</button>
                    <p class ="mt-1 text-center">Don't have an account? <a href="#" onclick="showForm('register-form')">Register</a></p>
            </form>
        </div>
        <div id = "register-form"class = "form-box">
            <form class = "border rounded border-dark p-3" action = "login_register.php" method = "POST">
                    <p class = "text-center"><strong>Register</strong></p>
                    <div class="form-group">
                        <label>User Name</label>
                        <input type = "text" name = "name" class = "form-control" placeholder="User Name" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type = "text" name = "email" class = "form-control" placeholder="Email" required>
                    </div>
                    <div class = "form-group">
                        <label>Password</label>
                        <input type = "password" name = "password" class = "form-control" placeholder="Password" required>
                    </div>
                    <div class = "form-group">
                        <label>Confirm Password</label>
                        <input type = "password" name = "conf-password" class = "form-control" placeholder="Password" required>
                    </div>
                    <button name = "register" class = "btn btn-primary btn-block">Sign Up</button>
                    <p class ="mt-1 text-center">Already have an account? <a href="#" onclick = "showForm('login-form')">Login</a></p>
            </form>
        </div>
    </div>
    <script>
        function showForm(formID){
        document.querySelectorAll(".form-box").forEach(form => form.classList.remove("active"));
        document.getElementById(formID).classList.add("active");
    }
    </script>
</body>
</html>