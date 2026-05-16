<html>
	<head>
		<title>
			registrationpage...
		</title>
       <link rel="stylesheet" href="reges.css">
	</head>
	<body>
		<?php $error = $_GET['error'] ?? ''; ?>
		<img class="background1" src="b1.jpg">
		<div class="container">
			<div class="mainbox">
				<fieldset>
				<a href="login.php" class="top-link">Back to login</a>
				<h1>Registration</h1>
				<p class="subtitle">Create your customer account with proper details and secure login information.</p>
				<div class="form">
					<?php if ($error === 'exists'): ?>
					<div class="auth-alert error">Username already exists. Please choose another username.</div>
					<?php elseif ($error === 'password'): ?>
					<div class="auth-alert error">Password and confirm password do not match.</div>
					<?php elseif ($error === 'invalid'): ?>
					<div class="auth-alert error">Please enter valid registration details.</div>
					<?php endif; ?>
					<form action="register.php" method="post">
						<label>Username<span>*</span></label>
						<br>
						<input type="text" name="username" minlength="3" maxlength="30" pattern="[A-Za-z0-9_ ]+" required>
						<br><br>
						<label>Email<span>*</span></label>
						<br>
						<input type="email" name="email" required>
						<br><br>
						<label>Password<span>*</span></label>
						<br>
						<input type="password" name="password" minlength="6" maxlength="50" required>
						<br><br>
						<label>Confirm Password<span>*</span></label>
						<br>
						<input type="password" name="cpassword" minlength="6" maxlength="50" required>
						<br><br><br>
						<input type="submit" name="Login" value="Sign up" class="button">
					</form>
				</div>
				</fieldset>
			</div>
		</div>	
	</body>
</html>
