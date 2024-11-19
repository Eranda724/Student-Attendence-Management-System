<?php 
    require_once('../connection.php');
    if (isset($_GET['u_id'])) {
        $u_id = $_GET['u_id'];
    }
    if (isset($_POST['u_id'])) {
        $u_id = $_POST['u_id'];
    }

    // Check if user_id is set in POST data
    if(isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];

        // Delete the lecturer from the user table
        $user_delete_query = "DELETE FROM `user` WHERE `user_id`='$user_id'";
        $user_delete_result = mysqli_query($connection, $user_delete_query);

        // Check if the deletion query was successful
        if($user_delete_result) {
            header("Location: lecture_list.php?user_id=$u_id");
        } else {
            echo "Error deleting lecturer.";
        }
    } else {
        echo "User ID not provided.";
    }
?>