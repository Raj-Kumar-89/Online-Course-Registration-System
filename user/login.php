
<?php
session_start();
include('../includes/connect.php');

if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email exists in reg table
    $stmt = mysqli_prepare($conn, "SELECT * FROM reg WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $check_user = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($check_user) == 1) {

        // Fetch user details
        $row = mysqli_fetch_assoc($check_user);

        // Verify hashed password
        if (password_verify($password, $row['pwd'])) {

            // Insert login activity without storing password
            $log = mysqli_prepare(
                $conn,
                "INSERT INTO userlog(email) VALUES(?)"
            );

            mysqli_stmt_bind_param($log, "s", $email);
            mysqli_stmt_execute($log);

            // Set session
            $_SESSION['login'] = $email;
            $_SESSION['email'] = $email;

            echo "<script>
                    alert('Login successful!!!');
                    window.location.href = 'my-profile.php';
                  </script>";
            exit();

        } else {

            // Wrong password
            $_SESSION['errmsg'] = "Invalid password";
            header("Location: login.php");
            exit();
        }

    } else {

        // Email not registered
        $_SESSION['errmsg'] = "Email not registered";
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student | Login</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css">

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

        <div class="container stuLogin-content">

            <div class="row">
                <div class="col-md-12">
                    <div class="page-head-line mt-3">
                        Please Login To Enter
                    </div>
                </div>
            </div>

            <form method="post" action="login.php">

                <div class="row">

                    <!-- LOGIN FORM -->
                    <div class="col-md-6">

                        <span class="stuLogin-error-msg">
                            <?php
                            if (isset($_SESSION['errmsg']) && $_SESSION['errmsg'] !== "") {
                                echo htmlentities($_SESSION['errmsg']);
                                unset($_SESSION['errmsg']);
                            }
                            ?>
                        </span>

                        <div class="stuLogin-label">
                            Enter Email :
                        </div>

                        <input
                            type="email"
                            name="email"
                            class="form-control stuLogin-input"
                            required
                        >

                        <div class="stuLogin-label">
                            Enter Password :
                        </div>

                        <input
                            type="password"
                            name="password"
                            class="form-control stuLogin-input"
                            required
                        >

                        <hr>

                        <button
                            type="submit"
                            name="submit"
                            class="btn btn-info stuLogin-submit-btn"
                        >
                            Log In
                        </button>

                        <div class="stuLogin-fpass-box">

                            <div class="row">

                                <div class="col-6">
                                    <a
                                        class="stuLogin-fpass-link"
                                        href="forgetpassword.php"
                                    >
                                        <i class="fa-solid fa-key"></i>
                                        Forget Password
                                    </a>
                                </div>

                                <div class="col-6">
                                    <a
                                        class="stuLogin-register-link"
                                        href="register.php"
                                    >
                                        Don't Have an Account! Click Here
                                    </a>
                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- LATEST NEWS -->
                    <div class="col-md-6">

                        <div class="alert alert-info stuLogin-news-box">

                            <strong>Latest News / Updates</strong>

                            <marquee
                                direction="up"
                                scrollamount="2"
                                onmouseover="this.stop();"
                                onmouseout="this.start();"
                            >

                                <ul>

                                    <?php

                                    $sql = mysqli_query($conn, "SELECT * FROM news");

                                    while ($row = mysqli_fetch_array($sql)) {
                                    ?>

                                        <li>

                                            <a
                                                style="color:black;"
                                                href="newsdetails.php?nid=<?php echo htmlentities($row['id']); ?>"
                                            >

                                                <?php echo htmlentities($row['newstitle']); ?>

                                                —

                                                <?php echo htmlentities($row['postingDate']); ?>

                                            </a>

                                        </li>

                                    <?php } ?>

                                </ul>

                            </marquee>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </main>

    <!-- FOOTER -->
    <?php include('../includes/footer.php'); ?>

    <script src="../assets/js/bootstrap.js"></script>

</body>
</html>