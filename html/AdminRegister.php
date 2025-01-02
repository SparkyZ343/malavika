<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration - Student Performance Management System</title>
    <link rel="stylesheet" href="../css/AdminRegister.css">
</head>
<body>
    <nav>
        <a href="index.html">Home</a>
        <a href="login.html">Login</a>
        <a href="register.html">Register</a>
    </nav>
    <div class="container">
        <h1>Admin Registration</h1>
        <form action="" method="post">
            <!-- Username -->
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" 
                   pattern="^[a-zA-Z0-9_]{3,20}$" 
                   title="Username must be 3-20 characters long and can only contain letters, numbers, and underscores." 
                   required>

            <!-- Email -->
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <!-- Password -->
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" 
                   pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" 
                   title="Password must be at least 8 characters long, with at least one letter and one number." 
                   required>

            <!-- Role -->
            <label for="role">Role:</label>
            <input type="text" id="role" name="role" value="Admin" readonly>

            <button type="submit">Register</button>
        </form>

        <?php
        // Include database connection
        include 'db_connection.php';

        // Check if an admin already exists
        $check_admin = "SELECT COUNT(*) AS admin_count FROM users WHERE role='Admin'";
        $result = $conn->query($check_admin);
        $row = $result->fetch_assoc();

        if ($row['admin_count'] > 0) {
            echo "<p style='color: red;'>Unauthorized access! Admin already exists.</p>";
        } else {
            // Handle form submission
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = trim($_POST['username']);
                $email = trim($_POST['email']);
                $password = trim($_POST['password']);
                $role = "Admin";

                // Backend validations
                if (empty($username) || empty($email) || empty($password)) {
                    echo "<p style='color: red;'>All fields are required.</p>";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo "<p style='color: red;'>Invalid email format.</p>";
                } elseif (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
                    echo "<p style='color: red;'>Password must be at least 8 characters long, with at least one letter and one number.</p>";
                } else {
                    // Hash the password before storing it
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Use prepared statement to insert data
                    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

                    if ($stmt->execute()) {
                        echo "<p style='color: green;'>New admin registered successfully!</p>";
                    } else {
                        echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
                    }
                    $stmt->close();
                }
            }
        }
        $conn->close();
        ?>
    </div>
    <footer>
        &copy; 2024 Student Performance Management System
    </footer>
</body>
</html>
