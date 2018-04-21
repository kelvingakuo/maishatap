<?php
session_start();
require_once 'class.user.php';

$req_scene = new USER();

// Be sure to include the file you've just downloaded
require_once('assets/API/AfricasTalkingGateway.php');
// Specify your authentication credentials
$username   = "mandenno";
$apikey = "e77a3b2c6569fc08e083683f2a6bac6e5cad0a004da75395f04ed0b87557f947";
$gateway    = new AfricasTalkingGateway($username, $apikey);

if(isset($_POST['reporterPhone'])){
  $reporterPhone =  $_POST['reporterPhone'];
  $longitude =  $_POST['longitude'];
  $latitude =  $_POST['latitude'];
  $hospitalID =  $_POST['hospitalID'];
  $hospitalName =  $_POST['hospitalName'];
  $detail =  $_POST['detail'];

  //Insert in Database
  $SQL = $conn->prepare("INSERT INTO tbl_request(reporterPhone, longitude, latitude, hospitalID, hospitalName, detail) VALUES(?,?,?,?,?,?)");
  $SQL->bind_param('ssssss',$reporterPhone, $longitude, $latitude, $hospitalID, $hospitalName, $detail);
  $id = $req_scene->lasdID();
  
  if (!$SQL) {
    $conn->error;
  }else{
    $SQL->execute();
    //get hospitals phone numbers
    $sqlhospital = $conn->query("SELECT * FROM tbl_hospitals WHERE id='$hospitalID'");
    $sqlhospitals = $sqlhospital->fetch_array();
    $phone1 = $sqlhospitals['hospitalPhone'];
    $phone2 = $sqlhospitals['hospitalPhone2'];
    $recipients = $phone1;
    
    $sqlrequest = $conn->query("SELECT * FROM tbl_request WHERE reporterPhone='$reporterPhone' AND longitude='$longitude' AND latitude='$latitude'");
    $sqlrequests = $sqlrequest->fetch_array();
    $id = $sqlrequests['id'];
    // Create a new instance of our awesome gateway class
    
    $message = "Allert: $detail.View at: https://www.veive.co.ke/maishatap/viewscene.php?request=$id";
    try
    {
      // Thats it, hit send and we'll take care of the rest.
      $results = $gateway->sendMessage($recipients, $message);

      foreach($results as $result) {
        // status is either "Success" or "error message"
        echo " Number: " .$result->number;
        echo " Status: " .$result->status;
        echo " MessageId: " .$result->messageId;
        echo " Cost: "   .$result->cost."\n";
      }
    }
    catch ( AfricasTalkingGatewayException $e )
    {
      echo "Encountered an error while sending: ".$e->getMessage();
    }
  }
}

?>
