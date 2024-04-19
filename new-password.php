<?php include ('./conn/conn.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System with Email Verification</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: -11px;
            padding: 7px;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url("./asset/bg1.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;
        }

        .login-form, .registration-form {
            backdrop-filter: blur(9px);
            color: rgb(255, 255, 255);
            padding: 100px;
            width: 402px;
            border: 2px solid;
            border-radius: 10px;
        }

        .switch-form-link {
            text-decoration: underline;
            cursor: pointer;
            color: rgb(100, 100, 200);
            margin-left: 6px;
        }

        .switch-form-link-register {
            text-decoration: underline;
            cursor: pointer;
            color: rgb(100, 100, 200);
            margin-left: 1px;
        }

        #register {
            margin-top: 1rem;
        }

        #forgot {
            margin-bottom: 1rem;
        }

        /* Logo styling */
        .logo {
            width: 109px; /* Adjust the width as needed */
            margin-bottom: 23px; /* Adjust the margin as needed */
            margin-left: 55px;
            margin-top: -77px;
        }
    </style>
</head>
<body>
    
    <div class="main">
        <!-- New Password -->
        <div class="login-container">
            <div class="login-form" id="loginForm">
                <img src="./asset/logo.png" alt="Logo" class="logo">
                <form action="./endpoint/login.php" method="POST">
                    <div class="form-group">
                        <label for="newpassword">New password</label>
                        <input type="password" class="form-control" id="newpassword" name="newpassword">
                        <input hidden type="text" name="status"></input>
                    </div>
                    <div class="form-group">
                        <label for="password">Confirm Password:</label>
                        <input type="password" class="form-control" id="confirmpassword" name="confirmpassword">
                        <input hidden type="text" name="status"></input>
                    </div>
                    <button type="submit" class="btn btn-secondary login-btn form-control" name="register">Register</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap Js -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
