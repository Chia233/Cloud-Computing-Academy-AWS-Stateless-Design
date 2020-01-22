<html>
	<head>Welcome to Cloud Computing Academy</head>
	<style>
		.simpleFlex {
			display: flex;
		}
		.simpleFlex > a {
			margin-right: 5px;
		}
	</style>
	<?php
	session_start();

	if (isset($_SESSION['user_name'])) {
		echo "<br>";
		echo "You are now logged in as ".$_SESSION['user_name'];
		echo "<br>";
		echo "<div class='simpleFlex' style='margin-right:10px;'>"; 
		echo "<a href='Welcome.php'> Home </a>
			  <a href='upload.php'> Upload Files</a>
			  <a href='logout.php'> Logout </a>
			  ";
		echo "</div>";
		
	}
	else {
		echo "<br>";	
		echo "Welcome, Guest!";
		echo "<br>";
		echo "<a href='Welcome.php'> Home </a>";
		echo '		
			<form name="ccaLogin" method="post" action="doLogin.php" onSubmit="return validateForm()">
				<fieldset>
    			<br>
    			<p> <label for="Username">Username :</label> <input type="text" name="userName" pattern="^[A-Za-z][A-Za-z0-9]*$" ></input></p>
    			<br>
    			<p>Password  : <input type="password" name="Password"></input> </p>
    			<br>
    			<input type="submit" name="Login" value="login" h ref="doLogin.php" style="width:80px; margin-bottom:10px;"></input>
    			</fieldset>
			</form>';
	}
	?>
	<body>
		
		<script type='text/javascript'>
    		function validateForm() {
				var checkusername=document.forms["ccaLogin"]["userName"].value;
				if (checkusername==null || checkusername=="") {
  					alert("Username must be filled out");
					return false;
				}
			var checkpassword=document.forms["ccaLogin"]["Password"].value;
				if (checkpassword==null || checkpassword=="") {
  					alert("Password must be filled out");
  					return false;
  				} 
		}
		</script>
	</body>
</html>
