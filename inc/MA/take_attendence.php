<?php require_once('../connection.php');
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
}
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
}
echo "$user_id"; ?>

<?php
$schedule_id = 'NULL';
if (isset($_POST['schedule_id'])) {
    $schedule_id = $_POST['schedule_id'];
    $ticks = $_POST['tick']; // Assuming tick is an array of student ids

    foreach ($ticks as $tick) {
        $query = "UPDATE `attendance` SET `attend`=1 WHERE `student_id`=$tick AND `schedule_id`=$schedule_id";
        $record = mysqli_query($connection, $query);
        if ($record) {
            
        }
    }
}

$year_query = "SELECT DISTINCT year FROM course";
$year_result = mysqli_query($connection, $year_query);

$semester_query = "SELECT DISTINCT semester FROM course";
$semester_result = mysqli_query($connection, $semester_query);

$course_list = array();
$lec_list = array();
$lec_id = array();
$date_list = array();
$time_list = array();
$selected_semester = "Select Semester";
$selected_year = "Select Year";
$selected_course = "Select Course";
$selected_lec_name="Select Lecture";
$selected_date="Select Date";
$selected_time="Select Time";
$students_data = array();
$schedule_id = 'NULL';
if (isset($_POST['schedule_id_2'])) {
    $schedule_id = $_POST['schedule_id_2'];
    echo "$schedule_id";
    $query = "SELECT St.reg_no,U.first_name,U.middle_name,U.last_name,U.user_id FROM course C,enroll E,student St,user U,schedule S WHERE S.schedule_id=$schedule_id AND S.course_id=C.course_id AND C.course_id=E.course_id AND E.student_id=St.student_id AND St.student_id=U.user_id";
    $record = mysqli_query($connection, $query);

    while ($result = mysqli_fetch_assoc($record)) {
        $students_data[] = $result;
    }
}

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

        $query = "SELECT U.first_name, U.user_id 
              FROM user U, teach T, course C 
              WHERE C.course_code='$selected_course' 
              AND C.course_id=T.course_id 
              AND T.lecture_id=U.user_id";

        $record = mysqli_query($connection, $query);

        while ($result = mysqli_fetch_assoc($record)) {
            $lec_name = $result['first_name'];
            $l_id = $result['user_id'];
            $lec_list[] = $lec_name;
            $lec_id[] = $l_id;
        }
    }
    if (isset($_POST['lec_name'])) {
        $selected_lec_name = $_POST['lec_name'];
        $query = "SELECT DISTINCT S.date FROM schedule S, user U, course C WHERE U.first_name='$selected_lec_name' AND U.user_id=S.lecture_id AND C.course_code='$selected_course' AND C.course_id=S.course_id";


        $record = mysqli_query($connection, $query);

        while ($result = mysqli_fetch_assoc($record)) {
            $d = $result['date'];
            $date_list[] = $d;
        }
    }

    if (isset($_POST['date'])) {
        $selected_date = $_POST['date'];
        $query = "SELECT S.start_time FROM schedule S, user U, course C WHERE U.first_name='$selected_lec_name' AND U.user_id=S.lecture_id AND C.course_code='$selected_course' AND C.course_id=S.course_id AND S.date='$selected_date'";


        $record = mysqli_query($connection, $query);

        while ($result = mysqli_fetch_assoc($record)) {
            $t = $result['start_time'];
            $time_list[] = $t;
        }
    }

    if (isset($_POST['time'])) {
        $selected_time = $_POST['time'];
        $query = "SELECT St.reg_no,U.first_name,U.middle_name,U.last_name,U.user_id FROM course C,enroll E,student St,user U WHERE C.course_code='$selected_course' AND C.course_id=E.course_id AND St.student_id=E.student_id AND St.student_id=U.user_id ";


        $record = mysqli_query($connection, $query);

        while ($result = mysqli_fetch_assoc($record)) {
            $students_data[] = $result;
        }

        $query2 = "SELECT S.schedule_id FROM schedule S,course C,user U WHERE U.first_name='$selected_lec_name' AND S.lecture_id=U.user_id AND C.course_code='$selected_course' AND C.course_id=S.course_id AND S.date='$selected_date' AND S.start_time='$selected_time'";
        $record2 = mysqli_query($connection, $query2);

        if ($result2 = mysqli_fetch_assoc($record2)) {
            $schedule_id = $result2['schedule_id'];
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>take_attendence</title>
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
    <div id="container">
        <h1>Take Attendance</h1>
        <?php
        if (!isset($_POST['schedule_id_2'])) {
            echo '<form action="take_attendence.php" method="post">';
            echo '<label for="semester">Semester:</label>';
            echo '<select id="semester" name="semester" required>';

            $options = '<option value="" disabled selected>'.htmlspecialchars($selected_semester).'</option>';
            while ($row = mysqli_fetch_assoc($semester_result)) {
                $options .= '<option value="' . $row['semester'] . '">' . $row['semester'] . '</option>';
            }
            echo $options;

            echo '</select>';
            echo ' | ';
            echo '<label for="year">Year:</label>';
            echo '<select id="year" name="year" required>';

            $options = '<option value="" disabled selected>'.htmlspecialchars($selected_year).'</option>';
            while ($row = mysqli_fetch_assoc($year_result)) {
                $options .= '<option value="' . $row['year'] . '">' . $row['year'] . '</option>';
            }
            echo $options;

            echo '</select>';
            echo '<input type="hidden" name="user_id" value="'.$user_id.'">';
            echo '<input type="submit" name="submit" value="Submit">';
            echo '</form>';
        }
        ?>




        <?php
        if (isset($_POST['semester'])) {
            echo "<form action='' method='post'>";
            echo "<label for='course'>Course:</label>";
            echo "<select id='course' name='course' required>";
            $options = "<option value='' disabled selected>".htmlspecialchars($selected_course)."</option>"; // Corrected syntax and initialization
            foreach ($course_list as $course_code) {
                $options .= "<option value='" . $course_code . "'>" . $course_code . "</option>"; // Concatenated the options
            }
            echo $options;
            echo "</select>";
            echo "<input type='hidden' name='year' value='$selected_year'>";
            echo "<input type='hidden' name='semester' value='$selected_semester'>";
            echo '<input type="hidden" name="user_id" value="'.$user_id.'">';
            echo "<input type='submit' name='submit2' value='Submit'>";
            echo "</form>";
        }
        ?>

        <?php
        if (isset($_POST['course'])) {
            echo "<form action='' method='post'>";
            echo "<label for='lec_name'>Lecture:</label>";
            echo "<select id='lec_name' name='lec_name' required>";
            $options = "<option value='' disabled selected>".htmlspecialchars($selected_lec_name)."</option>"; // Corrected syntax and initialization
            foreach ($lec_list as $lec) {
                $options .= "<option value='" . $lec . "'>" . $lec . "</option>"; // Concatenated the options
            }
            echo $options;
            echo "</select>";
            echo "<input type='hidden' name='year' value='$selected_year'>";
            echo "<input type='hidden' name='semester' value='$selected_semester'>";
            echo "<input type='hidden' name='course' value='$selected_course'>";
            echo '<input type="hidden" name="user_id" value="'.$user_id.'">';
            echo "<input type='submit' name='submit3' value='Submit'>";
            echo "</form>";
        }
        ?>

        <?php
        if (isset($_POST['lec_name'])) {
            echo "<form action='' method='post'>";
            echo "<label for='date'>Date:</label>";
            echo "<select id='date' name='date' required>";
            $options = "<option value='' disabled selected>".htmlspecialchars($selected_date)."</option>"; // Corrected syntax and initialization
            foreach ($date_list as $d) {
                $options .= "<option value='" . $d . "'>" . $d . "</option>"; // Concatenated the options
            }
            echo $options;
            echo "</select>";
            echo "<input type='hidden' name='year' value='$selected_year'>";
            echo "<input type='hidden' name='semester' value='$selected_semester'>";
            echo "<input type='hidden' name='course' value='$selected_course'>";
            echo "<input type='hidden' name='lec_name' value='$selected_lec_name'>";
            echo '<input type="hidden" name="user_id" value="'.$user_id.'">';
            echo "<input type='submit' name='submit4' value='Submit'>";
            echo "</form>";
        }
        ?>

        <?php
        if (isset($_POST['date'])) {
            echo "<form action='' method='post'>";
            echo "<label for='time'>Time:</label>";
            echo "<select id='time' name='time' required>";
            $options = "<option value='' disabled selected>".htmlspecialchars($selected_time)."</option>"; // Corrected syntax and initialization
            foreach ($time_list as $t) {
                $options .= "<option value='" . $t . "'>" . $t . "</option>"; // Concatenated the options
            }
            
            echo $options;
            echo "</select>";
            echo "<input type='hidden' name='year' value='$selected_year'>";
            echo "<input type='hidden' name='semester' value='$selected_semester'>";
            echo "<input type='hidden' name='course' value='$selected_course'>";
            echo "<input type='hidden' name='lec_name' value='$selected_lec_name'>";
            echo '<input type="hidden" name="user_id" value="'.$user_id.'">';
            echo "<input type='hidden' name='date' value='$selected_date'>";

            echo "<input type='submit' name='submit5' value='Submit'>";
            echo "</form>";
        }

        if (!empty($students_data)) {
            echo "<h2>Students Data</h2>
            <form action='' method='post'>
            <table border='1'>
                <thead>
                    <tr>
                        <th>Registration No</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Tick</th> <!-- Added column for tick -->
                    </tr>
                </thead>
                <tbody>";
            foreach ($students_data as $st) {
                echo "<tr>
                    <td>{$st['reg_no']}</td>
                    <td>{$st['first_name']}</td>
                    <td>{$st['last_name']}</td>
                    <td><input type='checkbox' name='tick[]' value='{$st['user_id']}'></td> <!-- Checkbox for tick -->
                </tr>";

                $student_id = $st['user_id'];
                $query = "INSERT IGNORE INTO `attendance` (`schedule_id`, `student_id`,`attend`) VALUES ('$schedule_id', '$student_id',0)";
                $record = mysqli_query($connection, $query); // Execute the query
                if (!$record) {
                    echo "Error inserting attendance record: " . mysqli_error($connection);
                }
            }
            echo "</tbody>
            </table>";
            echo "<input type='hidden' name='schedule_id' value='$schedule_id'>"; // Corrected quotation marks
            echo '<input type="hidden" name="user_id" value="' . $user_id . '">';
            echo "<input type='submit' name='submit6' value='Save'> <!-- Button to submit attendance -->
            </form>";
        }
        ?>
        <br><br>
        <form action="MA.php" method="post">
            <input type="hidden" name="user_id" value=<?php echo "$user_id";?>>
            <input type="submit" name="back" value="Back">
            
        </form>
    </div>
</body>

</html>