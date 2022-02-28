<?php

  if(session_status() == PHP_SESSION_NONE){
    session_start();
  }

  if(!isset($_SESSION['auth'])){
    header('Location: index.php');
    exit();
  }
  
  require_once 'cnxpdo.php';
  $idcom = connexpdo("network", "myparam");

  /** RECUPERER LES AMIS */
  $reqfriend = $idcom->prepare("SELECT DISTINCT u.* FROM friend f, user u 
  WHERE (u.Id = f.id_request OR u.Id = f.id_receive)
  AND (id_request = :id OR id_receive = :id)
  AND state = 0");
  $reqfriend->execute(array('id' => $_SESSION['auth']['Id']));
  $friend = $reqfriend->fetchAll();
  /** FIN RECUPERER LES AMIS */


?>

<!DOCTYPE html>
<html>
  <head>
    <title>Accueil</title>
    <link rel="stylesheet" type="text/css" href="CSS/side_right2.css">
    <script src="https://code.jquery.com/jquery-1.12.3.js"   integrity="sha256-1XMpEtA4eKXNNpXcJ1pmMPs8JV+nwLdEqwiJeCQEkyc="   crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="JS/side_right.js"></script>
  </head>
<body>

  <aside class="side_bar_right">
      <?php if($_SESSION['auth']['mode']==0){ ?>
       <img src="img/person-fill.svg" alt="i" class="contactImg" width="20" height="20">
       <span class="Text-contacts"><b>Contacts</b></span>
       <input type="search" id="findfriend" PLACEHOLDER="Recherche" class="Sear_amis">
      <?php }else{ ?>
        <img src="img/person-fill.png" alt="i" class="contactImg" width="20" height="20">
        <span class="Text-contacts" style="color:white"><b>Contacts</b></span>
        <input type="search" id="findfriend" PLACEHOLDER="Recherche" class="Sear_amis" style="background:var(--test2)">
      <?php } ?>

      
      

      <div class="affiche" id="affiche">
      <?php
      foreach($friend as $friend){
        if($friend['Id'] <> $_SESSION['auth']['Id']){ ?>
          <a href="profil.php?id=<?= $friend['Id'] ?>"><div class="afficher_contacts_ami1">
            <img src="img/profil/<?= $friend['profil_photo'] ?>" width="30" height="30" class="afficher_contacts_ami_img">
            <p class="afficher_contacts_ami_name"><?= $friend['Name'] .' '. $friend['LastName'] ?></p>
          </div></a>
  <?php } 
      } ?>
      </div>

     <div id="notif"></div>

  </aside>

</body>
</html>


  