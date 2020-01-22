html>
	<head>Welcome to Cloud Computing Academy</head>
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
		echo "Welcome, Guest!";
		echo "<a href='Welcome.php'> Home </a>";
	}
	?>
	<body>
		<form name="uploadFile" method="post" action="?" enctype="multipart/form-data">
		<fieldset>
    		<br>
            <label>Upload your File</label> 
            <br>
    		<p><input type="file" name="uploadedWork"/></p>
    		<br>
    		<input type="submit" name="uploadButton" value="Upload" style="width:80px; margin-bottom:10px;" />
    	</fieldset>
		</form>
		
    </body>
    <?php
        if (isset($_FILES["uploadedWork"])) {
            $file_name = $_FILES["uploadedWork"]["name"];
            $temp_location = $_FILES['uploadedWork']['tmp_name'];
            
            require 'vendor/autoload.php';

            $s3 = new Aws\S3\S3Client([
                'region'  => 'us-east-1',
                'version' => 'latest',
                'credentials' => [
                    'key'    => "your key here",
                    'secret' => "your secret here",
                ],
                'http' => [
                    'verify' => false
                ]
            ]);
            
            $result = $s3->putObject([
                'Bucket' => 'your bucket here',
                'Key'    => $file_name,
                'SourceFile' => $temp_location,		
                'http'    => [
                    'verify' => false
                ]
            ]);
    
            if ($result) {
                echo "File upload is successful!";
            }
            else {
                echo "File upload is unsuccessful :(";
            }
        }
    ?>
</html>