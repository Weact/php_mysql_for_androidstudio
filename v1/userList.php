<?php
include("../include/dboperation.php");
$dboperation = new dboperation();
$req = $dboperation->getUserByUsername($_POST['user_username']);

$response;

if($req == 2 || $req['username'] == null){ //!= ERROR
  $response = array(
    'status' => false,
    'error' => true,
    'message' => "Invalid Credentials, cannot find user"
  );
}else{
  $response = array(
    'status' => true,
    'error' => false,
    'message' => "User found",
    'username' => $req['username'],
    'email' => $req['email'],
    'password' => $req['password'],
    'birthdate' => $req['dateNaissance'],
    'localite' => $req['Localite']
  );
}

echo json_encode($response);


?>
