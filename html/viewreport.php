<?php
// Start the session and check if the admin is logged in
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Include database connection
include "db_connection.php";

// Fetch all students from the database
$sql = "SELECT student_id, first_name, last_name, email FROM students";
$result = mysqli_query($conn, $sql);
$students = [];
while ($row = mysqli_fetch_assoc($result)) {
    $students[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student Reports - Admin</title>
    <link rel="stylesheet" href="../css/viewreport.css">
</head>
<body>
    <nav>
        <a href="AdminDashboard.html">Admin Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>

    <h1>Student Reports</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Email</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></td>
                    <td><?php echo $student['email']; ?></td>
                    <td>
                        <form method="post" action="viewdetails.php">
                            <input type="hidden" name="student_id" value="<?php echo $student['student_id']; ?>">
                            <button type="submit" name="view_details">View Details</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
