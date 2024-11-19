<?php require_once('../connection.php'); 
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
}
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
}
echo "$user_id";
?>

<?php 
    $course_id = $_GET['id'];
    $year = $_GET['year'];
    $sem = $_GET['sem'];
    $query = "DELETE FROM `course` WHERE `course_id`='$course_id' ";

    $record = mysqli_query($connection,$query);

    if(isset($_POST['delete_b'])){
        if($record){
            header('Location: course_list.php?year=' . $year . '&sem=' . $sem.'&user_id='.$user_id); // Redirect to course_list.php with year and sem parameters
        } else {
            echo "Something went wrong!";
        } 
    }
 ?>

 <!DOCTYPE html>
 <html>
 <head>
     <title>Course Delete</title>
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
    <div class="container">
        <h1>Confirm before delete!</h1><hr>

        <form action="course_delete.php?id=<?php echo $course_id; ?>&year=<?php echo $year; ?>&sem=<?php echo $sem; ?>&user_id=<?php echo $user_id;?>" method="post" > <!-- Pass id, year, and semester as parameters in the action URL -->
            <input type="submit" name="delete_b" value="DELETE">
        </form>
        <form action="course_list.php?year=<?php echo $year; ?>&sem=<?php echo $sem; ?>&user_id=<?php echo $user_id;?>" method="post" >
            <input type="submit" value="NO">
        </form>
    </div>
 </body>
 </html>
