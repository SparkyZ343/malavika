<?php
// EditTeacherProfile.php
session_start();
include 'db_connection.php';

$loggedInUserId = $_SESSION['user_id'];

// Fetch teacher details
$sql = "SELECT u.username, u.email, t.first_name, t.last_name, t.phone_number, t.hire_date
        FROM users u
        JOIN teachers t ON u.user_id = t.user_id
        WHERE u.user_id = $loggedInUserId";
$result = $conn->query($sql);
$teacher = $result->fetch_assoc();

$passwordIncorrect = false;
$password_sql = ''; // Initialize password_sql

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update profile details
    $username = $_POST['username'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $hire_date = $_POST['hire_date'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // Password change logic
    if (!empty($new_password)) {
        // Check current password
        $sql = "SELECT password FROM users WHERE user_id = $loggedInUserId";
        $result = $conn->query($sql);
        $user = $result->fetch_assoc();

        if ($current_password === $user['password']) {
            // If current password is correct, update with new password
            $password_sql = ", u.password = '$new_password'";
        } else {
            // Current password is incorrect
            $passwordIncorrect = true;
        }
    }

    // Update teacher and user details
    if (!$passwordIncorrect) {
        $sql = "UPDATE users u
                JOIN teachers t ON u.user_id = t.user_id
                SET u.username = '$username', u.email = '$email',
                    t.first_name = '$first_name', t.last_name = '$last_name',
                    t.phone_number = '$phone_number', t.hire_date = '$hire_date'
                    $password_sql
                WHERE u.user_id = $loggedInUserId";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Profile updated successfully');</script>";
            header('Location: TeacherProfile.php');
            exit;
        } else {
            echo "Error updating profile: " . $conn->error;
        }
    }
}

// Delete Account Logic
if (isset($_POST['delete_account'])) {
    // Delete teacher and user data
    $sql = "DELETE t, u FROM teachers t JOIN users u ON t.user_id = u.user_id WHERE u.user_id = $loggedInUserId";
    if ($conn->query($sql) === TRUE) {
        echo "Account deleted successfully";
        header('Location: logout.php');
        exit;
    } else {
        echo "Error deleting account: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Teacher Profile - Student Performance Management System</title>
    <link rel="stylesheet" href="../css/EditTeacherProfile.css">
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
        <a href="logout.html">Logout</a>
        <a href="TeacherProfile.php">My Profile</a>
    </nav>
    <div class="container">
        <h1>Edit Teacher Profile</h1>
        <form action="" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $teacher['username']; ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $teacher['email']; ?>" required>
            
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo $teacher['first_name']; ?>" required>
            
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo $teacher['last_name']; ?>" required>
            
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" value="<?php echo $teacher['phone_number']; ?>">
            
            <label for="hire_date">Hire Date:</label>
            <input type="date" id="hire_date" name="hire_date" value="<?php echo $teacher['hire_date']; ?>" required>
            
            <!-- Password Fields -->
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>
            
            <label for="new_password">New Password (Leave blank if no change):</label>
            <input type="password" id="new_password" name="new_password" placeholder="Leave blank if there's no change">
            
            <div class="button-container">
                <button type="submit">Update Profile</button>
            </div>
        </form>

        <!-- Delete Account Form -->
        <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
            <div class="delete-container">
                <button type="submit" name="delete_account" class="delete-button">Delete Account</button>
            </div>
        </form>
    </div>
    <footer>
        &copy; 2024 Student Performance Management System
    </footer>
</body>
</html>
