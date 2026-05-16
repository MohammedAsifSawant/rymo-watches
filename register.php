<?php
$con = mysqli_connect('localhost', 'root', '', 'rymowatch');
if (!$con) {
    die('Database connection failed.');
}

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$pass = trim($_POST['password'] ?? '');
$cpass = trim($_POST['cpassword'] ?? '');

if ($username === '' || $email === '' || $pass === '' || $cpass === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 6) {
    header('Location: signup.php?error=invalid');
    exit;
}

if ($pass !== $cpass) {
    header('Location: signup.php?error=password');
    exit;
}

$username_safe = mysqli_real_escape_string($con, $username);
$email_safe = mysqli_real_escape_string($con, $email);
$password_safe = mysqli_real_escape_string($con, $pass);

$result = mysqli_query($con, "SELECT id FROM regi WHERE username='$username_safe' LIMIT 1");
if ($result && mysqli_num_rows($result) > 0) {
    header('Location: signup.php?error=exists');
    exit;
}

mysqli_query($con, "INSERT INTO regi(username, email, password) VALUES('$username_safe', '$email_safe', '$password_safe')");
header('Location: login.php?success=registered');
exit;
