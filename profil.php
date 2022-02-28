<?php

session_start();

if (!isset($_SESSION['auth'])) {
  header('Location: index.php');
  exit();
}

require('function.php');
require_once 'cnxpdo.php';
$idcom = connexpdo("network", "myparam");

$userid = (int) htmlentities(trim($_GET['id']));
if (empty($userid)) {
  header('Location: account.php');
  exit();
}

$reqsearch = $idcom->prepare("SELECT * FROM user WHERE Id = ?");
$reqsearch->execute(array($userid));
$user = $reqsearch->fetch();

/** SYSTEME DAMIS GESTION DANS LA BASE DE DONNEES */
if (!empty($_POST)) {
  /** AJOUTER UN USER */
  if (isset($_POST['add_user'])) {

    $reqadd = $idcom->prepare("INSERT INTO friend (id_request, id_receive, state) VALUES (?, ?, ?)");
    $reqadd->execute(array($_SESSION['auth']['Id'], $user['Id'], 1));
    $subject = "Invitation";
    $link = "profil.php?id=" . $_SESSION['auth']['Id'];
    $reqaddnotif = $idcom->prepare("INSERT INTO notification (id_receive, id_send, subject, link, date_notif) VALUES (?, ?, ?, ?, ?)");
    $reqaddnotif->execute(array($user['Id'], $_SESSION['auth']['Id'], $subject, $link, date('Y-m-d H:i:s')));

    header('Location: profil.php?id=' . $user['Id']);
    exit();
  }

  /** SUPPRIMER UN USER */
  if (isset($_POST['delete_user'])) {
    $req = $idcom->prepare("DELETE FROM friend 
            WHERE (id_request = :id1 AND id_receive = :id2) 
            OR id_receive = :id1 AND id_request = :id2");
    $req->execute(array(
      'id1' => $user['Id'],
      'id2' => $_SESSION['auth']['Id']
    ));
    header('Location: profil.php?id=' . $user['Id']);
    exit();
  }

  /** BLOQUER UN USER */
  if (isset($_POST['block_user'])) {
    $idcom->prepare("UPDATE friend 
			SET id_blocker = " . $_SESSION['auth']['Id'] . "
			WHERE (id_request = :id AND id_receive = :id1)
			OR (id_request = :id1 AND id_receive = :id)")->execute(['id' => $user['Id'], 'id1' => $_SESSION['auth']['Id']]);
    header('Location: profil.php?id=' . $user['Id']);
    exit();
  }

  /** DEBLOQUER UN USER */
  if (isset($_POST['deblock_user'])) {
    $idcom->prepare("UPDATE friend 
			SET id_blocker = NULL
			WHERE (id_request = :id AND id_receive = :id1)
			OR (id_request = :id1 AND id_receive = :id)")->execute(['id' => $user['Id'], 'id1' => $_SESSION['auth']['Id']]);
    header('Location: profil.php?id=' . $user['Id']);
    exit();
  }

  /** ACCEPTER UNE DEMANDE DUN USER */
  if (isset($_POST['accepte'])) {
    $idcom->prepare("UPDATE friend SET state = 0 WHERE id_request = ?")->execute([$user['Id']]);
    header('Location: profil.php?id=' . $user['Id']);
    exit();
  }

  /** REFUSER UNE DEMANDE DUN USER */
  if (isset($_POST['decline'])) {
    $req = $idcom->prepare("DELETE FROM friend 
            WHERE id_request = :id1 AND id_receive = :id2");
    $req->execute(array(
      'id1' => $user['Id'],
      'id2' => $_SESSION['auth']['Id']
    ));
    header('Location: profil.php?id=' . $user['Id']);
    exit();
  }
}

/** REQUETE POUR GERER LES BUTTONS */
if (isset($_SESSION['auth'])) {
  $reqrequest = $idcom->prepare("SELECT u.*, f.id_request, f.id_receive, f.state, f.id_blocker
        FROM user u
        RIGHT JOIN friend f ON (id_receive = u.Id AND id_request = :id2)
        OR (id_request = u.Id AND id_receive = :id2)
        wHERE u.Id = :id1");
  $reqrequest->execute(
    array(
      'id1' => $user['Id'],
      'id2' => $_SESSION['auth']['Id']
    )
  );
} else {
  $reqrequest = $idcom->query("SELECT * FROM user WHERE Id =" . $user['Id']);
}
$request = $reqrequest->fetch();

/** CHANGER PHOTO DE PROFIL ET DE COUVERTURE */

if (isset($_POST['change_profil'])) {

  if (!empty($_FILES['profil']['name'])) {

    $maxWeigth = 2000000;
    if ($_FILES['profil']['size'] < $maxWeigth) {
      $fileExt = explode('.', $_FILES['profil']['name']);
      $fileActualExt = strtolower(end($fileExt));
      $validExtension = array('jpg', 'jpeg', 'gif', 'png');

      if (in_array($fileActualExt, $validExtension)) {
        $fileNameNew = uniqid('', true) . "." . $fileActualExt;
        $fileDestintion = 'img/profil/' . $fileNameNew;

        move_uploaded_file($_FILES['profil']['tmp_name'], $fileDestintion);

        $add_date = date("Y-m-d H:i:s");
        $updatePhoto = $idcom->prepare("UPDATE user SET profil_photo = ? WHERE Id = ?");
        $updatePhoto->execute(array($fileNameNew, $_SESSION['auth']['Id']));
        $_SESSION['auth']['profil_photo'] = $fileNameNew;

        $insertPhoto = $idcom->prepare("INSERT INTO profil_photo (id_adder, photo, add_date) VALUES (?, ?, ?)");
        $insertPhoto->execute(array($_SESSION['auth']['Id'], $fileNameNew, $add_date));
      }
    }
  }

  if (!empty($_FILES['couverture']['name'])) {
    $maxWeigth = 2000000;
    if ($_FILES['couverture']['size'] < $maxWeigth) {
      $fileExt = explode('.', $_FILES['couverture']['name']);
      $fileActualExt = strtolower(end($fileExt));
      $validExtension = array('jpg', 'jpeg', 'gif', 'png');

      if (in_array($fileActualExt, $validExtension)) {
        $fileNameNew = uniqid('', true) . "." . $fileActualExt;
        $fileDestintion = 'img/profil/' . $fileNameNew;

        move_uploaded_file($_FILES['couverture']['tmp_name'], $fileDestintion);

        $add_date = date("Y-m-d H:i:s");
        $updatePhoto = $idcom->prepare("UPDATE user SET cover_photo = ? WHERE Id = ?");
        $updatePhoto->execute(array($fileNameNew, $_SESSION['auth']['Id']));
        $_SESSION['auth']['cover_photo'] = $fileNameNew;

        $insertPhoto = $idcom->prepare("INSERT INTO cover_photo (id_adder, photo, add_date) VALUES (?, ?, ?)");
        $insertPhoto->execute(array($_SESSION['auth']['Id'], $fileNameNew, $add_date));
      }
    }
  }

  header('Location: profil.php?id=' . $_SESSION['auth']['Id']);
  exit();
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Profile</title>
  <link rel="stylesheet" type="text/css" href="CSS/profil3.css">
  <?php require_once("modeT.php"); ?>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="JS/connect.js"></script>
</head>

<body>
  <header>
    <?php require_once("header.php"); ?>
  </header>

  <?php
  require_once("side_left.php");
  require_once("side_right.php");
  ?>

  <article>

    <div class="publication">

      <img src="img/profil/<?= $user['cover_photo'] ?>" width="822" height="300" class="fondProfil">
      <img src="img/profil/<?= $user['profil_photo'] ?>" width="90" height="90" class="imgProfil">

      <?php if ($_SESSION['auth']['mode'] == 0) { ?>
        <p class="nameProfil"><b><?= $user['Name'] . " " . $user['LastName'] ?></b></p>
      <?php } else { ?>
        <p class="nameProfil" style="color:white"><b><?= $user['Name'] . " " . $user['LastName'] ?></b></p>
      <?php } ?>

      <?php if (isset($_SESSION['auth']) && ($userid == $_SESSION['auth']['Id'])) { ?>

        <button class="modifier-button" id="myProfil"><b>Modifier mon profil</b></button>
        <div id="monProfil" class="modifier_photo">
          <div class="div_modifie">
            <h1>Modifier votre photo de profil et/ou votre photo de couverture</h1>
            <form method="POST" enctype="multipart/form-data">
              <input type="file" name="profil" id="profil" class="photoProfilC" />
              <label for="profil"></label>
              <input type="file" name="couverture" id="couverture" class="photoCouvC" />
              <label for="couverture"></label>
              <input type="submit" name="change_profil" class="changer_photo" value="Changer">
            </form>
          </div>
        </div>

      <?php } elseif (isset($_SESSION['auth'])) { ?>

        <form method="POST">

          <?php /** GERER LE FILE DATTENTE*/
          if (!isset($request['state'])) { ?>
            <button class="ajoute-button" name="add_user"><b>Ajouter</b></button>
          <?php } elseif (isset($request['state']) && $request['state'] == 1 && $request['id_request'] == $_SESSION['auth']['Id']) { ?>
            <button class="supprimer-button" name="delete_user"><b>Annuler la demande</b></button>
          <?php }

          /** SUPPRIMER OU BLOQUER */
          if (isset($request['state']) &&  $request['state'] == 0 && $request['id_blocker'] == NULL) { ?>
            <button class="supprimer-button" name="delete_user"><b>Supprimer</b></button>
            <button class="bloque-button" name="block_user"><b>Bloquer</b></button>
          <?php }

          /** SUPPRIMER OU DEBLOQUER  */
          elseif (isset($request['state']) && $request['state'] == 0 && $request['id_blocker'] != NULL && $request['id_blocker'] == $_SESSION['auth']['Id']) { ?>
            <button class="supprimer-button" name="delete_user"><b>Supprimer</b></button>
            <button class="bloque-button" name="deblock_user"><b>Debloque</b></button>
          <?php } elseif (isset($request['state']) && $request['state'] == 0 && $request['id_blocker'] != NULL && $request['id_blocker'] != $_SESSION['auth']['Id']) { ?>
            <button class="supprimer-button" name="delete_user"><b>Supprimer</b></button>
            <div>
              <?php if($_SESSION['auth']['mode']==0){ ?>
              <p style="position:absolute;bottom:80px;left:270px;">Vous avez été bloqué par cet utilisateur</p>
              <?php }else{ ?>
                <p style="position:absolute;bottom:80px;left:270px;color:white">Vous avez été bloqué par cet utilisateur</p>
              <?php } ?>
            </div>
          <?php }

          /** ACCEPTER OU REFUSER */
          if (isset($request['state']) && $request['state'] == 1 && $request['id_receive'] == $_SESSION['auth']['Id']) { ?>
            <button class="supprimer-button" name="accepte"><b>Accepter</b></button>
            <button class="bloque-button" name="decline"><b>Refuser</b></button>
          <?php } ?>

        </form>

      <?php } ?>

      <div class="menuProfil">
        <button name="post" id="post" class="menuProfil-posts" data-id="<?= $userid ?>"><b>Posts</b></button>
        <button name="propos" id="propos" class="menuProfil-apropos" data-id="<?= $userid ?>"><b>A propos</b></button>
        <button name="amis" id="friend" class="menuProfil-amis" data-id="<?= $userid ?>"><b>Amis</b></button>
        <button name="photo" id="photo" class="menuProfil-photos" data-id="<?= $userid ?>"><b>Photos</b></button>
      </div>

      <div id="display_post"></div>
      <div id="display_propos"></div>
      <div id="display_amis"></div>
      <div id="display_photo"></div>

  </article>

</body>

<script>
  $(document).ready(function() {

    $(document).on('click', '#post', function() {
      var id = $(this).data('id');
      $.ajax({
        url: 'post_profil.php',
        type: 'POST',
        data: {id: id},
        success: function(data) {
          $('#display_propos').hide();
          $('#display_amis').html(data).hide();
          $('#display_photo').html(data).hide();
          $('#display_post').html(data).show();
        }
      });
    });

    $(document).on('click', '#propos', function() {
      var id = $(this).data('id');
      $.ajax({
        url: 'propos_profil.php',
        type: 'POST',
        data: {id: id},
        success: function(data) {
          $('#display_post').hide();
          $('#display_amis').html(data).hide();
          $('#display_photo').html(data).hide();
          $('#display_propos').html(data).show();
        }
      });
    });

    $(document).on('click', '#friend', function() {
      var id = $(this).data('id');
      $.ajax({
        url: 'friend_profil.php',
        type: 'POST',
        data: {id: id},
        success: function(data) {
          $('#display_propos').hide();
          $('#display_post').hide();
          $('#display_photo').html(data).hide();
          $('#display_amis').html(data).show();
        }
      });
    });

    $(document).on('click', '#photo', function() {
      var id = $(this).data('id');
      $.ajax({
        url: 'photo_profil.php',
        type: 'POST',
        data: {id: id},
        success: function(data) {
          $('#display_propos').hide();
          $('#display_post').hide();
          $('#display_amis').html(data).hide();
          $('#display_photo').html(data).show();
        }
      });
    });


    function load_mypubli() {
      var id = $('#post').data('id');
      $.ajax({
        url: "post_profil.php",
        type: "POST",
        data: {id: id},
        success: function(data) {
          $('#display_post').html(data);
        }
      });
    }


    $(document).on('click', '.like', function() {
      var id = $(this).data('id');
      $.ajax({
        url: 'add_like.php',
        type: 'POST',
        data: {id: id},
        success: function(data) {
          load_mypubli();
        }
      });
    });

    $(document).on('click', '.unlike', function() {
      var id = $(this).data('id');
      $.ajax({
        url: 'dislike.php',
        type: 'POST',
        data: {id: id},
        success: function(data) {
          load_mypubli();
        }
      });
    });
  });

</script>

<script>
  var profil = document.getElementById("monProfil");
  var btnProfil = document.getElementById("myProfil");

  btnProfil.onclick = function() {
    profil.style.display = "block";
  }
</script>

</html>