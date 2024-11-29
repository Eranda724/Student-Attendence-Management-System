<?php 
	$connection = mysqli_connect('localhost', 'root', '' ,'mypro');

	if(mysqli_connect_errno()){
		die("error");
	}else{
		echo "connected";
	}
 ?>