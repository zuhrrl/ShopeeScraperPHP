<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("X-Robots-Tag: noindex, nofollow", true);
session_start();

// config connect database
include("../config/config.php");

//Declare variable
$name = null;
$email=null;
$phone=null;
$address=null;
$password=null;
$user_id=null;
$tagihan = null;
$errors = array(); 



// if server request method post

if ($_SERVER['REQUEST_METHOD']=='POST') {
  $email= $_POST['email'];
  $password = $_POST['password'];
  // if empty post

  if (empty($email)) {
  	array_push($errors, "Email is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM nasabah WHERE email='$email' AND pass='$password'";
  	$results = mysqli_query($conn, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['email'] = $email;
  	  $_SESSION['success'] = "You are now logged in";
      // Generate token
  	  $token = bin2hex(random_bytes(64));

      
      $sql = "SELECT name, tagihan, jatuh_tempo FROM nasabah WHERE email = '$email'";
      $queryuser = $conn->query($sql);

      if ($queryuser->num_rows > 0) {
          // output data of each row
          while($row = $queryuser->fetch_assoc()) {
              $name = $row['name'];
              $tagihan = $row['tagihan'];
              $japo = $row['jatuh_tempo'];
          }
          $data = array (
            'data' => 
            array (
            'name' => $name,
            'email'=> $email,
            'tagihan' => $tagihan,
            'token'=> $token,
            'jatuh_tempo' => $japo,

            ),
            );
          echo json_encode($data);
      } 

      
   
      else {
          array_push($errors, "Wrong username/password combination");
          echo json_encode($errors);

      }
    

      
    }
  }

}

 
?>