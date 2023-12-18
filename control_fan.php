<?php
	$fanStatus = isset($_POST['fanStatus']) ? 1 : 0;
	file_put_contents('fan_status.txt', $fanStatus);
	
	header("Location: index.php");
	exit();
?>