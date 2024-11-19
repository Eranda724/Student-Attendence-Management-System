<?php require_once("../connection.php");?>

<?php 
    $course_id = $_GET['course_id'];

    $year;

    $query = "SELECT `student_id` FROM `enroll` WHERE `course_id`='$course_id'";
    $record = mysqli_query($connection, $query);
    $student = array();

    if ($record) {
        while ($result = mysqli_fetch_assoc($record)) {
            $student[] = $result['student_id'];
        }
    }
   
    $student_batch = array();

    foreach ($student as $st) {
        //$std = $st;
        $query = "SELECT `batch_no` FROM `student` WHERE `student_id`='$st'";
        $record = mysqli_query($connection, $query);

        if ($record) {
            $result = mysqli_fetch_assoc($record);
            $batch_no = $result['batch_no'];

            $query = "SELECT `year` FROM `course` WHERE `course_id`='$course_id'";
            $record = mysqli_query($connection, $query);

            if ($record) {
                $year = mysqli_fetch_assoc($record);

                if ($batch_no != $year['year']) {
                    $student_batch[] = $st;
                }
            }
        }
    }
 ?>

 <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Students Enrolled</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: lightblue;
        }
        .container{
            width: 400px;
			margin: 100px auto;
			background-color:aliceblue;
			border-radius: 10px;
			padding: 20px;
			box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
        }
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
            background-color: aliceblue;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        button {
            background-color: lightblue;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            font-size: 16px;
            font-family: Arial, Helvetica, sans-serif;
            margin-top: 20px;
            margin-left: 150px;
            cursor: pointer;
            border-radius: 10px;
            display: block;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
    <h1>Students Enrolled in Course</h1>
    <table>
        <thead>
            <tr>
                <th>Student Registration Number</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach ($student_batch as $student_id) {
                    // Fetch student details from database based on student ID
                    $query = "SELECT reg_no FROM student WHERE student_id='$student_id'";
                    $record = mysqli_query($connection, $query);
                    if ($record) {
                        $student_details = mysqli_fetch_assoc($record);
                        echo "<tr>";
                        echo "<td>{$student_details['reg_no']}</td>";
                        echo "</tr>";
                    }
                }
            ?>
        </tbody>
    </table>
    
    <button onclick="goBack()">Back</button>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
    </div>
</body>
</html>
