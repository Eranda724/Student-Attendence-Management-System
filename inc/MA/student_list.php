<?php require_once('../connection.php');

// Get user ID from GET or POST request
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : (isset($_POST['user_id']) ? $_POST['user_id'] : '');
echo "$user_id";

// Fetch distinct batch numbers from the student table
$query = "SELECT DISTINCT batch_no FROM student ORDER BY batch_no ASC";
$result = mysqli_query($connection, $query);
$batch_nos = array();

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $batch_nos[] = $row['batch_no'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Management System - Batch and Students</title>
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

    <h1>Student Attendance Management System</h1>
    <div class="container">
        <h2>Batch and Students</h2>
        <form action="student_list.php?&user_id=<?php echo $user_id; ?>" method="post">
            <label for="batch_no">Select Batch:</label>
            <select name="batch" id="batch_no" required>
                <?php
                if (!empty($batch_nos)) {
                    foreach ($batch_nos as $batch_no) {
                        echo "<option value=\"$batch_no\">$batch_no</option>";
                    }
                }
                ?>
            </select>
            <input type="submit" name="ok" value="OK" style="background-color: blue; color: white;">
        </form>

        <?php
        if (isset($_POST['ok']) || isset($_POST['action_ex']) || isset($_GET['batch'])) {
            $batch = isset($_POST['batch']) ? $_POST['batch'] : $_GET['batch'];

            if (isset($_POST['action_ex'])) {
                $student_id = $_POST['student_id'];
                $action = $_POST['action_ex'];

                if ($action == 'edit') {
                    header("Location:student_list_edit.php?student_id=$student_id&user_id=$user_id&batch=$batch");
                    exit();
                } elseif ($action == 'delete') {
                    $q = "DELETE FROM `user` WHERE `user_id`=$student_id";
                    $result = mysqli_query($connection, $q);

                    if ($result) {
                        echo mysqli_affected_rows($connection) > 0 ? "Deleted" : "No rows affected.";
                    } else {
                        echo "Error: " . mysqli_error($connection);
                    }
                }
            }

            $query = "SELECT `student_id`, `reg_no` FROM `student` WHERE `batch_no`='$batch'";
            $records = mysqli_query($connection, $query);

            if ($records) {
                echo "<div id='batch_no'><h3>Batch: $batch</h3></div>";
                echo "<table>
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Registration Number</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>";

                while ($row = mysqli_fetch_assoc($records)) {
                    $student_id = $row['student_id'];
                    $reg_no = $row['reg_no'];

                    $qu = "SELECT `first_name`, `middle_name`, `last_name`, `email`, `status` FROM `user` WHERE `user_id`='$student_id'";
                    $result = mysqli_query($connection, $qu);

                    if ($result) {
                        $user_details = mysqli_fetch_assoc($result);
                        $full_name = $user_details['first_name'] . " " . $user_details['middle_name'] . " " . $user_details['last_name'];

                        echo "<tr>
                                <td>$full_name</td>
                                <td>{$user_details['email']}</td>
                                <td>$reg_no</td>
                                <td>{$user_details['status']}</td>
                                <td>
                                    <form method='POST' action='student_list.php' style='display:flex; gap:10px;'>
                                        <input type='hidden' name='student_id' value='$student_id' />
                                        <input type='hidden' name='batch' value='$batch' />
                                        <input type='hidden' name='user_id' value='$user_id' />
                                        <button type='submit' name='action_ex' value='edit'>Edit</button>
                                        <button type='submit' name='action_ex' value='delete'>Delete</button>
                                    </form>
                                </td>
                            </tr>";
                    }
                }
                echo "</table>";
            } else {
                echo "Error: " . mysqli_error($connection);
            }
        } else {
            $qu = "SELECT U.user_id, U.first_name, U.middle_name, U.last_name, U.email, S.index_no, S.reg_no, S.batch_no, S.current_level FROM student S JOIN user U ON S.student_id = U.user_id WHERE U.status = 'pending';";
            $re = mysqli_query($connection, $qu);

            if ($re) {
                if (mysqli_num_rows($re) > 0) {
                    echo "<div id='batch_no'><h3>Pending Students</h3></div>";
                    echo "<table>
                        <tr>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Index No</th>
                            <th>Reg No</th>
                            <th>Batch No</th>
                            <th>Current Level</th>
                            <th>Actions</th>
                        </tr>";

                    while ($row = mysqli_fetch_assoc($re)) {
                        $student_id = $row['user_id'];
                        echo "<tr>
                            <td>{$row['first_name']}</td>
                            <td>{$row['middle_name']}</td>
                            <td>{$row['last_name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['index_no']}</td>
                            <td>{$row['reg_no']}</td>
                            <td>{$row['batch_no']}</td>
                            <td>{$row['current_level']}</td>
                            <td>
                                <form method='POST' action='student_list.php' style='display:flex; gap:10px;'>
                                    <input type='hidden' name='student_id' value='$student_id' />
                                    <input type='hidden' name='user_id' value='$user_id' />
                                    <button type='submit' name='action' value='accept'>Accept</button>
                                    <button type='submit' name='action' value='delete'>Delete</button>
                                </form>
                            </td>
                        </tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<div id='batch_no'><h3>No Pending Students</h3></div>";
                }
            } else {
                echo "Error: " . mysqli_error($connection);
            }
        }
        ?>
        <button id="back">Back</button>
    </div>

    <script>
        document.getElementById("back").onclick = function() {
            window.location.href = "MA.php?&user_id=<?php echo $user_id; ?>";
        };
    </script>

</body>

</html>
