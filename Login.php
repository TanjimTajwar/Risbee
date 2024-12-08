<?php
// Start session
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize it
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // SQL query to find the user
    $sql = "SELECT * FROM studentInfo WHERE username = '$username' AND Password = '$password'";
    $result = $conn->query($sql);

    // Check if any record matches
    if ($result->num_rows > 0) {
        // Store the username in session
        $_SESSION['username'] = $username;
        
        // Redirect to dashboard if username and password match
        header("Location: ../php/std_dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid username or password');</script>";
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
    <title>Login - Kadirdi College</title>
    <link rel="stylesheet" href="../css/SignUp.css">
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Kadirdi College</h2>
    </div>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <button type="submit">Login</button>
        </div>
    </form>

    <div class="register-link">
        <p>Don't have an account? <a href="../php/SignUp.php">Register</a></p>
    </div>
</div>

</body>
</html>
