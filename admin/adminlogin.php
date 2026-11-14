
<?php
session_start();
include('../includes/connect.php');

if (isset($_POST['submit'])) {

    $email = trim($_POST['email']);
    $pwd = $_POST['password'];

    // PREPARED STATEMENT
    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM admin WHERE email = ? AND Password = ?"
    );

    mysqli_stmt_bind_param($stmt, "ss", $email, $pwd);
    mysqli_stmt_execute($stmt);

    $query = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($query) == 1) {

        $_SESSION['alogin'] = $email;

        header("Location: dashboard2.php");
        exit();

    } else {

        $_SESSION['errmsg'] = "Invalid username or password";

        header("Location: adminlogin.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin | Login</title>

    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body class="d-flex flex-column min-vh-100">

    <!-- HEADER -->
    <?php include('../includes/header.php'); ?>

    <!-- MENUBAR -->
    <section class="ocr-navbar-section">

        <div class="ocr-nav-wrapper">

            <ul id="menu-top" class="ocr-menu">

                <li class="ocr-menu-item">
                    <a href="../index.php" class="ocr-link">Home</a>
                </li>

                <li class="ocr-menu-item">
                    <a href="adminlogin.php" class="ocr-link">Admin</a>
                </li>

                <li class="ocr-menu-item ocr-dropdown">

                    <a class="ocr-dropbtn ocr-link" href="../user/login.php">
                        Student
                    </a>

                    <div class="ocr-dropdown-content">

                        <a href="../user/login.php" class="ocr-dropdown-link">
                            Login
                        </a>

                        <a href="../user/register.php" class="ocr-dropdown-link">
                            Register
                        </a>

                    </div>

                </li>

            </ul>

        </div>

    </section>


    <!-- MAIN CONTENT -->
    <main class="content-wrapper flex-grow-1">

        <div class="container">

            <div class="row">

                <div class="col-md-12">

                    <h4 class="page-head-line mt-3">
                        PLEASE LOGIN TO ENTER INTO THE ADMIN PAGE
                    </h4>

                </div>

            </div>


            <div class="container">

                <div class="row justify-content-center">

                    <div class="col-6">

                        <!-- ERROR MESSAGE -->
                        <span class="ocr-error-msg">

                            <?php

                            if (isset($_SESSION['errmsg']) && $_SESSION['errmsg'] != "") {

                                echo htmlentities($_SESSION['errmsg']);

                                unset($_SESSION['errmsg']);

                            } elseif (isset($_SESSION['logoutmsg']) && $_SESSION['logoutmsg'] != "") {

                                echo htmlentities($_SESSION['logoutmsg']);

                                unset($_SESSION['logoutmsg']);

                            }

                            ?>

                        </span>


                        <!-- LOGIN CARD -->
                        <div class="card ocr-login-card">

                            <div class="card-body">

                                <form name="admin" method="post" action="adminlogin.php">

                                    <!-- EMAIL -->
                                    <div class="mb-3 form-group">

                                        <label>
                                            <b>Your Email:</b>
                                        </label>

                                        <input
                                            type="email"
                                            class="form-control"
                                            name="email"
                                            placeholder="Enter Your Email"
                                            required
                                        >

                                    </div>


                                    <!-- PASSWORD -->
                                    <div class="mb-3 form-group">

                                        <label>
                                            <b>Your Password:</b>
                                        </label>

                                        <input
                                            type="password"
                                            class="form-control"
                                            name="password"
                                            placeholder="Enter Your Password"
                                            required
                                        >

                                    </div>


                                    <hr>

                                    <!-- LOGIN BUTTON -->
                                    <button
                                        type="submit"
                                        name="submit"
                                        class="btn btn-success"
                                    >
                                        Login
                                    </button>

                                </form>

                            </div>

                        </div>

                        <br>

                    </div>

                </div>

            </div>

        </div>

    </main>


    <!-- FOOTER -->
    <?php include('../includes/footer.php'); ?>


    <script src="../assets/js/bootstrap.js"></script>

</body>
</html>
