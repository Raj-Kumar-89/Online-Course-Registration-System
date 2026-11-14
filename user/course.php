
<?php
session_start();
include('../includes/connect.php');

if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student | Courses</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">

</head>

<body class="d-flex flex-column min-vh-100">

    <!-- HEADER -->
    <?php include('../includes/header.php'); ?>

    <!-- USER MENUBAR -->
    <?php include('../includes/user-menubar.php'); ?>

    <!-- MAIN CONTENT -->
    <main class="content-wrapper flex-grow-1">

        <div class="container">

            <h1 class="page-head-line">Courses</h1>

            <div class="row">

                <div class="col-12">

                    <div class="row">

                        <?php
                        $sql = mysqli_query($conn, "SELECT * FROM course");

                        while ($row = mysqli_fetch_array($sql)) {
                        ?>

                            <div class="col-4">

                                <div class="course-card">

                                    <!-- COURSE NAME -->
                                    <div class="card-head text-center bg-dark text-white">

                                        <h1>
                                            <?php echo htmlentities($row['coursename']); ?>
                                        </h1>

                                    </div>

                                    <!-- COURSE DETAILS -->
                                    <div class="card-body">

                                        <h6>
                                            <b>
                                                Course Code:
                                                <span style="color:violet;">
                                                    <?php echo htmlentities($row['coursecode']); ?>
                                                </span>
                                            </b>
                                        </h6>

                                        <h6>
                                            <b>
                                                Course Duration:
                                                <span style="color:violet;">
                                                    <?php echo htmlentities($row['duration']); ?>
                                                </span>
                                            </b>
                                        </h6>

                                        <h6>
                                            <b>
                                                No Of Seats:
                                                <span style="color:violet;">
                                                    <?php echo htmlentities($row['noofseats']); ?>
                                                </span>
                                            </b>
                                        </h6>

                                        <h6>
                                            <b>
                                                Last Date:
                                                <span style="color:violet;">
                                                    <?php echo htmlentities($row['lastdate']); ?>
                                                </span>
                                            </b>
                                        </h6>

                                        <h6>
                                            <b>
                                                Creation Date:
                                                <span style="color:violet;">
                                                    <?php echo htmlentities($row['creationdate']); ?>
                                                </span>
                                            </b>
                                        </h6>

                                        <!-- REGISTER BUTTON -->
                                        <a href="regcourse.php?id=<?php echo (int)$row['id']; ?>">
                                            <button
                                                type="button"
                                                class="btn btn-info"
                                                style="color:white;"
                                            >
                                                Register
                                            </button>
                                        </a>

                                    </div>

                                </div>

                                <br>

                            </div>

                        <?php } ?>

                    </div>

                </div>

            </div>

        </div>

    </main>

    <!-- FOOTER -->
    <?php include('../includes/footer.php'); ?>

    <script src="../assets/js/bootstrap.min.js"></script>

</body>
</html>
