<?php
// TeacherProfile.php
session_start();
include 'db_connection.php';

// Assuming you have a session variable storing the logged-in user's ID
$loggedInUserId = $_SESSION['user_id'];

// Fetch teacher details
$sql = "SELECT u.username, u.email, t.first_name, t.last_name, t.phone_number, t.hire_date
        FROM users u
        JOIN teachers t ON u.user_id = t.user_id
        WHERE u.user_id = $loggedInUserId";
$result = $conn->query($sql);
$teacher = $result->fetch_assoc();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Profile - Student Performance Management System</title>
    <link rel="stylesheet" href="../css/TeacherProfile.css">
</head>
<body>
    <nav>
        <a href="index.html">Home</a>
        <a href="logout.html">Logout</a>
        <a href="TeacherProfile.php">My Profile</a>
    </nav>
    <div class="container">
        <h1>Teacher Profile</h1>
        <div class="profile-details">
            <p><strong>Username:</strong> <?php echo $teacher['username']; ?></p>
            <p><strong>Email:</strong> <?php echo $teacher['email']; ?></p>
            <p><strong>First Name:</strong> <?php echo $teacher['first_name']; ?></p>
            <p><strong>Last Name:</strong> <?php echo $teacher['last_name']; ?></p>
            <p><strong>Phone Number:</strong> <?php echo $teacher['phone_number']; ?></p>
            <p><strong>Hire Date:</strong> <?php echo $teacher['hire_date']; ?></p>
            <a href="EditTeacherProfile.php" class="button">Edit Profile</a>
        </div>
    </div>
    <footer>
        &copy; 2024 Student Performance Management System
    </footer>
</body>
</html>
