
<?php
session_start();
include('../includes/connect.php');

if (isset($_POST['submit'])) {

    function generateRegNo() {
        $reg_no = mt_rand(100, 999);
        $date   = date('y');
        return $date . "OC" . $reg_no;
    }

    $name   = $_POST['name'];
    $dob    = $_POST['dob'];
    $gender = $_POST['gender'];
    $email  = $_POST['email'];
    $pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);

    /* CHECK IF EMAIL EXISTS */
    $checkEmail = mysqli_query($conn, "SELECT id FROM reg WHERE email='$email'");

    if (mysqli_num_rows($checkEmail) > 0) {
        echo "<script>
                alert('Email already exists! Please login.');
                window.location.href='login.php';
              </script>";
        exit();
    }

    /* INSERT NEW USER */
    $reg = generateRegNo();

    $res = mysqli_query($conn,
        "INSERT INTO reg (name, dob, gender, email, regno, pwd)
         VALUES ('$name','$dob','$gender','$email','$reg','$pwd')"
    );

    if ($res) {
        $_SESSION['login'] = $email;
        $_SESSION['email'] = $email;

        echo "<script>
                alert('Registered successfully!');
                window.location.href='login.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Cannot Register. Try Again!');
                window.location.href='register.php';
              </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
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
                    <a href="../admin/adminlogin.php" class="ocr-link">Admin</a>
                </li>

                <li class="ocr-menu-item ocr-dropdown">
                    <a class="ocr-dropbtn ocr-link" href="login.php">
                        Student
                    </a>

                    <div class="ocr-dropdown-content">
                        <a href="login.php" class="ocr-dropdown-link">Login</a>
                        <a href="register.php" class="ocr-dropdown-link">Register</a>
                    </div>
                </li>

            </ul>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <main class="flex-grow-1">

        <div class="container regPage-container">

            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-head-line mt-3">
                        Please Register Here to Login
                    </h1>
                </div>
            </div>

            <div class="row justify-content-center regPage-row">

                <div class="col-6">
                    <div class="regCard-box card">

                        <form method="post" action="register.php" class="regForm-wrapper">

                            <div class="regForm-block">
                                <div class="regForm-label">Enter Name:</div>
                                <input type="text" name="name" class="regForm-input form-control">
                            </div>

                            <div class="regForm-block">
                                <div class="regForm-label">DOB:</div>
                                <input type="date" name="dob" class="regForm-input form-control">
                            </div>

                            <div class="regForm-block">
                                <div class="regForm-label">Gender:</div>
                                <input type="text" name="gender" class="regForm-input form-control">
                            </div>

                            <div class="regForm-block">
                                <div class="regForm-label">Email:</div>
                                <input type="email" name="email" class="regForm-input form-control">
                            </div>

                            <div class="regForm-block">
                                <div class="regForm-label">Password:</div>
                                <input type="password" name="pwd" class="regForm-input form-control">
                            </div>

                            <button type="submit" name="submit" class="btn btn-success regSubmit-button">
                                Register
                            </button>

                            <a class="stuLogin-register-link" href="login.php">
                                Already Have an Account!!! Click Here
                            </a>

                        </form>

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
