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
$sql = "SELECT Roll, GroupSub FROM studentInfo WHERE username = '$loggedInUsername'";
$result = $conn->query($sql);

// Check if user exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $studentRoll = $row['Roll'];
    $groupSub = $row['GroupSub'];
} else {
    echo "<script>window.location.href='../php/login.php';</script>";
    exit();
}

// SQL query to get the student's attendance details based on the group
$attendanceSql = "SELECT s.CourseName, s.CourseID, t.Name AS TeacherName, a.TotalAttendance
                  FROM subject s
                  JOIN teacher t ON s.TeacherID = t.TeacherID
                  LEFT JOIN attendance a ON a.CourseID = s.CourseID AND a.Roll = '$studentRoll'
                  WHERE s.CourseGroup = '$groupSub'";

$attendanceResult = $conn->query($attendanceSql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance - Kadirdi College</title>
    <link rel="stylesheet" href="../css/std_attendance.css">
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
    <h3>Attendance Details</h3>

    <!-- Table to display attendance details -->
    <table>
        <tr>
            <th>Course Name</th>
            <th>Course ID</th>
            <th>Teacher Name</th>
            <th>Total Attendance (Out of 50)</th>
        </tr>

        <?php
        if ($attendanceResult->num_rows > 0) {
            // Output data of each row
            while($attendanceRow = $attendanceResult->fetch_assoc()) {
                $totalAttendance = $attendanceRow['TotalAttendance'] != NULL ? $attendanceRow['TotalAttendance'] : 'Not Added by the Teacher Yet';
                echo "<tr>
                        <td>" . $attendanceRow['CourseName'] . "</td>
                        <td>" . $attendanceRow['CourseID'] . "</td>
                        <td>" . $attendanceRow['TeacherName'] . "</td>
                        <td>" . $totalAttendance . "</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No courses found for your group.</td></tr>";
        }
        ?>

    </table>

</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
