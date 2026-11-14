
<?php
session_start();
include('../includes/connect.php');

// ADMIN SESSION CHECK
if (!isset($_SESSION['alogin']) || empty($_SESSION['alogin'])) {
    header('location:adminlogin.php');
    exit();
}

// DELETE ENROLLMENT
if (isset($_GET['id']) && isset($_GET['del'])) {

    $id = (int)$_GET['id'];

    if ($id > 0) {

        $stmt = mysqli_prepare(
            $conn,
            "DELETE FROM courseenrolls WHERE id = ?"
        );

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                    alert('Record Deleted Successfully');
                    window.location.href='enroll-history.php';
                  </script>";
            exit();
        }
    }
}

// FILTER VALUES
$fdate = "";
$tdate = "";
$coursename = "";

// BASE QUERY
$filterQuery = "SELECT * FROM courseenrolls WHERE 1=1";
$params = [];
$types = "";

// DATE AND COURSE FILTER
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // DATE FILTER
    if (isset($_POST['date_search'])) {

        $fdate = $_POST['fdate'] ?? '';
        $tdate = $_POST['tdate'] ?? '';

        if (!empty($fdate) && !empty($tdate)) {

            if ($fdate > $tdate) {
                echo "<script>alert('From Date cannot be greater than To Date');</script>";
            } else {
                $filterQuery .= " AND enrolldate BETWEEN ? AND ?";
                $params[] = $fdate;
                $params[] = $tdate;
                $types .= "ss";
            }
        }
    }

    // COURSE FILTER
    if (isset($_POST['course_search'])) {

        $coursename = trim($_POST['coursename'] ?? '');

        if (!empty($coursename)) {

            $filterQuery .= " AND coursename LIKE ?";
            $params[] = "%" . $coursename . "%";
            $types .= "s";
        }
    }
}

$filterQuery .= " ORDER BY enrolldate DESC";

$stmt = mysqli_prepare($conn, $filterQuery);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$recordCount = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin | Enroll History</title>

    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
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
                <div class="col-10 pt-3">

                    <div class="ocr-panel-heading">

                        <h1 class="page-head-line">
                            Enroll History
                        </h1>

                    </div>


                    <!-- SEARCH / FILTER BOX -->
                    <div class="card p-3 mb-4 shadow-sm">

                        <form method="post" action="enroll-history.php">

                            <div class="row g-3 align-items-end">

                                <!-- FROM DATE -->
                                <div class="col-md-3">

                                    <label class="form-label fw-bold">
                                        From Date
                                    </label>

                                    <input
                                        type="date"
                                        name="fdate"
                                        class="form-control"
                                        value="<?php echo htmlentities($fdate); ?>"
                                    >

                                </div>


                                <!-- TO DATE -->
                                <div class="col-md-3">

                                    <label class="form-label fw-bold">
                                        To Date
                                    </label>

                                    <input
                                        type="date"
                                        name="tdate"
                                        class="form-control"
                                        value="<?php echo htmlentities($tdate); ?>"
                                    >

                                </div>


                                <!-- DATE SEARCH -->
                                <div class="col-md-2">

                                    <button
                                        type="submit"
                                        name="date_search"
                                        class="btn btn-info w-100"
                                    >

                                        <i class="fa-solid fa-calendar-days"></i>
                                        Date

                                    </button>

                                </div>


                                <!-- COURSE SEARCH -->
                                <div class="col-md-3">

                                    <label class="form-label fw-bold">
                                        Course Name
                                    </label>

                                    <input
                                        type="text"
                                        name="coursename"
                                        class="form-control"
                                        placeholder="Search course"
                                        value="<?php echo htmlentities($coursename); ?>"
                                    >

                                </div>


                                <!-- COURSE SEARCH BUTTON -->
                                <div class="col-md-1">

                                    <button
                                        type="submit"
                                        name="course_search"
                                        class="btn btn-warning w-100"
                                    >

                                        <i class="fa-solid fa-search"></i>

                                    </button>

                                </div>

                            </div>

                        </form>


                        <!-- RESET FILTER -->
                        <div class="mt-3">

                            <a href="enroll-history.php" class="btn btn-secondary btn-sm">

                                <i class="fa-solid fa-rotate-left"></i>
                                Reset Filters

                            </a>

                        </div>

                    </div>


                    <!-- ENROLLMENT TABLE -->
                    <?php if ($recordCount == 0) { ?>

                        <div class="alert alert-warning text-center fw-bold ocr-table-empty">

                            <h4>No Enrollment Records Found</h4>

                        </div>

                    <?php } else { ?>


                        <div class="table-responsive">

                            <table class="table table-bordered table-striped align-middle">

                                <thead class="table-danger">

                                    <tr>

                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>DOB</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Age</th>
                                        <th>Country</th>
                                        <th>Qualification</th>
                                        <th>Course</th>
                                        <th>Enroll Date</th>
                                        <th>Action</th>

                                    </tr>

                                </thead>


                                <tbody>

                                    <?php

                                    $cnt = 1;

                                    while ($row = mysqli_fetch_assoc($result)) {

                                    ?>

                                        <tr>

                                            <td>
                                                <?php echo $cnt++; ?>
                                            </td>

                                            <td>
                                                <?php echo htmlentities($row['name']); ?>
                                            </td>

                                            <td>
                                                <?php
                                                echo htmlentities(
                                                    date("d/m/Y", strtotime($row['dob']))
                                                );
                                                ?>
                                            </td>

                                            <td>
                                                <?php echo htmlentities($row['email']); ?>
                                            </td>

                                            <td>
                                                <?php echo htmlentities($row['mobile']); ?>
                                            </td>

                                            <td>
                                                <?php echo htmlentities($row['age']); ?>
                                            </td>

                                            <td>
                                                <?php echo htmlentities($row['country']); ?>
                                            </td>

                                            <td>
                                                <?php echo htmlentities($row['qualification']); ?>
                                            </td>

                                            <td>
                                                <?php echo htmlentities($row['coursename']); ?>
                                            </td>

                                            <td>
                                                <?php
                                                echo htmlentities(
                                                    date("d/m/Y", strtotime($row['enrolldate']))
                                                );
                                                ?>
                                            </td>


                                            <!-- DELETE BUTTON -->
                                            <td>

                                                <a
                                                    href="enroll-history.php?id=<?php echo (int)$row['id']; ?>&del=delete"
                                                    onclick="return confirm('Are you sure you want to delete this record?');"
                                                    class="btn btn-danger btn-sm"
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
