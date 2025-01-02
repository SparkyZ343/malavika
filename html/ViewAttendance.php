<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
include "db_connection.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: Login.php");
    exit();
}

// Fetch the student_id from the students table using the user_id
$sql = "SELECT student_id FROM students WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$student_id = $row['student_id'];
$stmt->close();

// Fetch the courses the student is enrolled in
$sql = "SELECT c.course_id, c.course_name FROM courses c
        JOIN enrollments e ON c.course_id = e.course_id
        WHERE e.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$courses_result = $stmt->get_result();
$courses = [];
while ($row = $courses_result->fetch_assoc()) {
    $courses[] = $row;
}
$stmt->close();

$selected_course_id = $_POST['course_id'] ?? null;
$selected_date = $_POST['attendance_date'] ?? null;
$attendance_status = null;

if ($selected_course_id && $selected_date) {
    // Fetch the attendance status for the selected course and date
    $sql = "SELECT status FROM attendance WHERE student_id = ? AND course_id = ? AND attendance_date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iis', $student_id, $selected_course_id, $selected_date);
    $stmt->execute();
    $attendance_result = $stmt->get_result();
    if ($row = $attendance_result->fetch_assoc()) {
        $attendance_status = $row['status'];
    } else {
        $attendance_status = "No data found for the selected date.";
    }
    $stmt->close();
}

// Fetch the minimum date from the attendance records for date selection
$sql = "SELECT MIN(attendance_date) as min_date FROM attendance WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$date_result = $stmt->get_result();
$row = $date_result->fetch_assoc();
$min_date = $row['min_date'] ?? date('Y-m-d');
$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance - Student Performance Management System</title>
    <link rel="stylesheet" href="../css/ViewAttendance.css">
</head>
<body>
    <nav>
        <a href="StudentDashboard.php">Student Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>
    <div class="container">
        <h1>View Attendance</h1>
        <form action="ViewAttendance.php" method="post">
            <div class="form-group">
                <label for="course_id">Select Course:</label>
                <select name="course_id" id="course_id" required>
                    <option value="">-- Select Course --</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo $course['course_id']; ?>" <?php echo $selected_course_id == $course['course_id'] ? 'selected' : ''; ?>>
                            <?php echo $course['course_name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="attendance_date">Select Date:</label>
                <input type="date" name="attendance_date" id="attendance_date" min="<?php echo $min_date; ?>" max="<?php echo date('Y-m-d'); ?>" required value="<?php echo $selected_date; ?>">
            </div>
            <div class="form-group">
                <button type="submit">View Attendance</button>
            </div>
        </form>
        <?php if ($selected_course_id && $selected_date): ?>
            <div class="attendance-status">
                <h2>Attendance Status</h2>
                <p><strong>Course:</strong> 
                <?php 
                $course_name = array_filter($courses, fn($course) => $course['course_id'] == $selected_course_id);
                echo !empty($course_name) ? reset($course_name)['course_name'] : "Course not found";
                ?>
                </p>
                <p><strong>Date:</strong> <?php echo $selected_date; ?></p>
                <p><strong>Status:</strong> <?php echo $attendance_status; ?></p>
            </div>
        <?php endif; ?>
    </div>
    <footer>
        &copy; 2024 Student Performance Management System
    </footer>
</body>
</html>
