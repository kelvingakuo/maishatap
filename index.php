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
  </head>

  <body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
  	<!--banner-->
	<section id="banner" class="banner">
		<div class="bg-color">
			<nav class="navbar navbar-default navbar-fixed-top">
			  <div class="container">
			  	<div class="col-md-12">
				    <div class="navbar-header">
				      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				      </button>
				      <a class="navbar-brand" href="#"><!--<img src="img/logo.png" class="img-responsive" style="width: 140px; margin-top: -16px;">-->maishaTap</a>
				    </div>
				    <div class="collapse navbar-collapse navbar-right" id="myNavbar">
				      <ul class="nav navbar-nav">
				        <li class="active"><a href="#banner">Home</a></li>
                <li class=""><a href="" id="login">Login</a></li>
                <li class=""><a href="" id="signup">Signup</a></li>
				      </ul>
				    </div>
				</div>
			  </div>
			</nav>
			<div class="container">
				<div class="row">
					<div class="banner-info">
						<div class="banner-logo text-center">
							<!--<img src="img/logo.png" class="img-responsive">-->
						</div>
						<div class="banner-text text-center">
							<h1 class="white">Tap, save a life!</h1>
							<p>Hero is a verb not a noun. Report an Emergency health scene Today.<br> Save a life!</p>
							<a href="#" id="mtap" class="btn btn-appoint">Tap Now!</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--/ banner-->


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
      document.getElementById("mtap").addEventListener("click", mtap);
      function mtap() {
          window.location.assign('m.tap.php');
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
