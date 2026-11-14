
<?php
include('includes/connect.php');

// Fetch latest 5 courses
$courseQuery = "SELECT coursename, duration, noofseats
                FROM course
                ORDER BY creationdate DESC
                LIMIT 3";

$courseResult = mysqli_query($conn, $courseQuery);

// Fetch latest 5 news
$newsQuery = "SELECT newstitle, postingDate, newsDescription
              FROM news
              ORDER BY postingDate DESC
              LIMIT 2";

$newsResult = mysqli_query($conn, $newsQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Online Course Registration</title>

    <link rel="stylesheet" href="./assets/css/style.css">
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- HEADER -->
    <header class="header-section">

        <h1 class="header-title">
            <b>Online Course Registration</b>
        </h1>

        <p class="header-para">
            Life is nothing without knowledge
        </p>

    </header>


    <!-- NAVBAR -->
    <section class="ocr-navbar-section">

        <div class="ocr-nav-wrapper">

            <ul id="menu-top" class="ocr-menu">

                <li class="ocr-menu-item">
                    <a href="index.php" class="ocr-link">Home</a>
                </li>

                <li class="ocr-menu-item">
                    <a href="admin/adminlogin.php" class="ocr-link">Admin</a>
                </li>

                <li class="ocr-menu-item ocr-dropdown">

                    <a class="ocr-dropbtn ocr-link" href="user/login.php">
                        Student
                    </a>

                    <div class="ocr-dropdown-content">

                        <a href="user/login.php" class="ocr-dropdown-link">
                            Login
                        </a>

                        <a href="user/register.php" class="ocr-dropdown-link">
                            Register
                        </a>

                    </div>

                </li>

            </ul>

        </div>

    </section>


    <!-- MAIN CONTENT -->
    <main class="home-main">

        <!-- HOME PAGE IMAGE -->
        <div class="ocr-footerimg">

            <img
                src="img/online-course.png"
                alt="Online Courses Illustration"
                class="home-course-image">

        </div>


        <!-- MOBILE ONLY CONTENT -->
        <div class="mobile-latest-section">


            <!-- LATEST COURSES -->
            <section class="latest-section">

                <h2 class="latest-heading">
                    Latest Courses
                </h2>

                <div class="latest-list">

                    <?php
                    if ($courseResult && mysqli_num_rows($courseResult) > 0) {

                        while ($course = mysqli_fetch_assoc($courseResult)) {
                    ?>

                            <div class="latest-card">

                                <h3>
                                    <?php echo htmlspecialchars($course['coursename']); ?>
                                </h3>

                                <p>
                                    <strong>Duration:</strong>
                                    <?php echo htmlspecialchars($course['duration']); ?>
                                </p>

                                <p>
                                    <strong>Available Seats:</strong>
                                    <?php echo htmlspecialchars($course['noofseats']); ?>
                                </p>

                            </div>

                    <?php
                        }

                    } else {
                    ?>

                        <p class="no-data">
                            No courses available.
                        </p>

                    <?php } ?>

                </div>

            </section>


            <!-- LATEST NEWS -->
            <section class="latest-section">

                <h2 class="latest-heading">
                    Latest News
                </h2>

                <div class="latest-list">

                    <?php
                    if ($newsResult && mysqli_num_rows($newsResult) > 0) {

                        while ($news = mysqli_fetch_assoc($newsResult)) {
                    ?>

                            <div class="latest-card">

                                <h3>
                                    <?php echo htmlspecialchars($news['newstitle']); ?>
                                </h3>

                                <p class="news-date">
                                    <?php echo htmlspecialchars($news['postingDate']); ?>
                                </p>

                                <p class="news-description">
                                    <?php
                                    echo nl2br(
                                        htmlspecialchars($news['newsDescription'])
                                    );
                                    ?>
                                </p>

                            </div>

                    <?php
                        }

                    } else {
                    ?>

                        <p class="no-data">
                            No news available.
                        </p>

                    <?php } ?>

                </div>

            </section>


        </div>

    </main>


    <!-- FOOTER -->
    <?php include('includes/footer.php'); ?>


</body>

</html>