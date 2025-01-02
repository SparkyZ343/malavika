<?php
session_start();

// Include database connection
include 'db_connection.php';

// Check if the student is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to enroll in courses.");
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Check if the student exists in the students table
$sql_check_student = "SELECT student_id FROM students WHERE user_id = '$user_id'";
$result_check = $conn->query($sql_check_student);

if ($result_check->num_rows == 0) {
    die("Error: Student ID does not exist in the database.");
}

// Fetch the student ID
$student = $result_check->fetch_assoc();
$student_id = $student['student_id'];

// Fetch courses available for enrollment
$sql_courses = "SELECT c.course_id, c.course_name, c.course_description, t.first_name, t.last_name 
                FROM courses c 
                JOIN teachers t ON c.teacher_id = t.teacher_id";
$courses_result = $conn->query($sql_courses);

$courses = []; // Initialize an empty array to hold courses
if ($courses_result && $courses_result->num_rows > 0) {
    while ($course = $courses_result->fetch_assoc()) {
        $courses[] = $course; // Add each course to the array
    }
} else {
    echo "No courses available for enrollment.";
}

// Handle enrollment logic if a form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['course_id'])) {
        $course_id = $_POST['course_id'];

        // Prepare an SQL statement for enrollment
        $stmt = $conn->prepare("INSERT INTO enrollments (student_id, course_id, enrollment_date) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $student_id, $course_id);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            echo "Successfully enrolled in the course!";
        } else {
            echo "Error enrolling in the course: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll in Courses</title>
    <link rel="stylesheet" href="../css/EnrollCourses.css">
</head>
<body>
    <nav>
        <a href="index.html">Home</a>
        <a href="logout.html">Logout</a>
    </nav>
    <div class="container">
        <h1>Enroll in Courses</h1>

        <?php if (!empty($courses)): ?>
            <form action="EnrollCourses.php" method="post">
                <label for="course">Select Course:</label>
                <select id="course" name="course_id" required>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo $course['course_id']; ?>">
                            <?php echo htmlspecialchars($course['course_name']) . " - " . htmlspecialchars($course['first_name'] . " " . $course['last_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Enroll</button>
            </form>
        <?php else: ?>
            <p>No courses available for enrollment.</p>
        <?php endif; ?>

    </div>
    <footer>
        &copy; 2024 Student Performance Management System
    </footer>
</body>
</html>
