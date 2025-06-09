<?php 
	$connection = mysqli_connect('localhost', 'root', '' ,'SA');

	if(mysqli_connect_errno()){
		die("error");
	}else{
		echo "connected";
	}
 ?>