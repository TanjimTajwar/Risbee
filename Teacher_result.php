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

// Add result functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_result'])) {
    $roll = $_POST['roll'];
    $totalAttendance = $_POST['total_attendance'];
    $totalMarks = $_POST['total_marks'];
    $status = $_POST['status'];

    // Insert result into the database
    $stmt = $conn->prepare("INSERT INTO result (Roll, TotalAttendance, TotalMarks, Status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $roll, $totalAttendance, $totalMarks, $status);
    $stmt->execute();
}

// Fetch all result details
$sql = "SELECT result.ResultID, result.Roll, result.TotalAttendance, result.TotalMarks, result.Status, 
        studentInfo.StudentName, subject.CourseName
        FROM result
        JOIN studentInfo ON result.Roll = studentInfo.Roll
        JOIN attendance ON result.Roll = attendance.Roll
        JOIN subject ON attendance.CourseID = subject.CourseID
        ORDER BY result.Roll";
$stmt = $conn->prepare($sql);
$stmt->execute();
$resultData = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Result</title>
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

        /* Add result form */
        .add-result-form {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .add-result-form input, .add-result-form select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .add-result-form input[type="submit"] {
            background-color: #333;
            color: white;
            cursor: pointer;
        }

        .add-result-form input[type="submit"]:hover {
            background-color: #444;
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


    <h3>Teacher Result</h3>

    <!-- Display the result table -->
    <div class="dashboard-content">
        <table>
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Student Name</th>
                    <th>Roll Number</th>
                    <th>Total Attendance</th>
                    <th>Total Marks</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultData->num_rows > 0): ?>
                    <?php while ($result = $resultData->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $result['CourseName']; ?></td>
                            <td><?php echo $result['StudentName']; ?></td>
                            <td><?php echo $result['Roll']; ?></td>
                            <td><?php echo $result['TotalAttendance']; ?></td>
                            <td><?php echo $result['TotalMarks']; ?></td>
                            <td><?php echo $result['Status']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No result records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Add new result form -->
    <div class="add-result-form">
        <h3>Add Result</h3>
        <form method="POST" action="Teacher_result.php">
            <label for="roll">Student Roll Number:</label><br>
            <input type="number" name="roll" id="roll" required><br><br>

            <label for="total_attendance">Total Attendance:</label><br>
            <input type="number" name="total_attendance" id="total_attendance" required><br><br>

            <label for="total_marks">Total Marks:</label><br>
            <input type="number" name="total_marks" id="total_marks" required><br><br>

            <label for="status">Status:</label><br>
            <select name="status" id="status" required>
                <option value="Passed">Passed</option>
                <option value="Failed">Failed</option>
            </select><br><br>

            <input type="submit" name="add_result" value="Add Result">
        </form>
    </div>

</body>
</html>
