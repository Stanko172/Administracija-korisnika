<?php


include("../model/db.php"); 
include("../model/korisnik.class.php");

$result = Korisnik::dodaj($_POST);

$result_obj = array("result" => $result);
echo json_encode($result_obj);
exit();