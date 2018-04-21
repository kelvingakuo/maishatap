<?php
require_once 'conf/database/dbconfig.php';

class USER
{

 private $conn;

 public function __construct()
 {
  $database = new Database();
  $db = $database->dbConnection();
  $this->conn = $db;
    }

 public function runQuery($sql)
 {
  $stmt = $this->conn->prepare($sql);
  return $stmt;
 }

 public function lasdID()
 {
  $stmt = $this->conn->lastInsertId();
  return $stmt;
 }

 public function register($upass,$uphone,$urole)
 {
  try
  {
   $salt = "84B03D034B409D4E";
   $upass = $upass.$salt;
   $upass = sha1($upass);

   $stmt = $this->conn->prepare("INSERT INTO tbl_users(userPass,userPhone,loginType)
                                                VALUES( :user_pass, :user_phone,:user_type)");
   $stmt->bindparam(":user_pass",$upass);
   $stmt->bindparam(":user_phone",$uphone);
   $stmt->bindparam(":user_type",$urole);
   $stmt->execute();
   return $stmt;
  }
  catch(PDOException $ex)
  {
   echo $ex->getMessage();
  }
 }

 public function login($phone,$upass)
 {
  try
  {
   $stmt = $this->conn->prepare("SELECT * FROM tbl_users WHERE userPhone=:phone");
   $stmt->execute(array(":phone"=>$phone));
   $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
   //$type = $userRow['loginType'];
   $salt = "84B03D034B409D4E";
   $upass = $upass.$salt;
   $password = sha1($upass);

   if($stmt->rowCount() == 1)
   {
     if($userRow['userPass']==$password)
     {
       if($userRow['loginType']=="admin"){
         //$_SESSION['userSession'] = $type;
         $_SESSION['userSession'] = $userRow['userID'];
         echo "<script>window.location.assign('dashboard/home.php')</script>";
       }else{
         //$_SESSION['userSession'] = $type;
         $_SESSION['userSession'] = $userRow['userID'];
         echo "<script>window.location.assign('user/home.php')</script>";
       }
     }else{
      header("Location: login.php?error");
      exit;
     }

   } else {
    header("Location: login.php?error");
    exit;
   }
 }catch(PDOException $ex)
  {
   echo $ex->getMessage();
  }
 }


 public function is_logged_in()
 {
  if(isset($_SESSION['userSession']))
  {
   return true;
  }
 }

 public function redirect($url)
 {
  header("Location: $url");
 }

 public function logout()
 {
  session_destroy();
  $_SESSION['userSession'] = false;
 }

}
