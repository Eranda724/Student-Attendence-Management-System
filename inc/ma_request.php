<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-image: url('image1.jpg'); /* Add your background image path here */
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

		<h2>Request has been received by MA</h2>

		<form action="../index.php" action="post">
			<input type="submit" value="Back">
		</form>
</body>
</html>
