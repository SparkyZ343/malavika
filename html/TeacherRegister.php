<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Registration - Student Performance Management System</title>
    <link rel="stylesheet" href="../css/TeacherRegister.css">
</head>
<body>
    <nav>
        <a href="index.html">Home</a>
        <a href="login.html">Login</a>
        <a href="register.html">Register</a>
    </nav>
    <div class="container">
        <h1>Teacher Registration</h1>
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

            <!-- First Name -->
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>

            <!-- Last Name -->
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>

            <!-- Phone Number -->
            <label for="phone_number">Phone Number:</label>
            <input type="tel" id="phone_number" name="phone_number" 
                   pattern="^\d{10}$" 
                   title="Phone number must be 10 digits." 
                   required>

            <!-- Hire Date -->
            <label for="hire_date">Hire Date:</label>
            <input type="date" id="hire_date" name="hire_date" required>
            
            <button type="submit">Register</button>
        </form>

        <?php
        // Include database connection
        include 'db_connection.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get form data and sanitize inputs
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $first_name = trim($_POST['first_name']);
            $last_name = trim($_POST['last_name']);
            $phone_number = trim($_POST['phone_number']);
            $hire_date = trim($_POST['hire_date']);

            // Check for empty fields
            if (empty($username) || empty($email) || empty($password) || empty($first_name) || empty($last_name) || empty($phone_number) || empty($hire_date)) {
                echo "<p style='color: red;'>All fields are required.</p>";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<p style='color: red;'>Invalid email format.</p>";
            } elseif (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
                echo "<p style='color: red;'>Password must be at least 8 characters long, with at least one letter and one number.</p>";
            } elseif (!preg_match('/^\d{10}$/', $phone_number)) {
                echo "<p style='color: red;'>Phone number must be 10 digits.</p>";
            } else {
                // Check if email already exists
                $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo "<p style='color: red;'>Email already registered.</p>";
                } else {
                    $stmt_user = $conn->prepare("INSERT INTO Users (username, email, password, role) VALUES (?, ?, ?, 'teacher')");
                    $stmt_user->bind_param("sss", $username, $email, $password);

                    if ($stmt_user->execute()) {
                        $user_id = $stmt_user->insert_id;

                        $stmt_teacher = $conn->prepare("INSERT INTO Teachers (first_name, last_name, email, phone_number, hire_date, user_id) 
                                                        VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt_teacher->bind_param("sssssi", $first_name, $last_name, $email, $phone_number, $hire_date, $user_id);

                        if ($stmt_teacher->execute()) {
                            echo "<p style='color: green;'>New teacher registered successfully!</p>";
                        } else {
                            echo "<p style='color: red;'>Error in teacher registration: " . $stmt_teacher->error . "</p>";
                        }
                        $stmt_teacher->close();
                    } else {
                        echo "<p style='color: red;'>Error in user registration: " . $stmt_user->error . "</p>";
                    }
                    $stmt_user->close();
                }
                $stmt->close();
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
