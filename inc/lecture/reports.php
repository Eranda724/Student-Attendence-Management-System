<?php require_once('../connection.php'); ?>

<?php
if(isset($_GET['user_id'])){
  $user_id = $_GET['user_id'];
  // echo "get"."$user_id";
}
elseif(isset($_POST['user_id'])){
  $user_id = $_POST['user_id'];
  // echo "post"."$user_id";
}
$selected_semester = 'NULL';
$selected_year = 'NULL';
$selected_course = 'NULL';
$students_data = array();
$schedule_list = array();
$schedule_id = 'NULL';

// Query to retrieve distinct years and semesters
$year_query = "SELECT DISTINCT year FROM course";
$semester_query = "SELECT DISTINCT semester FROM course";

// Perform queries
$year_result = mysqli_query($connection, $year_query);
$semester_result = mysqli_query($connection, $semester_query);

// Check if form submitted
if (isset($_POST['submit'])) {
    $selected_semester = $_POST['semester'];
    $selected_year = $_POST['year'];

    // Retrieve courses available in the selected year and semester
    $course_query = "SELECT `course_code` FROM `course` WHERE `semester`='$selected_semester' AND `year`='$selected_year'";
    $record = mysqli_query($connection, $course_query);

    while ($result = mysqli_fetch_assoc($record)) {
        $course_list[] = $result['course_code'];
    }
}

if (isset($_POST['course'])) {
    $selected_course = $_POST['course'];
    
    // Query to retrieve students enrolled in the selected course
    $query = "SELECT S.reg_no, U.first_name, U.last_name, U.user_id 
              FROM course C
              JOIN enroll E ON C.course_id = E.course_id 
              JOIN student S ON E.student_id = S.student_id 
              JOIN user U ON S.student_id = U.user_id 
              WHERE C.course_code = '$selected_course'";

    $record = mysqli_query($connection, $query);

    while ($result = mysqli_fetch_assoc($record)) {
        $students_data[] = $result;
    }

    // Query to retrieve schedule for the selected course
    $query2 = "SELECT S.schedule_id, S.date, S.start_time 
               FROM course C
               JOIN schedule S ON C.course_id = S.course_id 
               WHERE C.course_code = '$selected_course'";
    $record2 = mysqli_query($connection, $query2);

    while ($result2 = mysqli_fetch_assoc($record2)) {
        $schedule_list[] = $result2;
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
    <form action="reports.php" method="post">
        <h1 style=text-align: center; >Reports</h1>
        <hr>
        <label for="semester" style=text-align: center;>Semester:</label>
        <select id="semester" name="semester">
            <?php
            $options = '<option value="">Select Semester</option>';
            while ($row = mysqli_fetch_assoc($semester_result)) {
                $options .= '<option value="' . $row['semester'] . '">' . $row['semester'] . '</option>';
            }
            echo $options;
            ?>
        </select>
        <label for="year">Year:</label>
        <select id="year" name="year">
            <?php
            $options = '<option value="">Select Year</option>';
            while ($row = mysqli_fetch_assoc($year_result)) {
                $options .= '<option value="' . $row['year'] . '">' . $row['year'] . '</option>';
            }
            echo $options;
            ?>
        </select>
        <input type="hidden" name="user_id" value='<?php echo $user_id ?>'>

        <input type="submit" name="submit" value="Submit" style="background-color: blue; color: white;">

    </form>

    <?php if (isset($_POST['semester'])): ?>
        <form action="" method="post">
            <label for="course">Course:</label>
            <select id="course" name="course">
                <?php
                $options = "<option value=''>Select Course</option>"; // Corrected syntax and initialization
                foreach ($course_list as $course_code) {
                    $options .= "<option value='" . $course_code . "'>" . $course_code . "</option>"; // Concatenated the options
                }
                echo $options;
                ?>
            </select>
            <input type="hidden" name="year" value='<?php echo $selected_year ?>'>
            <input type="hidden" name="semester" value='<?php echo $selected_semester ?>'>
            <input type="hidden" name="user_id" value='<?php echo $user_id ?>'>
            <input type="submit" name="submit2" value="Submit">
        </form>
    <?php endif; ?>

    <?php if (isset($_POST['course'])): ?>
        <form action="" method="post">
            <table border="1">
                <thead>
                    <tr>
                        <th>Registration No</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <?php foreach ($schedule_list as $schedule): ?>
                            <th><?php echo $schedule['date'] . " " . $schedule['start_time'] ?></th>
                        <?php endforeach; ?>
                        <th>Total</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students_data as $st): ?>
                        <tr>
                            <td><?php echo $st['reg_no'] ?></td>
                            <td><?php echo $st['first_name'] ?></td>
                            <td><?php echo $st['last_name'] ?></td>
                            <?php
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
                            ?>
                            <td><?php echo $present_dates ?></td>
                            <td><?php echo ($present_dates > 0) ? round(($present_dates * 100 / count($schedule_list)), 2) . "%" : "0" ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    <?php endif; ?>

    <form action="lecture.php" method="post">
        <input type="hidden" name="user_id" value='<?php echo $user_id ?>'>
        <input type="submit" name="back" value="Back" style="background-color: blue; color: white;">
    </form>
</body>

</html>
