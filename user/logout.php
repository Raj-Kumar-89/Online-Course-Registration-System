<?php
session_start();
include('../includes/connect.php');

date_default_timezone_set('Asia/Kolkata');

if (isset($_SESSION['login'])) {

    $email = $_SESSION['login'];

    mysqli_query(
        $conn,
        "UPDATE userlog 
         SET logouttime = CURRENT_TIMESTAMP 
         WHERE email='$email' 
         AND logouttime IS NULL
         ORDER BY id DESC 
         LIMIT 1"
    );
}

// Destroy session
session_unset();
session_destroy();

// Redirect
echo "<script>
        alert('You have successfully logged out');
        window.location.href='login.php';
      </script>";
exit();
?>