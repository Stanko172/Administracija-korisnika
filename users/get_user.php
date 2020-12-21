<?php


include("../model/db.php"); 
include("../model/korisnik.class.php");

$result = Korisnik::daj($_POST['id']);

echo json_encode($result);
exit();