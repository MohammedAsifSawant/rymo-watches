<?php
	session_start();
	if(isset($_POST['username']) && isset($_POST['password']))
	{
		
			
	$server="localhost";
  	$username="root";
  	$password="";
  	$dbname="rymowatch";

  	$con = mysqli_connect($server, $username, $password, $dbname);

	mysqli_select_db($con, 'rymowatch');

	$username=trim($_POST['username']);
	$password=trim($_POST['password']);

	if($username === '' || $password === '')
	{
		header('location:login.php?error=invalid');
		exit;
	}

	$stmt = $con->prepare("SELECT * FROM regi WHERE username=? AND password=?");
	$stmt->bind_param("ss", $username, $password);
	$stmt->execute();
	$result = $stmt->get_result();
	$num = $result->num_rows;

	if($num==1)
	{
		$_SESSION['username']=$_POST['username'];
		$_SESSION['password']=$_POST['password'];
		header('location:index.php');
	}
	else
	{
		header('location:login.php?error=invalid');
	}
}
?>
