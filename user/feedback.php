
<?php
include('../includes/connect.php');

if (isset($_POST['submit'])) {

    $uname = trim($_POST['uname']);
    $uemail = trim($_POST['uemail']);
    $feedback = trim($_POST['msg']);

    // EMAIL VALIDATION
    if (!filter_var($uemail, FILTER_VALIDATE_EMAIL)) {

        echo "<script>
                alert('Enter a valid email address!');
              </script>";
    }

    // INSERT FEEDBACK
    else {

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO feedback (username, useremail, feedbackmsg)
             VALUES (?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sss",
            $uname,
            $uemail,
            $feedback
        );

        $res = mysqli_stmt_execute($stmt);

        if ($res) {

            echo "<script>
                    alert('Feedback entered successfully');
                    window.location.href = 'feedback.php';
                  </script>";
            exit();

        } else {

            echo "<script>
                    alert('Cannot send feedback');
                  </script>";
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

    <title>Feedback</title>

    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body class="d-flex flex-column min-vh-100">

    <!-- HEADER -->
    <?php include('../includes/header.php'); ?>

    <!-- USER MENUBAR -->
    <?php include('../includes/user-menubar.php'); ?>

    <!-- MAIN CONTENT -->
    <main class="flex-grow-1">

        <div class="container mt-3">

            <h1 class="page-head-line">Feedback</h1>

            <div class="row justify-content-center">

                <div class="col-6">

                    <div class="card">

                        <div class="card-body">

                            <form name="feedback" method="post" action="feedback.php">

                                <!-- NAME -->
                                <div class="mb-3 form-group">

                                    <label for="uname">
                                        <b>Your Name:</b>
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="uname"
                                        name="uname"
                                        placeholder="Enter Your Name"
                                        required
                                    >

                                </div>

                                <!-- EMAIL -->
                                <div class="mb-3 form-group">

                                    <label for="uemail">
                                        <b>Your Email:</b>
                                    </label>

                                    <input
                                        type="email"
                                        class="form-control"
                                        id="uemail"
                                        name="uemail"
                                        placeholder="Enter Your Email"
                                        required
                                    >

                                </div>

                                <!-- FEEDBACK -->
                                <div class="mb-3 form-group">

                                    <textarea
                                        style="resize:none"
                                        name="msg"
                                        rows="10"
                                        cols="10"
                                        class="form-control"
                                        id="feedback"
                                        placeholder="Enter Your Feedback"
                                        required
                                    ></textarea>

                                </div>

                                <hr>

                                <!-- SUBMIT BUTTON -->
                                <button
                                    type="submit"
                                    name="submit"
                                    class="btn btn-warning"
                                >
                                    Upload
                                </button>

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
