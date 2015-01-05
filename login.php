<?php 
include_once '/home/andrewed/public_html/prediction/src/dbuser.class.php';
$error = "";
$registerSuccess = "";

if(array_key_exists('register',$_GET)){
	$registerSuccess = $_GET['register'];
}

if($registerSuccess == 'success'){
	$registerSuccess = 'Thanks for registering with the Prediction Factory, Please go ahead and login to get started';
}

if(isset($_POST)){
	if(array_key_exists('login',$_POST)){
	
		$email    = $_POST['email'];
		$password = $_POST['password'];
		
		$dbuser = new dbuser();
		$user = $dbuser->loadUser($email, $password);
			
		if($user != null) {
			session_start();
			$_SESSION['predictionuser'] = $user;
			header("location:home.php");
			exit;
		}
		else {
			$error = "Incorrect Email or Password";
		}
	}
}	
?>
 <!DOCTYPE html> 
<html> 
<head> 
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Andrew Edge">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>Prediction Factory</title>

    <!-- Bootstrap core CSS -->
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Bootstrap theme -->
    <link href="../../dist/css/bootstrap-theme.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/prediction/css/signin.css" rel="stylesheet">
</head> 
  <body>

    <div class="container">

      <form class="form-signin" role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <h2 class="form-signin-heading">The Prediction Factory </h2>
        <input type="email" name="email" class="form-control" placeholder="Email address" required autofocus>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="login">Login</button>
		<a class="btn btn-lg btn-default btn-block" href="register.php">Sign Up</a>
		<?php if($error != "") {
			echo '<div class="alert alert-danger"><p>'. $error . '</p></div>';
		} ?>
		<?php if($registerSuccess != "") {
			echo '<div class="alert alert-success"><p>'. $registerSuccess . '</p></div>';
		} ?>
      </form>

    </div> <!-- /container -->

  </body>

</html>
