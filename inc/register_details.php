<?php 
    require_once('connection.php');
    $user_id=$_GET['user_id'];

    if(isset($_POST['ok'])){

        $fname = mysqli_real_escape_string($connection, $_POST['Fname']);
        $mname = mysqli_real_escape_string($connection, $_POST['Mname']);
        $lname = mysqli_real_escape_string($connection, $_POST['Lname']);
        $level = mysqli_real_escape_string($connection, $_POST['level']);
        $batch_no = mysqli_real_escape_string($connection, $_POST['batch_no']);
        // $academic_year = mysqli_real_escape_string($connection, $_POST['academic_year']);
        $reg_no = mysqli_real_escape_string($connection, $_POST['reg_no']);
        $index_no = mysqli_real_escape_string($connection, $_POST['index_no']);

        $user_query="UPDATE `user` SET `first_name`='$fname' ,`middle_name`='$mname', `last_name`='$lname' WHERE `user_id`='$user_id' ";
        $user_result = mysqli_query($connection, $user_query);
        // $user_row = mysqli_fetch_array($user_result);

        $st_query="INSERT INTO `student`(`student_id`, `index_no`, `reg_no`, `batch_no`, `current_level`) VALUES ('$user_id','$index_no','$reg_no','$batch_no','$level')";
        $st_result=mysqli_query($connection,$st_query);
        // $st_row=mysqli_fetch_array($st_result);




        $loc = "ma_request.php?";
        $loc .= "Fname=" . $fname;
        $loc .= "&Mname=" . $mname;
        $loc .= "&Lname=" . $lname;
        $loc .= "&level=" . $level;
        $loc .= "&batch_no=" . $batch_no;
        $loc .= "&reg_no=" . $reg_no;
        $loc .= "&index_no=" . $index_no;

        header("Location: $loc");

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Management System</title>
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
    <form action="register_details.php?user_id=<?php echo $user_id?>" method="post">
        <label for="Fname">First Name</label>
        <input type="text" name="Fname" required><br>
        <label for="Mname">Middle Name</label>
        <input type="text" name="Mname" required><br>
        <label for="Lname">Last Name</label>
        <input type="text" name="Lname" required><br>
        <label for="level">Current Level</label>
        <input type="text" name="level" required><br>
        <label for="batch_no">Batch No</label>
        <input type="text" name="batch_no" required><br>
        <!-- <label for="academic_year">Academic Year</label> -->
        <!-- <input type="text" name="academic_year" required><br> -->
        <label for="reg_no">Registration No</label>
        <input type="text" name="reg_no" required><br>
        <label for="index_no">Index No</label>
        <input type="text" name="index_no" required><br>

        <a href="../index.php">Already a member? Log in</a>
        <input type="submit" name="ok" value="OK">
    </form>
</body>
</html>
