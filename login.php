<!DOCTYPE html>
<html>
    
<head>
	<title>Login Page</title>
	<!-- <link rel="stylesheet" href="login_style.css"> -->
</head>

<body>
	<?php
		if (!isset($_SESSION["cookie_name"])){
			echo file_get_contents('login_form.php');
		}
		else{
			echo "<h1 style='text-align: center'>Username: " . $_SESSION["cookie_name"] . "</h1>";
			if(isset($_COOKIE[$_SESSION["cookie_name"]])) {
				echo "<h5 style='text-align: center'>Name of Cookie: '" . $_SESSION["cookie_name"] . "' is set!<br> </h5>";
				echo "<h5 style='text-align: center'>Value of Cookie: " . $_COOKIE[$_SESSION["cookie_name"]] . "<br> </h5>";
				// echo "<br><br>";
				// echo "<form method='post' action='logout.php'>";
				// echo "<div style='text-align:center'>";
				// echo "<input type='submit' name='logout' value='Log out'/>";
				// echo "</div>";
				// echo "</form>";
			}
		}
	?>
</body>
</html>