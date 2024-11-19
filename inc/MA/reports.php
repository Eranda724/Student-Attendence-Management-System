<?php require_once('../connection.php');
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
}
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
}
echo "$user_id"; ?>
<?php
$year_query = "SELECT DISTINCT year FROM course";
$year_result = mysqli_query($connection, $year_query);

$semester_query = "SELECT DISTINCT semester FROM course";
$semester_result = mysqli_query($connection, $semester_query);

$course_list = array();
$schedule_list = array();
$selected_semester = "Select Semester";
$selected_year = "Select Year";
$selected_course = "Select Course";
$students_data = array();
$schedule_id = 'NULL';

if (isset($_POST['semester'])) {

    $selected_semester = $_POST['semester'];
    $selected_year = $_POST['year'];

    $query = "SELECT `course_code` FROM `course` WHERE `semester`='$selected_semester' AND `year`='$selected_year'";
    $record = mysqli_query($connection, $query);

    while ($result = mysqli_fetch_assoc($record)) {
        $c_id = $result['course_code'];
        $course_list[] = $c_id;
    }

    if (isset($_POST['course'])) {
        $selected_course = $_POST['course'];

        $query = "SELECT S.reg_no,U.first_name,U.last_name,U.user_id FROM course C,enroll E,student S,user U WHERE C.course_code='$selected_course' AND C.course_id=E.course_id AND E.student_id=S.student_id AND S.student_id=U.user_id";

        $record = mysqli_query($connection, $query);

        while ($result = mysqli_fetch_assoc($record)) {
            $students_data[] = $result;
        }

        $query2 = "SELECT S.schedule_id,S.date,S.start_time FROM course C,schedule S WHERE C.course_code='$selected_course' AND C.course_id=S.course_id";
        $record2 = mysqli_query($connection, $query2);

        while ($result2 = mysqli_fetch_assoc($record2)) {
            $schedule_list[] = $result2;
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Take Attendance</title>
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
    <form action="reports.php" method="post">
        <h1>Reports</h1>
        <hr>
        <label for="semester">Semester:</label>
        <select id="semester" name="semester" required>
            <?php
            // echo "$selected_semester";
            $options = '<option value="" disabled selected>' . htmlspecialchars($selected_semester) . '</option>';
            while ($row = mysqli_fetch_assoc($semester_result)) {
                $options .= '<option value="' . $row['semester'] . '">' . $row['semester'] . '</option>';
            }
            echo $options;
            ?>
        </select>
        <label for="year">Year:</label>
        <select id="year" name="year" required>
            <?php
            $options = '<option value="" disabled selected>' . htmlspecialchars($selected_year) . '</option>';
            while ($row = mysqli_fetch_assoc($year_result)) {
                $options .= '<option value="' . $row['year'] . '">' . $row['year'] . '</option>';
            }
            echo $options;
            ?>
        </select>
        <input type="hidden" name="user_id" value=<?php echo "$user_id"; ?>>
        <input type="submit" name="submit" value="Submit">

    </form>

    <?php
    if (isset($_POST['semester'])) {
        echo "<form action='' method='post'>";
        echo "<label for='course'>Course:</label>";
        echo "<select id='course' name='course' required>";
        $options = "<option value='' disabled selected>" . htmlspecialchars($selected_course) . "</option>";
 // Corrected syntax and initialization
        foreach ($course_list as $course_code) {
            $options .= "<option value='" . $course_code . "'>" . $course_code . "</option>"; // Concatenated the options
        }
        echo $options;
        echo "</select>";
        echo "<input type='hidden' name='year' value='$selected_year'>";
        echo "<input type='hidden' name='semester' value='$selected_semester'>";
        echo "<input type='hidden' name='user_id' value='$user_id'>";
        echo "<input type='submit' name='submit2' value='Submit'>";
        echo "</form>";
    }
    ?>

    <?php
    if (isset($_POST['course'])) {
        if (!empty($students_data)) {
            echo "
                <form action='' method='post'>
                <table border='1'>
                    <thead>
                        <tr>
                            <th>Registration No</th>
                            <th>First Name</th>
                            <th>Last Name</th>";
            $total_dates = 0;
            foreach ($schedule_list as $schedule) {
                echo "<th>" . $schedule['date'] . " " . $schedule['start_time'] . "</th>";
                $total_dates++;
            }
            echo "<th>Total</th>";
            echo "<th>%</th>";
            echo "</tr>
                    </thead>
                    <tbody>";
            foreach ($students_data as $st) {
                echo "<tr>
                    <td>{$st['reg_no']}</td>
                    <td>{$st['first_name']}</td>
                    <td>{$st['last_name']}</td>";
                $student_id = $st['user_id'];
                $present_dates = 0;
                foreach ($schedule_list as $schedule) {
                    $query = "SELECT `attend` FROM `attendance` WHERE `student_id`=$student_id AND `schedule_id`=" . $schedule['schedule_id'];
                    $record = mysqli_query($connection, $query);
                    if ($record && $result = mysqli_fetch_assoc($record)) {
                        if ($result['attend']) {
                            echo "<td>present</td>";
                            $present_dates++;
                        } else {
                            echo "<td>absent</td>";
                        }
                    } else {
                        // If attendance data not available, display a message or leave the cell empty
                        echo "<td>N/A</td>";
                    }
                }
                echo "<td>$present_dates</td>";
                if ($total_dates > 0) {
                    echo "<td>" . round(($present_dates * 100 / $total_dates), 2) . "%</td>";
                } else {
                    echo "<td>N/A</td>"; // Avoid division by zero
                }
                echo "</tr>";
            }
            echo "</tbody></table>";
            echo "</form>";
        }
    }
    ?>

    <form action="MA.php" method="post">
        <input type="hidden" name="user_id" value=<?php echo "$user_id"; ?>>
        <input type="submit" name="back" value="Back">
    </form>
</body>

</html>