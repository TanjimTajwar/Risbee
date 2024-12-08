<?php
// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the input values
    $admin = $_POST['admin'];
    $password = $_POST['password'];

    // Check if the admin and password are correct
    if ($admin == 'Risbee' && $password == '1234') {
        // Store session data
        $_SESSION['admin'] = $admin;  // Store the username in the session

        // Redirect to the teacher dashboard
        header("Location: teacher_dashboard.php");
        exit();
    } else {
        $error_message = "Wrong username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
</head>
<body>
    <h2>Teacher Login</h2>
    
    <!-- Display error message if any -->
    <?php if (isset($error_message)) { echo "<p style='color: red;'>$error_message</p>"; } ?>

    <form method="POST" action="teacher_login.php">
        <label for="admin">Admin:</label><br>
        <input type="text" name="admin" id="admin" required><br><br>
        
        <label for="password">Password:</label><br>
        <input type="password" name="password" id="password" required><br><br>
        
        <input type="submit" value="Submit"><br><br>
    </form>

    <p>Are you a student? <a href="../php/login.php">Click Here</a></p>
</body>
</html>
