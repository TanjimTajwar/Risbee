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

// SQL query to get the student's result details based on the group and combined group
$resultSql = "SELECT s.CourseName, s.CourseID, t.Name AS TeacherName, 
                     a.TotalAttendance, r.TotalMarks, r.Status AS ResultStatus
              FROM subject s
              JOIN teacher t ON s.TeacherID = t.TeacherID
              LEFT JOIN attendance a ON a.CourseID = s.CourseID
              LEFT JOIN result r ON r.Roll = '$studentRoll' AND r.TotalMarks = s.TotalMarks
              WHERE s.CourseGroup = '$groupSub' OR s.CourseGroup = 'Combined'";

$resultQuery = $conn->query($resultSql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result - Kadirdi College</title>
    <link rel="stylesheet" href="../css/stu_result.css">
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
    <h3>Result Details</h3>

    <!-- Table to display result details -->
    <table>
        <tr>
            <th>Course Name</th>
            <th>Course ID</th>
            <th>Teacher Name</th>
            <th>% of Attendance</th>
            <th>Total Marks Obtained</th>
            <th>Pass Status</th>
            <th>Grade</th>
        </tr>

        <?php
        if ($resultQuery->num_rows > 0) {
            // Output data of each row
            while($row = $resultQuery->fetch_assoc()) {
                // Calculate percentage of attendance
                $attendancePercentage = $row['TotalAttendance'] != NULL ? ($row['TotalAttendance'] / 125) * 100 : 'Not Available';

                // Check if marks are NULL and set value accordingly
                $marks = $row['TotalMarks'];
                $marksStatus = $marks !== NULL ? $marks : 'Marks not uploaded yet';

                // Determine pass/fail status
                $passStatus = ($marks !== NULL && $marks >= 33) ? 'Passed' : ($marks === NULL ? 'Marks not uploaded yet' : 'Failed');

                // Determine the grade based on total marks
                if ($marks !== NULL) {
                    if ($marks >= 80) {
                        $grade = 'A';
                    } elseif ($marks >= 60) {
                        $grade = 'B';
                    } elseif ($marks >= 40) {
                        $grade = 'C';
                    } elseif ($marks >= 33) {
                        $grade = 'D';
                    } else {
                        $grade = 'Failed';
                    }
                } else {
                    $grade = 'Marks not uploaded yet';
                }

                echo "<tr>
                        <td>" . $row['CourseName'] . "</td>
                        <td>" . $row['CourseID'] . "</td>
                        <td>" . $row['TeacherName'] . "</td>
                        <td>" . $attendancePercentage . "%</td>
                        <td>" . $marksStatus . "</td>
                        <td>" . $passStatus . "</td>
                        <td>" . $grade . "</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No result data available.</td></tr>";
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
