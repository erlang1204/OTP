<?php
include ('../conn/conn.php');
include ('../Aes.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $status = $_POST['status'];
    // $decoded_password = base64_encode($password);
    $aes = new Aes($password);
    $hasil = bin2hex($aes->encrypt($password));
        $hasil2 = hex2bin($hasil); 
    $pass_baru = $aes->decrypt($hasil2);
    var_dump($hasil,$hasil2,$pass_baru);

    $stmt = $conn->prepare("SELECT `first_name`,`password`,`status`,`role` FROM `tbl_user` WHERE `username` = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $stored_password = $row['password'];
        $stored_status = $row['status'];
        $role = $row['role'];
        $first_name = $row['first_name'];

        if($role == 'admin')
        {
            if (($pass_baru) === $stored_password) {
                echo "
                <script>
                    alert('Login Successfully!');
                    window.location.href = 'http://localhost/otp/home.php';
                </script>
                "; 
            } else  {
                echo "
                <script>
                    alert('Login Failed, Incorrect Password ');
                    window.location.href = 'http://localhost/otp/index.php';
                </script>
                ";
            }
        }
        else
        {
            if ($stored_status === 'yes')
            {
                if (($pass_baru) === $stored_password) {
                    echo "
                    <script>
                        alert('Login Successfully!');
                        window.location.href = 'http://localhost/otp/verification.php';
                    </script>
                    "; 
                } else  {
                    echo "
                    <script>
                        alert('Login Failed, Incorrect Password!');
                        window.location.href = 'http://localhost/otp/index.php';
                    </script>
                    ";
                }
            } else if($stored_status === 'no')
            {
                    echo "
                    <script>
                        alert('Belum verifikasi!');
                        window.location.href = 'http://localhost/otp/index.php';
                    </script>
                    "; 
            }
        }
        
        
    } else {
        echo "
            <script>
                alert('Login Failed, User Not Found!');
                window.location.href = 'http://localhost/otp/index.php';
            </script>
            ";
    }
}

?>
