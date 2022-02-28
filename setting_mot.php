<?php	
	include("function.php");
	session_start();
	require_once 'cnxpdo.php';
  	$idcom = connexpdo("network", "myparam");
	if(!isset($_SESSION['auth'])){
		header('Location: index.php');
		exit();
	}

	if(isset($_POST['termine'])){
		$requpdate = $idcom->prepare("UPDATE user SET sec_email = ?, sec_date = ?, sec_country = ?, sec_phone = ?, sec_situation = ?");
		$requpdate->execute(array($_POST['sec_mail'], $_POST['sec_date'], $_POST['sec_country'], $_POST['sec_phone'], $_POST['sec_situation']));
		$_SESSION['auth']['sec_email'] = $_POST['sec_mail'];
		$_SESSION['auth']['sec_date'] = $_POST['sec_date'];
		$_SESSION['auth']['sec_country'] = $_POST['sec_country'];
		$_SESSION['auth']['sec_phone'] = $_POST['sec_phone'];
		$_SESSION['auth']['sec_situation'] = $_POST['sec_situation'];

		header('Location: setting_mot.php');
		exit();
	}
	elseif(isset($_POST['set'])){
		$error = array();
		if(!empty($_POST['bmdp']) || !empty($_POST['mdp']) || !empty($_POST['cmdp'])){
      		if(password_verify($_POST["bmdp"], $_SESSION['auth']['Pw'])){
        		if($_POST['mdp'] == $_POST['cmdp'] && regExpPwd($_POST['mdp'])){
          			$password = password_hash($_POST["mdp"], PASSWORD_BCRYPT);
          			$requpdate = $idcom->prepare("UPDATE user SET Pw = ? WHERE Id = ?")->execute(array($password, $_SESSION['auth']['Id']));
          			$_SESSION['auth']['Pw'] = $password;
          			header('Location: setting_mot.php');
          			exit();
    			}else{
					$error[2] = "Erreur mot de passe
					le mot de passe doit être entre 8-12 contenir <br> au minimune majuscule, une minuscule,  un chiffre, un caractère spéciale";
				}
      		}else{
				  $error[1] = "Votre ancien mot de passe est incorrect";
			  }
    	}else{
			$error[0] = "Vous devez remplir tous les champs";
		}
	}

	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Confidentialité</title>
		<link rel="stylesheet" type="text/css" href="CSS/setting_mot.css">
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
				<p class="path">Paramètres &nbsp;> &nbsp;&nbsp;<span>Confidentialité</span></p>
			<?php }else{ ?>
				<p class="path" style='color:white'>Paramètres &nbsp;> &nbsp;&nbsp;<span>Confidentialité</span></p>
			<?php } ?>
		  <h1>Confidentialité</h1>
		  <a href="setting.php">&lt;&lt; Retour</a>
		  <div class="modifier_para">
		  	<p class="modifier_para_title"></p>



			  	
			  <form method="POST">
			  	<div class="modifier_para_email_type2" id="email_type2">
				  	
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
				
				  	<SELECT class="modifier_email" name="sec_mail">
				  		<?php if($_SESSION['auth']['sec_email'] == 0){ ?>
				  			<OPTION value="0" selected>Public</OPTION>
				  		<?php } else{ ?>
				  			<OPTION value="0">Public</OPTION>
				  		<?php } if($_SESSION['auth']['sec_email'] == 1){ ?>
				  			<OPTION value="1" selected>Amis uniquement</OPTION>
				  		<?php } else{ ?>
				  			<OPTION value="1">Amis uniquement</OPTION>
				  		<?php } if($_SESSION['auth']['sec_email'] == 2){ ?>
				  			<OPTION value="2" selected>Prive</OPTION>
				  		<?php } else{ ?>
				  			<OPTION value="2">Prive</OPTION>
				  		<?php } ?>
				  	</SELECT>
			  	</div>

			  
			  	<div class="modifier_para_nationalite_type2" id="nationalite_type2">
				  	
					<?php if($_SESSION['auth']['mode']==0){ ?>
						<div class="modifier_para_nationalite2">
							<label>Pays : </label>
							<p><?= $_SESSION['auth']['Country'] ?></p>
						</div>
					<?php }else{ ?>
						<div class="modifier_para_nationalite2" style='color:white'>
							<label>Pays : </label>
							<p><?= $_SESSION['auth']['Country'] ?></p>
						</div>
					<?php } ?>
				
				  	<SELECT class="modifier_nationalite" name="sec_country">
				  		<?php if($_SESSION['auth']['sec_country'] == 0){ ?>
				  			<OPTION value="0" selected>Public</OPTION>
				  		<?php } else{ ?>
				  			<OPTION value="0">Public</OPTION>
				  		<?php } if($_SESSION['auth']['sec_country'] == 1){ ?>
				  			<OPTION value="1" selected>Amis uniquement</OPTION>
				  		<?php } else{ ?>
				  			<OPTION value="1">Amis uniquement</OPTION>
				  		<?php } if($_SESSION['auth']['sec_country'] == 2){ ?>
				  			<OPTION value="2" selected>Prive</OPTION>
				  		<?php } else{ ?>
				  			<OPTION value="2">Prive</OPTION>
				  		<?php } ?>
				  	</SELECT>
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
				  	<SELECT class="modifier_date" name="sec_date">
				  		<?php if($_SESSION['auth']['sec_date'] == 0){ ?>
				  			<OPTION value="0" selected>Public</OPTION>
				  		<?php } else{ ?>
				  			<OPTION value="0">Public</OPTION>
				  		<?php } if($_SESSION['auth']['sec_date'] == 1){ ?>
				  			<OPTION value="1" selected>Amis uniquement</OPTION>
				  		<?php } else{ ?>
				  			<OPTION value="1">Amis uniquement</OPTION>
				  		<?php } if($_SESSION['auth']['sec_date'] == 2){ ?>
				  			<OPTION value="2" selected>Prive</OPTION>
				  		<?php } else{ ?>
				  			<OPTION value="2">Prive</OPTION>
				  		<?php } ?>
				  	</SELECT>
			  	</div>


			  	
			  
			  	<div class="modifier_para_langue_type2" id="langue_type2">
				  	
					<?php if($_SESSION['auth']['mode']==0){ ?>
						<div class="modifier_para_langue2">
							<label>Téléphone : </label>
							<p><?= $_SESSION['auth']['Phone'] ?></p>
						</div>
					<?php }else{ ?>
						<div class="modifier_para_langue2" style="color:white">
							<label>Téléphone : </label>
							<p><?= $_SESSION['auth']['Phone'] ?></p>
						</div>
					<?php } ?>
				
				  	<SELECT class="modifier_langue" name="sec_phone">
				  		<?php if($_SESSION['auth']['sec_phone'] == 0){ ?>
				  			<OPTION value="0" selected>Public</OPTION>
				  		<?php } else{ ?>
				  			<OPTION value="0">Public</OPTION>
				  		<?php } if($_SESSION['auth']['sec_phone'] == 1){ ?>
				  			<OPTION value="1" selected>Amis uniquement</OPTION>
				  		<?php } else{ ?>
				  			<OPTION value="1">Amis uniquement</OPTION>
				  		<?php } if($_SESSION['auth']['sec_phone'] == 2){ ?>
				  			<OPTION value="2" selected>Prive</OPTION>
				  		<?php } else{ ?>
				  			<OPTION value="2">Prive</OPTION>
				  		<?php } ?>
				  	</SELECT>
			  	</div>



			  	<div class="modifier_para_situation_type2">
				  	
					<?php if($_SESSION['auth']['mode']==0){ ?>
						<div class="modifier_para_situation2">
							<label>Situation : </label>
							<p><?= $_SESSION['auth']['Situation'] ?></p>
						</div>
					<?php }else{ ?>
						<div class="modifier_para_situation2" style="color:white">
							<label>Situation : </label>
							<p><?= $_SESSION['auth']['Situation'] ?></p>
						</div>
					<?php } ?>
				  	<SELECT class="modifier_situation" name="sec_situation">
				  		<?php if($_SESSION['auth']['sec_situation'] == 0){ ?>
				  			<OPTION value="0" selected>Public</OPTION>
				  		<?php } else{ ?>
				  			<OPTION value="0">Public</OPTION>
				  		<?php } if($_SESSION['auth']['sec_situation'] == 1){ ?>
				  			<OPTION value="1" selected>Amis uniquement</OPTION>
				  		<?php } else{ ?>
				  			<OPTION value="1">Amis uniquement</OPTION>
				  		<?php } if($_SESSION['auth']['sec_situation'] == 2){ ?>
				  			<OPTION value="2" selected>Prive</OPTION>
				  		<?php } else{ ?>
				  			<OPTION value="2">Prive</OPTION>
				  		<?php } ?>
				  	</SELECT>
				
			  	</div>
			  	<button class="info_type" name="termine">Terminer</button>
			  </form>

			  	<div class="modification_mdp">
			  	<form method="POST">
			  		<div class="ancien_mdp">
			  			<label>Ancien Mot de passe :</label>
			  			<INPUT type="password" name="bmdp"/>
			  		</div>
					
			  		<div class="nv_mdp">
			  			<label>Nouveau Mot de passe :</label>
			  			<INPUT type="password" name="mdp"/>
			  		</div>

			  		<div class="confi_nv_mdp">
			  			<label>Comfirmation Mot de passe :</label>
			  			<INPUT type="password" name="cmdp"/>
			  		</div>
			  	</div>
			  	
				  <?php if(isset($_POST["set"])){if(!empty($error[0])){ echo "<p style='position:absolute;right:10%; bottom:30%; font-size:13px; color:red;'> $error[0] </p>";}
				   elseif(!empty($error[1])){ echo "<p style='position:absolute;right:10%; bottom:30%; font-size:13px; color:red;'> $error[1] </p>";}
				   elseif(!empty($error[2])){ echo "<p style='position:absolute;right:-7%; bottom:27%; font-size:13px; color:red;'> $error[2] </p>";}} ?>
				  
			  	<button class="modi_mdp" name="set">Modifier</button>
			  </form>
		  	</div>

		  
		  </div>
 		</div>

      
    
	</article>

</body>
</html>

