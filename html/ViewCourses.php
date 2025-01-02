<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Courses - Student Performance Management System</title>
    <link rel="stylesheet" href="../css/ViewCourses.css">
</head>
<body>
    <nav>
        <a href="TeacherDashboard.html">Back to Dashboard</a>
        <a href="logout.html">Logout</a>
    </nav>
    <div class="container">
        <h1>View Courses</h1>
        <div class="table-container">
            <h2>Your Courses</h2>
            <table>
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Description</th>
                        <th>Teacher Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'db_connection.php';
                    session_start();
                    $user_id = $_SESSION['user_id'];
                    
                    $sql = "SELECT c.course_name, c.course_description, t.first_name, t.last_name
                            FROM courses c
                            JOIN teachers t ON c.teacher_id = t.teacher_id
                            JOIN users u ON t.user_id = u.user_id
                            WHERE u.user_id = $user_id";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['course_name']}</td>
                                    <td>{$row['course_description']}</td>
                                    <td>{$row['first_name']} {$row['last_name']}</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No courses found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="table-container">
            <h2>All Courses</h2>
            <table>
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Description</th>
                        <th>Teacher Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT c.course_name, c.course_description, t.first_name, t.last_name
                            FROM courses c
                            JOIN teachers t ON c.teacher_id = t.teacher_id";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['course_name']}</td>
                                    <td>{$row['course_description']}</td>
                                    <td>{$row['first_name']} {$row['last_name']}</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No courses found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <footer>
        &copy; 2024 Student Performance Management System
    </footer>
</body>
</html>
