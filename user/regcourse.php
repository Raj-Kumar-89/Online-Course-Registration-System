
<?php
session_start();
include('../includes/connect.php');

if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:login.php');
    exit();
}

if (isset($_POST['submit'])) {

    $name          = $_POST['name'];
    $dob           = $_POST['dob'];
    $email         = $_POST['email'];
    $num           = $_POST['mob'];
    $age           = $_POST['age'];
    $country       = $_POST['country'];
    $qualification = $_POST['qua'];
    $course        = $_POST['coursename'];

    // EMAIL VALIDATION
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
                alert('Invalid Email Address!');
                window.history.back();
              </script>";
        exit();
    }

    // MOBILE VALIDATION
    if (!preg_match('/^[6-9][0-9]{9}$/', $num)) {
        echo "<script>
                alert('Invalid Mobile Number! Enter a valid 10-digit number.');
                window.history.back();
              </script>";
        exit();
    }

    // INSERT QUERY
    $stmt = mysqli_prepare($conn, "
        INSERT INTO courseenrolls
        (name, dob, email, mobile, age, country, qualification, coursename)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    mysqli_stmt_bind_param(
        $stmt,
        "ssssisss",
        $name,
        $dob,
        $email,
        $num,
        $age,
        $country,
        $qualification,
        $course
    );

    $sql = mysqli_stmt_execute($stmt);

    if ($sql) {
        echo "<script>
                alert('Registered Successfully!');
                window.location.href='my-profile.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Registration Failed. Try Again!');
                window.history.back();
              </script>";
        exit();
    }
}

// GET COURSE ID
$cid = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$cid || $cid <= 0) {
    echo "<script>
            alert('Invalid Course Selection!');
            window.location.href='my-profile.php';
          </script>";
    exit();
}

// FETCH COURSE DETAILS
$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM course WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $cid);
mysqli_stmt_execute($stmt);

$courseResult = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($courseResult) != 1) {
    echo "<script>
            alert('Course not found!');
            window.location.href='my-profile.php';
          </script>";
    exit();
}

$courseData = mysqli_fetch_assoc($courseResult);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register Course</title>

    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- HEADER -->
    <?php include('../includes/header.php'); ?>

    <!-- USER MENUBAR -->
    <?php include('../includes/user-menubar.php'); ?>

    <!-- MAIN CONTENT -->
    <main class="flex-grow-1">

        <div class="container">

            <h1 class="page-head-line">
                Course Registration
            </h1>

            <br>

            <center>

                <form method="post" class="reg-form-container">

                    <!-- PERSONAL DETAILS -->
                    <div class="personal">

                        <h5 class="reg-heading">
                            <b>Personal Details</b>
                        </h5>

                        <label>
                            <b>Enter Name: <span class="text-danger">*</span></b>
                        </label>
                        <br>

                        <input
                            type="text"
                            name="name"
                            class="reg-input"
                            required
                        >
                        <br>

                        <label>
                            <b>Enter DOB: <span class="text-danger">*</span></b>
                        </label>
                        <br>

                        <input
                            type="date"
                            name="dob"
                            class="reg-input"
                            required
                        >
                        <br>

                        <label>
                            <b>Enter Email: <span class="text-danger">*</span></b>
                        </label>
                        <br>

                        <input
                            type="email"
                            name="email"
                            class="reg-input"
                            required
                        >
                        <br>

                        <label>
                            <b>Enter Mobile: <span class="text-danger">*</span></b>
                        </label>
                        <br>

                        <input
                            type="tel"
                            name="mob"
                            class="reg-input"
                            pattern="[6-9]{1}[0-9]{9}"
                            minlength="10"
                            maxlength="10"
                            title="Enter valid 10-digit mobile number"
                            required
                        >
                        <br>

                        <label>
                            <b>Enter Age: <span class="text-danger">*</span></b>
                        </label>
                        <br>

                        <input
                            type="number"
                            name="age"
                            class="reg-input"
                            required
                        >
                        <br>

                        <label>
                            <b>Enter Country: <span class="text-danger">*</span></b>
                        </label>
                        <br>

                        <input
                            type="text"
                            name="country"
                            class="reg-input"
                            required
                        >
                        <br>

                    </div>

                    <br>

                    <!-- EDUCATIONAL DETAILS -->
                    <div class="educational">

                        <h5 class="reg-heading">
                            <b>Educational Details</b>
                        </h5>

                        <label>
                            <b>Enter Qualification: <span class="text-danger">*</span></b>
                        </label>
                        <br>

                        <input
                            type="text"
                            name="qua"
                            class="reg-input"
                            required
                        >
                        <br>

                    </div>

                    <br>

                    <!-- COURSE DETAILS -->
                    <div class="course">

                        <h5 class="reg-heading">
                            <b>Course Details</b>
                        </h5>

                        <label>
                            <b>Select Course Name:</b>
                        </label>
                        <br>

                        <select
                            name="coursename"
                            class="reg-select"
                            required
                        >
                            <option value="<?php echo htmlentities($courseData['coursename']); ?>">
                                <?php echo htmlentities($courseData['coursename']); ?>
                            </option>
                        </select>

                        <br>

                        <label>
                            <b>Course Code:</b>
                        </label>
                        <br>

                        <input
                            type="text"
                            class="reg-input"
                            value="<?php echo htmlentities($courseData['coursecode']); ?>"
                            readonly
                        >

                        <br>

                        <label>
                            <b>No of Seats:</b>
                        </label>
                        <br>

                        <input
                            type="text"
                            class="reg-input"
                            value="<?php echo htmlentities($courseData['noofseats']); ?>"
                            readonly
                        >

                        <br>

                        <label>
                            <b>Course Duration:</b>
                        </label>
                        <br>

                        <input
                            type="text"
                            class="reg-input"
                            value="<?php echo htmlentities($courseData['duration']); ?>"
                            readonly
                        >

                        <br>

                        <label>
                            <b>Last Date for Register:</b>
                        </label>
                        <br>

                        <input
                            type="text"
                            class="reg-input"
                            value="<?php echo htmlentities($courseData['lastdate']); ?>"
                            readonly
                        >

                    </div>

                    <br>

                    <!-- REGISTER BUTTON -->
                    <div class="buttonreg">

                        <button
                            type="submit"
                            name="submit"
                            class="reg-button"
                        >
                            Register
                        </button>

                    </div>

                </form>

            </center>

        </div>

    </main>

    <!-- FOOTER -->
    <?php include('../includes/footer.php'); ?>

    <script src="../assets/js/bootstrap.js"></script>

</body>
</html>