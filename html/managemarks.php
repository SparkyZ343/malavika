<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include "db_connection.php";

// Ensure the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: Login.php");
    exit();
}

// Fetch the teacher_id from the teachers table using the user_id
$sql = "SELECT teacher_id FROM teachers WHERE user_id = " . $_SESSION['user_id'];
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $teacher_id = $row['teacher_id'];
}

// Fetch the courses taught by the teacher
$sql = "SELECT course_id, course_name FROM courses WHERE teacher_id = " . $teacher_id;
$courses_result = mysqli_query($conn, $sql);

$courses = [];
while ($row = mysqli_fetch_assoc($courses_result)) {
    $courses[] = $row;
}

// Check if course_id is set in the POST request, if so, fetch students for that course
$course_id = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];

    // Fetch students enrolled in the selected course and their marks
    $sql = "SELECT s.student_id, s.first_name, s.last_name, 
                   tp.marks AS test_paper_marks, 
                   ie.marks AS internal_exam_marks, 
                   me.marks AS model_exam_marks
            FROM students s 
            JOIN enrollments e ON s.student_id = e.student_id 
            LEFT JOIN testpapermarks tp ON s.student_id = tp.student_id AND tp.course_id = e.course_id
            LEFT JOIN internalexammarks ie ON s.student_id = ie.student_id AND ie.course_id = e.course_id
            LEFT JOIN modelexammarks me ON s.student_id = me.student_id AND me.course_id = e.course_id
            WHERE e.course_id = " . $course_id;
    $students_result = mysqli_query($conn, $sql);

    $students = []; // Initialize the array
    while ($row = mysqli_fetch_assoc($students_result)) {
        $students[] = $row; // Add each student row to the array
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_marks'])) {
    // Get the student ID from the submitted button value
    $student_id = $_POST['update_marks'];

    // Check if student_id is valid
    if (empty($student_id)) {
        echo "Error: Student ID is missing.";
        exit;
    }

    // Get marks from the form
    $test_paper_marks = isset($_POST['test_paper_marks_' . $student_id]) ? $_POST['test_paper_marks_' . $student_id] : 0;
    $internal_exam_marks = isset($_POST['internal_exam_marks_' . $student_id]) ? $_POST['internal_exam_marks_' . $student_id] : 0;
    $model_exam_marks = isset($_POST['model_exam_marks_' . $student_id]) ? $_POST['model_exam_marks_' . $student_id] : 0;

    // Ensure course_id is set
    $course_id = $_POST['course_id'] ?? 0;

    // Debugging output: Display the variables to verify their values
    // echo "Student ID: $student_id, Test Paper Marks: $test_paper_marks, Internal Exam Marks: $internal_exam_marks, Model Exam Marks: $model_exam_marks, Course ID: $course_id";

    // Check if the variables are correct
    if (empty($test_paper_marks) || empty($internal_exam_marks) || empty($model_exam_marks) || empty($course_id)) {
        echo "Error: Missing marks or course ID.";
        exit;
    }

    // Update the marks for the specific student and course
    $sql = "UPDATE testpapermarks SET marks = $test_paper_marks WHERE student_id = $student_id AND course_id = $course_id";
    if (mysqli_query($conn, $sql)) {
        // echo "Test Paper Marks updated successfully.";
    } else {
        echo "Error updating Test Paper Marks: " . mysqli_error($conn);
    }

    $sqlinternal = "UPDATE internalexammarks SET marks = $internal_exam_marks WHERE student_id = $student_id AND course_id = $course_id";
    if (mysqli_query($conn, $sqlinternal)) {
        // echo "Internal Exam Marks updated successfully.";
    } else {
        echo "Error updating Internal Exam Marks: " . mysqli_error($conn);
    }

    $sqlmodel = "UPDATE modelexammarks SET marks = $model_exam_marks WHERE student_id = $student_id AND course_id = $course_id";
    if (mysqli_query($conn, $sqlmodel)) {
        // echo "Model Exam Marks updated successfully.";
    } else {
        echo "Error updating Model Exam Marks: " . mysqli_error($conn);
    }
    header('Location: managemarks.php');
    exit();
    
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Marks - Student Performance Management System</title>
    <link rel="stylesheet" href="../css/ManageMarks.css">
</head>
<body>
    <nav>
        <a href="TeacherDashboard.html">Teacher Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>

    <div class="container">
        <h1>Manage Marks</h1>

        <form method="post">
            <label for="course_id">Select Course:</label>
            <select name="course_id" id="course_id" onchange="this.form.submit()">
                <option value="">-- Select a course --</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo $course['course_id']; ?>" <?php echo isset($course_id) && $course_id == $course['course_id'] ? 'selected' : ''; ?>>
                        <?php echo $course['course_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if (isset($students) && !empty($students)): ?>
            <form method="post">
    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Student Id</th>
                <th>Test Paper Marks</th>
                <th>Internal Exam Marks</th>
                <th>Model Exam Marks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></td>
                    <td><?php echo $student['student_id']; ?></td>
                    <td>
                        <input type="number" name="test_paper_marks_<?php echo $student['student_id']; ?>" 
                               value="<?php echo $student['test_paper_marks'] ?? 0; ?>" min="0" max="100">
                    </td>
                    <td>
                        <input type="number" name="internal_exam_marks_<?php echo $student['student_id']; ?>" 
                               value="<?php echo $student['internal_exam_marks'] ?? 0; ?>" min="0" max="100">
                    </td>
                    <td>
                        <input type="number" name="model_exam_marks_<?php echo $student['student_id']; ?>" 
                               value="<?php echo $student['model_exam_marks'] ?? 0; ?>" min="0" max="100">
                    </td>
                    <td>
                        <input type="hidden" name="student_id" value="<?php echo $student['student_id']; ?>">
                        <input type="hidden" name="course_id" value="<?php echo $course_id; ?>"> <!-- Add course_id here -->
                        <button type="submit" name="update_marks" value="<?php echo $student['student_id']; ?>">Update</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
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
