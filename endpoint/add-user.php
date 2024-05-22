<?php
include('../conn/conn.php');
include('../Aes.php');
// include('../index.php');
// include "AES.php"; //memasukan file AES

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

if (isset($_POST['register'])) { //proses saat register
    try {
        $b = $_POST['key']; //kunci utk enkripsi
        $a = $_POST['password']; //text yg akan di enkrip
        $aes = new Aes($a); // pembuatan objek dari class Aes, dengan parameter kunci dekrip
        $hasil = bin2hex($aes->encrypt($a));


        $hasil2 = hex2bin($hasil);
        $hasil_dekrip = $aes->decrypt($hasil2);
        // var_dump($a,$b,$hasil,$hasil2,$hasil_dekrip);
        // $password = $_POST['password'];
        // $io = substr(md5('password'),0,16);
        // $io = encrypt($password);
        // $aes = new AES($password);
        
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $contactNumber = $_POST['contact_number'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $pass = $hasil;

        $conn->beginTransaction();
    
        $stmt = $conn->prepare("SELECT `first_name`, `last_name` FROM `tbl_user` WHERE `first_name` = :first_name AND `last_name` = :last_name");
        $stmt->execute([
            'first_name' => $firstName,
            'last_name' => $lastName
        ]);
        $nameExist = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (empty($nameExist)) {
            $verificationCode = rand(100000, 999999);
            $enc=$pass;   //diganti kriptografi
    
            $insertStmt = $conn->prepare("INSERT INTO `tbl_user` (`tbl_user_id`, `first_name`, `last_name`, `contact_number`, `email`, `username`, `password`, `verification_code`,`status`) VALUES (NULL, :first_name, :last_name, :contact_number, :email, :username, :password, :verification_code, 'yes')");

            $insertStmt->bindParam(':first_name', $firstName, PDO::PARAM_STR);
            $insertStmt->bindParam(':last_name', $lastName, PDO::PARAM_STR);
            $insertStmt->bindParam(':contact_number', $contactNumber, PDO::PARAM_INT);
            $insertStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $insertStmt->bindParam(':username', $username, PDO::PARAM_STR);
            $insertStmt->bindParam(':password', $enc, PDO::PARAM_STR);
            $insertStmt->bindParam(':verification_code', $verificationCode, PDO::PARAM_INT);
            $insertStmt->execute();
    
            //Server settings
            $mail->isSMTP(); 
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true; 
            $mail->Username   = 'erlangbayu7@gmail.com';
            $mail->Password   = 'hkee hclw qafm qrvs';
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;                                    
        
            //Recipients
            $mail->setFrom('erlangbayu7@gmail.com', 'PT.Sinar Metrindo Perkasa');
            $mail->addAddress($email);   
            $mail->addReplyTo('erlangbayu7@gmail.com', 'PT.Sinar Metrindo Perkasa'); 
        
            //Content
            $mail->isHTML(true);  
            $mail->Subject = 'Verification Code';
            $mail->Body    = 'Your verification code is: <a href="index.php">' . $verificationCode . '</a>';

            
            // Success sent message alert
            $mail->send();
            
            session_start();
    
            $userVerificationID = $conn->lastInsertId();
            $_SESSION['user_verification_id'] = $userVerificationID;

            echo "
            <script>
                alert('Register Succesfully.');
                window.location.href = 'http://localhost/otp/index.php';
            </script>
            ";

            $conn->commit();
        } else {
            echo "
            <script>
                alert('User Already Exists');
                window.location.href = 'http://localhost/otp/index.php';
            </script>
            ";
        }
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_POST['forgot'])) { // proses saat lupa password
    try {
        $email = $_POST['email'];
        $conn->beginTransaction();
    
        $stmt = $conn->prepare("SELECT `tbl_user_id`, `email` FROM `tbl_user` WHERE `email` = :email");
        $stmt->execute([
            'email' => $email,
        ]);
        $nameExist = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!empty($nameExist)) {
            $random_code = rand(100000, 999999);
            $user_id = $nameExist['tbl_user_id'] ?? '';

            // hash code
            $verification_code = base64_encode($user_id .'|'. $random_code);

    
            $insertStmt = $conn->prepare("UPDATE `tbl_user` SET `verification_code` = :verification_code WHERE `email` = :email");

            $insertStmt->bindParam(':verification_code', $random_code, PDO::PARAM_INT);
            $insertStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $insertStmt->execute();
    
            //Server settings
            $mail->isSMTP(); 
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true; 
            $mail->Username   = 'erlangbayu7@gmail.com';
            $mail->Password   = 'hlrv hthv rwgl uaby';
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;                                    
        
            //Recipients
            $mail->setFrom('erlangbayu7@gmail.com', 'PT.Sinar Metrindo Perkasa');
            $mail->addAddress($email);   
            $mail->addReplyTo('erlangbayu7@gmail.com', 'PT.Sinar Metrindo Perkasa'); 
        
            //Content
            $mail->isHTML(true);  
            $mail->Subject = 'Verification Code';
            $mail->Body    = 'Your verification code is:  http://localhost/otp/verification.php?code=' . $verification_code; 
            
            // Success sent message alert
            $mail->send();
            
            session_start();
    
            $userVerificationID = $conn->lastInsertId();
            $_SESSION['user_verification_id'] = $userVerificationID;

            $conn->commit();

            header('Location: http://localhost/otp/forgot-password.php?message=success');
        } else {
            echo "
            <script>
                alert('User Already Exists');
                window.location.href = 'http://localhost/otp/index.php';
            </script>
            ";
        }
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_POST['verify'])) {

    try {
        $userVerificationID = $_POST['user_verification_id'];
        $verificationCode = $_POST['verification_code'];
    
        $stmt = $conn->prepare("SELECT `verification_code` FROM `tbl_user` WHERE `tbl_user_id` = :user_verification_id");
        $stmt->execute([
            'user_verification_id' => $userVerificationID,
        ]);
        $codeExist = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($codeExist && $codeExist['verification_code'] == $verificationCode) {
            // session_destroy();
            echo "
            <script>
                alert('Registered Successfully.');
                window.location.href = 'http://localhost/otp/home.php';
            </script>
            ";
        } else {
            $conn->prepare("DELETE FROM `tbl_user` WHERE `tbl_user_id` = :user_verification_id")->execute([
                'user_verification_id' => $userVerificationID
            ]);
            echo "
            <script>
                alert('Incorrect Verification Code. Register Again.');
                window.location.href = 'http://localhost/otp/index.php';
            </script>
            
            ";
            
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
