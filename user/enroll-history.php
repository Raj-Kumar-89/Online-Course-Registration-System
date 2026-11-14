
<?php
session_start();
include('../includes/connect.php');

if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit();
}

// FILTER LOGIC
$filterQuery = "SELECT * FROM courseenrolls WHERE 1=1";
$params = [];
$types = "";

// Date filter
if (isset($_POST['date_search'])) {
    if (!empty($_POST['fdate']) && !empty($_POST['tdate'])) {
        $from = $_POST['fdate'];
        $to = $_POST['tdate'];

        $filterQuery .= " AND enrolldate BETWEEN ? AND ?";
        $params[] = $from;
        $params[] = $to;
        $types .= "ss";
    }
}

// Course filter
if (isset($_POST['course_search'])) {
    if (!empty($_POST['coursename'])) {
        $course = "%" . $_POST['coursename'] . "%";

        $filterQuery .= " AND coursename LIKE ?";
        $params[] = $course;
        $types .= "s";
    }
}

// Execute query
$stmt = mysqli_prepare($conn, $filterQuery);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student | Enroll History</title>

    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php include('../includes/header.php'); ?>
    <?php include('../includes/user-menubar.php'); ?>

    <main class="content-wrapper flex-grow-1">

        <div class="container">

            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-head-line mt-3">Enroll History</h1>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="row">
                <div class="col-md-12">

                    <div class="filter-box">

                        <!-- LEFT SIDE (Date Filter) -->
                        <form method="post" class="left-filter">

                            <input
                                type="date"
                                name="fdate"
                                class="form-control"
                                value="<?php echo isset($_POST['fdate']) ? htmlentities($_POST['fdate']) : ''; ?>"
                            >

                            <input
                                type="date"
                                name="tdate"
                                class="form-control"
                                value="<?php echo isset($_POST['tdate']) ? htmlentities($_POST['tdate']) : ''; ?>"
                            >

                            <button type="submit" name="date_search" class="btn btn-info">
                                Search by Date
                            </button>

                        </form>

                        <!-- RIGHT SIDE (Course Search) -->
                        <form method="post" class="right-filter">

                            <input
                                type="text"
                                name="coursename"
                                class="form-control"
                                placeholder="Search Course"
                                value="<?php echo isset($_POST['coursename']) ? htmlentities($_POST['coursename']) : ''; ?>"
                            >

                            <button type="submit" name="course_search" class="btn btn-warning">
                                Search Course
                            </button>

                        </form>

                    </div>

                </div>
            </div>

            <!-- Table Section -->
            <div class="row justify-content-center mt-3">
                <div class="col-md-12">

                    <div class="panel panel-default">
                        <div class="panel-body">

                            <div class="table-responsive table-bordered">

                                <table class="table table-bordered table-striped">

                                    <thead class="table-danger">
                                        <tr>
                                            <th>S.No</th>
                                            <th>Student Name</th>
                                            <th>Date Of Birth</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Age</th>
                                            <th>Country</th>
                                            <th>Qualification</th>
                                            <th>Course Name</th>
                                            <th>Enroll Date</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php
                                        $cnt = 1;

                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                        ?>

                                                <tr>
                                                    <td><?php echo $cnt; ?></td>

                                                    <td><?php echo htmlentities($row['name']); ?></td>

                                                    <td>
                                                        <?php echo htmlentities(date("d/m/Y", strtotime($row['dob']))); ?>
                                                    </td>

                                                    <td><?php echo htmlentities($row['email']); ?></td>

                                                    <td><?php echo htmlentities($row['mobile']); ?></td>

                                                    <td><?php echo htmlentities($row['age']); ?></td>

                                                    <td><?php echo htmlentities($row['country']); ?></td>

                                                    <td><?php echo htmlentities($row['qualification']); ?></td>

                                                    <td><?php echo htmlentities($row['coursename']); ?></td>

                                                    <td>
                                                        <?php echo htmlentities(date("d/m/Y", strtotime($row['enrolldate']))); ?>
                                                    </td>
                                                </tr>

                                        <?php
                                                $cnt++;
                                            }
                                        } else {
                                        ?>

                                            <tr>
                                                <td colspan="10" class="text-center">
                                                    <div class="alert alert-warning m-0">
                                                        <b>No Records Found!</b>
                                                    </div>
                                                </td>
                                            </tr>

                                        <?php } ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>

    </main>

    <script src="../assets/js/bootstrap.js"></script>

    <?php include('../includes/footer.php'); ?>

</body>
</html>

<?php
mysqli_stmt_close($stmt);
?>
