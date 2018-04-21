<?php
session_start();
require_once 'class.user.php';

$user_home = new USER();

//get hospital name
$hospitalID = $_SESSION['hospitalID'];

$sqlhospitals = $conn->query("SELECT * FROM tbl_hospitals WHERE id='$hospitalID'");

$sqlhospitals = $sqlhospitals->fetch_array();

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
  </head>
  <body>
   <!--preloader-->
   <img src="assets/img/health-preloader.gif" style="margin-left: 40%;margin-top: 10%;" class="img-circle"></img>
   <span class="text-faded" style="margin-left: 40%;margin-top: 10%;"></span>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script>
    var map;
    function initMap() {
      //default coordinates
      var latA = -4.061034;
      var longA = 39.6797;
      var hospitalID = '<?php echo $sqlhospitals['id']; ?>';
      var hospitalName = '<?php echo $sqlhospitals['hospitalName']; ?>';
      var detail = '<?php echo $_SESSION['detail']; ?>';
      var reporterPhone = '<?php echo $_SESSION['reporterPhone']; ?>';

      console.log(hospitalID);
      console.log(hospitalName);

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
              var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
              };
              var latitude = pos.lat;
              var longitude = pos.lng;
              console.log(longitude);
              console.log(latitude);
              //post data using ajax
              $.ajax({
                  url: "report.php",
                  type: "POST",
                  data: { reporterPhone: reporterPhone, longitude: longitude, latitude: latitude, hospitalID: hospitalID, hospitalName: hospitalName, detail: detail },
                  success: function(data) {
                      //redirect to report.php
                      window.location.assign('m.tap.php');
                  }
              });

            }, function() {  handleLocationError(true); });
      } else {
        // Browser doesn't support Geolocation
        //handleLocationError(false, infowindow, map.getCenter());
      }
       function handleLocationError(browserHasGeolocation, infowindow, pos) {
       console.log('Error: The Geolocation service failed. Allow us to locate you.');
        }



      };

    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAye4m-oKGsEFaiYcNwta0XR_pdW2UPBek&callback=initMap">
    </script>

  </body>
</html>
