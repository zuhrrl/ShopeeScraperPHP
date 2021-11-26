<?php
// required for database
session_start();
include("config/config.php");

// initializing variables
$name = "";
$email = "";
$phone = "";
$address = "";
$kecamatan = "";
$kabupaten = "";
$provinsi = "";
$keterangan = "";
$idcard = "";
$errors = [];

// REGISTER USER
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // receive all input values from the form
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $kecamatan = $_POST["kecamatan"];
    $kabupaten = $_POST["kabupaten"];
    $provinsi = $_POST["provinsi"];
    $keterangan = $_POST["keterangan"];
    $idcard = $_POST["idcard"];

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($name)) {
        array_push($errors, "Name is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($phone)) {
        array_push($errors, "Phone is required");
    }
    if (empty($address)) {
        array_push($errors, "Address is required");
    }
    if (empty($kecamatan)) {
        array_push($errors, "Kecamatan is required");
    }
    if (empty($kabupaten)) {
        array_push($errors, "Kabupaten is required");
    }
    if (empty($provinsi)) {
        array_push($errors, "Provinsi required");
    }
    if (empty($keterangan)) {
        array_push($errors, "Keterangan is required");
    }
    if (empty($idcard)) {
        array_push($errors, "ID Card is required");
    }

    // Checking if user is in our database

    $user_check_query = "SELECT * FROM nasabah WHERE username='$username' OR email='$email' LIMIT 1";
    $result = mysqli_query($conn, $user_check_query);
    if (mysqli_num_rows($result) == 1) {
        // User is in our database
        if (count($errors) == 0) {
            $query = "UPDATE nasabah 
                  SET phone = '$phone', address_user = '$address', kecamatan = '$kecamatan', kabupaten = '$kabupaten', provinsi='$provinsi', keterangan='$keterangan', id_card='$idcard' WHERE email='$email'";
            if ($conn->query($query) === true) {
                $status = "success";
                $data = array (
                    'data' => 
                    array (
                    'record_update' => $status
                    ),
                    );
                  echo json_encode($data);
            } 
        }
        else {
           $errors_status = json_encode($errors);
           echo "Error updating record: " .$errors_status;
        }
    }
}
