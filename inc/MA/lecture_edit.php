<?php 
    require_once('../connection.php');
    if (isset($_GET['u_id'])) {
        $u_id = $_GET['u_id'];
    }
    if (isset($_POST['u_id'])) {
        $u_id = $_POST['u_id'];
    }
    echo "$u_id";

    if(isset($_POST['update'])){
        // Retrieve user_id and other form data
        $user_id = $_POST['user_id'];
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $profession = $_POST['profession'];

        // Update user table
        $user_update_query = "UPDATE `user` SET `first_name`='$first_name', `middle_name`='$middle_name', `last_name`='$last_name', `email`='$email' WHERE `user_id`='$user_id'";
        $user_update_result = mysqli_query($connection, $user_update_query);

        // Update lecture table
        $lecture_update_query = "UPDATE `lecture` SET `profession`='$profession' WHERE `lecture_id`='$user_id'";
        $lecture_update_result = mysqli_query($connection, $lecture_update_query);

        // Check if both updates were successful
        if($user_update_result && $lecture_update_result) {
            header("Location: lecture_list.php?user_id=$u_id");
        } else {
            echo "Error updating lecturer details.";
        }
    }

    // Check if user_id is set
    if(isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];

        // Query to fetch user details from the user table
        $user_query = "SELECT `first_name`, `middle_name`, `last_name`, `email` FROM `user` WHERE `user_id`='$user_id'";
        $user_record = mysqli_query($connection, $user_query);

        // Query to fetch lecturer details from the lecture table
        $lecturer_query = "SELECT `profession` FROM `lecture` WHERE `lecture_id`='$user_id'";
        $lecturer_record = mysqli_query($connection, $lecturer_query);

        // Check if both queries were successful
        if($user_record && $lecturer_record) {
            // Fetch user details
            $user_details = mysqli_fetch_assoc($user_record);

            // Fetch lecturer details
            $lecturer_details = mysqli_fetch_assoc($lecturer_record);
        } else {
            echo "Error fetching data from database.";
            exit();
        }
    } else {
        echo "User ID not provided.";
        exit();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Lecturer Details</title>
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
<h1>Student Attendance Management System</h1>
<hr>
    <h2>Edit Lecturer Details</h2>
    <form action="lecture_edit.php?&u_id=<?php echo $u_id;?>" method="post">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo $user_details['first_name']; ?>"><br>
        
        <label for="middle_name">Middle Name:</label>
        <input type="text" id="middle_name" name="middle_name" value="<?php echo $user_details['middle_name']; ?>"><br>
        
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo $user_details['last_name']; ?>"><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $user_details['email']; ?>"><br>
        <br>
        <label for="profession">Profession:</label>
        <input type="text" id="profession" name="profession" value="<?php echo $lecturer_details['profession']; ?>"><br>

        <input type="submit" name="update" value="Update">
    </form>
</body>
</html>
