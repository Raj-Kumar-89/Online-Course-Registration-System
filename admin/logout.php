<?php
session_start();

// Set the logout message first
$_SESSION['logoutmsg'] = "You have successfully logged out";

// Clear only login session data
unset($_SESSION['alogin']); 

// Destroy other session variables except logout message
foreach ($_SESSION as $key => $value) {
    if ($key !== 'logoutmsg') {
        unset($_SESSION[$key]);
    }
}

// Redirect to login page
header("Location: adminlogin.php");
exit();
?>
