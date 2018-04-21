<?php
session_start();
require_once 'class.user.php';
$reg_user = new USER();

if($reg_user->is_logged_in()!=""){
  $stmt = $reg_user->runQuery("SELECT * FROM tbl_users WHERE userID=:uid");
  $stmt->execute(array(":uid"=>$_SESSION['userSession']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if($row['loginType']=="admin"){
    $reg_user->redirect('dashboard/home.php');
  }else {
    $reg_user->redirect('user/home.php');
  }
}

if(isset($_POST['btn-signup'])){
 $upass = trim($_POST['txtpass']);
 $uphone = trim($_POST['txtphone']);
 $urole = trim($_POST['txtrole']);

 $stmt = $reg_user->runQuery("SELECT * FROM tbl_users WHERE userPhone=:phone");
 $stmt->execute(array(":phone"=>$uphone));
 $row = $stmt->fetch(PDO::FETCH_ASSOC);

 if($stmt->rowCount() > 0){
  $msg = "
        <div class='alert alert-danger'>
    <button class='close' data-dismiss='alert'>&times;</button>
     <strong>Sorry !</strong> Someone with that Phone Number already exists.
     </div>
     ";
 }else{
  if($reg_user->register($upass,$uphone,$urole)){
    $msg = "
       <div class='alert alert-success'>
        <button class='close' data-dismiss='alert'>&times;</button>
        <strong>Success!</strong> Login here <a href='login.php'>Login</a>
         </div>
       ";

  } else {
   echo "sorry , Query could no execute...";
  }
 }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>maishaTap</title>
    <meta name="description" content="Report Emergency health cases in one tap">
    <meta name="keywords" content="Healthcare, Accident, Help,">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans|Raleway|Candal">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">

    <script defer src="//cdn.rawgit.com/chrisveness/geodesy/v1.1.2/latlon-spherical.js"></script>
    <script defer src="//cdn.rawgit.com/chrisveness/geodesy/v1.1.2/dms.js"></script>
  </head>

  <body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
			<nav class="navbar navbar-default navbar-fixed-top" style="position:relative;background-color:#396672;">
			  <div class="container">
			  	<div class="col-md-12">
				    <div class="navbar-header">
				      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				      </button>
				      <a class="navbar-brand" href="#" id="index"><!--<img src="img/logo.png" class="img-responsive" style="width: 140px; margin-top: -16px;">-->maishaTap</a>
				    </div>
				    <div class="collapse navbar-collapse navbar-right" id="myNavbar">
				      <ul class="nav navbar-nav">
				        <li><a href="#" id="home">Home</a></li>
                <li class=""><a href="#" id="login">Login</a></li>
                <li class="active"><a href="#" id="signup">Signup</a></li>
				      </ul>
				    </div>
				</div>
			  </div>
			</nav>
	<!--/ banner-->


    <div  class="section-padding" style="background-color: rgb(243, 243, 243); visibility: visible; animation-name: fadeIn;min-height:300px; max-height:800px;">
    <div class="container">
        <div class="row">
          <div class="col-md-4 col-md-offset-4 col-sm-12 col-xs-12" style="background-color:white;min-height:450px; padding-top:50px;padding-left:30px;padding-right:30px;padding-bottom:50px;">
                        <h3 class="title">Sign Up</h3>
                        <form role="form" method="post">
                            <fieldset>
                              <?php if(isset($msg)) echo $msg;  ?>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Phone number +2547xxxxxxxx" name="txtphone" required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="txtpass" type="password" value="" required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name="txtrole" type="hidden" value="user" required>
                                </div>
                                <button class="btn btn-lg btn-primary btn-block" type="submit" name="btn-signup">Signup</button></br>
                            </fieldset>
                        </form>
            </div>
        </div>
    </div>
  </div>

  <!--footer-->
	<?php require_once('partials/footer.php'); ?>
	<!--/ footer-->

  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/jquery.easing.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/custom.js"></script>
  <script>
  $(document).ready(function() {

    //directs you to file
    document.getElementById("index").addEventListener("click", index);
    function index() {
        window.location.assign('index.php');
    }
    document.getElementById("home").addEventListener("click", home);
    function home() {
        window.location.assign('index.php');
    }
    document.getElementById("login").addEventListener("click", login);
    function login() {
        window.location.assign('login.php');
    }
    document.getElementById("signup").addEventListener("click", signup);
    function signup() {
        window.location.assign('signup.php');
    }

  });
  </script>

</body>
</html>
