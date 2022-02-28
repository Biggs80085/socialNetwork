<?php
  
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  if(!isset($_SESSION['auth'])){
    header('Location: index.php');
    exit();
  }
  
  require_once 'cnxpdo.php';
  $idcom = connexpdo("network", "myparam");

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Accueil</title>
    <link rel="stylesheet" type="text/css" href="CSS/header5.css">
    <?php require_once("modeT.php"); ?>
    <script src="https://code.jquery.com/jquery-1.12.3.js"   integrity="sha256-1XMpEtA4eKXNNpXcJ1pmMPs8JV+nwLdEqwiJeCQEkyc="   crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="JS/connect.js"></script>
    <script src="JS/load_publi.js"></script>
    <script src="JS/header.js"></script>
    <script src="JS/pub_pop.js"></script>
    <script src="JS/notif_msg.js"></script>
  </head>
<body>
  
    <a href="account.php" class="logo">
      <img src="img/ISMAIL_msn_LOGO.png">
    </a>

    <div>
      
      <?php if($_SESSION['auth']['mode']==0){ ?>
        <input class="search" type="text" name="search_bar" id="search" placeholder="Rechercher sur MySocialNetwork">
        <img src="img/loupe.svg" width="15" height="15" style="position:absolute;left:33.5%;">
      <?php }else{ ?>
        <input class="search" type="text" name="search_bar" id="search" placeholder="Rechercher sur MySocialNetwork" style="color:white">
        <img src="img/loupe.png" width="15" height="15" style="position:absolute;left:33.5%;">
      <?php } ?>
       <div class="defilement_search">
            <div id="resultat"></div>
        </div>
    </div>

    <?php if($_SESSION['auth']['mode']==0){ ?>
      <select class="typeSearch" id="type">
        <option value="1">Utilisateur</option>
        <option value="2">Publication</option>
      </select>
      <?php }else{ ?>
        <select class="typeSearch" id="type" style="color:white">
          <option value="1">Utilisateur</option>
          <option value="2">Publication</option>
        </select>
      <?php } ?>
    
    <div class="right_nav">
      <a href="profil.php?id=<?= $_SESSION['auth']['Id']?>"><img src="img/profil/<?= $_SESSION['auth']['profil_photo'] ?>" height="40" width="40" class="right_profil">
      <p class="right_profil_nom"><?= $_SESSION["auth"]["LastName"] ?></p>
      <?php if($_SESSION['auth']['mode']==0){ ?>
        <p class="right_profil_nom" style="color:black"><?= $_SESSION["auth"]["LastName"] ?></p>
      <?php }else{ ?>
        <p class="right_profil_nom" style="color:white"><?= $_SESSION["auth"]["LastName"] ?></p>
      <?php } ?>
    </a>

    <div id="notif_msg"></div>
       
        <a href="logout.php" class="right_logout">
          <img src="img/x-circle-fill.svg" height="30" width="30" >
          <?php if($_SESSION['auth']['mode']==0){ ?> 
            <img src="img/x-circle-fill.svg" height="30" width="30" >
          <?php }if($_SESSION['auth']['mode']==1){ ?>
            <img src="img/x-circle-fill.png" height="30" width="30" >
          <?php }?>
        </a>
      
    
    <button id="btn" class="head_publier">
      <img src="img/plus.svg" width="30" height="30">
        <?php if($_SESSION['auth']['mode']==0){ ?> 
          <img src="img/plus.svg" width="30" height="30">
        <?php }if($_SESSION['auth']['mode']==1){ ?>
          <img src="img/plus.png" width="30" height="30">
        <?php }?>
    </button>

    <div class="dropdown">
     
          <button onclick="myFunction()" 
          <?php if($_SESSION['auth']['mode']==0){ ?>
            class="dropbtn" style="background-image:url('img/bell-fill.svg')"
          <?php }if($_SESSION['auth']['mode']==1){ ?>
            class="dropbtn" style="background-image:url('img/bell-fill.png')"
          <?php } ?>
          ></button>
        <?php if($_SESSION['auth']['mode']==0){ ?> 
          <div id="myDropdown" class="dropdown-content">
        <?php }if($_SESSION['auth']['mode']==1){ ?>
          <div id="myDropdown" class="dropdown-content" style="color:white">
        <?php }?>
          
            <div id="menu"></div>
          </div>
    </div>
    </div>

     <!-- pop up-->
        <div id="publier" class="publier">
        <form id="publi_form" method="POST" enctype="multipart/form-data">
          <div class="publier_designe">
            <?php if($_SESSION['auth']['mode']==0){ ?>
              <div class="publier_designe_cadre" style="background: #f5f5ef;">
            <?php }else{ ?>
              <div class="publier_designe_cadre" style="background: var(--color1);">
            <?php }?>

              <div id="typebtn" class="publier_designe_type" ><span>...</span></div>
              <div id="typeaffiche" class="publier_designe_type_bloc">
                <p>Veuillez choisir un mode pour votre publication:</p>

                <input type="radio" class="publier_designe_type_publique" name="confi" id="publique" value="Publique">
                <?php if($_SESSION['auth']['mode']==0){ ?>
                  <label for="publique">Publique<span> (par defaut)</span></label>
                <?php }else{ ?>
                  <label for="publique" style="color:white">Publique<span> (par defaut)</span></label>
                <?php }?>


                <input type="radio" class="publier_designe_type_amis" name="confi" id="amis" value="Amis">
              
                <?php if($_SESSION['auth']['mode']==0){ ?>
                  <label for="amis">Amis</label>
                <?php }else{ ?>
                  <label for="amis" style="color:white">Amis</label>
                <?php }?>
         

                <input type="radio" class="publier_designe_type_prive" name="confi" id="prive" value="Prive">
                
                <?php if($_SESSION['auth']['mode']==0){ ?>
                  <label for="prive">Prive</label>
                <?php }else{ ?>
                  <label for="prive" style="color:white">Prive</label>
                <?php }?>

              </div>

              <img src="img/profil/<?= $_SESSION['auth']['profil_photo'] ?>" width="40" height="40" class="publier_designe_img">
              <p class="publier_designe_name"><?=$_SESSION['auth']['Name'] ." ". $_SESSION['auth']['LastName']?></p>
                <?php if($_SESSION['auth']['mode']==0){ ?>
                  <textarea id="whatsup" name="whatsup" placeholder="Ecrive Quelque chose..."></textarea>
                <?php }else{ ?>
                  <textarea id="whatsup" name="whatsup" placeholder="Ecrive Quelque chose..." style="color:white"></textarea>
                <?php }?>
              

            </div>
            <input type="file" id="addimg" name="addimg" class="publier_designe_loadImg"/>
            <label for="addimg"></label>
            <input type="submit" name="submit" id="submit" class="publier_designe_envoie" value="PUBLIER"/>
          </div>
          </form>
          
        </div>
      </div>

 
      <!-- fin pop up-->



<script>

$(document).ready(function(){

  $('#publi_form').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "add_publi.php",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#publi_form')[0].reset();
                publication.style.display = "none";
                load_publi();
            }
        });
    });

  notif_msg();
  setInterval(function(){
    notif_msg();
  },5000);

  
});

var publication = document.getElementById("publier");
var btnPublie = document.getElementById("btn");
var btntype = document.getElementById("typebtn");
var type = document.getElementById("typeaffiche");
var profil = document.getElementById("monProfil");


btnPublie.onclick = function(){
    publication.style.display = "block";
}

btntype.onclick = function(){
    type.style.display="block";
}

</script>

</body>
</html>


  