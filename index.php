<?php
session_start();
if(isset($_POST['ime'])){
  echo $_POST['ime'] + "hahaha";
}
if (!isset($_SESSION["token"])) header("Location: login.php");
include("model/db.php"); 
include("model/korisnik.class.php");

if (isset($_GET["akcija"]) && $_GET["akcija"] == "pobrisi") {
  Korisnik::pobrisi($_GET["id"]);
}

$id = $_SESSION["token"];
$upit = "SELECT * FROM korisnik WHERE ID=".$id;

$rezultat = mysqli_query($konekcija, $upit);
$prijavljeni_korisnik = mysqli_fetch_assoc($rezultat);
$naslov = "Dobrodošli na sustav " . $prijavljeni_korisnik["ime"]. " " .$prijavljeni_korisnik["prezime"];
include("static/header.php");
?>
<div class="container h-100">
    <div class="row shadow p-5">
        <div class="col-12 mb-5 d-flex justify-content-around">
          <h3 class="float-left">Administracija korisnika</h3>
          <div style="min-width: 100px;" class="d-flex justify-content-between">
            <a type="button" class="btn btn-primary" href="index.php?akcija=pobrisi&id=<?= $korisnik["ID"] ?>" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="Dodavanje"><i class="fas fa-plus"></i></a>
            <a title="Odjavite se sa sustava" data-toggle="tooltip" data-placement="top"  class="btn btn-light float-right mt-1" href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
          </div>
        </div>
        <div class="col-12">
        
          <table class="table table-striped table-hover">
            <tr>
              <th>#ID</th>
              <th>Ime</th>
              <th>Prezime</th>
              <th>Email</th>
              <th>JMBG</th>
              <th>Akcije</th>
            </tr>
            <?php
              foreach(Korisnik::dajSve() as $korisnik):
            ?>
            <tr>
              <td><?= $korisnik["ID"] ?></td>
              <td><?= $korisnik["ime"] ?></td>
              <td><?= $korisnik["prezime"] ?></td>
              <td><?= $korisnik["email"] ?></td>
              <td><?= $korisnik["JMBG"] ?></td>
              <td>
                <a class="btn btn-danger" href="index.php?akcija=pobrisi&id=<?= $korisnik["ID"] ?>"><i class="fas fa-trash"></i></a>
                <button class="btn btn-info" id="<?= $korisnik["ID"] ?>" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="Uređivanje"><i class="fas fa-edit"></i></button>
              </td>
            </tr>
            <?php endforeach ?>
          </table>
        </div>
    </div>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">New message</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form method="POST" action="users/add.php" id="addUserForm">
                <div class="form-group">
                    <label>Ime korisnika:</label>
                    <!-- Dodavanje polja za pohranu ID korisnika -->
                    <input type="hidden" id="idKorisnika" />
                    <!-- Dodavanje polja za pohranu ID korisnika -->
                    <input type="text" required class="form-control" id="imeKorisnika" placeholder="Unesite Vaše ime" />
                </div> <br />

                <div class="form-group">
                    <label>Prezime korisnika:</label>
                    <input type="text" required class="form-control" id="prezimeKorisnika" placeholder="Unesite Vaše prezime" />
                </div> <br />

                <div class="form-group">
                    <label>Jedinstveni matični broj korisnika:</label>
                    <input type="text" required class="form-control" id="jmbgKorisnika" placeholder="Unesite Vaš JMBG" />
                </div> <br />

                <div class="form-group">
                    <label>E-mail adresa korisnika:</label>
                    <input type="email" required class="form-control" id="emailKorisnika" placeholder="Unesite Vašu email adresu" />
                </div> <br />

                <div class="form-group">
                    <label>Lozinka korisnika:</label>
                    <input type="password" class="form-control" id="lozinkaKorisnika" placeholder="Unesite Vašu lozinku" />
                </div> <br />

                <div class="form-group">
                    <label>Uloga korisnika:</label>
                    <select required class="form-control" id="ulogaKorisnika">
                      <option value="nastavnik">Nastavnik</option>
                      <option value="učenik">Ucenik</option>
                      <option value="administrator">Administrator</option>
                    </select>
                </div> 
                
                <br />

                <div id="alert" class="alert alert-success" style="display: none;" role="alert">
                  Korisnik uspješno dodan!
                </div>
                <div id="alert-fail" class="alert alert-danger" style="display: none;" role="alert">
                  Dogodila se greška!
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button id="save" type="button" class="btn btn-primary">Spremi</button>
        </div>
      </div>
    </div>
  </div>

<script>
  var exampleModal = document.getElementById('exampleModal')

  exampleModal.addEventListener('show.bs.modal', function (event) {
    // Button that triggered the modal
    var button = event.relatedTarget
    // Extract info from data-bs-* attributes
    var recipient = button.getAttribute('data-bs-whatever')
    // If necessary, you could initiate an AJAX request here
    // and then do the updating in a callback.
    //
    // Update the modal's content.
    var modalTitle = exampleModal.querySelector('.modal-title')
    var modalBodyInput = exampleModal.querySelector('.modal-body input')

    modalTitle.textContent = recipient + ' korisnika';
    modalBodyInput.value = recipient

    var saveFormButton = document.getElementById('save')
    //Counter -> quick fix
    var counter = 0;
    
    if(recipient === "Dodavanje"){
      saveFormButton.addEventListener('click', addNewUser);
    }else{

      console.log(button.id)

      var userid = button.id;

      saveFormButton.addEventListener('click', editUser);

      var param = "id=" + userid;

      var xhr =new XMLHttpRequest();

      xhr.open('POST', 'users/get_user.php', true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

      xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200){
          console.log(JSON.parse(this.responseText));

          var response = JSON.parse(this.responseText);

          document.getElementById('imeKorisnika').value = response.ime;
          document.getElementById('prezimeKorisnika').value = response.prezime;
          document.getElementById('jmbgKorisnika').value = response.JMBG;
          document.getElementById('emailKorisnika').value = response.email;
          document.getElementById('lozinkaKorisnika').value = response.lozinka;
          if(response.uloga == "Administrator"){
            document.getElementById('ulogaKorisnika').selectedIndex = "2";
          }else if(response.uloga == "Ucenik"){
            document.getElementById('ulogaKorisnika').selectedIndex = "1";
          }else{
            document.getElementById('ulogaKorisnika').selectedIndex = "0";
          }
      }
    }

      xhr.send(param);

    }


  });

    function addNewUser(e){
        e.preventDefault();

        //console.log("Activated!!!");


        var ime = document.getElementById('imeKorisnika').value;
        var prezime = document.getElementById('prezimeKorisnika').value;
        var jmbg = document.getElementById('jmbgKorisnika').value;
        var email = document.getElementById('emailKorisnika').value;
        var lozinka = document.getElementById('lozinkaKorisnika').value;
        var uloga = document.getElementById('ulogaKorisnika').value;

        var param = "imeKorisnika=" + ime + "&prezimeKorisnika=" + prezime + "&jmbgKorisnika=" + jmbg + "&emailKorisnika=" + email + "&lozinkaKorisnika=" + lozinka + "&ulogaKorisnika=" + uloga;
        //console.log(param);

        var xhr =new XMLHttpRequest();

        xhr.open('POST', 'users/add.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200){
            console.log(JSON.parse(this.responseText));
            if(JSON.parse(this.responseText).result == true){
              console.log("reload")

              document.getElementById('alert-fail').style.display = "none";
              document.getElementById('alert').style.display = "block";
              
              window.setTimeout(function alert(){
                location.reload();
              }, 2000);
            }else{
              document.getElementById('alert-fail').style.display = "block";
            }
        }
      }

        xhr.send(param);
      }

      function editUser(){
        console.log("EditUser button!");

      }

</script>

</div>
<?php
include("static/header.php");
?>