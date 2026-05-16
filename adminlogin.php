<html>
	<head>
		<title>
			AdminLogin...
		</title>
	    <link rel="stylesheet" href="reges.css">
	</head>
	<body>
		<img class="background1" src="images/admin.jpg">
		<div class="container">
			<div class="mainbox">

				<fieldset>	
				
				<a href="login.php">Login</a>
				<h1>Admin Login</h1>
				<div class="form">
					<form action="avalidation.php" method="post">
						<label>Username<span>*</span></label>
						<br>
						<input type="text" name="username">
						<br><br>
						<label>Password<span>*</span></label>
						<br>
						<input type="password" name="password">
						<br><br><br>
						<input type="submit" name="Login" value="Admin Login" class="button">
					</form>
				</div>
				</fieldset>
			</div>
		</div>
	</body>
</html>