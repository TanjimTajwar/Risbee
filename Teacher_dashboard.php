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

// Check if the user is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: teacher_login.php");
    exit();
}

// Fetch teacher information from session
$teacherID = $_SESSION['teacher_id']; // Assuming the teacher's ID is stored in the session
$teacherName = $_SESSION['teacher_name']; // Assuming the teacher's name is stored in the session

// Add course functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_course'])) {
    $courseName = $_POST['course_name'];
    $courseGroup = $_POST['course_group'];
    $totalMarks = $_POST['total_marks'];

    // Insert new course into the database
    $stmt = $conn->prepare("INSERT INTO subject (CourseName, CourseGroup, TotalMarks, TeacherID, TeacherName) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $courseName, $courseGroup, $totalMarks, $teacherID, $teacherName);
    $stmt->execute();
}

// Delete course functionality
if (isset($_GET['delete_course'])) {
    $courseID = $_GET['delete_course'];

    // Delete the course from the database
    $stmt = $conn->prepare("DELETE FROM subject WHERE CourseID = ?");
    $stmt->bind_param("i", $courseID);
    $stmt->execute();
}
// Fetch courses assigned to the teacher
$sql = "SELECT * FROM subject WHERE TeacherID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacherID);
$stmt->execute();
$courses = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses</title>
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

        /* Result styling */
        table td, table th {
            font-size: 16px;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:nth-child(odd) {
            background-color: #ffffff;
        }

        /* Footer or no result message */
        table td[colspan="5"] {
            text-align: center;
            font-size: 18px;
            color: red;
            background-color: #ffcccb;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="../Tech/Teacher_dashboard.php">Dashboard</a>
        <a href="../Tech/teacher_mycourse.php">My Courses</a>
        <a href="../Tech/T_Attendance.php">Attendance</a>
        <a href="../Tech/Teacher_result.php">Result</a>
    </div>

    <h3>My Courses</h3>

    <!-- Display the course table -->
    <div class="dashboard-content">
        <table>
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Course Teacher</th>
                    <th>Total Marks</th>
                    <th>Course Group</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($courses->num_rows > 0): ?>
                    <?php while ($course = $courses->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $course['CourseName']; ?></td>
                            <td><?php echo $course['TeacherName']; ?></td>
                            <td><?php echo $course['TotalMarks']; ?></td>
                            <td><?php echo $course['CourseGroup']; ?></td>
                            <td><a href="myCourses.php?delete_course=<?php echo $course['CourseID']; ?>">Delete</a></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No courses found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Add new course form -->
        <h3>Add New Course</h3>
        <form method="POST" action="myCourses.php">
            <label for="course_name">Course Name:</label><br>
            <input type="text" name="course_name" id="course_name" required><br><br>

            <label for="course_group">Course Group:</label><br>
            <select name="course_group" id="course_group" required>
                <option value="Science">Science</option>
                <option value="Arts">Arts</option>
                <option value="Business">Business</option>
                <option value="Combined">Combined</option>
            </select><br><br>

            <label for="total_marks">Total Marks:</label><br>
            <input type="number" name="total_marks" id="total_marks" required><br><br>

            <input type="submit" name="add_course" value="Add Course">
        </form>
    </div>
</body>
</html>
