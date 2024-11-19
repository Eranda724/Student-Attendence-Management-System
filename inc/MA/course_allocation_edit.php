<?php require_once('../connection.php'); 
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
}
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
}
echo "$user_id";?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Course Members</title>
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
    <div class="container">
<?php 
    
    if(isset($_POST['submit1'])){
        $course_id=$_POST['course_id'];
        $student_id=$_POST['student_id'];
        // Check if the student is already enrolled in the course
        $check_query = "SELECT * FROM enroll WHERE course_id='$course_id' AND student_id='$student_id'";
        $check_result = mysqli_query($connection, $check_query);
        if(mysqli_num_rows($check_result) == 0){
            // If the student is not already enrolled, insert them
            $query="INSERT INTO `enroll`(`course_id`,`student_id`) values ('$course_id','$student_id')";
            $record=mysqli_query($connection, $query);
            if($record){
                echo "inserted";
            } 
        } else {
            echo "Student is already enrolled in the course.";
        }
    }

    if(isset($_POST['submit_batch'])){
        $course_id=$_POST['course_id'];
        $batch_id=$_POST['batch_id'];
        // Query to fetch students with the selected batch
        $batch_students_query = "SELECT student_id FROM student WHERE batch_no='$batch_id'";
        $batch_students_result = mysqli_query($connection, $batch_students_query);
        // Insert students with the selected batch into the enroll table
        while ($batch_student_row = mysqli_fetch_assoc($batch_students_result)) {
            $student_id = $batch_student_row['student_id'];
            // Check if the student is already enrolled in the course
            $check_query = "SELECT * FROM enroll WHERE course_id='$course_id' AND student_id='$student_id'";
            $check_result = mysqli_query($connection, $check_query);
            if(mysqli_num_rows($check_result) == 0){
                // If the student is not already enrolled, insert them
                $query="INSERT INTO `enroll`(`course_id`,`student_id`) values ('$course_id','$student_id')";
                $record=mysqli_query($connection, $query);
                if($record){
                    echo "Batch added";
                }
            } else {
                echo "Student is already enrolled in the course.";
            }
        }
    }

    if(isset($_POST['submit2'])){
        $course_id=$_POST['course_id'];
        $lecture_id=$_POST['lecture_id'];

        $check_query = "SELECT * FROM teach WHERE course_id='$course_id' AND lecture_id='$lecture_id'";
        $check_result = mysqli_query($connection, $check_query);
        if(mysqli_num_rows($check_result) == 0){
            $query="INSERT INTO `teach`(`course_id`,`lecture_id`) values ('$course_id','$lecture_id')";
            $record=mysqli_query($connection, $query);
            if($record){
                echo "inserted";
            } 
        } else {
            echo "Lecture is already in the course.";
        }
    }

    



    if(isset($_POST['remove2'])){
        $course_id=$_POST['course_id'];
        $lecture_id=$_POST['lecture_id'];
        $query="DELETE FROM teach WHERE course_id='$course_id' AND lecture_id='$lecture_id'";
        $record=mysqli_query($connection, $query);
        if($record){
            echo "deleted";
        }
    } 

    if(isset($_POST['remove'])){
        $course_id=$_POST['course_id'];
        $student_id=$_POST['student_id'];
        $query="DELETE FROM enroll WHERE course_id='$course_id' AND student_id='$student_id'";
        $record=mysqli_query($connection, $query);
        if($record){
            echo "deleted";
        }
    } 

    $course_id = $_POST['course_id'];

    // Query to fetch students already enrolled in the course
    $enrolled_students_query = "SELECT student_id FROM enroll WHERE course_id='$course_id'";
    $enrolled_students_result = mysqli_query($connection, $enrolled_students_query);

    // Query to fetch all students
    $all_students_query = "SELECT student_id, reg_no FROM student";
    $all_students_result = mysqli_query($connection, $all_students_query);

    // Query to fetch all distinct batch numbers
    $distinct_batch_query = "SELECT DISTINCT batch_no FROM student";
    $distinct_batch_result = mysqli_query($connection, $distinct_batch_query);

    // Query to fetch all lecturers not associated with the course
    $available_lecturers_query = "SELECT U.user_id, U.first_name, U.middle_name, U.last_name FROM lecture L,user U WHERE L.lecture_id=U.user_id AND user_id NOT IN (SELECT lecture_id FROM teach WHERE course_id='$course_id')";
    $available_lecturers_result = mysqli_query($connection, $available_lecturers_query);
?>
    <h1>Manage Course Members</h1>

    <!-- Section for managing students -->
    <h2>Students</h2>
    <table>
        <tr>
            <th>Registration Number</th>
            <th>Batch</th>
            <th>Action</th>
        </tr>
        <?php 
            while ($student_row = mysqli_fetch_assoc($enrolled_students_result)) {
                $student_id = $student_row['student_id'];
                // Query to fetch student details
                $student_details_query = "SELECT * FROM student WHERE student_id='$student_id'";
                $student_details_result = mysqli_query($connection, $student_details_query);
                $student_details = mysqli_fetch_assoc($student_details_result);
                // Query to fetch batch for student
                $batch_query = "SELECT `batch_no` FROM `student` WHERE `student_id`='$student_id'";
                $batch_result = mysqli_query($connection, $batch_query);
                $batch_row = mysqli_fetch_assoc($batch_result);
                $batch = $batch_row['batch_no'];
                // Display student details with remove button
                echo "<tr>";
                echo "<td>{$student_details['reg_no']}</td>";
                echo "<td>{$batch}</td>";
                echo "<td>";
                echo "<form action='course_allocation_edit.php' method='post'>";
                echo "<input type='hidden' name='course_id' value='$course_id'>";
                echo "<input type='hidden' name='student_id' value='$student_id'>";
                echo "<input type='hidden' name='user_id' value='$user_id'>";
                echo "<input type='submit' name='remove' value='Remove'></form>";
                echo "</td>";
                echo "</tr>";
            }
        ?>
    </table>
    <form action="course_allocation_edit.php?&user_id=<?php echo $user_id;?>" method="post">
        <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
        <label for="student_id">Add a Student:</label>
        <select name="student_id" id="student_id">
            <?php 
                // Fetch and display students not enrolled in the course
                while ($student_row = mysqli_fetch_assoc($all_students_result)) {
                    $student_id = $student_row['student_id'];
                    if (!mysqli_num_rows(mysqli_query($connection, "SELECT * FROM enroll WHERE course_id='$course_id' AND student_id='$student_id'"))) {
                        echo "<option value='{$student_row['student_id']}'>{$student_row['reg_no']}</option>";
                    }
                }
            ?>
        </select>
        
        <input type="submit" name="submit1" value="Add">
    </form>

    <!-- Section for adding students by batch -->
    <h2>Add Students by Batch</h2>
    <form action="course_allocation_edit.php?&user_id=<?php echo $user_id;?>" method="post">
        <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
        <label for="batch_id">Select Batch:</label>
        <select name="batch_id" id="batch_id">
            <?php 
                // Fetch and display distinct batch numbers
                while ($batch_row = mysqli_fetch_assoc($distinct_batch_result)) {
                    $batch_no = $batch_row['batch_no'];
                    echo "<option value='{$batch_no}'>{$batch_no}</option>";
                }
            ?>
        </select>
        <input type="submit" name="submit_batch" value="Add Batch">
    </form>

    <!-- Section for managing lectures -->
    <h2>Lectures</h2>
    <table>
        <tr>
            <th>Lecturer Name</th>
            <th>Action</th>
        </tr>
        <?php 
            while ($lecture_row = mysqli_fetch_assoc($available_lecturers_result)) {
                $lecture_id = $lecture_row['user_id'];
                $lecturer_name = $lecture_row['first_name'] . " " . $lecture_row['middle_name'] . " " . $lecture_row['last_name'];
                // Display lecturer details with add button
                echo "<tr>";
                echo "<td>{$lecturer_name}</td>";
                echo "<td>";
                echo "<form action='course_allocation_edit.php' method='post'>";
                echo "<input type='hidden' name='course_id' value='$course_id'>";
                echo "<input type='hidden' name='lecture_id' value='$lecture_id'>";
                echo "<input type='hidden' name='user_id' value='$user_id'>";
                echo "<input type='submit' name='submit2' value='Add'></form>";
                echo "</td>";
                echo "</tr>";
            }
        ?>
    </table>

    <!-- Back button -->
    <form action="course_allocation.php?&user_id=<?php echo $user_id;?>" method="post">
                <input type="submit" name="cancel" value="Cancel">
            </form>
    </div>
</body>
</html>
