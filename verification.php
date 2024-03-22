<?php 
    include ('./conn/conn.php');
    session_start();

    if (isset($_SESSION['user_verification_id'])) {
        $userVerificationID = $_SESSION['user_verification_id'];
    }
    $code = $_GET['code'] ?? '';
    $verification_code_encode = base64_decode($code);

    $array_encode = explode('|', $verification_code_encode);
    $user_id = $array_encode[0];
    $verification_code = $array_encode[1];
    
    if (!empty($verification_code)) {
        $stmt = $conn->prepare("SELECT `tbl_user_id`, `email` FROM `tbl_user` WHERE `tbl_user_id` = :user_id and `verification_code` = :verification_code");
        $stmt->execute(['user_id' => $user_id, 'verification_code' => $verification_code]);
        $check_user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($check_user)) {
            // echo '<pre>';
            // var_dump($check_user);
            // echo '</pre>';

            // echo "Kode verifikasi sesuai dengan yang ada di database.";
        } else {
            echo "
            <script>
                alert('Link Expired');
                window.location.href = 'http://localhost/otp/forgot-password.php';
            </script>
            ";
        }
    }
    // } else {
    //     echo "
    //     <script>
    //         alert('Link yang anda klik salah!');
    //         window.location.href = 'http://localhost/otp/index.php';
    //     </script>
    //     ";
    // }
 ?>

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
            margin: 0;
            padding: 0;
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

        .verification-form {
            backdrop-filter: blur(9px);
            color: rgb(255, 255, 255);
            padding: 40px;
            width: 500px;
            border: 2px solid;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    
    <div class="main">
        <!-- Email Verification Area -->

        <div class="verification-container">
            <div class="verification-form" id="loginForm">
                <h2 class="text-center">Email Verification</h2>
                <p class="text-center">Please check your email for verification code.</p>
                <form action="./endpoint/add-user.php" method="POST">
                    <input type="text" name="user_verification_id" value="<?= $userVerificationID ?>" hidden>
                    <input type="number" class="form-control text-center" id="verificationCode" name="verification_code">
                    <button type="submit" class="btn btn-secondary login-btn form-control mt-4" name="verify">Verify</button>
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