<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<div id="signin">
	<fieldset>
		<form name="form1" method="post" action="" enctype="multipart/form-data">
		<h3>DINO RUN</h3>
		<p>SILAHKAN LOGIN<p> 
		<input name="username" type="text" id="username" placeholder="Username">
		<input name="password" type="password" id="password" placeholder="Password">
		<input name="login" type="submit" value="LOGIN">
		<a style="color: white;" href="signup.php">SIGNUP</a>
		</form>
		

		<?php
			if ($_POST["login"]) {
				$username = mysqli_real_escape_string($kon, $_POST["username"]);
				$password = mysqli_real_escape_string($kon, $_POST["password"]);

				$sqla = mysqli_query($kon, "SELECT * FROM tbplayer WHERE username='$username' AND password='$password'");
				$ra = mysqli_fetch_array($sqla);
				$row = mysqli_num_rows($sqla);

				if ($row > 0) {
					session_start();
					$_SESSION["useradm"] = $ra["username"];
					$_SESSION["passadm"] = $ra["password"];
					echo "<div align='center' style='color: white;'>Login Berhasil</div>";
				} else {
					echo "<div align='center' style='color: red;'>Login Gagal</div>";
				}
				echo "<META HTTP-EQUIV='Refresh' Content='1; URL=?p=beranda'>";
			}
		?>


	</fieldset>
</div>
	
</body>
</html>