<?php


//include("../model/db.php"); 
//include("../model/korisnik.class.php");



//Korisnik::spasi($_POST);

if ($_POST){  
    define("RACUNALO", "localhost");
    define("KORISNIK", "root");
    define("LOZINKA", "");
    define("BAZA", "ednevnik");

    $konekcija = mysqli_connect(RACUNALO, KORISNIK, LOZINKA, BAZA);   

    $ime = htmlspecialchars(mysqli_real_escape_string($konekcija, $_POST["imeKorisnika"]));
    $prezime = htmlspecialchars(mysqli_real_escape_string($konekcija, $_POST["prezimeKorisnika"]));
    $JMBG = htmlspecialchars(mysqli_real_escape_string($konekcija, $_POST["jmbgKorisnika"]));
    $email = htmlspecialchars(mysqli_real_escape_string($konekcija, $_POST["emailKorisnika"]));
    $lozinka = md5($_POST["lozinkaKorisnika"]);
    $uloga = htmlspecialchars(mysqli_real_escape_string($konekcija, $_POST["ulogaKorisnika"]));

    $upit = "INSERT INTO `korisnik`(`ime`, `prezime`, `JMBG`, `email`, `lozinka`, `uloga`) VALUES ('$ime', '$prezime', '$JMBG', '$email', '$lozinka', '$uloga')";

    echo json_encode(mysqli_query($konekcija, $upit));
    exit;
    // Make a array with the values  
}