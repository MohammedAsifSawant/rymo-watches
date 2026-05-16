<?php
session_start();
// Database Connection
$con = mysqli_connect('localhost', 'root', '', 'rymowatch');

// Check if connection is successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetching data from 'regi' table
$query = "SELECT * FROM regi";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Registered Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background: #1a1a1a; color: white; padding: 50px; }
        .container { background: rgba(255, 255, 255, 0.1); padding: 30px; border-radius: 15px; }
        table { color: white !important; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Registered User Profiles</h2>
        <table class="table table-hover border">
            <thead class="table-dark">
                <tr>
                    <th>Username</th>
                    <th>Email Address</th>
                    <th>Password (Encrypted/Stored)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['username']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['password']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="admin.php" class="btn btn-primary">View Orders</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>