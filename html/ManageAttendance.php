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

// Ensure the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: Login.php");
    exit();
}

// Fetch the teacher_id from the teachers table using the user_id
$sql = "SELECT teacher_id FROM teachers WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$teacher_id = $row['teacher_id'];
$stmt->close();

// Fetch the courses taught by the teacher
$sql = "SELECT course_id, course_name FROM courses WHERE teacher_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $teacher_id);
$stmt->execute();
$courses_result = $stmt->get_result();

$courses = [];
while ($row = $courses_result->fetch_assoc()) {
    $courses[] = $row;
}

$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];
    
    // Fetch students enrolled in the selected course
    $sql = "SELECT s.student_id, s.first_name, s.last_name 
            FROM students s 
            JOIN enrollments e ON s.student_id = e.student_id 
            WHERE e.course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $students_result = $stmt->get_result();

    $students = [];
    while ($row = $students_result->fetch_assoc()) {
        $students[] = $row;
    }

    $stmt->close();

    // Handle attendance submission
    if (isset($_POST['attendance'])) {
        $attendance = array_filter($_POST['attendance']); // Remove empty statuses
        foreach ($attendance as $student_id => $status) {
            $sql = "INSERT INTO attendance (student_id, course_id, attendance_date, status) VALUES (?, ?, NOW(), ?) 
                    ON DUPLICATE KEY UPDATE status = VALUES(status), attendance_date = VALUES(attendance_date)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iis', $student_id, $course_id, $status);
            $stmt->execute();
        }
        $stmt->close();
        header("Location: ManageAttendance.php");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Attendance - Student Performance Management System</title>
    <link rel="stylesheet" href="../css/ManageAttendance.css">
</head>
<body>
    <nav>
        <a href="TeacherDashboard.php">Teacher Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>

    <div class="container">
        <h1>Manage Attendance</h1>

        <form method="post">
            <label for="course_id">Select Course:</label>
            <select name="course_id" id="course_id" onchange="this.form.submit()">
                <option value="">-- Select a course --</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo $course['course_id']; ?>" <?php echo isset($_POST['course_id']) && $_POST['course_id'] == $course['course_id'] ? 'selected' : ''; ?>>
                        <?php echo $course['course_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if (isset($students) && !empty($students)): ?>
            <form method="post">
                <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                <table>
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Attendance Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></td>
                                <td>
                                    <select name="attendance[<?php echo $student['student_id']; ?>]">
                                        <option value="">-- Select Status --</option>
                                        <option value="present">Present</option>
                                        <option value="absent">Absent</option>
                                        <option value="late">Late</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit">Submit Attendance</button>
            </form>
        <?php elseif (isset($course_id)): ?>
            <p>No students enrolled in this course.</p>
        <?php endif; ?>
    </div>

    <footer>
        &copy; 2024 Student Performance Management System
    </footer>
</body>
</html>
