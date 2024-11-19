<?php
require_once('../connection.php');
if (isset($_GET['user_id'])) {
    $u_id = $_GET['user_id'];
}
if (isset($_POST['user_id'])) {
    $u_id = $_POST['user_id'];
}
echo "$u_id";
$passwordMismatch = false;

if (isset($_POST['add_lec'])) {
    // Retrieve form data
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $profession = $_POST['profession'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $passwordMismatch = true;
    } else {
        // Hash the password


        // Insert lecturer details into user table
        $user_insert_query = "INSERT INTO `user` (`first_name`, `middle_name`, `last_name`, `email`, `password`, `role`) VALUES ('$first_name', '$middle_name', '$last_name', '$email', '$password', 'lecture')";
        $user_insert_result = mysqli_query($connection, $user_insert_query);

        // Get the user_id of the newly inserted lecturer
        $user_id = mysqli_insert_id($connection);

        // Insert lecturer's profession into lecture table
        $lecture_insert_query = "INSERT INTO `lecture` (`lecture_id`, `profession`) VALUES ('$user_id', '$profession')";
        $lecture_insert_result = mysqli_query($connection, $lecture_insert_query);

        // Check if both insertions were successful
        if ($user_insert_result && $lecture_insert_result) {
            header("Location: lecture_list.php?user_id=$u_id");
        } else {
            echo "Error adding lecturer.";
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add Lecturer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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

        .register-link {
            text-align: center;
        }

        .register-link a {
            color: #007bff;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>

<body>
    <h1>Add Lecturer</h1>
    <form action="lecture_add.php?user_id=<?php echo $u_id;?>" method="post">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br>
        <br>
        <label for="middle_name">Middle Name:</label>
        <input type="text" id="middle_name" name="middle_name"><br>
        <br>
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <?php
        if ($passwordMismatch) {
            echo "<span class='error'>Passwords do not match. Please try again.</span>";
        }
        ?>

        <label for="profession">Profession:</label>
        <input type="text" id="profession" name="profession" required><br>
        <br>
        <input type="submit" name="add_lec" value="Add Lecturer">
    </form>


</body>

</html>