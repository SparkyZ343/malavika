<?php
// Start the session and check if the admin is logged in
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Include database connection
include "db_connection.php";

// Include FPDF library
require_once('fpdf/fpdf.php');

// Fetch the student_id from the POST request
if (isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    // Fetch the student's basic details
    $sql = "SELECT first_name, last_name, email FROM students WHERE student_id = $student_id";
    $result = mysqli_query($conn, $sql);
    $student = mysqli_fetch_assoc($result);

    // Fetch the student's attendance details
    $sql_attendance = "SELECT attendance_date, status FROM attendance WHERE student_id = $student_id ORDER BY attendance_date DESC";
    $attendance_result = mysqli_query($conn, $sql_attendance);
    $attendance = [];
    while ($row = mysqli_fetch_assoc($attendance_result)) {
        $attendance[] = $row;
    }

    // Fetch the student's marks details with subject names
    $sql_marks = "SELECT sub.course_name, 
                          tp.marks AS test_paper_marks, 
                          ie.marks AS internal_exam_marks, 
                          me.marks AS model_exam_marks, 
                          pm.marks AS project_marks, 
                          sm.marks AS seminar_marks
                   FROM students s
                   LEFT JOIN testpapermarks tp ON s.student_id = tp.student_id
                   LEFT JOIN internalexammarks ie ON s.student_id = ie.student_id
                   LEFT JOIN modelexammarks me ON s.student_id = me.student_id
                   LEFT JOIN projectmarks pm ON s.student_id = pm.student_id
                   LEFT JOIN seminarmarks sm ON s.student_id = sm.student_id
                   LEFT JOIN courses sub ON sub.course_id = tp.course_id
                   WHERE s.student_id = $student_id";
    $marks_result = mysqli_query($conn, $sql_marks);
    $marks = [];
    while ($row = mysqli_fetch_assoc($marks_result)) {
        $marks[] = $row;
    }

    // Check if the PDF button is clicked
    if (isset($_POST['download_pdf'])) {
        $pdf = new FPDF();
        $pdf->AddPage();

        // Set title
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(200, 10, 'Student Details - Admin', 0, 1, 'C');

        // Student basic details
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Name: ' . $student['first_name'] . ' ' . $student['last_name']);
        $pdf->Ln(10);
        $pdf->Cell(50, 10, 'Email: ' . $student['email']);

        // Attendance details
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(200, 10, 'Attendance Details', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        foreach ($attendance as $att) {
            $pdf->Cell(50, 10, 'Date: ' . $att['attendance_date']);
            $pdf->Cell(50, 10, 'Status: ' . ucfirst($att['status']));
            $pdf->Ln(10);
        }

        // Marks details with subject names
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(200, 10, 'Marks Details', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        foreach ($marks as $mark) {
            $pdf->Cell(50, 10, 'Subject: ' . $mark['course_name']);
            $pdf->Ln(10);
            $pdf->Cell(50, 10, 'Test Paper Marks: ' . ($mark['test_paper_marks'] ?? 'N/A'));
            $pdf->Ln(10);
            $pdf->Cell(50, 10, 'Internal Exam Marks: ' . ($mark['internal_exam_marks'] ?? 'N/A'));
            $pdf->Ln(10);
            $pdf->Cell(50, 10, 'Model Exam Marks: ' . ($mark['model_exam_marks'] ?? 'N/A'));
            $pdf->Ln(10);
            $pdf->Cell(50, 10, 'Project Marks: ' . ($mark['project_marks'] ?? 'N/A'));
            $pdf->Ln(10);
            $pdf->Cell(50, 10, 'Seminar Marks: ' . ($mark['seminar_marks'] ?? 'N/A'));
            $pdf->Ln(10);
        }

        // Output PDF with the email as the file name
        $file_name = $student['email'] . '.pdf';
        $pdf->Output('D', $file_name);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details - Admin</title>
    <link rel="stylesheet" href="../css/viewdetails.css">
</head>
<body>
    <nav>
        <a href="viewreport.php">Back to Student Reports</a>
        <a href="logout.php">Logout</a>
    </nav>

    <h1>Student Details</h1>
    
    <?php if (isset($student)): ?>
        <h2><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></h2>
        <p>Email: <?php echo $student['email']; ?></p>

        <h3>Marks Details</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Test Paper Marks</th>
                    <th>Internal Exam Marks</th>
                    <th>Model Exam Marks</th>
                    <th>Project Marks</th>
                    <th>Seminar Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($marks) > 0): ?>
                    <?php foreach ($marks as $mark): ?>
                        <tr>
                            <td><?php echo $mark['course_name']; ?></td>
                            <td><?php echo $mark['test_paper_marks'] ?? 'N/A'; ?></td>
                            <td><?php echo $mark['internal_exam_marks'] ?? 'N/A'; ?></td>
                            <td><?php echo $mark['model_exam_marks'] ?? 'N/A'; ?></td>
                            <td><?php echo $mark['project_marks'] ?? 'N/A'; ?></td>
                            <td><?php echo $mark['seminar_marks'] ?? 'N/A'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No marks records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Button to download PDF -->
        <form method="POST">
            <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
            <button type="submit" name="download_pdf">Download PDF</button>
        </form>

    <?php else: ?>
        <p>No student details available.</p>
    <?php endif; ?>
</body>
</html>
