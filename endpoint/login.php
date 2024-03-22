<?php
include ('../conn/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $status = $_POST['status'];
    $decoded_password = base64_encode($password);

    $stmt = $conn->prepare("SELECT `password`,`status` FROM `tbl_user` WHERE `username` = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $stored_password = $row['password'];
        $stored_status = $row['status'];

        if ($stored_status === 'yes')
        {
            if (($decoded_password) === $stored_password) {
                echo "
                <script>
                    alert('Login Successfully!');
                    window.location.href = 'http://localhost/otp/home.php';
                </script>
                "; 
            } else  {
                echo "
                <script>
                    alert('Login Failed, Incorrect Password!');
                    window.location.href = 'http://localhost/otp/login.php';
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
