<?php
session_start();
error_reporting(0);
include("../includes/connect.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Details</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/style.css">
    
</head>
<body class="d-flex flex-column min-vh-100">

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
                    <a class="ocr-dropbtn ocr-link" href="login.php">Student</a>
                    <div class="ocr-dropdown-content">
                        <a href="login.php" class="ocr-dropdown-link">Login</a>
                        <a href="register.php" class="ocr-dropdown-link">Register</a>
                    </div>
                </li>
            </ul>
        </div>
    </section>

    <!-- CONTENT -->
    <main class="content-wrapper flex-grow-1">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <br>
                    <h1 class="page-head-line">News Details</h1>
                </div>

                <div class="col-md-12">
                    <div class="alert alert-info">

                        <?php
                        $nid = $_GET['nid'] ?? '';

                        $stmt = mysqli_prepare(
                            $conn,
                            "SELECT * FROM news WHERE id = ?"
                        );

                        mysqli_stmt_bind_param($stmt, "i", $nid);
                        mysqli_stmt_execute($stmt);

                        $sql = mysqli_stmt_get_result($stmt);

                        while ($row = mysqli_fetch_assoc($sql)) {
                        ?>

                            <h3>
                                <?php echo htmlentities($row['newstitle']); ?>
                            </h3>

                            <small>
                                <?php echo htmlentities(date(
                                    "d/m/Y",
                                    strtotime($row['postingDate'])
                                )); ?>
                            </small>

                            <hr>

                            <p>
                                <?php echo nl2br(htmlentities($row['newsDescription'])); ?>
                            </p>

                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include('../includes/footer.php'); ?>

    <script src="../assets/js/bootstrap.js"></script>

</body>
</body>
</html>
