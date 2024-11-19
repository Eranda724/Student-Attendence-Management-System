<?php 
    require_once('../connection.php'); 

    if (isset($_GET['user_id'])) {
        $u_id = $_GET['user_id'];
    }
    if (isset($_POST['user_id'])) {
        $u_id = $_POST['user_id'];
    }
    echo "$u_id";
?>


<!DOCTYPE html>
<html>
<head>
    <title>Lecture List</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #4CAF50;
            color: white;
            text-align: center;
        }

        table td {
            text-align: center;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        button, input[type="submit"] {
            padding: 8px 16px;
            margin: 10px;
            border: none;
            border-radius: 4px;
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover, input[type="submit"]:hover {
            background-color: #3e8e41;
        }

        button#back {
            background-color: #d9534f;
        }

        button#back:hover {
            background-color: #c9302c;
        }

        .actions form {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .actions button {
            flex: 1;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
        }

        #batch_no h3 {
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">

    <?php 

        $query = "SELECT `user_id`, `first_name`, `middle_name`, `last_name`, `email` FROM `user` WHERE `role`='lecture'";
        $record = mysqli_query($connection, $query);

        // Check if the query was successful
        if($record) {
            echo "<h1>Lecturers</h1>";
            echo "<hr>";
            echo "<table border='1'>";
            echo "<tr>
                      <th>Full Name</th>
                      <th>Profession</th>
                      <th>Email</th>
                      <th>Edit</th>
                      <th>Delete</th>
                  </tr>";

            // Loop through the records and display each lecturer in a table row
            while ($row = mysqli_fetch_assoc($record)) {
                $user_id = $row['user_id'];
                $full_name = $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name'];
                $email = $row['email'];

                // Query to fetch profession from the lecture table based on user_id
                $profession_query = "SELECT `profession` FROM `lecture` WHERE `lecture_id`='$user_id'";
                $profession_result = mysqli_query($connection, $profession_query);

                // Check if the profession query was successful
                if($profession_result && mysqli_num_rows($profession_result) > 0) {
                    $profession_row = mysqli_fetch_assoc($profession_result);
                    $profession = $profession_row['profession'];
                } else {
                    $profession = "Unknown"; // Default value if profession is not found
                }

                // Displaying data in table row
                echo "<tr>
                          <td>$full_name</td>
                          <td>$profession</td>
                          <td>$email</td>
                          <td>
                              <form action='lecture_edit.php?&u_id=$u_id' method='post'> 
                                  <input type='hidden' name='user_id' value='$user_id' >
                                  <input class='edit' type='submit' name='edit' value='Edit' >
                              </form>
                          </td>
                          <td>
                              <form action='lecture_delete.php?&u_id=$u_id' method='post'> 
                                  <input type='hidden' name='user_id' value='$user_id'>
                                  <input class='delete' type='submit' name='delete' value='Delete'>
                              </form>
                          </td>
                      </tr>";
            }

            echo "</table>";
        } else {
            echo "Error in database";
        }
    ?>
    <form class="container" action="lecture_add.php?&user_id=<?php echo $u_id;?>" method="post">
      <input type="submit" name="add" value="Add" style="background-color: blue; color: white;">
    </form>
    <button id="back" style="background-color: blue; color: white;">back</button>
    </div>

    <script>
        document.getElementById("back").onclick = function() {
            window.location.href = "MA.php?&user_id=<?php echo $u_id;?>";
        };
    </script>
    
</body>
</html>
