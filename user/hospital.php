<?php
session_start();
require_once '../class.user.php';

$user_home = new USER();

$stmt = $user_home->runQuery("SELECT * FROM tbl_users WHERE userID=:uid");
$stmt->execute(array(":uid"=>$_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['submit'])) { //if submit button is clicked
  //get all data from the form
  $userID = $row['userID'];
  $hospitalName = $_POST['hospitalName'];
  $hospitalPhone = $_POST['hospitalPhone'];
  $hospitalPhone2 = $_POST['hospitalPhone2'];
  $hospitalEmail = $_POST['hospitalEmail'];
  $longitude = $_POST['longitude'];
  $latitude = $_POST['latitude'];

  //insert in db
  $SQL = $conn->prepare("INSERT INTO tbl_hospitals(userID,hospitalName,hospitalPhone,hospitalPhone2,hospitalEmail,longitude,latitude) VALUES(?,?,?,?,?,?,?)");
  $SQL->bind_param('sssssss',$userID,$hospitalName,$hospitalPhone,$hospitalPhone2,$hospitalEmail,$longitude,$latitude);

  if (!$SQL) {
    $conn->error;
  }else{
    $SQL->execute();
    header('location: hospital.php');
  }
}


if (isset($_GET['hospitalEdit'])) {
  //create a session and redirect user to editing page
  $_SESSION['hospitalID'] = $_GET['hospitalEdit'];
  header('location: editHospital.php');
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
				        <!--<li class=""><a href="#" id="hospitals">Hospitals</a></li>-->
				        <li class="active"><a href="#" id="hospital">Hospital</a></li>
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
        <?php
        //check if user created a hospital before
        $userID = $row['userID'];
        $sqlhospital = $conn->query("SELECT * FROM tbl_hospitals WHERE userID='$userID'");
        if($sqlhospital->num_rows > 0){
          //show data on the user
          while($sqlhospitals = $sqlhospital->fetch_array()){
            $hospitalID = $userID;
            ?>
            <div class="col-md-8 col-md-offset-2">
    					<h2 class="ser-title">Hospital Details.</h2>
    					<hr class="botm-line">
    				</div>
            <div class="col-md-8 col-md-offset-2 list-group">
              <div>
                <div>
                  <h4> <?php echo $sqlhospitals['hospitalName']; ?></h4>
                  <span><i class="fa fa-phone"></i> <?php echo $sqlhospitals['hospitalPhone']; ?></span><br>
                  <span><i class="fa fa-phone"></i> <?php echo $sqlhospitals['hospitalPhone2']; ?></span><br>
                  <span><i class="fa fa-envelope-o"></i> <?php echo $sqlhospitals['hospitalEmail']; ?></span>
                  <span class="pull-right"><a href="?hospitalEdit=<?php echo $sqlhospitals['id']; ?>" class="btn btn-danger"><i class="fa fa-edit"></i> Edit</a></span>
                </div>
                <hr>
              </div>
            </div>
            <?php
          }

        }else {
          //display form to create hospital
          ?>
          <div class="col-md-8 col-md-offset-2">
  					<h2 class="ser-title">Create Hospital.</h2>
  					<hr class="botm-line">
  				</div>
          <div class="col-md-8 col-md-offset-2">
          <div class="panel panel-default" style="min-height:300px;">
            <div class="panel-body">
  				   <div class="col-md-12" style="min-height:300px">
               <form method="POST" action="" role="form">
                 <div class="form-group">
                   <input type="text" name="hospitalName" class="form-control"  placeholder="Hospital Name" />
                 </div>
                 <div class="form-group">
                   <input type="text" name="hospitalPhone" class="form-control" value="<?php echo $row['userPhone']; ?>" placeholder="Primary Hospital Phone" />
                 </div>
                 <div class="form-group">
                   <input type="text" name="hospitalPhone2" class="form-control" value="<?php echo $row['userPhone2']; ?>" placeholder="Secondary Hospital Phone" />
                 </div>
                 <div class="form-group">
                   <input type="text" name="hospitalEmail" class="form-control" placeholder="Hospital Email" />
                 </div>
                 <div class="form-group">
                     <div id="map" style="width:100%; height: 200px;">
                     </div>
                     <input  type="hidden" id="lng" name="longitude"/>
                     <input  type="hidden" id="lat" name="latitude"/>
                 </div>
                <button type="submit" class="btn btn-block btn-primary" name="submit">Submit</button>
               </form>
  			     </div>
           </div>
  			  </div>
          </div>
          <?php } ?>
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

    <!-- location picker -->
         <script type="text/javascript">

          //Set up some of our variables.
          var map; //Will contain map object.
          var marker = false; ////Has the user plotted their location marker?
          //Function called to initialize / create the map.
          //This is called when the page has loaded.
          function initMap() {
           //default coordinates
           var latA = -4.061034;
           var longA = 39.6797;
           map = new google.maps.Map(document.getElementById('map'), {
             zoom: 16,
             center: new google.maps.LatLng(latA, longA),
             mapTypeId: 'roadmap'
           });

         var infowindow = new google.maps.InfoWindow;

         //geolocate
          if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(function(position) {
                var pos = {
                  lat: position.coords.latitude,
                  lng: position.coords.longitude
                };

                infowindow.setPosition(pos);
                infowindow.setContent('Where You are.<br>Click map to pick<br> Event location');
                infowindow.open(map);
                map.setCenter(pos);
              }, function() {
                handleLocationError(true, infowindow, map.getCenter());
              });
          } else {
               // Browser doesn't support Geolocation
              handleLocationError(false, infowindow, map.getCenter());
          }
          function handleLocationError(browserHasGeolocation, infowindow, pos){
          infowindow.setPosition(pos);
          infowindow.setContent(browserHasGeolocation ?
              'Error: The Geolocation service failed. Allow us to locate you.' :
              'Error: Your browser doesn\'t support geolocation.');
              infowindow.open(map);
         }

         //Listen for any clicks on the map.
         google.maps.event.addListener(map, 'click', function(event) {
             //Get the location that the user clicked.
             var clickedLocation = event.latLng;
             //If the marker hasn't been added.
             if(marker === false){
                 //Create the marker.
                 marker = new google.maps.Marker({
                     position: clickedLocation,
                     map: map,
                     draggable: true //make it draggable
                 });
                 //Listen for drag events!
                 google.maps.event.addListener(marker, 'dragend', function(event){
                     markerLocation();
                 });

             } else{
                 //Marker has already been added, so just change its location.
                 marker.setPosition(clickedLocation);
             }
             //Get the marker's location.
             markerLocation();
         });
        }

        //This function will get the marker's current location and then add the lat/long
        //values to our textfields so that we can save the location.
        function markerLocation(){
            //Get location.
            var currentLocation = marker.getPosition();
            //Add lat and lng values to a field that we can save.
            document.getElementById('lat').value = currentLocation.lat(); //latitude
            document.getElementById('lng').value = currentLocation.lng(); //longitude
        }


        //Load the map when the page has finished loading.
        google.maps.event.addDomListener(window, 'load', initMap);
        </script>
    <script async defer  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAye4m-oKGsEFaiYcNwta0XR_pdW2UPBek&callback=initMap">
    </script>

  </body>
</html>
