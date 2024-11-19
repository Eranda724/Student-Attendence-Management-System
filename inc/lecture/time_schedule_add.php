<?php 
    require_once('../connection.php');

    // Check if user_id is provided in the URL
    if(isset($_GET['user_id'])){
        $user_id = $_GET['user_id'];
    } else {
        // If user_id is not provided, handle the error by redirecting to an error page or displaying a message
        echo "User ID is missing!";
        exit; // Stop further execution 
    }

    // Retrieve lecture name associated with user_id
    $lecture_query = "SELECT `first_name`, `middle_name`, `last_name` FROM `user` WHERE `user_id`='$user_id'";
    $lecture_result = mysqli_query($connection, $lecture_query);
    $lecture_row = mysqli_fetch_assoc($lecture_result);
    $lecture_name = $lecture_row['first_name'] . " " . $lecture_row['middle_name'] . " " . $lecture_row['last_name'];

    // Fetch available courses for the lecture
    $course_query = "SELECT `course_id` FROM `teach` WHERE `lecture_id`='$user_id'";
    $course_result = mysqli_query($connection, $course_query);

    // Initialize an array to store course options
    $course_options = array();

    // Loop through the courses and retrieve their course codes
    while ($course_row = mysqli_fetch_assoc($course_result)) {
        $course_id = $course_row['course_id'];
        // Fetch course code for the current course_id
        $course_code_query = "SELECT `course_code` FROM `course` WHERE `course_id`='$course_id'";
        $course_code_result = mysqli_query($connection, $course_code_query);
        $course_code_row = mysqli_fetch_assoc($course_code_result);
        $course_code = $course_code_row['course_code'];
        // Add course code to options array
        $course_options[$course_id] = $course_code;
    }

    // Check if form is submitted
    if(isset($_POST['submit'])) {
        // Retrieve form data
        $course_id = $_POST['course_id'];
        $chapter = $_POST['chapter'];
        $date = $_POST['date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

        // Insert the new row into the schedule table
        $query = "INSERT INTO `schedule`(`lecture_id`, `course_id`, `chapter`, `date`, `start_time`, `end_time`) VALUES ('$user_id', '$course_id', '$chapter', '$date', '$start_time', '$end_time')";
        $result = mysqli_query($connection, $query);

        if($result) {
            echo "New schedule entry added successfully!";
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Schedule Entry</title>
</head>
<body>
    <h1>Add Schedule Entry</h1>
    <p>Lecture Name: <?php echo $lecture_name; ?></p>
    
    <!-- Form to add a new schedule entry -->
    <form action="time_schedule_add.php?user_id=<?php echo $user_id; ?>" method="post">
        <label for="course_id">Course:</label>
        <select id="course_id" name="course_id" required>
            <?php foreach ($course_options as $course_id => $course_code) : ?>
                <option value="<?php echo $course_id; ?>"><?php echo $course_code; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="chapter">Chapter:</label>
        <input type="text" id="chapter" name="chapter" required><br>

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required><br>

        <label for="start_time">Start Time:</label>
        <input type="time" id="start_time" name="start_time" required><br>

        <label for="end_time">End Time:</label>
        <input type="time" id="end_time" name="end_time" required><br>

        <input type="submit" name="submit" value="Submit">
    </form>
    <form action="time_schedule.php?u_id=<?php echo $user_id; ?>" method="post">
        <input type="submit" name="" value="Back">
    </form>
</body>
</html>

