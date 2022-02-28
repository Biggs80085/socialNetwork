<?php
  
  session_start();
  include('function.php');
  require_once 'cnxpdo.php';
  $idcom = connexpdo("network", "myparam");

  if(!isset($_SESSION['auth'])){
    header('Location: index.php');
    exit();
  }

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Accueil</title>
		<link rel="stylesheet" type="text/css" href="CSS/account.css">
    <?php require_once("modeT.php"); ?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="JS/connect.js"></script>
    <script src="JS/load_publi.js"></script>
  </head>
<body>
	<header>
    <?php require_once("header.php");?>
	</header>

  <?php 
    require_once("side_left.php");
    require_once("side_right.php"); 
  ?>

	<article>
    <div id="display_publi"></div>
	</article>

  <script>

    jQuery(document).ready(function($) {
      load_publi();
      setInterval(function() {
          load_publi();
      }, 5000);

      $(document).on('click', '.like', function() {
          var id = $(this).data('id');
          $.ajax({
              url: 'add_like.php',
              type: 'POST',
              data: { id: id },
              success: function(data) {
                  load_publi();
              }
          });
      });

      $(document).on('click', '.unlike', function() {
          var id = $(this).data('id');
          $.ajax({
              url: 'dislike.php',
              type: 'POST',
              data: { id: id },
              success: function(data) {
                  load_publi();
              }
          });
      });
    });

  </script>
  
</body>
</html>


	