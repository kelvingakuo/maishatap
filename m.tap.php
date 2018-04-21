<?php
session_start();
require_once 'class.user.php';

$user_home = new USER();

$sqlpoliceposts = $conn->query("SELECT * FROM tbl_policeposts WHERE longitude!='' AND latitude!=''");

$sqlhospitals = $conn->query("SELECT * FROM tbl_hospitals WHERE longitude!='' AND latitude!=''");

$sqlrequests = $conn->query("SELECT * FROM tbl_request WHERE longitude!='' AND latitude!=''");


if (isset($_POST['pickHospital'])) {
  //Create session and redirect user to page where you pick their location
  $_SESSION['hospitalID'] = $_POST['hospitalID'];
  $_SESSION['detail'] = $_POST['detail'];
  $_SESSION['reporterPhone'] = $_POST['reporterPhone'];

  header('location: locationpicker.php');
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

	<!--map-section -->
	<section id="map-section" class="section-padding" style="min-height: 450px;">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<h2 class="ser-title">Select Hospital.</h2>
					<hr class="botm-line">
				</div>
        <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default" style="min-height:300px;">
          <div class="panel-body" style="padding:0px;">
            <div class="row">
				   <div class="col-md-8" id="map" style="min-height:300px">

			     </div>
           <div class="col-md-4">
            <div><h5>Select Hospital</h5></div>
            <div  id="locations" class="list-group">
              <?php
              $response = $conn->query("SELECT * FROM tbl_hospitals");
              if($response->num_rows > 0){
                 while($Prow=$response->fetch_array()){ ?>
                   <div class="" id="latiloni" data-latlon="<?php echo $Prow['latitude'];?>, <?php echo $Prow['longitude'];?>" style="padding-right:5px">
                       <span><a href="" data-toggle="modal" data-target="#hospital<?php echo $Prow['id']; ?>" class="btn btn-info btn-block"><i class="fa fa-medkit"></i> <?php echo $Prow['hospitalName']; ?></a></span>
                     <hr>
                     <div class="modal fade" id="hospital<?php echo $Prow['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                       <div class="modal-dialog" role="document">
                         <div class="modal-content">
                             <div class="modal-body text-center">
                               <form method="POST" action="" role="form">
                                  <input  type="hidden"  name="hospitalID" value="<?php echo $Prow['id']; ?>"/>
                                 <div class="form-group">
                                   <textarea type="text" name="detail" class="form-control"  placeholder="Describe the scene" ></textarea>
                                 </div>
                                 <div class="form-group">
                                   <input type="text" name="reporterPhone" class="form-control" placeholder="Your Phone Number +2547xxxxxxxx" />
                                 </div>
                                 <button type="submit" class="btn btn-block btn-primary" name="pickHospital">Report</button>
                               </form>
                             </div>
                        </div>
                      </div>
                    </div>

                   </div>

              <?php } }else{ ?>
                <div class='alert alert-info' style='text-align:center;'>
                 <button class='close' data-dismiss='alert'>&times;</button>
                 <i class='fa fa-warning'></i><strong>Sorry!</strong><br> No hospitals Registered yet.
                </div>
              <?php } ?>
            </div>
           </div>
         </div>
         </div>
			  </div>
      </div>
			</div>
		</div>
	</section>
	<!--/ map-->

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

    <script>
      window.onload = function() {
        navigator.geolocation.getCurrentPosition(sortResults);
      }

      function sortResults(position) {
        // Grab current position
        var latlon = new LatLon(position.coords.latitude, position.coords.longitude);

        var locations = document.getElementById('locations');
        var locationList = locations.querySelectorAll('#latiloni');
        var locationArray = Array.prototype.slice.call(locationList, 0);

        locationArray.sort(function(a,b){
          var locA  = a.getAttribute('data-latlon').split(',');
          var locB  = b.getAttribute('data-latlon').split(',');

          distA = latlon.distanceTo(new LatLon(Number(locA[0]),Number(locA[1])));
          distB = latlon.distanceTo(new LatLon(Number(locB[0]),Number(locB[1])));
          return distA - distB;
        });

        //Reorder the list
        locations.innerHTML = "";
        locationArray.forEach(function(el) {
          locations.appendChild(el);
        });

    };
    </script>

<script>
var map;
function initMap() {
  //default coordinates
  var latA = -1.319244;
  var longA = 36.894419;

  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 13,
    center: new google.maps.LatLng(latA, longA),
    mapTypeId: 'roadmap'
  });

  var iconBase = 'assets/img/map-icons/';
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

  var hospitals = [
  <?php while($hospitals = $sqlhospitals->fetch_array()){ ?>
  {
  position: new google.maps.LatLng(<?php echo $hospitals['latitude']; ?>, <?php echo $hospitals['longitude']; ?>),
  type: 'hospital',
  name: '<?php echo $hospitals['hospitalName']; ?>',
  phone: '<?php echo $hospitals['hospitalPhone']; ?>',
  },
  <?php } ?>
  ];

  var policeposts = [
  <?php while($policeposts = $sqlpoliceposts->fetch_array()){ ?>
  {
  position: new google.maps.LatLng(<?php echo $policeposts['latitude']; ?>, <?php echo $policeposts['longitude']; ?>),
  type: 'policepost',
  id: '<?php echo $policeposts['id']; ?>',
  name: '<?php echo $policeposts['policepostName']; ?>',
  phone: '<?php echo $policeposts['policepostPhone']; ?>',
  },
    <?php } ?>
  ];


  var requests = [
  <?php while($requests = $sqlrequests->fetch_array()){ ?>
  {
  position: new google.maps.LatLng(<?php echo $requests['latitude']; ?>, <?php echo $requests['longitude']; ?>),
  type: 'accident',
  id: '<?php echo $requests['id']; ?>',
  description: '<?php echo $requests['detail']; ?>',
  },
  <?php }  ?>
  ];

  var infowindow = new google.maps.InfoWindow;

  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
          var pos = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
          };

          infowindow.setPosition(pos);
          infowindow.setContent('Your Location.');
          infowindow.open(map);
          map.setCenter(pos);
        }, function() {  handleLocationError(true, infowindow, map.getCenter()); });
  } else {
    // Browser doesn't support Geolocation
    handleLocationError(false, infowindow, map.getCenter());
  }
   function handleLocationError(browserHasGeolocation, infowindow, pos) {
    infowindow.setPosition(pos);
    infowindow.setContent(browserHasGeolocation ?
   'Error: The Geolocation service failed. Allow us to locate you.' :
   'Error: Your browser doesn\'t support geolocation.');
    infowindow.open(map);  }
  // Create markers.
  var i = 0;
  hospitals.forEach(function(hospital) {
      marker = new google.maps.Marker({
      position: hospital.position,
      icon: icons[hospital.type].icon,
      map: map
    });

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent('<div><strong>Hospital: '+hospital.name+'</strong><br>Phone: '+hospital.phone+'</div>');
        infowindow.open(map, this);
    });

  });

  policeposts.forEach(function(policepost) {
    marker = new google.maps.Marker({
      position: policepost.position,
      icon: icons[policepost.type].icon,
      map: map
    });

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent('<div><strong>Police Post: '+policepost.name+'</strong><br>Phone: '+policepost.phone+'</div>');
        infowindow.open(map, this);
    });

  });

  requests.forEach(function(request) {
    marker = new google.maps.Marker({
      position: request.position,
      icon: icons[request.type].icon,
      map: map
    });

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent('<div>Description: '+request.description+'</div>');
        infowindow.open(map, this);
    });

  });
}
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAye4m-oKGsEFaiYcNwta0XR_pdW2UPBek&callback=initMap">
</script>

  </body>
</html>
