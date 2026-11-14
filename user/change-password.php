
<?php
session_start();
include('../includes/connect.php');

// CHECK LOGIN
if (!isset($_SESSION['login']) || empty($_SESSION['login'])) {
    header('location:login.php');
    exit();
}

$email = $_SESSION['email'];

if (isset($_POST['submit'])) {

    $current = $_POST['cpass'];
    $newpass = $_POST['newpass'];
    $cnfpass = $_POST['cnfpass'];

    // CHECK EMPTY FIELDS
    if (empty($current) || empty($newpass) || empty($cnfpass)) {

        echo "<script>
                alert('Please fill all required fields!');
              </script>";
    }

    // CHECK PASSWORD MATCH
    elseif ($newpass !== $cnfpass) {

        echo "<script>
                alert('New Password and Confirm Password do not match!');
              </script>";
    }

    else {

        // FETCH CURRENT PASSWORD
        $stmt = mysqli_prepare(
            $conn,
            "SELECT pwd FROM reg WHERE email = ? LIMIT 1"
        );

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        if ($row && password_verify($current, $row['pwd'])) {

            // HASH NEW PASSWORD
            $hashedPassword = password_hash($newpass, PASSWORD_DEFAULT);

            // UPDATE PASSWORD
            $update = mysqli_prepare(
                $conn,
                "UPDATE reg SET pwd = ? WHERE email = ?"
            );

            mysqli_stmt_bind_param(
                $update,
                "ss",
                $hashedPassword,
                $email
            );

            if (mysqli_stmt_execute($update)) {

                echo "<script>
                        alert('Password Changed Successfully!!!');
                        window.location.href = 'my-profile.php';
                      </script>";
                exit();

            } else {

                echo "<script>
                        alert('Password Update Failed. Try Again!');
                      </script>";
            }

        } else {

            echo "<script>
                    alert('Invalid Current Password!');
                  </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Change Password</title>

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

        <div class="container change-pass-container">

            <div class="row justify-content-center">

                <h1 class="page-head-line custom-changepass-title mt-3">
                    Change Password
                </h1>

                <div class="col-md-6">

                    <div class="card custom-changepass-card">

                        <div class="card-body">

                            <form
                                name="chngpwd"
                                method="post"
                                action="change-password.php"
                                onsubmit="return validatePassword();"
                            >

                                <!-- EMAIL -->
                                <div class="mb-3 form-group custom-field-email">

                                    <label class="form-label">
                                        <b>Enter Email:</b>
                                    </label>

                                    <input
                                        type="email"
                                        class="form-control"
                                        value="<?php echo htmlentities($email); ?>"
                                        readonly
                                    >

                                </div>


                                <!-- CURRENT PASSWORD -->
                                <div class="mb-3 form-group custom-field-cpass">

                                    <label class="form-label">
                                        <b>Current Password:</b>
                                    </label>

                                    <input
                                        type="password"
                                        name="cpass"
                                        class="form-control"
                                        placeholder="Current Password"
                                        required
                                    >

                                </div>


                                <!-- NEW PASSWORD -->
                                <div class="mb-3 form-group custom-field-newpass">

                                    <label class="form-label">
                                        <b>New Password:</b>
                                    </label>

                                    <input
                                        type="password"
                                        name="newpass"
                                        class="form-control"
                                        placeholder="New Password"
                                        required
                                    >

                                </div>


                                <!-- CONFIRM PASSWORD -->
                                <div class="mb-3 form-group custom-field-cnfpass">

                                    <label class="form-label">
                                        <b>Confirm Password:</b>
                                    </label>

                                    <input
                                        type="password"
                                        name="cnfpass"
                                        class="form-control"
                                        placeholder="Confirm Password"
                                        required
                                    >

                                </div>


                                <!-- SUBMIT BUTTON -->
                                <button
                                    type="submit"
                                    name="submit"
                                    class="btn btn-primary custom-changepass-btn"
                                >
                                    Change Password
                                </button>

                            </form>

                        </div>

                    </div>

                    <br>

                </div>

            </div>

        </div>

    </main>


    <!-- FOOTER -->
    <?php include('../includes/footer.php'); ?>


    <!-- PASSWORD VALIDATION -->
    <script>

        function validatePassword() {

            let cpass = document.chngpwd.cpass.value.trim();

            let newpass = document.chngpwd.newpass.value.trim();

            let cnfpass = document.chngpwd.cnfpass.value.trim();

            if (cpass === "") {
                alert("Current Password field is empty!");
                return false;
            }

            if (newpass === "") {
                alert("New Password field is empty!");
                return false;
            }

            if (cnfpass === "") {
                alert("Confirm Password field is empty!");
                return false;
            }

            if (newpass !== cnfpass) {
                alert("New Password and Confirm Password do not match!");
                return false;
            }

            if (cpass === newpass) {
                alert("New Password must be different from Current Password!");
                return false;
            }

            return true;
        }

    </script>

    <script src="../assets/js/bootstrap.js"></script>

</body>
</html>
