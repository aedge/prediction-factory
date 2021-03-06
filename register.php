<?php 
include_once '/home/andrewed/public_html/prediction/src/dbuser.class.php';
$error = "";

if(isset($_POST) && array_key_exists('register',$_POST)){
	$name 	   = $_POST['name'];
	$email     = $_POST['email'];
	$password1 = $_POST['password1'];
	$password2 = $_POST['password2'];
	
	//Page Validation
	if($password1 == "" || $password2 == "" || $password1 <> $password2){
		$error = "Passwords must be entered in both fields and must match";		
	}
	if($email == ""){
		$error = "Please enter your email address";
	}
	if($name == ""){
		$error = "Please enter you name";		
	}
	$dbuser = new dbuser();	

	$passwordSalt = $dbuser->generateSalt();	
	$hashedpassword =  hash("sha256", $password1 . $passwordSalt);
	
	$userid = $dbuser->checkUser($email);
	
	if($userid != 0 || $userid = null){
		$error = "Email address already in use";
	} else {
		$userid = $dbuser->createUser($email, $hashedpassword, $name, $passwordSalt);
			
		if($userid != 0 && $userid != null) {
			header("location:login.php?register=success&id=" . $userid);
			exit;
		}
		else {
			$error = "Could not create user";
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

    <title>Prediction Factory | Sign Up </title>

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
        <h2 class="form-signin-heading">Please Enter Details </h2>
		<div class="form-group">
			<label for="inName">Name</label>
			<input type="text" id="inName" name="name" class="form-control" placeholder="Name" required autofocus>
		</div>
		<div class="form-group">
			<label for="inEmail">Email</label>
			<input type="email" id="inEmail" name="email" class="form-control" placeholder="Email address" required>
		</div>
		<div class="form-group">
			<label for="inPass1">Password</label>
			<input type="password" id="inPass1" name="password1" class="form-control" placeholder="Password" required>
		</div>
		<div class="form-group">
			<label for="inPass2">Re-Enter Password</label>
			<input type="password" id="inPass2" name="password2" class="form-control" placeholder="Re-Enter Password" required>
		</div>
		<button class="btn btn-lg btn-primary btn-block" type="submit" name="register">Sign Up</button>
		<?php if($error != "") {
			echo '<div class="alert alert-danger"><p>'. $error . '</p></div>';
		} ?>
      </form>

    </div> <!-- /container -->

  </body>
</html>
