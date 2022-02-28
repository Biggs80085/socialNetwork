<?php	
	include("function.php");
	session_start();
	require_once 'cnxpdo.php';
  	$idcom = connexpdo("network", "myparam");
	if(!isset($_SESSION['auth'])){
		header('Location: index.php');
		exit();
	}

	if(isset($_POST['valide_nom'])){
    	if(!empty($_POST['nom']) && regExpName($_POST['nom'])){
			
			$requpdate = $idcom->prepare("UPDATE user SET Name = ? WHERE Id = ?")->execute(array(strtoupper($_POST['nom']),$_SESSION["auth"]["Id"]));
			$_SESSION["auth"]["Name"] = strtoupper($_POST['nom']);
			header('Location: setting_ge.php');
			exit();
			
    	}else{
			$error = "Votre Nom n'est pas valide
			Il dois contenir que des lettre A-Z a-z";
		}
    }

    if(isset($_POST['valide_prenom'])){
    	if(!empty($_POST['prenom']) && regExpName($_POST['prenom'])){
	      	$requpdate = $idcom->prepare("UPDATE user SET LastName = ? WHERE Id = ?")->execute(array(ucfirst(strtolower($_POST['prenom'])),$_SESSION["auth"]["Id"]));
	      	$_SESSION["auth"]["LastName"] = ucfirst(strtolower($_POST['prenom']));
	       	header('Location: setting_ge.php');
      		exit();
    	}else{
			$error = "Votre Prenom n'est pas valide
			Il dois contenir que des lettre A-Z a-z";
		}
    }

    if(isset($_POST['valide_pays'])){
		$requpdate = $idcom->prepare("UPDATE user SET Country = ? WHERE Id = ?")->execute(array($_POST['pays'],$_SESSION["auth"]["Id"]));
		$_SESSION["auth"]["Country"] = $_POST['pays'];
		header('Location: setting_ge.php');
		exit();
    }
      
	if(isset($_POST['valide_phone'])){
		if(!empty($_POST['phone']) && preg_match('/[0-9]/', $_POST['phone']) && strlen($_POST['phone']) == 10){
			$requpdate = $idcom->prepare("UPDATE user SET Phone = ? WHERE Id = ?")->execute(array($_POST['phone'],$_SESSION["auth"]["Id"]));
			$_SESSION["auth"]["Phone"] = $_POST['phone'];
			header('Location: setting_ge.php');
			exit();
		}else{
			$error = "Votre numero de téléphone dois avoire 10 chiffre";
		}
    }
      
	if(isset($_POST['valide_situation'])){
		$requpdate = $idcom->prepare("UPDATE user SET Situation = ? WHERE Id = ?")->execute(array($_POST['situation'],$_SESSION["auth"]["Id"]));
		$_SESSION["auth"]["Situation"] = $_POST['situation'];
		header('Location: setting_ge.php');
		exit();
    }

	$reqcountry = $idcom->query("SELECT * FROM country");
	$pays = $reqcountry->fetchAll();

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Général</title>
		<link rel="stylesheet" type="text/css" href="CSS/setting_ge3.css">
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
			<?php if($_SESSION['auth']['mode']==0){ ?>
		  		<p class="path">Paramètres &nbsp;> &nbsp;&nbsp;<span>Général</span></p>
			<?php }else{ ?>
				<p class="path" style="color:white">Paramètres &nbsp;> &nbsp;&nbsp;<span>Général</span></p>
			<?php } ?>

		  <h1>Général</h1>
		  <a href="setting.php">&lt;&lt; Retour</a>
		  <div class="modifier_para">
		  	
			<?php if($_SESSION['auth']['mode']==0){ ?>
				<p class="modifier_para_title">Information personelles</p>
			<?php }else{ ?>
				<p class="modifier_para_title" style="color:white">Information personelles</p>
			<?php } ?>
		  		<form method="POST">
					<div class="modifier_para_nom_type1" id="nom_type1">
						<div class="modifier_para_nom1">
							<label>Nom : </label>
							<input type="text" name="nom" value="<?= $_SESSION['auth']['Name'] ?>">
						</div>
						<button class="valide_nom" name="valide_nom" id="valide_nom"><img src="img/checkL_white.png" width="10" height="10" style="position:absolute;top:5px;right:5px;"></button>
						<button class="annule_nom" id="annule_nom"><b style="position:absolute;top:2px;right:4.5px;color:var(--vert);">X</b></button>
					</div>
			  	</form>
			  
			  	<div class="modifier_para_nom_type2" id="nom_type2">
				  	<div class="modifier_para_nom2">
				  		<label>Nom : </label>
				  		<p><?= $_SESSION['auth']['Name'] ?></p>
				  	</div>
					<?php if($_SESSION['auth']['mode']==0){ ?>
						<div class="modifier_para_nom2">
							<label>Nom : </label>
							<p><?= $_SESSION['auth']['Name'] ?></p>
				  		</div>
					<?php }else{ ?>
						<div class="modifier_para_nom2" style='color:white'>
							<label>Nom : </label>
							<p><?= $_SESSION['auth']['Name'] ?></p>
				  		</div>
					<?php } ?>
				  	<button class="modifier_nom" id="modifier_nom"><img src="img/pencil-square.png" height="10" width="10"><p>Modifier</p></button>	
					  
						
			  	</div>
				  <?php if(isset($_POST["valide_nom"])){if(isset($error)){ echo "<p style='position:absolute;top:11%; left:30%; font-size:12px; color:red'>$error</p>";}} ?>

			  	<form method="POST">
			  	<div class="modifier_para_prenom_type1" id="prenom_type1">
			  	  
				  	<div class="modifier_para_prenom1">
				  		<label>Prenom : </label>
				  		<input type="text" name="prenom" value="<?= $_SESSION['auth']['LastName'] ?>">
				  	</div>
				
				  	<button class="valide_prenom" name="valide_prenom" id="valide_prenom"><img src="img/checkL_white.png" width="10" height="10" style="position:absolute;top:5px;right:5px;"></button>
				  	<button class="annule_prenom" id="annule_prenom"><b style="position:absolute;top:2px;right:4.5px;color:var(--vert);">X</b></button>
				
			  	</div>
			    </form>

			  	<div class="modifier_para_prenom_type2" id="prenom_type2">
				  	
					<?php if($_SESSION['auth']['mode']==0){ ?>
						<div class="modifier_para_prenom2">
							<label>Prenom : </label>
							<p><?= $_SESSION['auth']['LastName'] ?></p>
				  		</div>
					<?php }else{ ?>
						<div class="modifier_para_prenom2" style='color:white'>
							<label>Prenom : </label>
							<p><?= $_SESSION['auth']['LastName'] ?></p>
				  		</div>
					<?php } ?>
				
				  	<button class="modifier_prenom" id="modifier_prenom"><img src="img/pencil-square.png" height="10" width="10"><p>Modifier</p></button>
			  	</div>
				  <?php if(isset($_POST["valide_prenom"])){if(isset($error)){ echo "<p style='position:absolute;top:21%; left:30%; font-size:12px; color:red'>$error</p>";}} ?>

			  	<div class="modifier_para_email_type2">
				  	
					<?php if($_SESSION['auth']['mode']==0){ ?>
						<div class="modifier_para_email2">
							<label>E-mail : </label>
							<p><?= $_SESSION['auth']['Email'] ?></p>
						</div>
					<?php }else{ ?>
						<div class="modifier_para_email2" style="color:white">
							<label>E-mail : </label>
							<p><?= $_SESSION['auth']['Email'] ?></p>
						</div>
					<?php } ?>
			  	</div>

			  	<div class="modifier_para_nationalite_type1" id="nationalite_type1">
			  	  <form method="POST">
				  	<div class="modifier_para_nationalite1">
						  <label>Pays : </label>
				  		<select name="pays">
							<option <?php if($_SESSION['auth']["Country"] == "Non Renseigne"){ echo 'selected="selected"'; } ?>>Non Renseigne</option>
							<?php foreach($pays as $pays){ ?>
									<option style="color:black" value="<?= $pays['nom_fr_fr'] ?>" <?php if($_SESSION['auth']["Country"] == $pays['nom_fr_fr']){ echo 'selected="selected"'; } ?>><?= $pays['nom_fr_fr'] ?></option>
							<?php } ?>
						</select>
				  	</div>
				
				  	<button class="valide_nationalite" name="valide_pays"id="valide_nationalite"><img src="img/checkL_white.png" width="10" height="10" style="position:absolute;top:5px;right:5px;"></button>
				  	<button class="annule_nationalite" id="annule_nationalite"><b style="position:absolute;top:2px;right:4.5px;color:var(--vert);">X</b></button>
				  </form>
			  	</div>
			  
			  	<div class="modifier_para_nationalite_type2" id="nationalite_type2">
				  	
					<?php if($_SESSION['auth']['mode']==0){ ?>
						<div class="modifier_para_nationalite2">
							<label>Pays: </label>
							<p><?= $_SESSION['auth']["Country"] ?></p>
				  		</div>
					<?php }else{ ?>
						<div class="modifier_para_nationalite2" style="color:white">
							<label>Pays: </label>
							<p><?= $_SESSION['auth']["Country"] ?></p>
						</div>
					<?php } ?>
				  	<button class="modifier_nationalite" id="modifier_nationalite"><img src="img/pencil-square.png" height="10" width="10"><p>Modifier</p></button>
			  	</div>

			  	<div class="modifier_para_date_type2">
				  	
					<?php if($_SESSION['auth']['mode']==0){ ?>
						<div class="modifier_para_date2">
							<label>Date de naissance : </label>
							<p><?= $_SESSION['auth']['Birth'] ?></p>
						</div>
					<?php }else{ ?>
						<div class="modifier_para_date2" style="color:white">
							<label>Date de naissance : </label>
							<p><?= $_SESSION['auth']['Birth'] ?></p>
						</div>
					<?php } ?>
			  	</div>

			  	<div class="modifier_para_langue_type1" id="langue_type1">
			  	  <form method="POST">
				  	<div class="modifier_para_langue1">
				  		<label>Téléphone : </label>
				  		<input type="tel" name="phone" value="<?= $_SESSION["auth"]["Phone"] ?>" >
				  	</div>
				
				  	<button class="valide_langue" name="valide_phone" id="valide_langue"><img src="img/checkL_white.png" width="10" height="10" style="position:absolute;top:5px;right:5px;"></button>
				  	<button class="annule_langue" id="annule_langue"><b style="position:absolute;top:2px;right:5px;color:var(--vert);">X</b></button>
				  </form>
			  	</div>
			  
			  	<div class="modifier_para_langue_type2" id="langue_type2">
				  	<div class="modifier_para_langue2">
				  		<label>Téléphone : </label>
				  		<p><?= $_SESSION["auth"]["Phone"] ?></p>
				  	</div>
					<?php if($_SESSION['auth']['mode']==0){ ?>
						<div class="modifier_para_langue2">
							<label>Téléphone : </label>
							<p><?= $_SESSION["auth"]["Phone"] ?></p>
						</div>
					<?php }else{ ?>
						<div class="modifier_para_langue2" style='color:white'>
							<label>Téléphone : </label>
							<p><?= $_SESSION["auth"]["Phone"] ?></p>
						</div>
					<?php } ?>
				  	<div class="modifier_langue" id="modifier_langue"><img src="img/pencil-square.png" height="10" width="10"><p>Modifier</p></div>
			  	</div>
				  <?php if(isset($_POST["valide_phone"])){if(isset($error)){ echo "<p style='position:absolute;top:61%; left:40%; font-size:12px; color:red'>$error</p>";}} ?>

			  	<div class="modifier_para_sexe_type2">
				  	
					<?php if($_SESSION['auth']['mode']==0){ ?>
						<div class="modifier_para_sexe2">
							<label>Sexe : </label>
							<p><?= $_SESSION["auth"]["Sexe"] ?></p>
						</div>
					<?php }else{ ?>
						<div class="modifier_para_sexe2" style="color:white">
							<label>Sexe : </label>
							<p><?= $_SESSION["auth"]["Sexe"] ?></p>
						</div>
					<?php } ?>
				
			  	</div>

			  	<div class="modifier_para_situation_type1" id="situation_type1">
			  	  <form method="POST">
				  	<div class="modifier_para_situation1">
				  		<label>Situation : </label>
				  		<select name="situation">
							<option  style="color:black" value="Non Renseigne" <?php if($_SESSION["auth"]["Situation"] == "Non Renseigne"){ echo 'selected="selected"'; } ?>>Non Renseigne</option>
							<option   style="color:black" value="En couple" <?php if($_SESSION["auth"]["Situation"] == "En couple"){ echo 'selected="selected"'; } ?>>En couple</option>
							<option  style="color:black" value="Celibataire" <?php if($_SESSION["auth"]["Situation"] == "Celibataire"){ echo 'selected="selected"'; } ?>>Celibataire</option>

						</select>
				  	</div>
				
				  	<button class="valide_situation" name="valide_situation" id="valide_situation"><img src="img/checkL_white.png" width="10" height="10" style="position:absolute;top:5px;right:5px;"></button>
				  	<button class="annule_situation" id="annule_situation"><b style="position:absolute;top:2px;right:4px;color:var(--vert);">X</b></button>
				  </form>
			  	</div>
			  
			  	<div class="modifier_para_situation_type2" id="situation_type2">
				  	
					<?php if($_SESSION['auth']['mode']==0){ ?>
						<div class="modifier_para_situation2">
							<label>Situation : </label>
							<p><?= $_SESSION["auth"]["Situation"] ?></p>
						</div>
					<?php }else{ ?>
						<div class="modifier_para_situation2" style="color:white">
							<label>Situation : </label>
							<p><?= $_SESSION["auth"]["Situation"] ?></p>
						</div>
					<?php } ?>
				  	<div class="modifier_situation" id="modifier_situation"><img src="img/pencil-square.png" height="10" width="10"><p>Modifier</p></div>
			  	</div>

		  	</div>
	
		  </div>
 		</div>

	</article>

<script>

	var valide_nom = document.getElementById("valide_nom");
	var annule_nom = document.getElementById("annule_nom");
	var modifier_nom = document.getElementById("modifier_nom");
	var nom_type1 = document.getElementById("nom_type1");
	var nom_type2 = document.getElementById("nom_type2");

	valide_nom.onclick = function(){
	nom_type1.style.display = "none";
	nom_type2.style.display = "block";
	}
	annule_nom.onclick = function(){
	nom_type1.style.display = "none";
	nom_type2.style.display = "block";
	}
	modifier_nom.onclick = function(){
	nom_type1.style.display = "block";
	nom_type2.style.display = "none";
	}

	var valide_prenom = document.getElementById("valide_prenom");
	var annule_prenom = document.getElementById("annule_prenom");
	var modifier_prenom = document.getElementById("modifier_prenom");
	var prenom_type1 = document.getElementById("prenom_type1");
	var prenom_type2 = document.getElementById("prenom_type2");

	valide_prenom.onclick = function(){
	prenom_type1.style.display = "none";
	prenom_type2.style.display = "block";
	}
	annule_prenom.onclick = function(){
	prenom_type1.style.display = "none";
	prenom_type2.style.display = "block";
	}
	modifier_prenom.onclick = function(){
	prenom_type1.style.display = "block";
	prenom_type2.style.display = "none";
	}
	
	var valide_nationalite = document.getElementById("valide_nationalite");
	var annule_nationalite = document.getElementById("annule_nationalite");
	var modifier_nationalite = document.getElementById("modifier_nationalite");
	var nationalite_type1 = document.getElementById("nationalite_type1");
	var nationalite_type2 = document.getElementById("nationalite_type2");

	valide_nationalite.onclick = function(){
	nationalite_type1.style.display = "none";
	nationalite_type2.style.display = "block";
	}
	annule_nationalite.onclick = function(){
	nationalite_type1.style.display = "none";
	nationalite_type2.style.display = "block";
	}
	modifier_nationalite.onclick = function(){
	nationalite_type1.style.display = "block";
	nationalite_type2.style.display = "none";
	}

	var valide_langue = document.getElementById("valide_langue");
	var annule_langue = document.getElementById("annule_langue");
	var modifier_langue = document.getElementById("modifier_langue");
	var langue_type1 = document.getElementById("langue_type1");
	var langue_type2 = document.getElementById("langue_type2");

	valide_langue.onclick = function(){
	langue_type1.style.display = "none";
	langue_type2.style.display = "block";
	}

	annule_langue.onclick = function(){
	langue_type1.style.display = "none";
	langue_type2.style.display = "block";
	}

	modifier_langue.onclick = function(){
	langue_type1.style.display = "block";
	langue_type2.style.display = "none";
	}

	var valide_situation = document.getElementById("valide_situation");
	var annule_situation = document.getElementById("annule_situation");
	var modifier_situation = document.getElementById("modifier_situation");
	var situation_type1 = document.getElementById("situation_type1");
	var situation_type2 = document.getElementById("situation_type2");

	valide_situation.onclick = function(){
	situation_type1.style.display = "none";
	situation_type2.style.display = "block";
	}
	annule_situation.onclick = function(){
	situation_type1.style.display = "none";
	situation_type2.style.display = "block";
	}
	modifier_situation.onclick = function(){
	situation_type1.style.display = "block";
	situation_type2.style.display = "none";
	}

</script>









































</body>
</html>

