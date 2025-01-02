<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - Student Performance Management System</title>
    <link rel="stylesheet" href="../css/StudentRegister.css">
</head>
<body>
    <nav>
        <a href="index.html">Home</a>
        <a href="login.html">Login</a>
        <a href="register.html">Register</a>
    </nav>
    <div class="container">
        <h1>Student Registration</h1>
        <form action="" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>
            
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>
            
            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" required>
            
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
            
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            
            <label for="phone_number">Phone Number:</label>
            <input type="tel" id="phone_number" name="phone_number" pattern="^\d{10}$" required>
            
            <label for="enrollment_date">Enrollment Date:</label>
            <input type="date" id="enrollment_date" name="enrollment_date" required>

            <button type="submit">Register</button>
        </form>

        <?php
        // Include database connection
        include 'db_connection.php';

        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Server-side validation
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password']; // Do not hash until further notice
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $date_of_birth = $_POST['date_of_birth'];
            $gender = $_POST['gender'];
            $address = $_POST['address'];
            $phone_number = $_POST['phone_number'];
            $enrollment_date = $_POST['enrollment_date'];

            // Check if the phone number is valid (must be 10 digits)
            if (!preg_match('/^\d{10}$/', $phone_number)) {
                echo "<p style='color: red;'>Error: Invalid phone number. It must be 10 digits.</p>";
            } else {
                // Insert common user info into users table
                $sql_user = "INSERT INTO Users (username, email, password, role) VALUES ('$username', '$email', '$password', 'student')";

                if ($conn->query($sql_user) === TRUE) {
                    // Get the last inserted user ID
                    $user_id = $conn->insert_id;

                    // Insert student-specific info into students table
                    $sql_student = "INSERT INTO Students (first_name, last_name, date_of_birth, gender, address, phone_number, email, enrollment_date, user_id) 
                    VALUES ('$first_name', '$last_name', '$date_of_birth', '$gender', '$address', '$phone_number', '$email', '$enrollment_date', $user_id)";

                    if ($conn->query($sql_student) === TRUE) {
                        echo "<p style='color: green;'>New student registered successfully!</p>";
                    } else {
                        echo "<p style='color: red;'>Error: " . $sql_student . "<br>" . $conn->error . "</p>";
                    }
                } else {
                    echo "<p style='color: red;'>Error: " . $sql_user . "<br>" . $conn->error . "</p>";
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
