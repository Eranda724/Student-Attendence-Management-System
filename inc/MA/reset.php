<?php require_once('../connection.php');
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
}
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
}
echo "$user_id"; ?>
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
    background-image: url('image2.jpg'); /* Add your background image path here */
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    margin: 0;
    padding: 0;
    color: #fff;
    text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
}

h1, h2 {
    text-align: center;
    color: #fff;
    margin: 0;
    padding: 20px 0;
}

header {
    background-color: rgba(0, 0, 0, 0.8);
    padding: 20px 0;
    border-bottom: 2px solid #007bff;
}

nav {
    background-color: rgba(0, 0, 0, 0.8);
    padding: 10px;
    margin-bottom: 20px;
}

nav ul {
    list-style-type: none;
    padding: 0;
    text-align: center;
    margin: 0;
}

nav ul li {
    display: inline;
    margin-right: 20px;
}

nav ul li a {
    color: #fff;
    text-decoration: none;
    font-weight: bold;
}

form {
    max-width: 800px;
    margin: 20px auto;
    padding: 100px;
    background-color: rgba(255, 255, 255, 0.9);
    border-radius: 5px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
    color: #333;
}

label {
    display: block;
    margin-bottom: 10px;
}

input[type="email"],
input[type="password"],
input[type="submit"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

input[type="submit"] {
    background-color: #007bff;
    color: #fff;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

#attendance-table {
    width: 90%;
    margin: 0 auto;
    border-collapse: collapse;
    margin-bottom: 40px;
    background-color: rgba(255, 255, 255, 0.9);
}

#attendance-table th, #attendance-table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
    color: #333;
}

#attendance-table th {
    background-color: #007bff;
    color: #fff;
}

.submit-button {
    background-color: #007bff;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.submit-button:hover {
    background-color: #0056b3;
}

footer {
    text-align: center;
    padding: 10px;
    background-color: rgba(0, 0, 0, 0.8);
    color: #fff;
    position: fixed;
    width: 100%;
    bottom: 0;
}

    </style>
</head>

<body>
<h1>Student Attendance Management System</h1>
<hr>
  <div class="container">
    <h1>Reset Password</h1>
    <form action="reset.php" method="post">
      Password <input type="password" name="pw" required><br>
      Confirm password <input type="password" name="pw2" required><br>
      <?php
      if ($passwordMismatch) {
        echo "Passwords do not match. Please try again.";
      }
      ?><br>
      <br>
      <input type="hidden" name="user_id" value='<?php echo "$user_id"?>'>
      <input type="submit" name="change" value="change">
      <input type="hidden" name="user_id" value=<?php echo "$user_id"; ?>>
    </form>
    <form action="MA.php" method="post">
        <input type="hidden" name="user_id" value=<?php echo "$user_id"; ?>>
        <input type="submit" name="back" value="Back">
    </form>
    
  </div>




</body>

</html>