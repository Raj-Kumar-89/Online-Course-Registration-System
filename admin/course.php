
<?php
session_start();
include('../includes/connect.php');

// ADMIN SESSION CHECK
if (!isset($_SESSION['alogin']) || empty($_SESSION['alogin'])) {
    header('location:adminlogin.php');
    exit();
}

// INSERT COURSE
if (isset($_POST['submit'])) {

    $coursecode = trim($_POST['coursecode']);
    $coursename = trim($_POST['coursename']);
    $seatlimit = $_POST['seatlimit'];
    $duration = trim($_POST['duration']);
    $ldate = $_POST['lastdate'];

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO course (coursecode, coursename, noofseats, duration, lastdate)
         VALUES (?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssiss",
        $coursecode,
        $coursename,
        $seatlimit,
        $duration,
        $ldate
    );

    if (mysqli_stmt_execute($stmt)) {

        echo "<script>
                alert('Course created successfully!!!');
                window.location.href = 'course.php';
              </script>";
        exit();

    } else {

        echo "<script>
                alert('Cannot create course!!!');
              </script>";
    }
}


// DELETE COURSE
if (isset($_GET['del']) && $_GET['del'] == 'delete' && isset($_GET['id'])) {

    $id = (int)$_GET['id'];

    if ($id > 0) {

        $stmt = mysqli_prepare(
            $conn,
            "DELETE FROM course WHERE id = ?"
        );

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {

            echo "<script>
                    alert('Course Deleted Successfully!!!');
                    window.location.href = 'course.php';
                  </script>";
            exit();

        } else {

            echo "<script>
                    alert('Cannot Delete Course!');
                    window.location.href = 'course.php';
                  </script>";
            exit();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin | Course</title>

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


                <!-- CONTENT -->
                <div class="col-10">

                    <div class="ocr-panel-heading">

                        <h1 class="page-head-line">
                            Courses
                        </h1>

                    </div>


                    <!-- ADD COURSE FORM -->
                    <div class="row justify-content-center">

                        <div class="col-md-6">

                            <form method="post" action="course.php" class="ocr-form-wrapper">

                                <div class="form-group">

                                    <label>
                                        <b>Course Code</b>
                                    </label>

                                    <input
                                        type="text"
                                        name="coursecode"
                                        class="form-control"
                                        required
                                    >

                                </div>


                                <div class="form-group">

                                    <label>
                                        <b>Course Name</b>
                                    </label>

                                    <input
                                        type="text"
                                        name="coursename"
                                        class="form-control"
                                        required
                                    >

                                </div>


                                <div class="form-group">

                                    <label>
                                        <b>Seat Limit</b>
                                    </label>

                                    <input
                                        type="number"
                                        name="seatlimit"
                                        class="form-control"
                                        min="1"
                                        required
                                    >

                                </div>


                                <div class="form-group">

                                    <label>
                                        <b>Duration</b>
                                    </label>

                                    <input
                                        type="text"
                                        name="duration"
                                        class="form-control"
                                        required
                                    >

                                </div>


                                <div class="form-group">

                                    <label>
                                        <b>Last Date</b>
                                    </label>

                                    <input
                                        type="date"
                                        name="lastdate"
                                        class="form-control"
                                        required
                                    >

                                </div>


                                <button
                                    type="submit"
                                    name="submit"
                                    class="btn btn-success mt-3"
                                >
                                    <i class="fas fa-paper-plane"></i>
                                    &nbsp;&nbsp;Submit
                                </button>

                            </form>

                        </div>

                    </div>


                    <!-- MANAGE COURSES TABLE -->
                    <div class="ocr-table-wrapper">

                        <div class="panel panel-default">

                            <div class="panel-heading">

                                <h3 class="page-head-line">
                                    Manage Courses
                                </h3>

                            </div>


                            <?php

                            $sql = mysqli_query(
                                $conn,
                                "SELECT * FROM course ORDER BY creationdate DESC"
                            );

                            $cnt = 1;

                            if (mysqli_num_rows($sql) == 0) {

                            ?>

                                <!-- EMPTY MESSAGE -->
                                <div class="ocr-table-empty">
                                    NO COURSES FOUND
                                </div>

                            <?php

                            } else {

                            ?>

                                <!-- COURSE TABLE -->
                                <div class="table-responsive table-bordered">

                                    <table class="table table-bordered table-striped text-center">

                                        <thead>

                                            <tr>
                                                <th>S.NO</th>
                                                <th>Course Code</th>
                                                <th>Course Name</th>
                                                <th>Seat Limit</th>
                                                <th>Duration</th>
                                                <th>Last Date</th>
                                                <th>Created Time</th>
                                                <th>Action</th>
                                            </tr>

                                        </thead>


                                        <tbody>

                                            <?php

                                            while ($row = mysqli_fetch_assoc($sql)) {

                                            ?>

                                                <tr>

                                                    <td>
                                                        <?php echo $cnt; ?>
                                                    </td>

                                                    <td>
                                                        <?php echo htmlentities($row['coursecode']); ?>
                                                    </td>

                                                    <td>
                                                        <?php echo htmlentities($row['coursename']); ?>
                                                    </td>

                                                    <td>
                                                        <?php echo htmlentities($row['noofseats']); ?>
                                                    </td>

                                                    <td>
                                                        <?php echo htmlentities($row['duration']); ?>
                                                    </td>

                                                    <td>
                                                        <?php echo htmlentities(date("d/m/Y", strtotime($row['lastdate']))); ?>
                                                    </td>

                                                    <td>
                                                        <?php echo htmlentities(date("d/m/Y", strtotime($row['creationdate']))); ?>
                                                    </td>


                                                    <!-- ACTION BUTTONS -->
                                                    <td class="d-flex gap-3 justify-content-center align-items-center">

                                                        <a
                                                            href="edit-course.php?id=<?php echo (int)$row['id']; ?>&ed=edit"
                                                            class="btn btn-primary btn-sm action-btn"
                                                        >
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                            Edit
                                                        </a>


                                                        <a
                                                            href="course.php?id=<?php echo (int)$row['id']; ?>&del=delete"
                                                            onclick="return confirm('Are you sure you want to delete?')"
                                                            class="btn btn-danger btn-sm action-btn"
                                                        >
                                                            <i class="fa-solid fa-trash"></i>
                                                            Delete
                                                        </a>

                                                    </td>

                                                </tr>

                                            <?php

                                                $cnt++;

                                            }

                                            ?>

                                        </tbody>

                                    </table>

                                </div>

                            <?php } ?>

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
