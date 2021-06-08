<?php
///// WEBSERVICE REST (json)

include("../include/dboperation.php");
$dboperation = new dboperation();
$rep = $dboperation -> checkLoginCredentialsAndSubmit();
echo json_encode($rep);
?>
