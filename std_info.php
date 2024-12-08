<?php
// Connect to the database
$servername = "localhost";
$username = "root";  // Change if necessary
$password = "";  // Change if necessary
$dbname = "std1";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch student information
$sql = "SELECT Roll, StudentName, GroupSub, Gender, Address FROM studentInfo";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information</title>
    <link rel="stylesheet" type="text/css" href="../CSS/std_info.css"> <!-- Link to the CSS -->
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

    <div class="dashboard-content">
        <h3>Student Information</h3>
        
        <?php
        // Check if there are any records
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Roll</th><th>Student Name</th><th>Group</th><th>Gender</th><th>Address</th></tr>";
            
            // Fetch and display each student record
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["Roll"] . "</td>";
                echo "<td>" . $row["StudentName"] . "</td>";
                echo "<td>" . $row["GroupSub"] . "</td>";
                echo "<td>" . $row["Gender"] . "</td>";
                echo "<td>" . $row["Address"] . "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
        } else {
            echo "<p>No records found.</p>";
        }

        // Close the connection
        $conn->close();
        ?>
        
    </div>

    <footer>
        <p>&copy; 1996 Kadirdi College. All Rights Reserved.</p>
    </footer>

</body>
</html>
