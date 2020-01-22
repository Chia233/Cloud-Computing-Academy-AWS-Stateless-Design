<?php
        session_start();
		$Username=$_POST['userName'];
        $Password=$_POST['Password'];
        
        
        
        
        $link = mysqli_connect('xxx.rds.amazonaws.com','user','password','dbName') or die(my_sql_connect_error());
		$checkname = "Login Query here";
		$result3 = mysqli_query($link ,$checkname) or die(mysqli_error($link));

		if (mysqli_num_rows($result3)== 1) {
			$row = mysqli_fetch_array($result3);
            $_SESSION['user_name'] = $row[0];
            if (!is_writable(session_save_path())) {
                echo 'Session path "'.session_save_path().'" is not writable for PHP!'; 
            }
            echo $row[0].", You are now logged in. You will be redirected in 2 seconds.";
            header("refresh:2, url=Welcome.php");
		}

		else if (mysqli_num_rows($result3)== 0) {
			echo "<br>";
            echo "You have entered the wrong username or password. You will be redirected in 2 seconds.";
            header("refresh:2, url=Welcome.php");
		}
		else if (isset($_SESSION['user_name'])){ 
		echo "<br>";
        echo "You are already logged in. You will be redirected in 2 seconds.";
        header("refresh:2, url=Welcome.php");
        }
        

        
		?>

        