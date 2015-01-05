<?php
session_start();
if(isset($_SESSION) && array_key_exists('predictionuser',$_SESSION)){
	$user = $_SESSION['predictionuser'];
} else {
	
	if(!strpos($_SERVER['PHP_SELF'],'login')){
		header('Location: login.php');
	}
	$user = null;
}

?>