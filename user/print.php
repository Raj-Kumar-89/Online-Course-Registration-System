<?php
session_start();
include('../includes/connect.php');

/* Session & parameter validation */
if (!isset($_SESSION['email']) || !isset($_GET['enroll_id'])) {
    header('location:index.php');
    exit();
}

$enroll_id = intval($_GET['enroll_id']);

/* Fetch specific enrolled course (security check included) */
$query = mysqli_query(
    $conn,
    "SELECT * FROM courseenrolls 
     WHERE id='$enroll_id' 
     AND email='" . $_SESSION['email'] . "'"
);

if (mysqli_num_rows($query) == 0) {
    echo "Invalid enrollment.";
    exit();
}

$row = mysqli_fetch_array($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print | Enrollment</title>

    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<div class="invoice-container">

    <div class="invoice-header">
        <h3 class="invoice-title">Course Enrollment Receipt</h3>
        <p class="text-muted">Official Enrollment Document</p>
    </div>

    <table class="info-table">

        <tr>
            <td><b>Student Name</b></td>
            <td><?php echo htmlentities($row['name']); ?></td>
        </tr>

        <tr>
            <td><b>Date of Birth</b></td>
            <td><?php echo date("d/m/Y", strtotime($row['dob'])); ?></td>
        </tr>

        <tr>
            <td><b>Email</b></td>
            <td><?php echo htmlentities($row['email']); ?></td>
        </tr>

        <tr>
            <td><b>Mobile</b></td>
            <td><?php echo htmlentities($row['mobile']); ?></td>
        </tr>

        <tr>
            <td><b>Age</b></td>
            <td><?php echo htmlentities($row['age']); ?></td>
        </tr>

        <tr>
            <td><b>Country</b></td>
            <td><?php echo htmlentities($row['country']); ?></td>
        </tr>

        <tr>
            <td><b>Qualification</b></td>
            <td><?php echo htmlentities($row['qualification']); ?></td>
        </tr>

        <tr>
            <td><b>Course Name</b></td>
            <td><?php echo htmlentities($row['coursename']); ?></td>
        </tr>

        <tr>
            <td><b>Enroll Date</b></td>
            <td><?php echo date("d/m/Y", strtotime($row['enrolldate'])); ?></td>
        </tr>

    </table>

    <div class="text-center">
        <button onclick="window.print()" class="btn btn-primary print-btn">
            <i class="fa-solid fa-print"></i> Print
        </button>
    </div>

</div>

</body>
</html>