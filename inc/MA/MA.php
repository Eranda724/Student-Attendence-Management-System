<?php require_once('../connection.php');
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
}
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
}
?>

<?php
$query = "SELECT S.date, S.start_time, C.course_code,S.schedule_id
          FROM schedule S, course C
          WHERE S.course_id = C.course_id 
          AND S.schedule_id NOT IN (SELECT DISTINCT schedule_id FROM attendance)";

$record = mysqli_query($connection, $query);
?>

<?php
if (isset($_POST['wh'])) {
    $selectedOption = $_POST['wh'];
    header("Location: $selectedOption");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Management System</title>
    <link rel="stylesheet" href="styles.css">


</head>

<body>
    <header>
        <h1>Student Attendance Management System</h1>
    </header>
    
    <nav>
        <ul>
            <li>
                <form method="post">
                    <select id="wh" name="wh" onchange="this.form.submit()">
                        <option value="" disabled selected>Dashboard</option>
                        <option value="course_list.php?user_id=<?php echo $user_id; ?>">Course List</option>
                        <option value="student_list.php?user_id=<?php echo $user_id; ?>">Student List</option>
                        <option value="lecture_list.php?user_id=<?php echo $user_id; ?>">Lecture List</option>
                        <option value="course_allocation.php?user_id=<?php echo $user_id; ?>">Course Allocation</option>
                        <option value="take_attendence.php?user_id=<?php echo $user_id; ?>">Take Attendance</option>
                        <option value="reports.php?user_id=<?php echo $user_id; ?>">Reports</option>
                        <option value="reset.php?user_id=<?php echo $user_id; ?>">Reset Password</option>
                        <option value="../../index.php">Log Out</option>
                    </select>
                </form>
            </li>
        </ul>
    </nav>

    <main>
        <?php if ($record && $record->num_rows > 0) { ?>
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
                    <?php while ($row = $record->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['date']; ?></td>
                            <td><?php echo $row['start_time']; ?></td>
                            <td><?php echo $row['course_code']; ?></td>
                            <td>
                                <form action="take_attendence.php" method="post">
                                    <input type="hidden" name="schedule_id_2" value="<?php echo $row['schedule_id']; ?>">
                                    <input type="submit" class="submit-button" value="Mark Attendance">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No attendance sheets to mark.</p>
        <?php } ?>
    </main>
</body>
</html>
