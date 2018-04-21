<?php
session_start();
require_once '../class.user.php';
// Be sure to include the file you've just downloaded
require_once('../assets/API/AfricasTalkingGateway.php');
// Specify your authentication credentials
$username   = "mandenno";
$apikey = "e77a3b2c6569fc08e083683f2a6bac6e5cad0a004da75395f04ed0b87557f947";
$gateway    = new AfricasTalkingGateway($username, $apikey);

$user_home = new USER();

$stmt = $user_home->runQuery("SELECT * FROM tbl_users WHERE userID=:uid");
$stmt->execute(array(":uid"=>$_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$userID = $row['userID'];

//check if there is a session
if (isset($_SESSION['requestID'])) {
  $requestID = $_SESSION['requestID'];
  //get data on this hospital from the db
  $sqlrequests = $conn->query("SELECT * FROM tbl_request WHERE id='$requestID'");
}else {
  header('location: home.php');
}

$sqlrequests = $conn->query("SELECT * FROM tbl_request WHERE id='$requestID'");
$rowRequests = $sqlrequests->fetch_array();

//if user clicks respond
if(isset($_POST['response'])){
    $message = $_POST['responseMessage'];
    $phone = $rowRequests['reporterPhone'];
    //find reporters Phone Number then send sms
    $to = $phone;
    
    try
    {
      // Thats it, hit send and we'll take care of the rest.
      $results = $gateway->sendMessage($to, $message);

      foreach($results as $result) {
        // status is either "Success" or "error message"
        //echo " Number: " .$result->number;
        //echo " Status: " .$result->status;
        //echo " MessageId: " .$result->messageId;
        //echo " Cost: "   .$result->cost."\n";
      }
    }
    catch ( AfricasTalkingGatewayException $e )
    {
      echo "Encountered an error while sending: ".$e->getMessage();
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
				        <li><a href="#" id="home">Home</a></li>
				        <li><a href="#" id="hospital">Hospital</a></li>
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
					<h2 class="ser-title">Scene Location</h2>
					<hr class="botm-line">
				</div>
        <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default" style="min-height:300px;">
          <div id="map" class="panel-body" style="min-height:300px;">


          </div>

          <input type="hidden" id="latA" value="<?php echo $rowRequests['latitude']; ?>">
          <input type="hidden" id="longA" value="<?php echo $rowRequests['longitude']; ?>">
          <input type="hidden" id="requestID" value="<?php echo $rowRequests['id']; ?>">
			  </div>
      </div>
			</div>
		</div>
	</section>
	<!--/ map-->


<div class="modal fade" id="response" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog" role="document">
     <div class="modal-content">
         <div class="modal-body text-center">
           <form method="POST" action="" role="form">
             <div class="form-group">
               <textarea type="text" name="responseMessage" class="form-control"  placeholder="Type response..." ></textarea>
             </div>
             <button type="submit" class="btn btn-block btn-primary" name="response">Send</button>
           </form>
         </div>
    </div>
  </div>
</div>
                    
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

    <script>
      var map;
      function initMap() {
        var latA = document.getElementById("latA").value;
        var longA = document.getElementById("longA").value;
        var requestID = document.getElementById("requestID").value;

        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 16,
          center: new google.maps.LatLng(latA, longA),
          mapTypeId: 'roadmap'
        });

        var iconBase = '../assets/img/map-icons/';
        var icons = {
          hospital: {
            icon: iconBase + 'firstaid.png'
          },
          policepost: {
            icon: iconBase + 'police.png'
          },
          accident: {
            icon: iconBase + 'caution.png'
          },
        };

        var features = [
          {
            position: new google.maps.LatLng(latA, longA),
            type: 'accident',
          }
        ];

        var infowindow = new google.maps.InfoWindow;

        // Create markers.
        features.forEach(function(feature) {
          var marker = new google.maps.Marker({
            position: feature.position,
            icon: icons[feature.type].icon,
            map: map
          });

          google.maps.event.addListener(marker, 'click', function() {
              infowindow.setContent('<div><strong> <a data-toggle="modal" data-target="#response" class="btn btn-warning" ">Respond</a></strong></div>');
              infowindow.open(map, this);
          });

        });

      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAye4m-oKGsEFaiYcNwta0XR_pdW2UPBek&callback=initMap">
    </script>
  </body>
</html>
