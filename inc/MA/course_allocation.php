<?php require_once('../connection.php'); 
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
}
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
}
echo "$user_id"; ?>

<?php 
    // Retrieve list of existing semesters and years
    $semester_query = "SELECT DISTINCT semester FROM course";
    $semester_result = mysqli_query($connection, $semester_query);

    $year_query = "SELECT DISTINCT year FROM course";
    $year_result = mysqli_query($connection, $year_query);

    $selected_semester;
    $selected_year;

    // Check if form submitted
    if (isset($_POST['submit']) || isset($_POST['delete'])) {

        if(isset($_POST['delete'])){
            $course_id=$_POST['course_id'];
            $query="DELETE FROM `teach` WHERE `course_id`='$course_id'";
            $query2="DELETE FROM `enroll` WHERE `course_id`='$course_id'";
            $record=mysqli_query($connection, $query);
            $record2=mysqli_query($connection, $query2);
            if($record && $record2){
                echo "deleted";
            }
        }
        $selected_semester = $_POST['semester'];
        $selected_year = $_POST['year'];
        
        // Fetch course names based on selected semester and year
        $course_query = "SELECT course_id, course_code FROM course WHERE semester = '$selected_semester' AND year = '$selected_year'";
        $course_result = mysqli_query($connection, $course_query);

        // Fetch lectures associated with selected courses
        $courses = array(); // Array to store course details

        while ($course_row = mysqli_fetch_assoc($course_result)) {
            $course_id = $course_row['course_id'];
            $course_code = $course_row['course_code'];

            // Fetch lectures associated with this course
            $lecture_query = "SELECT lecture_id FROM teach WHERE course_id = '$course_id'";
            $lecture_result = mysqli_query($connection, $lecture_query);

            $lectures = array(); // Array to store lecture details

            while ($lecture_row = mysqli_fetch_assoc($lecture_result)) {
                $lecture_id = $lecture_row['lecture_id'];

                // Fetch lecturer name associated with this lecture
                $lecturer_query = "SELECT first_name, middle_name, last_name FROM user WHERE user_id = '$lecture_id'";
                $lecturer_result = mysqli_query($connection, $lecturer_query);

                $lecturer_row = mysqli_fetch_assoc($lecturer_result);
                $lecturer_name = $lecturer_row['first_name'] . " " . $lecturer_row['middle_name'] . " " . $lecturer_row['last_name'];

                $lectures[] = $lecturer_name;
            }

            // Store course details along with associated lectures and students
            $courses[] = array(
                'course_id'=> $course_id,
                'course_code' => $course_code,
                'lectures' => $lectures
            );
        }
    }
?>





<!DOCTYPE html>
<html>

<head>
    <title>Course Allocation</title>
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
        <h1>Course Allocation</h1>
        
        <hr>
        <form action="course_allocation.php?&user_id=<?php echo $user_id;?>" method="post">
            <label for="semester">Select Semester:</label>
            <select id="semester" name="semester">
                <?php
                while ($row = mysqli_fetch_assoc($semester_result)) {
                    echo "<option value='" . $row['semester'] . "'>" . $row['semester'] . "</option>";
                }
                ?>
            </select>
            <br><br>
            <label for="year">Select Year:</label>
            <select id="year" name="year">
                <?php
                while ($row = mysqli_fetch_assoc($year_result)) {
                    echo "<option value='" . $row['year'] . "'>" . $row['year'] . "</option>";
                }
                ?>
            </select>
<br><br>
            <input type="submit" name="submit" value="Submit">
        </form>

        <?php
        // Display course names and associated lectures if form submitted
        if (isset($_POST['submit']) || isset($_POST['delete'])) {
            echo "<hr>";
            echo "<h4>Semester:$selected_semester   Year:$selected_year</h4>";

            echo "<table>";
            echo "<tr><th>Course Name</th><th>Lecturers</th><th>Students</th><th>Edit</th><th>Delete</th></tr>";
            foreach ($courses as $course) {
                echo "<tr>";
                echo "<td>{$course['course_code']}</td>";
                echo "<td>";

                foreach ($course['lectures'] as $lecture) {
                    echo "<li>$lecture</li>";
                }

                echo "<td>";
                $c = $course['course_id'];

                echo '
                    <form method="post">
                        <select name="wh2" onchange="this.form.action = this.value; this.form.submit()">
                            <option value="">' . $selected_year . ' batch</option>
                            <option value="course_allocation_batch.php?course_id=' . $c . '">' . $selected_year . ' batch</option>
                            <option value="course_allocation_individual.php?course_id=' . $c . '">Individual</option>
                        </select>
                    </form>
                ';

                echo "</td>";
                echo "
                    <td>
                        <form action='course_allocation_edit.php?&user_id=.$user_id.' method='post'> 
                            <input type='hidden' name='course_id' value='$c'>
                            <input type='hidden' name='year' value='$selected_year'>
                            <input type='hidden' name='semester' value='$selected_semester'>
                            <input type='hidden' name='user_id' value='$user_id'>
                            <input type='submit' name='edit' value='Edit'>
                        </form>
                    </td>";
                echo "
                    <td>
                        <form action='course_allocation.php?&user_id=.$user_id.' method='post'> 
                            <input type='hidden' name='course_id' value='$c'>
                            <input type='hidden' name='year' value='$selected_year'>
                            <input type='hidden' name='semester' value='$selected_semester'>
                            <input type='hidden' name='user_id' value='$user_id'>
                            <input type='submit' name='delete' value='Delete'>
                        </form>
                    </td>";

                echo "</tr>";
            }
            echo "</table>";
        }
        ?>

<form action="MA.php?&user_id=<?php echo $user_id; ?>" method="post">

<input type="submit" name="back" value="back">
</form>
    </div>
</body>

</html>