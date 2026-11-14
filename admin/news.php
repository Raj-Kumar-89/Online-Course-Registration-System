
<?php
session_start();
include('../includes/connect.php');

if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

// INSERT NEWS
if (isset($_POST['submit'])) {

    $ntitle = $_POST['newstitle'];
    $ndescription = $_POST['description'];

    $ret = mysqli_query($conn, "
        INSERT INTO news (newstitle, newsDescription)
        VALUES ('$ntitle', '$ndescription')
    ");

    if ($ret) {
        echo "<script>
                alert('News added successfully!!!');
                window.location.href = 'news.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Something went wrong. Please try again!!!');
                window.location.href = 'news.php';
              </script>";
        exit();
    }
}

// DELETE NEWS
if (isset($_GET['del'], $_GET['id'])) {

    $nid = intval($_GET['id']);

    mysqli_query($conn, "DELETE FROM news WHERE id = '$nid'");

    echo "<script>
            alert('News deleted successfully');
            window.location.href = 'news.php';
          </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin | News</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css">
</head>

<body class="d-flex flex-column min-vh-100">

<?php include('../includes/header.php'); ?>

<div class="container-fluid ocr-container flex-grow-1">

    <div class="row min-vh-100">

        <!-- SIDEBAR -->
        <div class="col-2 ocr-sidebar">
            <?php include('includes/sidebar.php'); ?>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-10">

            <div class="container">

                <br>

                <!-- PAGE TITLE -->
                <h1 class="page-head-line">News</h1>

                <!-- ADD NEWS FORM -->
                <div class="row justify-content-center">

                    <div class="col-md-6">

                        <div class="panel panel-default">

                            <div class="panel-body">

                                <br>

                                <form name="dept" method="post" class="ocr-news-form">

                                    <div class="form-group">
                                        <label><b>News Title</b></label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            name="newstitle"
                                            placeholder="News Title"
                                            required
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label><b>News Description</b></label>

                                        <textarea
                                            class="form-control"
                                            name="description"
                                            placeholder="News Description"
                                            required
                                        ></textarea>
                                    </div>

                                    <br>

                                    <button type="submit" name="submit" class="btn btn-primary">
                                        Submit
                                    </button>

                                </form>

                            </div>
                        </div>

                    </div>
                </div>

                <br>

                <!-- MANAGE NEWS -->
                <div class="row">

                    <div class="col-md-12">

                        <div class="panel panel-default">

                            <div class="panel-heading">
                                <h1 class="page-head-line">Manage News</h1>
                            </div>

                            <div class="panel-body">

                                <?php
                                $sql = mysqli_query($conn, "SELECT * FROM news");
                                
                                if (mysqli_num_rows($sql) == 0) {
                                ?>

                                    <div class="ocr-empty-table">
                                        NO NEWS RECORDS FOUND
                                    </div>

                                <?php } else { ?>

                                    <div class="table-responsive table-bordered ocr-news-table">

                                        <table class="table table-bordered table-striped">

                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>News Title</th>
                                                    <th>News Description</th>
                                                    <th>Creation Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                <?php
                                                $cnt = 1;

                                                while ($row = mysqli_fetch_array($sql)) {
                                                ?>

                                                <tr>

                                                    <td>
                                                        <?php echo $cnt++; ?>
                                                    </td>

                                                    <td>
                                                        <?php echo htmlentities($row['newstitle']); ?>
                                                    </td>

                                                    <td>
                                                        <?php echo htmlentities($row['newsDescription']); ?>
                                                    </td>

                                                    <td>
                                                        <?php echo htmlentities(date("d/m/Y", strtotime($row['postingDate']))); ?>
                                                    </td>

                                                    <td>
                                                        <a
                                                            href="news.php?id=<?php echo $row['id']; ?>&del=delete"
                                                            onclick="return confirm('Are you sure you want to delete?');"
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
                </div>

            </div>

        </div>

    </div>

</div>

<!-- FOOTER -->
<?php include('../includes/footer.php'); ?>

<script src="../assets/js/bootstrap.js"></script>

</body>
</html>
