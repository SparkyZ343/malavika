<?php
session_start();  // Make sure session_start() is at the beginning

// Include database connection
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);  // 's' means string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Compare the password directly (no hashing)
        if ($password == $row['password']) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            // Fetch teacher_id if the user is a teacher
            if ($row['role'] == 'teacher') {
                $teacher_sql = "SELECT teacher_id FROM teachers WHERE user_id = ?";
                $stmt = $conn->prepare($teacher_sql);
                $stmt->bind_param('i', $row['user_id']);
                $stmt->execute();
                $teacher_result = $stmt->get_result();
                if ($teacher_result->num_rows > 0) {
                    $teacher_row = $teacher_result->fetch_assoc();
                    $_SESSION['teacher_id'] = $teacher_row['teacher_id'];
                }
            }

            // Redirect based on role
            if ($row['role'] == 'admin') {
                header("Location: AdminDashboard.html");
            } elseif ($row['role'] == 'teacher') {
                header("Location: TeacherDashboard.html");
            } elseif ($row['role'] == 'student') {
                header("Location: StudentDashboard.html");
            }
            exit();  // Stop further code execution after redirect
        } else {
            echo "<p style='color: red;'>Invalid password.</p>";
        }
    } else {
        echo "<p style='color: red;'>No user found with this email.</p>";
    }

    // Close the prepared statement
    $stmt->close();
}

$conn->close();  // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student Performance Management System</title>
    <link rel="stylesheet" href="../css/Login.css">
</head>
<body>
    <nav>
        <a href="index.html">Home</a>
        <a href="login.html">Login</a>
        <a href="register.html">Register</a>
    </nav>
    <div class="container">
        <h1>Login</h1>
        <form action="login.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
    <footer>
        &copy; 2024 Student Performance Management System
    </footer>
</body>
</html>
