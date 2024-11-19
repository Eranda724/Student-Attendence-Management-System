<!DOCTYPE html>
<html>
<head>
    <title>Delete Course</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: lightblue;
        }
        .container {
            width: 400px;
            margin: 100px auto;
            background-color: aliceblue;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
         
        h1 {
            text-align: center
        }
        form {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        input[type="submit"] {
            background-color: lightblue;
            border: none; 
            color: white; 
            padding: 15px 32px;
            text-align: center;
            font-size: 16px;
            font-family: Arial, Helvetica, sans-serif;
            margin-top: 20px;
            margin-left: 150px;
            cursor: pointer;
            border-radius: 10px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<?php
    // Include connection file
    require_once('../connection.php');

    // Check if course ID is set
    if (isset($_POST['course_id'])) {
        // Sanitize the course ID
        $course_id = mysqli_real_escape_string($connection, $_POST['course_id']);

        // Perform delete operation
        $query = "DELETE FROM enroll WHERE course_id = '$course_id'";
        $result = mysqli_query($connection, $query);

        // Check if delete operation was successful
        if ($result) { 
            // Deletion successful
            echo "<div class='container'><h1>Course deleted successfully.</h1></div>";
        } else {
            // Deletion failed
            echo "<div class='container'><h1>Error deleting course: " . mysqli_error($connection) . "</h1></div>";
        }
    } else {
        // Course ID not set
        echo "<div class='container'><h1>Course ID not provided.</h1></div>";
    }
?>
</body>
</html>
