<?php
	session_start();
	require_once 'cnxpdo.php';
  	$idcom = connexpdo("network", "myparam");

	if(!isset($_SESSION['auth'])){
		header('Location: index.php');
		exit();
	}

	if(isset($_POST['light'])){
		$idcom->query("UPDATE user SET mode = 0 WHERE Id = ".$_SESSION['auth']['Id']."");
		$_SESSION['auth']['mode'] = 0;
		header('Location: setting.php');
		exit();
	}elseif(isset($_POST['dark'])){
		$idcom->query("UPDATE user SET mode = 1 WHERE Id = ".$_SESSION['auth']['Id']."");
		$_SESSION['auth']['mode'] = 1;
		header('Location: setting.php');
		exit();
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Paramétre</title>
		<link rel="stylesheet" type="text/css" href="CSS/setting.css">
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
		<script src="JS/connect.js"></script>
	</head>
<body>
	<header>
		<?php 
   require_once("header.php");
   

   ?>

	</header>

	 <?php 
   require_once("side_left.php");
   require_once("side_right.php"); 
   ?>


	<article>

		<div class="publication">
			<p class="title-parametre">Paramètres</p>

			<DIV class="general">
				<img src="img/person-lines-fill.png" width="80" height="80" class="imgG">
				<h1>Général</h1>
				<?php if($_SESSION['auth']['mode']==0){ ?>
					<p>Modifiez votre nom, prénom<br>
						et d'autre informations que vous citerez...</p>
				<?php }else{ ?>
					<p style="color:white">Modifiez votre nom, prénom<br>
						et d'autre informations que vous citerez...</p>
				<?php } ?>

					<a href="setting_ge.php" class="acceder">Accéder</a>
			</DIV>
			<DIV class="confidentialite">
				<img src="img/shield-lock.png" width="80" height="80" class="imgG">
				<h1>Confidentialité</h1>
				
				<?php if($_SESSION['auth']['mode']==0){ ?>
					<p>Modifiez qui peut voir<br>
						votre contenu, changer votre mot de passe</p>
				<?php }else{ ?>
					<p style="color:white">Modifiez qui peut voir<br>
					votre contenu, changer votre mot de passe</p>
				<?php } ?>
					<a href="setting_mot.php" class="acceder">Accéder</a>

			</DIV>
			<DIV class="theme">
				<img src="img/palette.png" width="80" height="80" class="imgG">
				<h1>Thème</h1>
				
				<?php if($_SESSION['auth']['mode']==0){ ?>
					<p>Veuillez choisir votre théme préfére :</p>
				<?php }else{ ?>
					<p style="color:white">Veuillez choisir votre théme préfére :</p>
				<?php } ?>
				<form method="POST">
					<button name="light" class="light_mode" <?php if($_SESSION['auth']['mode']==0){ echo "style='background:#00cc00;color:white;'";}?>>LIGHT MODE</button>
					<button name="dark" class="dark_mode" <?php if($_SESSION['auth']['mode']==1){ echo "style='background:#00cc00;color:white'";}?>>DARK MODE</button>
				</form>
			</DIV>
 		</div>

      
    
	</article>

</body>
</html>


	