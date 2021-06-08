<?php
include("../include/dboperation.php");
$dboperation = new dboperation();
$rep = $dboperation -> removeUserByUsername($_POST['user_username']);

$reponse;

//-2 : SERVER REQUEST METHOD IS NOT POST
//-1 : ERROR USER DOESNT EXISTS
//0 : ERROR USERNAME IS UNSEST
//1 : Success
//2 : ERROR SYSTEM EXCEPTION

switch($rep){
  case -2:
    $reponse = array(
      'status' => false,
      'error' => true,
      'message' => "SERVER REQUEST METHOD IS NOT POST"
    );
    break;
  case -1:
    $reponse = array(
      'status' => false,
      'error' => true,
      'message' => "ERROR USER DOES NOT EXIST"
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
      'message' => "USER DELETED"
    );
    break;
  case 2:
    $reponse = array(
      'status' => false,
      'error' => true,
      'message' => "EXCEPTION ERROR SYSTEM. CHECK userRemove.php"
    );
    break;
}

echo json_encode($reponse);
?>
