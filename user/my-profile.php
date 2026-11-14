
<?php
session_start();
include('../includes/connect.php');

if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:login.php');
    exit();
}

// Get logged-in user email
$email = $_SESSION['email'];

// Fetch registered courses
$courseStmt = mysqli_prepare(
    $conn,
    "SELECT id, coursename FROM courseenrolls WHERE email = ?"
);

mysqli_stmt_bind_param($courseStmt, "s", $email);
mysqli_stmt_execute($courseStmt);

$courseSql = mysqli_stmt_get_result($courseStmt);

// Fetch student profile
$userStmt = mysqli_prepare(
    $conn,
    "SELECT * FROM reg WHERE email = ? LIMIT 1"
);

mysqli_stmt_bind_param($userStmt, "s", $email);
mysqli_stmt_execute($userStmt);

$userSql = mysqli_stmt_get_result($userStmt);
$row = mysqli_fetch_assoc($userSql);

if (!$row) {
    session_destroy();
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

    <title>Student Profile</title>

    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css">
</head>

<body class="sp-body d-flex flex-column min-vh-100">

    <!-- HEADER -->
    <?php include('../includes/header.php'); ?>

    <!-- USER MENUBAR -->
    <?php include('../includes/user-menubar.php'); ?>

    <!-- MAIN CONTENT -->
    <main class="content-wrapper sp-content-wrapper flex-grow-1">

        <div class="container">

            <!-- PAGE TITLE -->
            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-head-line mt-3">My Profile</h1>
                </div>
            </div>

            <!-- PRINT REGISTERED COURSES SECTION -->
            <?php if (mysqli_num_rows($courseSql) > 0) { ?>

                <div class="row justify-content-center mt-3">

                    <div class="col-md-6 col-sm-10 sp-print-box">

                        <div class="alert alert-info text-center">

                            <h5 class="mb-2">
                                <i class="fa-solid fa-print"></i>
                                Print Registered Courses
                            </h5>

                            <hr>

                            <marquee
                                direction="up"
                                scrollamount="2"
                                onmouseover="this.stop();"
                                onmouseout="this.start();"
                                style="max-height:150px;"
                            >

                                <?php while ($course = mysqli_fetch_assoc($courseSql)) { ?>

                                    <div class="d-flex justify-content-between align-items-center mb-2 px-2">

                                        <span>
                                            <i class="fa-solid fa-book"></i>

                                            <?php echo htmlentities($course['coursename']); ?>
                                        </span>

                                        <a
                                            href="print.php?enroll_id=<?php echo (int)$course['id']; ?>"
                                            target="_blank"
                                        >
                                            <button type="button" class="btn btn-primary btn-sm">
                                                <i class="fa-solid fa-print"></i>
                                                Print
                                            </button>
                                        </a>

                                    </div>

                                <?php } ?>

                            </marquee>

                        </div>

                    </div>

                </div>

            <?php } ?>


            <!-- PROFILE DETAILS -->
            <div class="row mt-4">

                <div class="col-md-3"></div>

                <div class="col-md-6">

                    <div class="panel panel-primary sp-profile-panel">

                        <div class="panel-heading sp-profile-heading">
                            My Profile Details
                        </div>

                        <div class="panel-body sp-profile-body">

                            <form>

                                <div class="form-group sp-form-group">

                                    <label class="sp-label">
                                        <b>Student Name</b>
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control sp-input"
                                        value="<?php echo htmlentities($row['name']); ?>"
                                        readonly
                                    >

                                </div>


                                <div class="form-group sp-form-group">

                                    <label class="sp-label">
                                        <b>Student Reg No</b>
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control sp-input"
                                        value="<?php echo htmlentities($row['regno']); ?>"
                                        readonly
                                    >

                                </div>


                                <div class="form-group sp-form-group">

                                    <label class="sp-label">
                                        <b>Date of Birth</b>
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control sp-input"
                                        value="<?php echo date('d/m/Y', strtotime($row['dob'])); ?>"
                                        readonly
                                    >

                                </div>


                                <div class="form-group sp-form-group">

                                    <label class="sp-label">
                                        <b>Email</b>
                                    </label>

                                    <input
                                        type="email"
                                        class="form-control sp-input"
                                        value="<?php echo htmlentities($row['email']); ?>"
                                        readonly
                                    >

                                </div>


                                <div class="form-group sp-form-group">

                                    <label class="sp-label">
                                        <b>Gender</b>
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control sp-input"
                                        value="<?php echo htmlentities($row['gender']); ?>"
                                        readonly
                                    >

                                </div>

                                <!-- PASSWORD NOT DISPLAYED -->

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