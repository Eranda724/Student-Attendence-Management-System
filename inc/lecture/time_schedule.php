<?php
require_once('../connection.php');
if (isset($_GET['u_id'])) {
    $user_id = $_GET['u_id'];
    echo "$user_id";
} else {
    $user_id = $_POST['u_id'];
    echo "$user_id";
}

// Retrieve available years and semesters
$year_query = "SELECT DISTINCT `year` FROM course";
$year_result = mysqli_query($connection, $year_query);

$semester_query = "SELECT DISTINCT semester FROM course";
$semester_result = mysqli_query($connection, $semester_query);

$course_list = array();
$selected_course_id = null;

// Initialize selected semester and year
$selected_semester = "";
$selected_year = "";

// Display course names and associated lectures if form submitted
if (isset($_POST['submit']) || isset($_POST['submit1'])) {
    // Fetch other necessary data for the selected course and display it
    $selected_semester = $_POST['semester'];
    $selected_year = $_POST['year'];

    $query = "SELECT distinct C.course_id, C.course_code 
    FROM lecture AS L
    INNER JOIN teach AS T ON L.lecture_id = T.lecture_id
    INNER JOIN course AS C ON T.course_id = C.course_id Where L.lecture_id=$user_id";

    $record_course = mysqli_query($connection, $query);
}
if (isset($_POST['submit1'])) {
    $selected_semester = $_POST['semester'];
    $selected_year = $_POST['year'];
    $selected_course_id = $_POST['course'];
    $q="SELECT `course_code` FROM `course` Where `course_id`=$selected_course_id";
    $r=mysqli_query($connection,$q);
    if($c=mysqli_fetch_assoc($r)){
        $selected_course_code =$c['course_code'];
    }
}

$schedule_data = array();
if ($selected_course_id !== null) {
    $schedule_query = "SELECT `schedule_id`,`chapter`, `lecture_id`, `course_id`, `date`, `start_time`, `end_time` FROM `schedule` WHERE `lecture_id`='$user_id' AND `course_id`='$selected_course_id'";
    $schedule_result = mysqli_query($connection, $schedule_query);
    while ($row = mysqli_fetch_assoc($schedule_result)) {
        $schedule_data[] = $row;
    }
}

// Delete schedule row if delete button clicked
if (isset($_POST['delete'])) {
    $schedule_id = $_POST['schedule_id'];
    $query = "DELETE FROM `schedule` WHERE `schedule_id`='$schedule_id'";
    mysqli_query($connection, $query);
    $selected_semester = $_POST['semester'];
    $selected_year = $_POST['year'];
    $selected_course_id = $_POST['course'];
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Time Schedule</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        form {
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-left: 10px;
        }


        #batch_no {
            margin-left: 40px;

        }

        form {
            margin-top: 20px;
            margin-bottom: 20px;
            margin-left: 20px;
            text-align: left;
        }

        select,
        input[type="submit"] {
            width: auto;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        table {
            width: 90%;
            border-collapse: collapse;
            align-items: center;
            margin-left: 2px;
            font-size: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            width: 50px;
        }

        th {
            background-color: #f2f2f2;
        }

        #back {
            margin-left: 40px;
            margin-bottom: 40px;
            size: 100%;
            width: 60px;
            height: 40px;
            font-size: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #form_1 {
            /* width: 95%; */
            float: right;
            font-size: 100%;
        }
    </style>
</head>

<body>
<h1>Student Attendance Management System</h1>
<hr>
    <div class="container">

        <form action="time_schedule.php?u_id=<?php echo $user_id; ?>" method="post">
            
        <h1>Time Schedule</h1>
        <br>
        <label for="semester">Select Semester:</label>
            <select id="semester" name="semester">
                <?php
                while ($row = mysqli_fetch_assoc($semester_result)) {
                    echo "<option value='" . $row['semester'] . "'>" . $row['semester'] . "</option>";
                }
                ?>
            </select>

            <label for="year">Select Year:</label>
            <select id="year" name="year">
                <?php
                while ($row = mysqli_fetch_assoc($year_result)) {
                    echo "<option value='" . $row['year'] . "'>" . $row['year'] . "</option>";
                }
                ?>
            </select>

            <input type="submit" name="submit" value="Submit" style="background-color: blue; color: white;">
        </form>

        <?php
        if (isset($_POST['submit']) || isset($_POST['submit1'])) {
            echo '<form action="time_schedule.php?u_id=' . $user_id . '" method="post">';
            echo '<label for="course">Course:</label>';
            echo '<select id="course" name="course">';

            while ($result = mysqli_fetch_assoc($record_course)) {
                $c_id = $result['course_id'];
                $c_code = $result['course_code'];
                echo "<option value='" . $c_id . "'>" . $c_code . "</option>";
            }
            echo '</select>';
            echo '<input type="hidden" name="year" value="' . $selected_year . '">';
            echo '<input type="hidden" name="semester" value="' . $selected_semester . '">';
            echo '<input type="submit" name="submit1" value="Submit">';
            echo '</form>';
        }
        ?>


        <?php if (!empty($schedule_data)) : ?>
            <h2>Schedule</h2>
            <p> Course ID: <?php echo $selected_course_code; ?>, Semester: <?php echo $selected_semester; ?>, Year: <?php echo $selected_year; ?></p>

            <table>
                <tr>
                    <th>Chapter</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>

                <?php foreach ($schedule_data as $row) : ?>
                    <tr>
                        <td><?php echo $row['chapter']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['start_time']; ?></td>
                        <td><?php echo $row['end_time']; ?></td>
                        <td>
                            <form action="edit_schedule.php" method="post">
                                <input type="hidden" name="lecture_id" value="<?php echo $row['lecture_id']; ?>">
                                <input type="submit" name="edit" value="Edit">
                            </form>
                        </td>
                        <td>
                            <form action="time_schedule.php" method="post">
                                <input type="hidden" name="u_id" value="<?php echo $user_id; ?>">
                                <input type="hidden" name="year" value="<?php echo $selected_year; ?>">
                                <input type="hidden" name="semester" value="<?php echo $selected_semester; ?>">
                                <input type="hidden" name="course" value="<?php echo $selected_course_id; ?>">
                                <input type="hidden" name="course_code" value="<?php echo $selected_course_code; ?>">
                                <input type="hidden" name="schedule_id" value="<?php echo $row['schedule_id']; ?>">
                                <input type="submit" name="delete" value="Delete">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else : ?>
            <p>No schedule data available.</p>
        <?php endif; ?>

        <br>
        <a class="add-btn" href="time_schedule_add.php?user_id=<?php echo $user_id; ?>">Add Schedule</a>
        <a class="back-btn" href="lecture.php?user_id=<?php echo $user_id; ?>">Back</a>
    </div>
</body>

</html>
