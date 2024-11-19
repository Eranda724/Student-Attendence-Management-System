<?php require_once('../connection.php'); ?><br>

<?php 

	$passwordMismatch = false;
	$oldpasswordMismatch=false;
	$u_id=$_GET['user_id'];
		 
	$u_id= mysqli_real_escape_string($connection, $u_id);

	$query = "SELECT `password`,`first_name`, `middle_name`, `last_name` FROM `user` WHERE `user_id` = $u_id";

	$record=mysqli_query($connection,$query);

	if($record){
		$result=mysqli_fetch_assoc($record);
		$name = $result['first_name']." ".$result['middle_name']." ".$result['last_name'];
		$old_pw=$result['password'];
	}else{
		echo "Error in database";
	}

	if(isset($_POST['reset'])){
		$old_password= mysqli_real_escape_string($connection, $_POST['old_password']);
		$new_password = mysqli_real_escape_string($connection, $_POST['new_pw']);
        $confirm_password = mysqli_real_escape_string($connection, $_POST['confirm_new_pw']);

        if($old_password !==$old_pw){
        	$oldpasswordMismatch =true;
        }else{
        	if($new_password !== $confirm_password){
        		$passwordMismatch =true;
        	}else{
        		$query ="UPDATE `user` SET `password`='$new_password'WHERE `user_id`='$u_id' ";
        		$result = mysqli_query($connection, $query);

            	if($result){
            		echo "Password reset successfull";
            	} else {
                	echo "Error";
            	}
        	}
        }
	}else{
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $selectedOption = $_POST['wh'];

        switch($selectedOption) {
            case "reset":
                header("Location: ../../index.php");
                break;
            case "dashboard":
                $loc="student.php?user_id=".$u_id;
                header("Location: $loc");
                exit();
            case "logout":
                header("Location: ../../index.php");
                break;
            default:
                break;
        }
    }
    }
 ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .menu-icon {
            padding: 10px;
            background-color: #a9a9a9;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        #menu-toggle {
            display: none;
        }

        #menu-toggle:checked+.popup {
            display: block;
        }

        .popup {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            top: 40px;
            right: 10px;
            z-index: 1;
        }

        .popup a {
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            color: #333;
        }

        .popup a:hover {
            background-color: #ddd;
        }

        h2 {
            position: absolute;
            left: 410px;
            top: 40px;
            margin-top: 0;
            color: #333;
        }

        p {
            margin: 5px 0;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<h1 style="text-align: center;">Student Attendance Management System</h1>
<hr>
    <div class="container">
    <h1>Password Reset</h1>
    <hr>
        <form method="post">
            <select name="wh" onchange="this.form.submit()" placeholder="Select Action">
                <option value="" disabled selected hidden>Select</option>
                <option value="reset">Password Reset</option>
                <option value="dashboard">Dashboard</option>
                <option value="logout">Log Out</option>
            </select>
        </form>
        <br><br><br><br>
        <?php $loc="reset_pw.php?user_id=".$u_id; ?>
        <form action="<?php echo $loc; ?>" method="post">
        <label for="password">Old Password:</label>
            <input type="password" name="old_password">
            <?php 
                if ($oldpasswordMismatch) {
                    echo "Old Passwords do not match. Please try again.";
                }
            ?>
            <br><br>
            <label for="new_pw">New Password:   </label>
            <input type="password" name="new_pw"><br><br>
            <label for="confirm_new_pw">Confirm Password:</label>
            <input type="password" name="confirm_new_pw">
            <?php 
                if ($passwordMismatch) {
                    echo "Passwords do not match. Please try again.";
                }
            ?>
            <br><br>
            <input type="submit" name="reset" value="Reset">
        </form>
        
    </div>
</body>
</html>



