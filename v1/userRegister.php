<?php
///// WEBSERVICE REST (json)

include ("../include/dboperation.php");
$dboperation = new dboperation();
$rep = $dboperation->create_user();
echo json_encode($rep);
 ?>
