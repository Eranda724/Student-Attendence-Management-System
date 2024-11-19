<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Management System - Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
    background-image: url('image1.jpg'); /* Add your background image path here */
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand,
        .nav-link,
        .dropdown-item {
            color: #ffffff !important;
        }

        .navbar-brand:hover,
        .nav-link:hover,
        .dropdown-item:hover {
            color: #adb5bd !important;
        }

        .container {
            margin-top: 30px;
        }

        .user-info {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .user-info h3 {
            margin-bottom: 20px;
        }

        .semester-buttons .btn {
            margin: 5px;
            color: #050505;
            background-color: #eb3131;
        }

        table {
            width: 100%;
            margin-bottom: 30px;
        }

        th {
            background-color: #343a40;
            color: #ffffff;
        }

        .total {
            font-weight: bold;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .semester-buttons .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body>
    <?php require_once('../connection.php'); ?>

    <?php
    // Handle redirection for edit action
    if (isset($_POST['edit'])) {
        header("Location: ../../index.php");
        exit();
    }

    // Retrieve user_id from GET or POST
    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
    } elseif (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];
    } else {
        // Handle missing user_id
        die("User ID not specified.");
    }

    $user_id = mysqli_real_escape_string($connection, $user_id);

    // Fetch user information
    $query = "SELECT `email`, `first_name`, `middle_name`, `last_name` FROM `user` WHERE `user_id` = $user_id";
    $record = mysqli_query($connection, $query);

    if ($record) {
        $result = mysqli_fetch_assoc($record);
        $name = htmlspecialchars($result['first_name'] . " " . $result['middle_name'] . " " . $result['last_name']);
        $email = htmlspecialchars($result['email']);
    } else {
        echo "<div class='alert alert-danger'>Error fetching user data.</div>";
    }

    // Fetch student details
    $query = "SELECT `current_level`, `batch_no`,`reg_no`,`index_no` FROM `student` WHERE `student_id`='$user_id'";
    $record2 = mysqli_query($connection, $query);
    if ($record2) {
        $result = mysqli_fetch_assoc($record2);
        $current_level = htmlspecialchars($result['current_level']);
        $batch_no = htmlspecialchars($result['batch_no']);
        $reg_no = htmlspecialchars($result['reg_no']);
        $index_no = htmlspecialchars($result['index_no']);
    }
    ?>

    <?php
    // Handle navigation dropdown actions
    if (isset($_POST['wh'])) {
        $selectedOption = $_POST['wh'];                                                                                                                                 
        switch ($selectedOption) {
            case "dashboard":
                header("Location: student.php");
                exit();
            case "pw_reset":
                $loc = "reset_pw.php?user_id=" . urlencode($user_id);
                header("Location: ../../reset_pw.php");
                exit();
            case "logout":
                header("Location: ../../index.php");
                exit();
            default:
                break;
        }
    }
    ?>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Attendance System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <form method="post" class="d-flex align-items-center">
                            <select class="form-select" name="wh" onchange="this.form.submit()">
                                <option selected disabled>Welcome, <?php echo $name; ?></option>
                                <option value="dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</option>
                                <option value="pw_reset"><i class="fas fa-key"></i> Password Reset</option>
                                <option value="logout"><i class="fas fa-sign-out-alt"></i> Log Out</option>
                            </select>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container">
        <!-- User Information Section -->
        <div class="user-info">
            <h3><i class="fas fa-user-circle"></i> Student Details</h3>
            <p><strong>Name:</strong> <?php echo $name; ?></p>
            <p><strong>Current Level:</strong> <?php echo $current_level; ?></p>
            <p><strong>Batch No:</strong> <?php echo $batch_no; ?></p>
            <p><strong>Registration No:</strong> <?php echo $reg_no; ?></p>
            <p><strong>Index No:</strong> <?php echo $index_no; ?></p>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
            <form method="post" class="mt-3">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                <button type="submit" name="edit" class="btn btn-primary"><i class="fas fa-edit"></i> Edit Profile</button>
            </form>
        </div>

        <!-- Semester Selection Section -->
        <div class="semester-selection mb-4">
            <h4>Select Semester:</h4>
            <div class="semester-buttons d-flex flex-wrap">
                <?php
                for ($i = 1; $i <= 8; $i++) {
                    echo '
                    <form action="student.php" method="post" class="me-2 mb-2">
                        <input type="hidden" name="user_id" value="' . htmlspecialchars($user_id) . '">
                        <button type="submit" name="sem" value="Semester ' . $i . '" class="btn btn-outline-secondary">
                            Semester ' . $i . '
                        </button>
                    </form>
                    ';
                }
                ?>
            </div>
        </div>

        <!-- Attendance Tables Section -->
        <?php
        if (isset($_POST['sem'])) {
            $selected_sem = null;
            $sem = $_POST['sem'];
            switch ($sem) {
                case "Semester 1":
                    $selected_sem = 1;
                    break;
                case "Semester 2":
                    $selected_sem = 2;
                    break;
                case "Semester 3":
                    $selected_sem = 3;
                    break;
                case "Semester 4":
                    $selected_sem = 4;
                    break;
                case "Semester 5":
                    $selected_sem = 5;
                    break;
                case "Semester 6":
                    $selected_sem = 6;
                    break;
                case "Semester 7":
                    $selected_sem = 7;
                    break;
                case "Semester 8":
                    $selected_sem = 8;
                    break;
                default:
                    $selected_sem = null;
                    break;
            }

            if ($selected_sem !== null) {
                // Prepare and execute the first query using prepared statements
                $query1 = "SELECT DISTINCT C.course_id, C.course_code 
                           FROM enroll AS E 
                           JOIN course AS C ON E.course_id = C.course_id 
                           WHERE E.student_id = ? AND C.semester = ?";
                $stmt1 = mysqli_prepare($connection, $query1);
                mysqli_stmt_bind_param($stmt1, "ii", $user_id, $selected_sem);
                mysqli_stmt_execute($stmt1);
                $record1 = mysqli_stmt_get_result($stmt1);

                if (mysqli_num_rows($record1) > 0) {
                    while ($c = mysqli_fetch_assoc($record1)) {
                        $c_id = htmlspecialchars($c['course_id']);
                        $c_code = htmlspecialchars($c['course_code']);

                        // Fetch attendance records
                        $query2 = "SELECT S.date, S.start_time, A.attend 
                                   FROM schedule AS S 
                                   JOIN attendance AS A ON S.schedule_id = A.schedule_id 
                                   WHERE S.course_id = ? AND A.student_id = ?";
                        $stmt2 = mysqli_prepare($connection, $query2);
                        mysqli_stmt_bind_param($stmt2, "ii", $c_id, $user_id);
                        mysqli_stmt_execute($stmt2);
                        $record2 = mysqli_stmt_get_result($stmt2);

                        $attendance_array = [];
                        $day_count = 0;
                        $p_count = 0;

                        while ($day = mysqli_fetch_assoc($record2)) {
                            $day_count++;
                            $date = htmlspecialchars($day['date']);
                            $time = htmlspecialchars($day['start_time']);
                            $attendance_status = $day['attend'] ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>';
                            $attendance_array[] = $attendance_status;

                            // Increment p_count if attendance is 1
                            if ($day['attend'] == 1) {
                                $p_count++;
                            }
                        }

                        // Calculate total attendance
                        $total_attendance = $p_count . '/' . $day_count;

                        // Display the attendance table
                        echo '
                        <div class="card mb-4">
                            <div class="card-header bg-dark text-white">
                                <strong>Course Code:</strong> ' . $c_code . '
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date & Time</th>
                                                <th>Attendance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                        ';

                        // Fetch schedule and attendance again to display
                        mysqli_stmt_execute($stmt2);
                        $record2 = mysqli_stmt_get_result($stmt2);
                        while ($day = mysqli_fetch_assoc($record2)) {
                            $date = htmlspecialchars($day['date']);
                            $time = htmlspecialchars($day['start_time']);
                            $attendance_status = $day['attend'] ? '<i class="fas fa-check-circle text-success"></i> Present' : '<i class="fas fa-times-circle text-danger"></i> Absent';
                            echo '
                                <tr>
                                    <td>' . $date . ' ' . $time . '</td>
                                    <td>' . $attendance_status . '</td>
                                </tr>
                            ';
                        }

                        echo '
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Total Attendance</th>
                                                <th>' . $total_attendance . '</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        ';
                    }
                } else {
                    echo '<div class="alert alert-info">No courses found for Semester ' . $selected_sem . '.</div>';
                }
            }
        }
        ?>
    </div>

    <!-- Bootstrap JS and dependencies (Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Optional: Font Awesome JS for better icon handling -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>

</html>
