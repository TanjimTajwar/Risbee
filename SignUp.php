<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "std1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $roll = $_POST['roll'];
    $username = $_POST['username'];
    $studentName = $_POST['studentName'];
    $password = $_POST['password'];
    $groupSub = $_POST['groupSub'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];

    // SQL query to insert the data into the studentInfo table
    $sql = "INSERT INTO studentInfo (Roll, username, StudentName, Password, GroupSub, Gender, Address) 
            VALUES ('$roll', '$username', '$studentName', '$password', '$groupSub', '$gender', '$address')";

    // Check if the query was successful
    if ($conn->query($sql) === TRUE) {
        // Redirect to login page after successful registration
        echo "<script>alert('Registration successful!'); window.location.href='../php/login.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Kadirdi College</title>
    <link rel="stylesheet" href="../css/SignUp.css">
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Kadirdi College</h2>
    </div>

    <form action="signup.php" method="POST">
        <div class="form-group">
            <label for="roll">Roll Number:</label>
            <input type="number" id="roll" name="roll" required>
        </div>

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>

        <div class="form-group">
            <label for="studentName">Student Name:</label>
            <input type="text" id="studentName" name="studentName" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="groupSub">Group:</label>
            <select id="groupSub" name="groupSub" required>
                <option value="Science">Science</option>
                <option value="Arts">Arts</option>
                <option value="Business">Business</option>
            </select>
        </div>

        <div class="form-group">
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
        </div>

        <div class="form-group">
            <button type="submit">Register</button>
        </div>
    </form>

    <div class="login-link">
        <p>Already Registered? <a href="../php/login.php">Login</a></p>
    </div>
</div>

</body>
</html>
