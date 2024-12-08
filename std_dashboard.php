<?php
// Start session to track the logged-in user
session_start();

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

// Check if the user is logged in (username stored in session)
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    echo "<script>window.location.href='../php/login.php';</script>";
    exit();
}

// Get the logged-in student's username
$loggedInUsername = $_SESSION['username'];

// SQL query to get student information
$sql = "SELECT StudentName FROM studentInfo WHERE username = '$loggedInUsername'";
$result = $conn->query($sql);

// Check if user exists and get the student's name
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $studentName = $row['StudentName'];
} else {
    // If no user found, log out and redirect to login page
    echo "<script>window.location.href='../php/login.php';</script>";
    exit();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Kadirdi College</title>
    <link rel="stylesheet" href="../css/std_dashboard.css">
</head>
<body>

<!-- Navbar 1 -->
<div class="navbar">
    <h2>Kadirdi College</h2>
</div>

<!-- Navbar 2 -->
<div class="navbar">
    <a href="../php/std_dashboard.php">Dashboard</a>
    <a href="../php/std_course.php">My Courses</a>
    <a href="../php/stu_attendance.php">Attendance</a>
    <a href="../php/Stu_Result.php">Result</a>
    <a href="../php/login.php">LogOut</a>
</div>

<!-- Dashboard Content -->
<div class="dashboard-content">
    <h3>Hello, <?php echo $studentName; ?>! How are you?</h3>
</div>

</body>
</html>
