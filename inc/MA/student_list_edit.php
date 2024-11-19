<?php
require_once('../connection.php');
if (isset($_GET['user_id'])) {
  $user_id = $_GET['user_id'];
}
if (isset($_POST['user_id'])) {
  $user_id = $_POST['user_id'];
}
echo "$user_id";
$batch=$_GET['batch'];

if (isset($_POST['update'])) {
  // Retrieve user_id and other form data
  $student_id = $_POST['student_id'];
  $first_name = $_POST['first_name'];
  $middle_name = $_POST['middle_name'];
  $last_name = $_POST['last_name'];
  $email = $_POST['email'];
  $status = $_POST['status'];
  $index_no = $_POST['index_no'];
  $reg_no = $_POST['reg_no'];
  $batch_no = $_POST['batch_no'];
  $current_level = $_POST['current_level'];

  // Update user table
  // Update user table
  $user_update_query = "UPDATE `user` SET `first_name`='$first_name', `middle_name`='$middle_name', `last_name`='$last_name', `email`='$email', `status`='$status' WHERE `user_id`='$student_id'";
  mysqli_query($connection, $user_update_query);
  $user_update_result = mysqli_query($connection, $user_update_query);

  // Update student table
  $student_update_query = "UPDATE `student` SET `index_no`='$index_no', `reg_no`='$reg_no', `batch_no`='$batch_no', `current_level`='$current_level' WHERE `student_id`='$student_id'";
  mysqli_query($connection, $student_update_query);
  $student_update_result = mysqli_query($connection, $student_update_query);

  // Check if both updates were successful
  if ($user_update_result && $student_update_result) {
    header("Location: student_list.php?user_id=$user_id&batch=$batch");
  } else {
    echo "Error updating lecturer details.";
  }
}

// Check if user_id is set
if (isset($_GET['student_id'])) {
  $student_id = $_GET['student_id'];
  $user_query = "SELECT U.first_name, U.middle_name, U.last_name, U.email, U.status, S.index_no, S.reg_no, S.batch_no, S.current_level FROM user U JOIN student S ON U.user_id = S.student_id WHERE U.user_id = '$student_id'";
  $user_record = mysqli_query($connection, $user_query);

  // Check if both queries were successful
  if ($user_record) {
    // Fetch user details
    $user_details = mysqli_fetch_assoc($user_record);
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
  <h1>Edit Lecturer Details</h1>
  <form action="student_list_edit.php?&batch=<?php echo $batch; ?>" method="post">

    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" value="<?php echo $user_details['first_name']; ?>"><br>

    <label for="middle_name">Middle Name:</label>
    <input type="text" id="middle_name" name="middle_name" value="<?php echo $user_details['middle_name']; ?>"><br>

    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" value="<?php echo $user_details['last_name']; ?>"><br>

    <label for="email">Email:</label>
    <input type="text" id="email" name="email" value="<?php echo $user_details['email']; ?>"><br>

    <label for="status">Status:</label>
    <input type="text" id="status" name="status" value="<?php echo $user_details['status']; ?>"><br>

    <label for="index_no">Index_no:</label>
    <input type="text" id="index_no" name="index_no" value="<?php echo $user_details['index_no']; ?>"><br>

    <label for="reg_no">Reg no:</label>
    <input type="text" id="reg_no" name="reg_no" value="<?php echo $user_details['reg_no']; ?>"><br>

    <label for="batch_no">Batch No:</label>
    <input type="text" id="batch_no" name="batch_no" value="<?php echo $user_details['batch_no']; ?>"><br>

    <label for="current_level">Current Level:</label>
    <input type="text" id="current_level" name="current_level" value="<?php echo $user_details['current_level']; ?>"><br>

    <input type="submit" name="update" value="Update">
  </form>
</body>

</html>