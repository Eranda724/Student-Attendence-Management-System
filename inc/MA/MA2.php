<!-- index.php -->

<?php
require_once('../connection.php');

// Database query with prepared statement
$stmt = $connection->prepare("SELECT S.date, S.start_time, C.course_code, S.schedule_id
                              FROM schedule S
                              INNER JOIN course C ON S.course_id = C.course_id
                              WHERE S.schedule_id NOT IN (SELECT DISTINCT schedule_id FROM attendance)");
$stmt->execute();
$record = $stmt->get_result();
?>

<?php
    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
    }
    ?>
<?php
    if (isset($_POST['wh'])) {
        $selectedOption = $_POST['wh'];
        echo "$selectedOption";

        switch ($selectedOption) {

            case "dashboard":
                header("Location: MA.php");
                exit();
            case "course_list":
                header("Location: course_list.php");
                exit();
            case "student_list":
                header("Location: student_list.php");
                exit();
            case "lecture_list":
                header("Location: lecture_list.php");
                exit();
            case "course_allocation":
                header("Location: course_allocation.php");
                exit();
            case "take_attendence":
                header("Location: take_attendence.php");
                exit();
            case "reports":
                header("Location: reports.php");
                exit();
            case "logout":
                header("Location: ../../index.php");
                exit();
            default:
                header("Location: $selectedOption");
                exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Management System</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add CSS file -->
</head>
<body>
    <header>
        <h1>Student Attendance Management System</h1>
        <hr>
    </header>
    <nav>
        <ul>
            <li>
                <form method="post">
                    <label for="wh">Select an option:</label>
                    <select id="wh" name="wh" onchange="this.form.submit()">
                        <option value="">Dashboard</option>
                        <option value="course_list">Course List</option>
                        <option value="student_list">Student List</option>
                        <option value="lecture_list">Lecture List</option>
                        <option value="course_allocation">Course Allocation</option>
                        <option value="take_attendence">Take Attendance</option>
                        <option value="reports">Reports</option>
                        <option value="reset.php?user_id=<?php echo $_GET['user_id']?>">Reset Password</option>
                        <option value="logout">Log Out</option>
                    </select>
                </form>
            </li>
        </ul>
    </nav>
    <main>
        <?php if ($record && $record->num_rows > 0) {?>
            <table id="attendance-table">
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Start Time</th>
                        <th scope="col">Course Code</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $record->fetch_assoc()) {?>
                        <tr>
                            <td><?php echo $row['date'];?></td>
                            <td><?php echo $row['start_time'];?></td>
                            <td><?php echo $row['course_code'];?></td>
                            <td>
                                <form action="take_attendence.php" method="post">
                                    <input type="hidden" name="schedule_id_2" value="<?php echo $row['schedule_id'];?>">
                                    <input type="submit" class="submit-button" value="Mark Attendance">
                                </form>
                            </td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
        <?php } else {?>
            <p>No Attendence sheets have to mark.</p>
        <?php }?>

    </main>


</body>
</html>