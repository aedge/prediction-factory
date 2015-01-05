<?php
include_once '/home/andrewed/public_html/prediction/src/session.php';
include_once '/home/andrewed/public_html/prediction/src/dbuser.class.php';

/** Page security logic **/
if($user->accesslevel < 50) {
	header("location:home.php");
}

$dbuser = new dbuser();

if(isset($_POST['save']) && $_POST['save'] != "" ){

	$saveSuccess = "";
	$saveError   = "";
	$userid    = $_POST['user'];
	$password  = $_POST['password'];
	
	echo $userid;
	
	if($password == "" || $password == null) {
		$saveError = "Password cannot be blank";
	}
	
	$passwordSalt = $dbuser->generateSalt();	
	$hashedpassword =  hash("sha256", $password . $passwordSalt);
	
	if($saveError == "") {
		$dbuser->updateUserPassword($userid, $hashedpassword, $passwordSalt);
		$saveSuccess = "Users password has been updated";
	}

} 

$users = $dbuser->loadUserList();

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

    <title>Prediction Factory | Leagues </title>

    <!-- Bootstrap core CSS -->
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Jumbotron Narrow theme -->
    <link href="/bootstrap/css/jumbotron-narrow.css" rel="stylesheet">
	
	<link href="/prediction/css/prediction.css" rel="stylesheet" >
	
	<!-- Bootstrap Javascript -->
	<script src="/js/jquery.min.js" type="text/javascript" ></script>
    <script src="/bootstrap/js/bootstrap.min.js" type="text/javascript" ></script>
	
	<!-- Bootstrap Input tags -->
	<link href="/bootstrap/css/jquery.tagsinput.css" rel="stylesheet">
	<script src="/bootstrap/js/jquery.tagsinput.min.js" type="text/javascript" ></script>

</head>
<body>
	<div class="container">
	<?php include '/home/andrewed/public_html/prediction/header.php'; ?>
	  <div class="row">
		<div class="col-md-12">	
			<form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"> Reset Passwords </h3>
				</div>
				<div class="panel-body">					
					<?php
					if(isset($saveError) && $saveError != ""){
						echo '<p class="bg-danger">'. $saveError .'</p>';
					}
					
					if(isset($saveSuccess) && $saveSuccess != ""){
						echo '<p class="bg-success">'. $saveSuccess .'</p>';
					}
					
					?>				
				
						<div class="form-group">
							<label for="selUser">User</label>
							<select id="selUser" name="user" class="form-control">
								<?php foreach($users as $user) { ?>
									<option value="<?php echo $user->userid; ?>"><?php echo $user->name; ?></option>
								<?php } ?>
							</select>
						</div>
												
						<div class="form-group">
							<label for="inUserPass">Description</label>
							<input type="password" id="inUserPass" name="password" class="form-control" placeholder="New password" value=""  />
						</div>
						
						<div class="text-center col-md-12">
							<button type="submit" class="btn btn-primary" name="save" value="save" > Save </button>
							<button type="reset" class="btn btn-default" > Cancel </button>
						</div>
				</div>
			</div>
			</form>
	    </div>
	  </div>
	  <div class="footer">
		<p>&copy; Andrew Edge 2014</p>
	  </div>	
  </div>
</body>
</html>
