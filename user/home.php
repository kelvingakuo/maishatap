<?php
session_start();
require_once '../class.user.php';

$user_home = new USER();

$stmt = $user_home->runQuery("SELECT * FROM tbl_users WHERE userID=:uid");
$stmt->execute(array(":uid"=>$_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$userID = $row['userID'];

$sqlhospital = $conn->query("SELECT * FROM tbl_hospitals WHERE userID='$userID'");
$sqlhospitals = $sqlhospital->fetch_array();
$id = $sqlhospitals['id'];
$sqlrequest = $conn->query("SELECT * FROM tbl_request WHERE hospitalID='$id' ORDER BY id DESC");

//If Hospital admin wants to view location of scene
if (isset($_GET['caseLocation'])) {
  //create a session and redirect user to scenelocation page
  $_SESSION['requestID'] = $_GET['caseLocation'];
  header('location: sceneLocation.php');
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
    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
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
				        <li class="active"><a href="#" id="home">Home</a></li>
				        <!--<li class=""><a href="#" id="hospitals">Hospitals</a></li>-->
				        <li class=""><a href="#" id="hospital">Hospital</a></li>
                <li><a href="#" id="logout">Logout</a></li>
				      </ul>
				    </div>
				</div>
			  </div>
			</nav>
	<!--/ banner-->

	<!--home-section -->
	<section class="section-padding" style="min-height: 500px;">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<h2 class="ser-title">Reported Cases</h2>
					<hr class="botm-line">
				</div>
        <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default" style="min-height:300px;">
          <div class="panel-body">
				   <div class="col-md-12 list-group" style="min-height:300px">
            <?php
            //display a list of reported cases.
            if($sqlrequest->num_rows > 0){
              while($sqlrequests=$sqlrequest->fetch_array()){
                //loop through the reported casses
                ?>
                <div>
                  <div>
                    <h4> <?php echo $sqlrequests['detail']; ?></h4>
                    <span><i class="fa fa-phone"></i> <?php echo $sqlrequests['reporterPhone']; ?></span>
                    <span class="pull-right"><a href="?caseLocation=<?php echo $sqlrequests['id']; ?>" class="btn btn-danger"><i class="fa fa-map-marker"></i> Location</a></span>
                  </div>
                  <hr>
                </div>
                <?php
              }
             }
            ?>
			     </div>
         </div>
			  </div>
      </div>
			</div>
		</div>
	</section>
	<!--/ map-->

	<!--footer-->
	<?php require_once('../partials/footer.php'); ?>
	<!--/ footer-->

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/jquery.easing.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/custom.js"></script>
    <script>
    $(document).ready(function() {

      //directs you to file
      document.getElementById("home").addEventListener("click", home);
      function home() {
          window.location.assign('home.php');
      }
      document.getElementById("hospital").addEventListener("click", hospital);
      function hospital() {
          window.location.assign('hospital.php');
      }

      document.getElementById("logout").addEventListener("click", logout);
      function logout() {
          window.location.assign('logout.php');
      }

    });
    </script>

  </body>
</html>
