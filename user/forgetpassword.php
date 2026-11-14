
<?php
session_start();
include('../includes/connect.php');

if (isset($_POST['sbt'])) {

    $email = trim($_POST['email']);
    $npwd  = $_POST['npwd'];
    $cpwd  = $_POST['cpwd'];

    // CHECK EMPTY FIELDS
    if ($email == "" || $npwd == "" || $cpwd == "") {

        echo "<script>
                alert('Enter the details in the required fields');
              </script>";
    }

    // EMAIL VALIDATION
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        echo "<script>
                alert('Enter a valid email address!');
              </script>";
    }

    // PASSWORD MATCH VALIDATION
    elseif ($npwd != $cpwd) {

        echo "<script>
                alert('New Password does not match Confirm Password!');
              </script>";
    }

    else {

        // CHECK IF EMAIL EXISTS
        $check = mysqli_prepare(
            $conn,
            "SELECT id FROM reg WHERE email = ?"
        );

        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);

        $result = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($result) == 1) {

            // HASH NEW PASSWORD
            $hashedPassword = password_hash($npwd, PASSWORD_DEFAULT);

            // UPDATE PASSWORD
            $stmt = mysqli_prepare(
                $conn,
                "UPDATE reg SET pwd = ? WHERE email = ?"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "ss",
                $hashedPassword,
                $email
            );

            if (mysqli_stmt_execute($stmt)) {

                echo "<script>
                        alert('Password Changed Successfully!');
                        window.location.href = 'login.php';
                      </script>";
                exit();

            } else {

                echo "<script>
                        alert('Password Update Failed. Try Again!');
                      </script>";
            }

        } else {

            echo "<script>
                    alert('Email address not registered!');
                  </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student | Forget Password</title>

    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body class="fp-body d-flex flex-column min-vh-100">

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

                        <a href="login.php" class="ocr-dropdown-link">
                            Login
                        </a>

                        <a href="register.php" class="ocr-dropdown-link">
                            Register
                        </a>

                    </div>

                </li>

            </ul>

        </div>

    </section>


    <!-- MAIN CONTENT -->
    <main class="content-wrapper fp-content-wrapper flex-grow-1">

        <div class="container fp-container">

            <div class="row justify-content-center fp-row">

                <h1 class="page-head-line fp-page-head-line mt-3">
                    Forget Password
                </h1>

                <div class="col-6 fp-form-col">

                    <br>

                    <div class="card fp-card">

                        <div class="card-body fp-card-body">

                            <form method="post" name="forgetpwd" class="fp-form">

                                <!-- EMAIL -->
                                <label class="form-label fp-label">
                                    <b>Enter Email Id:</b>
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    class="form-control fp-input-email"
                                    required
                                >

                                <!-- NEW PASSWORD -->
                                <label class="form-label fp-label">
                                    <b>Enter New Password</b>
                                </label>

                                <input
                                    type="password"
                                    name="npwd"
                                    id="fp-input-newpwd"
                                    class="form-control fp-input-newpwd"
                                    required
                                >

                                <!-- CONFIRM PASSWORD -->
                                <label class="form-label fp-label">
                                    <b>Confirm New Password</b>
                                </label>

                                <input
                                    type="password"
                                    name="cpwd"
                                    id="fp-input-confirmpwd"
                                    class="form-control fp-input-confirmpwd"
                                    required
                                >

                                <hr class="fp-divider">

                                <!-- SUBMIT BUTTON -->
                                <button
                                    type="submit"
                                    name="sbt"
                                    class="btn btn-primary fp-btn-submit"
                                >
                                    Change Password
                                </button>

                            </form>

                        </div>

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
