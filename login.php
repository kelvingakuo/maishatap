<?php
session_start();
require_once 'class.user.php';
$user_login = new USER();
if($user_login->is_logged_in()!=""){
  $stmt = $user_login->runQuery("SELECT * FROM tbl_users WHERE userID=:uid");
  $stmt->execute(array(":uid"=>$_SESSION['userSession']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if($row['loginType']=="admin"){
    $user_login->redirect('dashboard/home.php');
  }else {
    $user_login->redirect('user/home.php');
  }
}

if(isset($_POST['btn-login'])){
 $phone = trim($_POST['txtphone']);
 $upass = trim($_POST['txtupass']);

 if($user_login->login($phone,$upass)) {
  #$user_login->redirect('home.php');
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
                <li class=""><a href="#" id="signup">Signup</a></li>
				      </ul>
				    </div>
				</div>
			  </div>
			</nav>
	<!--/ banner-->


    <div  class="section-padding" style="background-color: rgb(243, 243, 243); visibility: visible; animation-name: fadeIn;min-height:550px; max-height:800px;">
    <div class="container">
        <div class="row">
          <div class="col-md-4 col-md-offset-4 col-sm-12 col-xs-12" style="background-color:white;min-height:450px; padding-top:50px;padding-left:30px;padding-right:30px;padding-bottom:50px;">
                        <h3 class="title">Sign In</h3>
                        <form role="form" method="post">
                            <fieldset>
                              <?php
                              if(isset($_GET['error'])) {
                               ?>
                                <div class='alert alert-danger'>
                                <button class='close' data-dismiss='alert'>&times;</button>
                                <strong>Sorry!</strong> Seems like you entered wrong details wrong Details.
                               </div>
                              <?php
                              }
                              ?>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Phone Number" name="txtphone" type="text" autofocus required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="txtupass" type="password" value="" required>
                                </div>
                                <button class="btn btn-lg btn-primary btn-block" type="submit" name="btn-login">Login</button></br>
                                <br>
                                New Here? <a href="signup.php">Create Account. </a></br>
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
