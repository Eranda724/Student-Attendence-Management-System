<?php require_once('../connection.php'); 
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
}
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
}
echo "$user_id";?>

<?php
// Query to retrieve available years
$year_query = "SELECT DISTINCT `year` FROM course";
$year_result = mysqli_query($connection, $year_query);
?>

<?php
if (isset($_POST['save'])) {
    // Escape user inputs for security
    $courseName = mysqli_real_escape_string($connection, $_POST['name']);
    $courseCode = mysqli_real_escape_string($connection, $_POST['code']);
    $year = mysqli_real_escape_string($connection, $_POST['year']);
    $sem = mysqli_real_escape_string($connection, $_POST['sem']);
    $credits = mysqli_real_escape_string($connection, $_POST['credits']);
    $lectureHours = mysqli_real_escape_string($connection, $_POST['hours']);

    // Build the SQL query
    $query = "INSERT INTO `course` (`course_name`, `course_code`, `credits`, `lecture_hours`, `semester`, `year`) 
                  VALUES ('$courseName', '$courseCode', '$credits', '$lectureHours', '$sem', '$year')";

    // Execute the query
    $result = mysqli_query($connection, $query);

    // Check if query executed successfully
    if ($result) {
        // Redirect to course_list.php after adding the course with year and sem parameters
        header("Location: course_list.php?year=$year&sem=$sem&user_id=$user_id");
        exit(); // Stop further execution
    } else {
        // Display error message if query fails
        echo "Error: " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Course</title>
    <style>
        /* Global styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            background-size: cover;
    background-position: center;
    background-attachment: fixed;
            margin: 0;
            padding: 0;
            background-image: url('image1.jpg');
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        form {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }


        hr {
            border: none;
            border-top: 1px solid #ccc;
            margin: 20px 0;
        }

        form {
            margin-top: 20px;
        }

        select, input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            width: calc(100% - 22px);
        }

        input[type="submit"] {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #3e8e41;
        }

        a {
            text-decoration: none;
            color: #337ab7;
        }

        a:hover {
            color: #23527c;
        }

        /* Adjustments for inline elements */
        input[type="text"], select {
            display: block;
            margin-bottom: 10px;
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
<h1 style="text-align: center;">Student Attendance Management System</h1>
<hr>
    <div class="container">
        <form action="course_add.php?&user_id=<?php echo $user_id;?>" method="post">
            
        <h2>Add Course</h2>
        <label for="name">Course name:</label>
            <input type="text" id="name" name="name" required><br>
            <label for="code">Course code:</label>
            <input type="text" id="code" name="code" required><br>
            <label for="hours">Lecture hours:</label>
            <input type="text" id="hours" name="hours" required><br>

            <label for="year">Year:</label>
            <select id="year" name="year" required>
                <?php
                while ($row = mysqli_fetch_assoc($year_result)) {
                    echo "<option value='" . $row['year'] . "'>" . $row['year'] . "</option>";
                }
                ?>
            </select>

            <label for="sem">Semester:</label>
            <select id="sem" name="sem" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
            </select>

            <label for="credits">Credits:</label>
            <select id="credits" name="credits" required>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
            
            <input type="submit" name="save" value="Save">
        </form>
        <div class="button-close">
            <form action="course_list.php?&user_id=<?php echo $user_id;?>" method="post">
                <input type="submit" name="cancel" value="Cancel">
            </form>
        </div>
    </div>
</body>

</html>
