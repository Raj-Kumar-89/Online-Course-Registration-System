
<?php
session_start();
include('../includes/connect.php');

// ADMIN SESSION CHECK
if (!isset($_SESSION['alogin']) || empty($_SESSION['alogin'])) {
    header('location:adminlogin.php');
    exit();
}

// DELETE LOGIN RECORD
if (isset($_GET['del']) && $_GET['del'] == 'delete' && isset($_GET['id'])) {

    $id = (int)$_GET['id'];

    if ($id > 0) {

        $stmt = mysqli_prepare($conn, "DELETE FROM userlog WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                    alert('Deleted Successfully');
                    window.location.href='dashboard2.php';
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin | Dashboard</title>

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

                    <div class="ocr-panel-heading">

                        <h1 class="page-head-line">
                            Admin Dashboard
                        </h1>

                    </div>


                    <!-- DASHBOARD CARDS -->
                    <div class="row">

                        <!-- ADMIN CARD -->
                        <div class="col-3">

                            <div class="card ocr-card ocr-card-admin">

                                <div class="card-body">

                                    <h5 class="ocr-card-title">
                                        <b>Admin</b>
                                    </h5>

                                    <p class="card-para">
                                        Total Admins:

                                        <b>
                                            <?php
                                            $s = mysqli_query($conn, "SELECT id FROM admin");
                                            echo mysqli_num_rows($s);
                                            ?>
                                        </b>
                                    </p>

                                </div>

                            </div>

                        </div>


                        <!-- COURSES CARD -->
                        <div class="col-3">

                            <div class="card ocr-card ocr-card-courses">

                                <div class="card-body">

                                    <h5 class="ocr-card-title">
                                        <b>Courses</b>
                                    </h5>

                                    <p class="card-para">
                                        Total Courses:

                                        <b>
                                            <?php
                                            $s = mysqli_query($conn, "SELECT id FROM course");
                                            echo mysqli_num_rows($s);
                                            ?>
                                        </b>
                                    </p>

                                </div>

                            </div>

                        </div>


                        <!-- REGISTRATIONS CARD -->
                        <div class="col-3">

                            <div class="card ocr-card ocr-card-registers">

                                <div class="card-body">

                                    <h5 class="ocr-card-title">
                                        <b>Registrations</b>
                                    </h5>

                                    <p class="card-para">
                                        Total Registrations:

                                        <b>
                                            <?php
                                            $s = mysqli_query($conn, "SELECT id FROM courseenrolls");
                                            echo mysqli_num_rows($s);
                                            ?>
                                        </b>
                                    </p>

                                </div>

                            </div>

                        </div>


                        <!-- FEEDBACK CARD -->
                        <div class="col-3">

                            <div class="card ocr-card ocr-card-feedbacks">

                                <div class="card-body">

                                    <h5 class="ocr-card-title">
                                        <b>Feedbacks</b>
                                    </h5>

                                    <p class="card-para">
                                        Total Feedbacks:

                                        <b>
                                            <?php
                                            $s = mysqli_query($conn, "SELECT fid FROM feedback");
                                            echo mysqli_num_rows($s);
                                            ?>
                                        </b>
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>


                    <hr>


                    <!-- FETCH USER LOGIN RECORDS -->
                    <?php

                    $ql = mysqli_query(
                        $conn,
                        "SELECT * FROM userlog ORDER BY logintime DESC"
                    );

                    $rowCount = mysqli_num_rows($ql);

                    ?>


                    <!-- LOGIN HISTORY TABLE -->
                    <?php if ($rowCount > 0) { ?>

                        <div class="table-responsive">

                            <table class="table table-bordered table-striped ocr-log-table">

                                <thead>

                                    <tr>
                                        <th>S.No</th>
                                        <th>Email</th>
                                        <th>Login Time</th>
                                        <th>Logout Time</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>


                                <tbody>

                                    <?php

                                    $cnt = 1;

                                    while ($row = mysqli_fetch_assoc($ql)) {

                                    ?>

                                        <tr>

                                            <td>
                                                <?php echo $cnt++; ?>
                                            </td>

                                            <td>
                                                <?php echo htmlentities($row['email']); ?>
                                            </td>

                                            <td>
                                                <?php
                                                echo htmlentities(
                                                    date('d/m/Y H:i:s', strtotime($row['logintime']))
                                                );
                                                ?>
                                            </td>

                                            <td>
                                                <?php
                                                echo !empty($row['logouttime'])
                                                    ? htmlentities(date('d/m/Y H:i:s', strtotime($row['logouttime'])))
                                                    : 'Not Logged Out';
                                                ?>
                                            </td>


                                            <!-- DELETE BUTTON -->
                                            <td>

                                                <a
                                                    href="dashboard2.php?id=<?php echo (int)$row['id']; ?>&del=delete"
                                                    onclick="return confirm('Are you sure you want to Delete?')"
                                                    class="btn btn-danger ocr-btn-delete"
                                                >
                                                    <i class="fa-solid fa-trash"></i>
                                                    Delete
                                                </a>

                                            </td>

                                        </tr>

                                    <?php } ?>

                                </tbody>

                            </table>

                        </div>

                    <?php } else { ?>

                        <!-- NO RECORDS -->
                        <div class="alert alert-warning text-center ocr-table-empty">

                            <h4>No login records found</h4>

                        </div>

                    <?php } ?>


                </div>

            </div>

        </div>

    </main>


    <!-- FOOTER -->
    <?php include('../includes/footer.php'); ?>


    <script src="../assets/js/bootstrap.js"></script>

</body>
</html>
