
<?php 
session_start();
include('../includes/connect.php');

if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

// DELETE FEEDBACK
if (isset($_GET['del'], $_GET['id'])) {
    $did = intval($_GET['id']);

    mysqli_query($conn, "DELETE FROM feedback WHERE fid='$did'");

    header('location:feedbackview.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Student Feedbacks</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css">
</head>

<body class="d-flex flex-column min-vh-100">

<?php include('../includes/header.php'); ?>

<div class="container-fluid flex-grow-1">
    <div class="row min-vh-100">

        <!-- SIDEBAR -->
        <div class="col-2 ocr-sidebar">
            <?php include('includes/sidebar.php'); ?>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-10 p-4">

            <h1 class="page-head-line">Feedbacks</h1>

            <?php  
                $sql = mysqli_query($conn, "SELECT * FROM feedback");
                $recordCount = mysqli_num_rows($sql);

                if ($recordCount == 0) { 
            ?>

                <div class="ocr-no-data-box">
                    NO FEEDBACK RECORDS FOUND
                </div>

            <?php } else { ?>

                <div class="table-responsive table-bordered mt-3 ocr-table-wrapper">

                    <table class="table table-bordered table-striped ocr-table">

                        <thead class="table-danger">
                            <tr>
                                <th>S.No</th>
                                <th>Student Name</th>
                                <th>Student Email</th>
                                <th>Date of Feedback</th>
                                <th>Feedback Message</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                                $cnt = 1;

                                while ($row = mysqli_fetch_array($sql)) {
                            ?>

                            <tr>
                                <td><?php echo $cnt++; ?></td>

                                <td>
                                    <?php echo htmlentities($row['username']); ?>
                                </td>

                                <td>
                                    <?php echo htmlentities($row['useremail']); ?>
                                </td>

                                <td>
                                    <?php echo htmlentities(date("d/m/Y", strtotime($row['dateoffeedback']))); ?>
                                </td>

                                <td>
                                    <?php echo htmlentities($row['feedbackmsg']); ?>
                                </td>

                                <td>
                                    <a href="feedbackview.php?id=<?php echo $row['fid']; ?>&del=delete"
                                       onclick="return confirm('Are you sure you want to delete this feedback?');"
                                       class="btn btn-danger btn-sm">
                                        <i class="fa-solid fa-trash"></i> Delete
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

<!-- FOOTER -->
<?php include('../includes/footer.php'); ?>

<script src="../assets/js/bootstrap.js"></script>

</body>
</html>
