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

// SQL query to get the group of the logged-in student
$sql = "SELECT GroupSub FROM studentInfo WHERE username = '$loggedInUsername'";
$result = $conn->query($sql);

// Check if user exists and get the student's group
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $studentGroup = $row['GroupSub'];
} else {
    // If no user found, log out and redirect to login page
    echo "<script>window.location.href='../php/login.php';</script>";
    exit();
}

// SQL query to fetch courses based on the student's group
$sql_courses = "SELECT s.CourseID, s.CourseName, t.Name AS TeacherName 
                FROM subject s
                INNER JOIN teacher t ON s.TeacherID = t.TeacherID
                WHERE s.CourseGroup = '$studentGroup'";

$coursesResult = $conn->query($sql_courses);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - Kadirdi College</title>
    <link rel="stylesheet" href="../css/std_attendance.css"> <!-- Using same CSS -->
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

<!-- Courses Table -->
<div class="dashboard-content">
    
    <h3>My Courses - <?php echo $studentGroup; ?> Student</h3>
    <?php if ($coursesResult->num_rows > 0): ?>
        <center>
        <table border="1">
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Teacher's Name</th>
            </tr>
            <?php while($row = $coursesResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['CourseID']; ?></td>
                    <td><?php echo $row['CourseName']; ?></td>
                    <td><?php echo $row['TeacherName']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        </center>
    <?php else: ?>
        <p>No courses available for your group.</p>
    <?php endif; ?>
</div>

</body>
</html>
