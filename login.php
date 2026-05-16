<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>
			loginpage...
		</title>
	    <link rel="stylesheet" href="reges.css?v=20260423-2">
	</head>
	<body>
		<?php $error = $_GET['error'] ?? ''; $success = $_GET['success'] ?? ''; ?>
		<img class="background1" src="b1.jpg">
		<div class="container">
			<div class="mainbox">

				<fieldset>	
				<div class="role-heading">Choose Login Role</div>
				<div class="login-shortcuts">
					<a href="adminlogin.php" class="admin admin-primary">Admin Login</a>
					<a href="staff_login.php" class="admin admin-secondary">Staff Login</a>
				</div>
				<a href="signup.php" class="top-link">Create account</a>
				<h1>Login</h1>
				<p class="subtitle">Login to place orders, track history, and access your account.</p>
				<div class="form">
					<?php if ($error === 'invalid'): ?>
					<div class="auth-alert error">Invalid username or password.</div>
					<?php endif; ?>
					<?php if ($success === 'registered'): ?>
					<div class="auth-alert success">Registration successful. Please login now.</div>
					<?php endif; ?>
					<form action="validation.php" method="post">
						<label>Username<span>*</span></label>
						<br>
						<input type="text" name="username" minlength="3" maxlength="30" required>
						<br><br>
						<label>Password<span>*</span></label>
						<br>
						<input type="password" name="password" minlength="5" maxlength="50" required>
						<br><br><br>
						<input type="submit" name="Login" value="Login" class="button">
					</form>
				</div>
				</fieldset>
			</div>
		</div>
	</body>
</html>
