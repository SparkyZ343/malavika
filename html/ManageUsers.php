<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Student Performance Management System</title>
    <link rel="stylesheet" href="../css/ManageUsers.css">
</head>
<body>
    <nav>
        <a href="AdminDashboard.html">Admin Dashboard</a>
        <a href="logout.html">Logout</a>
    </nav>
    <div class="container">
        <h1>Manage Users</h1>
        <div class="user-table">
            <h2>Teachers</h2>
            <table>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone Number</th>
                    <th>Hire Date</th>
                    <th>Actions</th>
                </tr>
                <?php
                include 'db_connection.php';

                // Fetch teacher details
                $sql = "SELECT u.username, u.email, t.first_name, t.last_name, t.phone_number, t.hire_date, t.teacher_id
                        FROM users u
                        JOIN teachers t ON u.user_id = t.user_id";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['username']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['first_name']}</td>
                                <td>{$row['last_name']}</td>
                                <td>{$row['phone_number']}</td>
                                <td>{$row['hire_date']}</td>
                                <td><a href='EditUsers.php?type=teacher&id={$row['teacher_id']}'>Edit</a></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No teachers found</td></tr>";
                }
                ?>
            </table>
            
            <h2>Students</h2>
            <table>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Enrollment Date</th>
                    <th>Actions</th>
                </tr>
                <?php
                // Fetch student details
                $sql = "SELECT u.username, u.email, s.first_name, s.last_name, s.date_of_birth, s.gender, s.enrollment_date, s.student_id
                        FROM users u
                        JOIN students s ON u.user_id = s.user_id";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['username']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['first_name']}</td>
                                <td>{$row['last_name']}</td>
                                <td>{$row['date_of_birth']}</td>
                                <td>{$row['gender']}</td>
                                <td>{$row['enrollment_date']}</td>
                                <td><a href='EditUsers.php?type=student&id={$row['student_id']}'>Edit</a></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No students found</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
    <footer>
        &copy; 2024 Student Performance Management System
    </footer>
</body>
</html>
