
<?php
session_start();
include('../includes/connect.php');

// ADMIN SESSION CHECK
if (!isset($_SESSION['alogin']) || empty($_SESSION['alogin'])) {
    header('location:adminlogin.php');
    exit();
}

// CHECK COURSE ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('location:course.php');
    exit();
}

$id = (int)$_GET['id'];

// FETCH COURSE DETAILS
$stmt = mysqli_prepare($conn, "SELECT * FROM course WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "<script>
            alert('Course not found!');
            window.location.href='course.php';
          </script>";
    exit();
}

// UPDATE COURSE
if (isset($_POST['submit'])) {

    $coursecode = trim($_POST['coursecode']);
    $coursename = trim($_POST['coursename']);
    $duration = trim($_POST['duration']);
    $seatlimit = (int)$_POST['seatlimit'];
    $lastdate = $_POST['lastdate'];

    if ($seatlimit < 1) {
        echo "<script>alert('Seat limit must be greater than zero!');</script>";
    } else {

        $update = mysqli_prepare(
            $conn,
            "UPDATE course SET
                coursecode = ?,
                coursename = ?,
                duration = ?,
                noofseats = ?,
                lastdate = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $update,
            "sssisi",
            $coursecode,
            $coursename,
            $duration,
            $seatlimit,
            $lastdate,
            $id
        );

        if (mysqli_stmt_execute($update)) {

            echo "<script>
                    alert('Course Updated Successfully!!!');
                    window.location.href='course.php';
                  </script>";
            exit();

        } else {

            echo "<script>
                    alert('Error: Course Not Updated');
                  </script>";
        }
    }

    // Update displayed values if validation fails
    $row['coursecode'] = $coursecode;
    $row['coursename'] = $coursename;
    $row['duration'] = $duration;
    $row['noofseats'] = $seatlimit;
    $row['lastdate'] = $lastdate;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin | Edit Course</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css">

</head>

<body class="d-flex flex-column min-vh-100">

    <!-- HEADER -->
    <?php include('../includes/header.php'); ?>


    <!-- MAIN CONTENT -->
    <main class="flex-grow-1">

        <div class="container-fluid">

            <div class="row">

                <!-- SIDEBAR -->
                <div class="col-2 min-vh-100 ocr-sidebar">

                    <?php include('includes/sidebar.php'); ?>

                </div>


                <!-- MAIN CONTENT -->
                <div class="col-10">

                    <h1 class="page-head-line mt-3">
                        Edit Course
                    </h1>


                    <div class="row justify-content-center">

                        <div class="col-md-6">

                            <div class="ocr-form-box">

                                <form method="post" action="edit-course.php?id=<?php echo $id; ?>">

                                    <!-- COURSE CODE -->
                                    <div class="form-group">

                                        <label>
                                            <b>Course Code</b>
                                        </label>

                                        <input
                                            type="text"
                                            name="coursecode"
                                            class="form-control"
                                            value="<?php echo htmlentities($row['coursecode']); ?>"
                                            required
                                        >

                                    </div>


                                    <!-- COURSE NAME -->
                                    <div class="form-group">

                                        <label>
                                            <b>Course Name</b>
                                        </label>

                                        <input
                                            type="text"
                                            name="coursename"
                                            class="form-control"
                                            value="<?php echo htmlentities($row['coursename']); ?>"
                                            required
                                        >

                                    </div>


                                    <!-- DURATION -->
                                    <div class="form-group">

                                        <label>
                                            <b>Duration</b>
                                        </label>

                                        <input
                                            type="text"
                                            name="duration"
                                            class="form-control"
                                            value="<?php echo htmlentities($row['duration']); ?>"
                                            required
                                        >

                                    </div>


                                    <!-- SEAT LIMIT -->
                                    <div class="form-group">

                                        <label>
                                            <b>Seat Limit</b>
                                        </label>

                                        <input
                                            type="number"
                                            name="seatlimit"
                                            class="form-control"
                                            min="1"
                                            value="<?php echo htmlentities($row['noofseats']); ?>"
                                            required
                                        >

                                    </div>


                                    <!-- LAST DATE -->
                                    <div class="form-group">

                                        <label>
                                            <b>Last Date</b>
                                        </label>

                                        <input
                                            type="date"
                                            name="lastdate"
                                            class="form-control"
                                            value="<?php echo htmlentities($row['lastdate']); ?>"
                                            required
                                        >

                                    </div>


                                    <!-- UPDATE BUTTON -->
                                    <button
                                        type="submit"
                                        name="submit"
                                        class="btn btn-primary mt-3"
                                    >

                                        <i class="fa fa-refresh"></i>
                                        Update

                                    </button>


                                    <a href="course.php" class="btn btn-secondary mt-3">
                                        Cancel
                                    </a>

                                </form>

                            </div>

                        </div>

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
