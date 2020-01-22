<?php
session_start();
session_destroy();
echo "You have logged out. Redirecting you back in 2 seconds.";
header("refresh:2, url=Welcome.php");
?>