<?php
session_start();
include("../config/config.php");
header("X-Robots-Tag: noindex, nofollow", true);
// initializing variables
$username = "";
$email    = "";
$name = "";
$errors = array(); 
$status = "";
$tagihan = "";


// REGISTER USER
if ($_SERVER['REQUEST_METHOD']=='POST') {
  // receive all input values from the form
  $name = $_POST['name'];
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $tagihan = $_POST['tagihan'];

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($name)) { array_push($errors, "Name is required"); }
  if (empty($password)) { array_push($errors, "Password is required"); }
  if (empty($tagihan)) { array_push($errors, "Tagihan is required"); }


  /*if ($password != $password_2) {
	array_push($errors, "The two passwords do not match");
  }
  */

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM nasabah WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($conn, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password);//encrypt the password before saving in the database

  	$query = "INSERT INTO nasabah (name, username, email, pass, tagihan) 
  			  VALUES('$name','$username', '$email', '$password', '$tagihan')";
  	mysqli_query($conn, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
    $status = "success";
      $data = array (
        'data' => 
        array (
        'register' => $status));
        echo json_encode($data);
  }
  else {
    $status = $errors;
    $data = array (
      'data' => 
      array (
      'register' => $status));
      echo json_encode($data);
  }
}

// ... 
