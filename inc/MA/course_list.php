<?php require_once('../connection.php');
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
}
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
}
echo "$user_id";
?>

<!DOCTYPE html>
<html>

<head>
    <title></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('image1.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1, h2 {
            text-align: center;
            color: #030202;
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        select, input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            background-color: #a11616;
            color: #fff;
            cursor: pointer;
            font-size: 14px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .button-container {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
            margin-top: 10px;
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
<h1>Student Attendance Management System</h1>
    <div class="container">
        <?php

        // Query to fetch distinct years
        $year_query = "SELECT DISTINCT `year` FROM `course`";
        $year_result = mysqli_query($connection, $year_query);

        // Array to store available years
        $available_years = array();

        // Fetching available years and populating the array
        while ($row = mysqli_fetch_assoc($year_result)) {
            $available_years[] = $row['year'];
        }

        // Check if semester and year are received via $_GET
        if (isset($_GET['year']) && isset($_GET['sem'])) {
            $year = $_GET['year'];
            $sem = $_GET['sem'];
        } else {
            // If not received, use form inputs
            if (isset($_POST['ok'])) {
                $year = $_POST['year'];
                $sem = $_POST['sem'];
            }
        }

        // Fetch courses based on year and semester
        if (isset($year) && isset($sem)) {
            $query = "SELECT `course_id`,`course_name`,`course_code`,`credits`,`lecture_hours` FROM `course` WHERE `year`='$year' AND `semester`='$sem'";
            $record = mysqli_query($connection, $query);

            if ($record) {
                $table = '<table>';
                $table .= '<caption>Semester: ' . $sem . '|  Year: ' . $year . '</caption>' . '<hr>';
                $table .= '<tr><th>Name</th> <th>Code</th> <th>Credits</th> <th>Lecture Hours</th> <th>Action</th></tr>';
                while ($result = mysqli_fetch_assoc($record)) {
                    $table .= "<tr>";
                    $table .= '<td>' . $result['course_name'] . '</td>';
                    $table .= '<td>' . $result['course_code'] . '</td>';
                    $table .= '<td>' . $result['credits'] . '</td>';
                    $table .= '<td>' . $result['lecture_hours'] . '</td>';
                    $table .= '<td><a href="course_edit.php?id=' . $result['course_id'] . '&year=' . $year . '&sem=' . $sem . '&user_id=' . $user_id . '">Edit</a> | <a href="course_delete.php?id=' . $result['course_id'] . '&year=' . $year . '&sem=' . $sem . '&user_id=' . $user_id . '">Delete</a></td>';
                    $table .= "</tr>";
                }
                $table .= '</table>';
            }
        }
        ?>
        
        <hr>

        <!-- Form to select year and semester -->
        <form action="course_list.php?user_id=<?php echo $user_id; ?>" method="post">

        <h2>Course List</h2>
        <hr>
        <br>
            <!-- Dynamic generation of year options -->
            year-<select name="year" required>
                <?php foreach ($available_years as $year) { ?>
                    <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                <?php } ?>
            </select>

            semester-<select name="sem" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
            </select>
            
    
            <input style="margin-top: 20px;" type="submit" name="ok" value="OK">

        </form>

        <!-- Display the table if records are fetched -->
        <?php if (isset($table)) {
            echo $table;
        } ?>
        <br>
<hr>
        <form action="course_add.php?&user_id=<?php echo $user_id; ?>" method="post">
            <input type="submit" name="add" value="Add">
        </form>

        <form action="MA.php?&user_id=<?php echo $user_id; ?>" method="post">

            <input type="submit" name="back" value="back">
        </form>
    </div>
</body>

</html>