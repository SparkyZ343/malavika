<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: Login.php");
    exit();
}

// Include the database connection
include 'db_connection.php';

// Fetch student details from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM students WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id); // "i" indicates the type of the parameter (integer)
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Close the prepared statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile - Student Performance Management System</title>
    <link rel="stylesheet" href="../css/StudentProfile.css">
</head>
<body>
    <nav>
        <a href="index.html">Home</a>
        <a href="logout.php">Logout</a>
        <a href="StudentDashboard.php">Dashboard</a>
    </nav>

    <div class="container">
        <h1>Student Profile</h1>

        <div class="profile">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($student['first_name']) . ' ' . htmlspecialchars($student['last_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($student['date_of_birth']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($student['gender']); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($student['phone_number']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($student['address']); ?></p>
            <p><strong>Enrollment Date:</strong> <?php echo htmlspecialchars($student['enrollment_date']); ?></p>
            
            <a href="EditStudentProfile.php" class="button">Edit Profile</a>
        </div>
    </div>

    <footer>
        &copy; 2024 Student Performance Management System
    </footer>
</body>
</html>
