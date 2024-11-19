<?php require_once('../connection.php'); ?>

<?php

$passwordMismatch = false; // Flag to track password mismatch
if (isset($_GET['user_id'])) {
  $user_id = $_GET['user_id'];
}
if (isset($_POST['user_id'])) {
  $user_id = $_POST['user_id'];
}

if (isset($_POST['change'])) {
  $password = mysqli_real_escape_string($connection, $_POST['pw']);
  $confirmPassword = mysqli_real_escape_string($connection, $_POST['pw2']);

  // Check if passwords match
  if ($password !== $confirmPassword) {
    $passwordMismatch = true;
  } else {

    // Build the SQL query with proper quoting
    $query = "UPDATE `user` SET `password`='$password' WHERE `user_id`='$user_id';";

    $result = mysqli_query($connection, $query);

    if ($result) {
      echo "Password changing is sucssesfull.";
      
      echo "dsmld";
    } else {
      echo "Error";
    }
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Reset Password</title>
  <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            margin-left: 300px;
            
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
            margin-left: 100px;
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
    <form action="reset.php" method="post">
    <h2>Reset Password</h2>
      Password <input type="password" name="pw" required>
      <br><br>
      Confirm password <input type="password" name="pw2" required><br>
      <?php
      if ($passwordMismatch) {
        echo "Passwords do not match. Please try again.";
      }
      ?><br>
      <br>
      <input type="hidden" name="user_id" value='<?php echo "$user_id"?>'>
      <input type="submit" name="change" value="change" style="background-color: blue; color: white;">
    </form>

    <form action="lecture.php" method="post">
      <input type="hidden" name='user_id' value="<?php echo "$user_id"?>">
      <input type="submit" name="back" value="back" style="background-color: blue; color: white;">
    </form>
  </div>




</body>

</html>