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

$passwordIncorrect = false;
$password_sql = ''; // Initialize password_sql

// Handle form submission for updating the profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // Password change logic
    if (!empty($new_password)) {
        // Check current password
        $sql = "SELECT password FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($current_password === $user['password']) {
            // If current password is correct, update with new password
            $password_sql = ", password = ?";
        } else {
            // Current password is incorrect
            $passwordIncorrect = true;
        }
    }

    if (!$passwordIncorrect) {
        // Update student profile in the database
        $update_query = "UPDATE students SET first_name = ?, last_name = ?, email = ?, date_of_birth = ?, gender = ?, phone_number = ?, address = ? WHERE user_id = ?";
        $stmt_update = $conn->prepare($update_query);
        $stmt_update->bind_param("sssssssi", $first_name, $last_name, $email, $date_of_birth, $gender, $phone_number, $address, $user_id);
        
        // Update password if needed
        if (!empty($password_sql)) {
            $update_user_query = "UPDATE users SET password = ? WHERE user_id = ?";
            $stmt_user_update = $conn->prepare($update_user_query);
            $stmt_user_update->bind_param("si", $new_password, $user_id);
            $stmt_user_update->execute();
            $stmt_user_update->close();
        }

        if ($stmt_update->execute()) {
            $message = "Profile updated successfully!";
        } else {
            $message = "Failed to update profile. Please try again.";
        }

        $stmt_update->close();
    }
}

// Handle account deletion
if (isset($_POST['delete_account'])) {
    $delete_query = "DELETE FROM students WHERE user_id = ?";
    $stmt_delete = $conn->prepare($delete_query);
    $stmt_delete->bind_param("i", $user_id);

    if ($stmt_delete->execute()) {
        // Delete associated user data from the users table
        $delete_user_query = "DELETE FROM users WHERE user_id = ?";
        $stmt_user_delete = $conn->prepare($delete_user_query);
        $stmt_user_delete->bind_param("i", $user_id);
        $stmt_user_delete->execute();
        $stmt_user_delete->close();

        // Redirect to the login page after successful account deletion
        header("Location: Login.php");
        exit();
    } else {
        $delete_message = "Failed to delete account. Please try again.";
    }

    $stmt_delete->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Student Performance Management System</title>
    <link rel="stylesheet" href="../css/EditStudentProfile.css">
    <script>
        function showAlert(message) {
            alert(message);
        }

        <?php if ($passwordIncorrect): ?>
        window.onload = function() {
            showAlert('Current password is incorrect.');
        }
        <?php endif; ?>
    </script>
</head>
<body>
    <nav>
        <a href="index.html">Home</a>
        <a href="logout.php">Logout</a>
        <a href="StudentDashboard.php">Dashboard</a>
    </nav>

    <div class="container">
        <h1>Edit Profile</h1>

        <div class="profile-form">
            <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>
            <?php if (isset($delete_message)) { echo "<p class='delete-message'>$delete_message</p>"; } ?>

            <form action="EditStudentProfile.php" method="POST">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($student['first_name']); ?>" required>

                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" required>

                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>

                <label for="date_of_birth">Date of Birth:</label>
                <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($student['date_of_birth']); ?>" required>

                <label for="gender">Gender:</label>
                <select name="gender" required>
                    <option value="male" <?php echo ($student['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?php echo ($student['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                    <option value="other" <?php echo ($student['gender'] == 'other') ? 'selected' : ''; ?>>Other</option>
                </select>

                <label for="phone_number">Phone Number:</label>
                <input type="text" name="phone_number" value="<?php echo htmlspecialchars($student['phone_number']); ?>" required>

                <label for="address">Address:</label>
                <textarea name="address" required><?php echo htmlspecialchars($student['address']); ?></textarea>

                <!-- Password Fields -->
                <label for="current_password">Current Password:</label>
                <input type="password" name="current_password" required>

                <label for="new_password">New Password (Leave blank if no change):</label>
                <input type="password" name="new_password" placeholder="Leave blank if there's no change">

                <button type="submit">Update Profile</button>
            </form>

            <form action="EditStudentProfile.php" method="POST" class="delete-form">
                <button type="submit" name="delete_account" class="delete-button" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">Delete Account</button>
            </form>
        </div>
    </div>

    <footer>
        &copy; 2024 Student Performance Management System
    </footer>
</body>
</html>
