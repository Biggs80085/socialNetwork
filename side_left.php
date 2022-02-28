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

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Accueil</title>
    <link rel="stylesheet" type="text/css" href="CSS/side_left.css">
    <?php require_once("modeT.php"); ?>
  </head>
<body>

  <div class="side_bar">

    <img src="img/profil/<?= $_SESSION['auth']['profil_photo'] ?>" class="profile_image">
    <?php if($_SESSION['auth']['mode'] == 0){ ?> 
      <h4><?=$_SESSION['auth']['Name'] ." ". $_SESSION['auth']['LastName']?></h4>
    <?php }if($_SESSION['auth']['mode'] == 1){ ?>
      <h4 style="color:white"><?=$_SESSION['auth']['Name'] ." ". $_SESSION['auth']['LastName']?></h4>
    <?php }?>
    
    <a class="active" href="account.php">
    <?php if($_SESSION['auth']['mode'] == 0){ ?> 
      <img src="img/house-door-fill.svg" class="img_icon" >
      <span>Accueil</span>
    <?php }if($_SESSION['auth']['mode'] == 1){ ?>
      <img src="img/house-door-fill.png" class="img_icon2">
      <span style="color:white">Accueil</span>
    <?php }?>
      </a>
    <a class="active1" href="profil.php?id=<?= $_SESSION['auth']['Id'] ?>">
    <?php if($_SESSION['auth']['mode'] == 0){ ?> 
      <img src="img/file-earmark-person.svg" class="img_icon" >
      <span>Profil</span>
    <?php }if($_SESSION['auth']['mode'] == 1){ ?>
      <img src="img/file-earmark-person.png" class="img_icon2">
      <span style="color:white">Profil</span>
    <?php }?>
    </a>
    <a class="active2" href="my_poste.php">
    <?php if($_SESSION['auth']['mode'] == 0){ ?> 
      <img src="img/chat-quote-fill.svg" class="img_icon" >
      <span>Mes postes</span>
    <?php }if($_SESSION['auth']['mode'] == 1){ ?>
      <img src="img/chat-quote-fill.png" class="img_icon2">
      <span style="color:white">Mes postes</span>
    <?php }?>
      </a>
    <a class="active3" href="setting.php">
    <?php if($_SESSION['auth']['mode'] == 0){ ?> 
      <img src="img/gear-fill.svg" class="img_icon" >
      <span>Paramètres</span>
    <?php }if($_SESSION['auth']['mode'] == 1){ ?>
      <img src="img/gear-fill.png" class="img_icon2">
      <span style="color:white">Paramètres</span>
    <?php }?>
      </a> 
  </div>
</body>
</html>


  