<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses - Student Performance Management System</title>
    <link rel="stylesheet" href="../css/ManageCourses.css">
</head>
<body>
    <nav>
        <a href="AdminDashboard.html">Admin Dashboard</a>
        <a href="logout.html">Logout</a>
    </nav>
    <div class="container">
        <h1>Manage Courses</h1>
        <form action="" method="post">
            <label for="course_name">Course Name:</label>
            <input type="text" id="course_name" name="course_name" required>

            <label for="course_description">Course Description:</label>
            <textarea id="course_description" name="course_description" required></textarea>

            <label for="teacher_id">Teacher:</label>
            <select id="teacher_id" name="teacher_id" required>
                <?php
                // Include database connection
                include 'db_connection.php';
                
                // Fetch teacher IDs and names from teachers table
                $sql = "SELECT teacher_id, CONCAT(first_name, ' ', last_name) AS teacher_name FROM teachers";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['teacher_id'] . "'>" . $row['teacher_name'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No teachers available</option>";
                }
                ?>
            </select>

            <button type="submit">Add Course</button>
        </form>

        <?php
        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $course_name = $_POST['course_name'];
            $course_description = $_POST['course_description'];
            $teacher_id = $_POST['teacher_id'];

            // Insert data into courses table
            $sql = "INSERT INTO courses (course_name, course_description, teacher_id) VALUES ('$course_name', '$course_description', '$teacher_id')";

            if ($conn->query($sql) === TRUE) {
                echo "<p style='color: green;'>New course added successfully!</p>";
            } else {
                echo "<p style='color: red;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
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
