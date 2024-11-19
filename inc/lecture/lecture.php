<?php require_once('../connection.php'); ?>
<?php

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    echo "get"."$user_id";
} elseif (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    echo "post"."$user_id";
}

$query = "SELECT C.course_id,C.course_code,C.course_name FROM 
            lecture AS L
            JOIN teach AS T ON L.lecture_id=T.lecture_id
            JOIN course AS C ON T.course_id=C.course_id
            WHERE L.lecture_id='$user_id'";
$course_list = mysqli_query($connection, $query);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['wh'])) {
    $selectedOption = $_POST['wh'];

    switch ($selectedOption) {
        case "dashboard":
            header("Location: lecture.php?u_id=$user_id");
            exit();
        case "course_list":
            header("Location: course_list.php?u_id=$user_id");
            exit();
        case "time_schedule":
            header("Location: time_schedule.php?u_id=$user_id");
            exit();
        case "course_allocation":
            header("Location: course_allocation.php?u_id=$user_id");
            exit();
        case "reports":
            header("Location: reports.php?user_id=$user_id");
            exit();
        case "reset":
            header("Location: reset.php?user_id=$user_id");
            exit();
        case "logout":
            header("Location: ../../index.php");
            break;
        default:
            break;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('image1.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1, h2 {
            text-align: center;
            color: #030202;
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        select, input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            background-color: #a11616;
            color: #fff;
            cursor: pointer;
            font-size: 14px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .button-container {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
            margin-top: 10px;
        }

        .button-close input[type="submit"] {
            background-color: #d9534f;
        }

        .button-close input[type="submit"]:hover {
            background-color: #c9302c;
        }
    </style>
</head>

<body>
    <h1>Student Attendance Management System</h1>
    <hr>
    <form method="post">
        <select name="wh" onchange="this.form.submit()">
            <option value="" disabled selected hidden>Dashboard</option>
            <option value="course_list">Course List</option>
            <option value="time_schedule">Time Schedule</option>
            <option value="course_allocation">Course Allocation</option>
            <option value="reports">Reports</option>
            <option value="reset">Reset Password</option>
            <option value="logout">Log Out</option>
        </select>
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
    </form>

    <h1>Course and Attendance</h1>

    <?php
if (mysqli_num_rows($course_list) > 0) {
    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Course Code</th>';
    echo '<th>Course Name</th>';
    echo '<th>Action</th>'; // Added Action column
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    while ($row = mysqli_fetch_assoc($course_list)) {
        echo '<tr>';
        echo '<td>' . $row['course_code'] . '</td>';
        echo '<td>' . $row['course_name'] . '</td>';
        echo '<td><form action="reports.php" method="post">
        <input type="hidden" name="course" value="' . $row['course_code'] . '">
        <input type="hidden" name="user_id" value="' . $user_id . '">
        <input type="submit" value="Go Next" style="background-color: blue; color: white;"></form></td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
} else {
    echo 'No courses found.';
}
?>


</body>

</html>