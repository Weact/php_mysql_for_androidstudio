<?php
include("../include/dboperation.php");
$dboperation = new dboperation();
$rep = $dboperation -> updateUsername();

$reponse;

// -3 : SERVER REQUEST METHOD IS NOT POST
// -2 : ERROR USER DOESNT EXISTS
// -1 : ERROR NONE OF THE REQUIRED FIELDS ARE SET OR FILLED
// 0 : ERROR USERNAME IS UNSEST
// 1 : Success
// 2 : ERROR SYSTEM EXCEPTION

switch($rep){
  case -3:
    $reponse = array(
      'status' => false,
      'error' => true,
      'message' => "SERVER REQUEST METHOD IS NOT POST"
    );
    break;
  case -2:
    $reponse = array(
      'status' => false,
      'error' => true,
      'message' => "ERROR USER DOES NOT EXIST"
    );
    break;
  case -1:
    $reponse = array(
      'status' => false,
      'error' => true,
      'message' => "NONE OF THE REQUIRED FIELDS ARE SET OR FILLED"
    );
    break;
  case 0:
    $reponse = array(
      'status' => false,
      'error' => true,
      'message' => "ERROR USERNAME POST VARIABLE IS UNSET"
    );
    break;
  case 1:
    $reponse = array(
      'status' => true,
      'error' => false,
      'message' => "USER UPDATED"
    );
    break;
  case 2:
    $reponse = array(
      'status' => false,
      'error' => true,
      'message' => "EXCEPTION ERROR SYSTEM. CHECK userUpdate.php"
    );
    break;
}

echo json_encode($reponse);
?>
