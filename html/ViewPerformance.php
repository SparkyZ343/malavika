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

// Fetch the courses for the student
$sql = "SELECT courses.course_id, courses.course_name FROM enrollments
        JOIN courses ON enrollments.course_id = courses.course_id
        WHERE enrollments.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();
$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}
$stmt->close();

// Initialize marks array
$marks = [
    'test_paper' => 0,
    'internal_exam' => 0,
    'model_exam' => 0,
    'project' => 0,
    'seminar' => 0,
    'attendance' => 0,
];

// Fetch the selected course
$selected_course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : null;

// Only fetch and calculate marks if a course is selected
if ($selected_course_id !== null) {
    // Fetch the marks for the selected course
    $marks_query = [
        'test_paper' => "SELECT marks FROM testpapermarks WHERE student_id = ? AND course_id = ?",
        'internal_exam' => "SELECT marks FROM internalexammarks WHERE student_id = ? AND course_id = ?",
        'model_exam' => "SELECT marks FROM modelexammarks WHERE student_id = ? AND course_id = ?",
        'project' => "SELECT marks FROM projectmarks WHERE student_id = ? AND course_id = ?",
        'seminar' => "SELECT marks FROM seminarmarks WHERE student_id = ? AND course_id = ?",
    ];

    // Execute each query and store the results
    foreach ($marks_query as $key => $sql) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $student_id, $selected_course_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $marks[$key] = $row['marks'];
        }
        $stmt->close();
    }

    // Calculate attendance marks for the selected course
    $sql = "SELECT status FROM attendance WHERE student_id = ? AND course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $student_id, $selected_course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $attendance_marks = 0;
    $total_attendance = 0;
    while ($row = $result->fetch_assoc()) {
        if ($row['status'] == 'present') {
            $attendance_marks += 10;
        } elseif ($row['status'] == 'late') {
            $attendance_marks += 5;
        }
        $total_attendance += 10;
    }
    $stmt->close();

    if ($total_attendance > 0) {
        $marks['attendance'] = $attendance_marks / ($total_attendance / 10); // Calculate average attendance marks
    } else {
        $marks['attendance'] = 0; // Avoid division by zero
    }

    // Calculate weighted scores
    $weighted_scores = [
        'test_paper' => $marks['test_paper'] * 1.0,
        'internal_exam' => $marks['internal_exam'] * 2.5,
        'model_exam' => $marks['model_exam'] * 3.0,
        'project' => $marks['project'] * 1.5,
        'seminar' => $marks['seminar'] * 2.0,
        'attendance' => $marks['attendance'] * 0.5,
    ];

    // Calculate total weighted score
    $total_weighted_score = array_sum($weighted_scores);

    // Calculate maximum possible weighted score
    $max_weighted_scores = [
        'test_paper' => 5 * 1.0,
        'internal_exam' => 10 * 2.5,
        'model_exam' => 10 * 3.0,
        'project' => 10 * 1.5,
        'seminar' => 10 * 2.0,
        'attendance' => 5 * 0.5,
    ];
    $max_total_weighted_score = array_sum($max_weighted_scores);

    // Scale total weighted score to 50
    $scaled_total_score = ($total_weighted_score / $max_total_weighted_score) * 50;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Performance - Student Performance Management System</title>
    <link rel="stylesheet" href="../css/ViewPerformance.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <nav>
        <a href="StudentDashboard.php">Student Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>
    <div class="container">
        <h1>View Performance</h1>
        <form method="POST" action="ViewPerformance.php">
            <label for="course_id">Select Course:</label>
            <select name="course_id" id="course_id" onchange="this.form.submit()">
                <option value="">-- Select a Course --</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo $course['course_id']; ?>" <?php echo $course['course_id'] == $selected_course_id ? 'selected' : ''; ?>>
                        <?php echo $course['course_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        <?php if ($selected_course_id !== null): ?>
        <canvas id="performanceChart"></canvas>
        <?php endif; ?>
    </div>
    <footer>
        &copy; 2024 Student Performance Management System
    </footer>
    <?php if ($selected_course_id !== null): ?>
    <script>
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const performanceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Test Paper', 'Internal Exam', 'Model Exam', 'Project', 'Seminar', 'Attendance', 'Total'],
                datasets: [{
                    label: 'Marks',
                    data: [
                        <?php echo $weighted_scores['test_paper']; ?>,
                        <?php echo $weighted_scores['internal_exam']; ?>,
                        <?php echo $weighted_scores['model_exam']; ?>,
                        <?php echo $weighted_scores['project']; ?>,
                        <?php echo $weighted_scores['seminar']; ?>,
                        <?php echo $weighted_scores['attendance']; ?>,
                        <?php echo $scaled_total_score; ?>
                    ],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 205, 86, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 205, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 50
                    }
                }
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>
