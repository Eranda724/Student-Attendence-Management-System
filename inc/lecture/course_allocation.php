<?php
require_once('../connection.php');

// Check if user_id is provided in the URL
if (isset($_GET['u_id'])) {
  $user_id = $_GET['u_id'];
} else {
  echo "User ID is missing!";
  exit;
}

// Retrieve available years and semesters
$year_query = "SELECT DISTINCT year FROM course";
$year_result = mysqli_query($connection, $year_query);

$semester_query = "SELECT DISTINCT semester FROM course";
$semester_result = mysqli_query($connection, $semester_query);
$course_list = array();


// Fetch available courses for the selected year and semester
if (isset($_POST['submit'])) {
  $selected_semester = $_POST['semester'];
  $selected_year = $_POST['year'];

  // Retrieve courses available in the selected year and semester
  $course_query = "SELECT C.course_code, C.course_id, 
    COALESCE(U.first_name, 'NULL') AS first_name, 
    COALESCE(U.last_name) AS last_name
    FROM course AS C 
    LEFT JOIN teach AS T ON C.course_id = T.course_id 
    LEFT JOIN lecture AS L ON T.lecture_id = L.lecture_id 
    LEFT JOIN user AS U ON L.lecture_id = U.user_id 
    WHERE C.year='$selected_year' AND C.semester='$selected_semester'
    ORDER BY C.course_code";


  $result_list = mysqli_query($connection, $course_query);

  // Filter courses taught by the lecture
}

?>

<!DOCTYPE html>
<html>

<head>
  <title>Course Allocation</title>
  <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            margin-left: 10px;
            
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
            margin-left: 100px;
        }


        #batch_no {
            margin-left: 40px;

        }

        form {
            margin-top: 20px;
            margin-bottom: 20px;
            margin-left: 20px;
            text-align: left;
        }

        select,
        input[type="submit"] {
            width: auto;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        table {
            width: 90%;
            border-collapse: collapse;
            align-items: center;
            margin-left: 2px;
            font-size: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            width: 50px;
        }

        th {
            background-color: #f2f2f2;
        }

        #back {
            margin-left: 40px;
            margin-bottom: 40px;
            size: 100%;
            width: 60px;
            height: 40px;
            font-size: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #form_1 {
            /* width: 95%; */
            float: right;
            font-size: 100%;
        }
    </style>
</head>

<body>
<h1>Student Attendance Management System</h1>
<hr>
  <div class="container">
    <h1>Course Allocation</h1>
    <form action="course_allocation.php?u_id=<?php echo $user_id; ?>" method="post">
      <label for="semester">Select Semester:</label>
      <select id="semester" name="semester">
        <?php
        while ($row = mysqli_fetch_assoc($semester_result)) {
          echo "<option value='" . $row['semester'] . "'>" . $row['semester'] . "</option>";
        }
        ?>
      </select>

      <label for="year">Select Year:</label>
      <select id="year" name="year">
        <?php
        while ($row = mysqli_fetch_assoc($year_result)) {
          echo "<option value='" . $row['year'] . "'>" . $row['year'] . "</option>";
        }
        ?>
      </select>

      <input type="submit" name="submit" value="Submit" style="background-color: blue; color: white;">
    </form>
    <?php
    if (isset($_POST['submit'])) {
      if (mysqli_num_rows($result_list) > 0) {
        echo '<table>';
        echo '<tr><th>Course Code</th><th>Lecturer</th></tr>';

        // Loop through each row in the result set
        while ($row = mysqli_fetch_assoc($result_list)) {
          echo '<tr>';
          echo '<td>' . $row['course_code'] . '</td>';
          echo '<td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>';
          echo '</tr>';
        }

        echo '</table>';
      } else {
        echo 'No courses available.';
      }
    }
    ?>
    <form action="lecture.php?user_id=<?php echo $user_id; ?>" method="post">
      <input type="submit" name="back" value="Back" style="background-color: blue; color: white;">
    </form>
  </div>
</body>

</html>