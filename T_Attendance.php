<?php
// Start the session
session_start();

// Database connection
$servername = "localhost";  // Change this if needed
$username = "root";         // Your database username
$password = "";             // Your database password
$dbname = "std1";           // The database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add attendance functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_attendance'])) {
    $courseID = $_POST['course_id'];
    $roll = $_POST['roll'];
    $totalAttendance = $_POST['total_attendance'];
    $teacherID = $_POST['teacher_id'];

    // Insert attendance into the database
    $stmt = $conn->prepare("INSERT INTO attendance (CourseID, Roll, TotalAttendance, TeacherID) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiii", $courseID, $roll, $totalAttendance, $teacherID);
    $stmt->execute();
}

// Fetch all attendance details sorted by Roll number
$sql = "SELECT attendance.CourseID, attendance.Roll, attendance.TotalAttendance, attendance.TeacherID, 
        studentInfo.StudentName, subject.CourseName, teacher.Name AS TeacherName
        FROM attendance
        JOIN studentInfo ON attendance.Roll = studentInfo.Roll
        JOIN subject ON attendance.CourseID = subject.CourseID
        JOIN teacher ON attendance.TeacherID = teacher.TeacherID
        ORDER BY attendance.Roll";
$stmt = $conn->prepare($sql);
$stmt->execute();
$attendanceData = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Attendance</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h3 {
            text-align: center;
            font-size: 28px;
            margin-top: 20px;
            color: #333;
        }

        /* Navbar styles */
        .navbar {
            background-color: #333;
            overflow: hidden;
            text-align: center;
        }

        .navbar a {
            color: #f2f2f2;
            padding: 14px 20px;
            text-decoration: none;
            display: inline-block;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #333;
            color: white;
        }

        table td {
            background-color: #f9f9f9;
        }

        /* Add attendance form */
        .add-attendance-form {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .add-attendance-form input, .add-attendance-form select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .add-attendance-form input[type="submit"] {
            background-color: #333;
            color: white;
            cursor: pointer;
        }

        .add-attendance-form input[type="submit"]:hover {
            background-color: #444;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="../Tech/Teacher_dashboard.php">Dashboard</a>
        <a href="../Tech/teacher_mycourse.php">My Courses</a>
        <a href="../Tech/stu_attendance.php">Attendance</a>
        <a href="../Tech/Teacher_result.php">Result</a>
    </div>

    <h3>Teacher Attendance</h3>

    <!-- Display the attendance table -->
    <div class="dashboard-content">
        <table>
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Course Teacher</th>
                    <th>Student Name</th>
                    <th>Roll Number</th>
                    <th>Attendance out of 100</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($attendanceData->num_rows > 0): ?>
                    <?php while ($attendance = $attendanceData->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $attendance['CourseName']; ?></td>
                            <td><?php echo $attendance['TeacherName']; ?></td>
                            <td><?php echo $attendance['StudentName']; ?></td>
                            <td><?php echo $attendance['Roll']; ?></td>
                            <td><?php echo $attendance['TotalAttendance']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No attendance records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Add new attendance form -->
    <div class="add-attendance-form">
        <h3>Add Attendance</h3>
        <form method="POST" action="T_Attendance.php">
            <label for="course_id">Course ID:</label><br>
            <input type="number" name="course_id" id="course_id" required><br><br>

            <label for="roll">Student Roll Number:</label><br>
            <input type="number" name="roll" id="roll" required><br><br>

            <label for="total_attendance">Total Attendance:</label><br>
            <input type="number" name="total_attendance" id="total_attendance" required><br><br>

            <label for="teacher_id">Teacher ID:</label><br>
            <input type="number" name="teacher_id" id="teacher_id" required><br><br>

            <input type="submit" name="add_attendance" value="Add Attendance">
        </form>
    </div>

</body>
</html>
