<?php


include("../model/db.php"); 
include("../model/korisnik.class.php");

$result = Korisnik::spasi($_POST);

echo json_encode($result);
exit();