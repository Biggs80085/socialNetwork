<?php


	session_start();
    require_once 'cnxpdo.php';
  	$idcom = connexpdo("network", "myparam");

    if(!isset($_SESSION['auth'])){
        header('Location: index.php');
    exit();
    }

    $reqsearch = $idcom->prepare("SELECT * FROM user WHERE Id = ?");
    $reqsearch->execute(array($_POST['id']));
    $user = $reqsearch->fetch();

    /** LIEN AMITIER SI EXISTE*/
    $reqfriend = $idcom->prepare("SELECT * FROM friend f 
    WHERE (f.id_request = :id AND f.id_receive = :id1)
    OR (id_request = :id1 AND id_receive = :id) 
    AND state = 0");
    $reqfriend->execute(array('id' => $_SESSION['auth']['Id'], 'id1' => $_POST['id']));

	$email='';
	$birth='';
	$country='';
	$situation='';
	$phone='';
	if($_SESSION['auth']['mode'] == 0) {
		$email='<p class="apropos_email">E-mail : '.$user['Email'].'<span></span></p>';
		$birth='<p class="apropos_date">Date de naissance : <span>'.$user['Birth'].'</span></p>';
		$country='<p class="apropos_nationalite">Pays : <span>'.$user['Country'].'</span></p>';
		$situation='<p class="apropos_situation">Situation : <span>'.$user['Situation'].'</span></p>';
		$phone='<p class="apropos_langue">Teléphone : <span>'.$user['Phone'].'</span></p>';
	}else{
		$email='<p class="apropos_email" style="color:white">E-mail : '.$user['Email'].'<span></span></p>';
		$birth='<p class="apropos_date" style="color:white">Date de naissance : <span>'.$user['Birth'].'</span></p>';
		$country='<p class="apropos_nationalite" style="color:white">Pays : <span>'.$user['Country'].'</span></p>';
		$situation='<p class="apropos_situation" style="color:white">Situation : <span>'.$user['Situation'].'</span></p>';
		$phone='<p class="apropos_langue" style="color:white">Teléphone : <span>'.$user['Phone'].'</span></p>';
	}


    $output = '<div class="form_apropos"> <img src="img/person-bounding-box.png" width="15" height="15" style="position:absolute;top:15px;left:10px"><p class="form_apropos_titre">mes information personnelles</p>';


    if($_POST['id']== $_SESSION['auth']['Id']){
    	$output .= $email.$birth.$country.$situation.$phone;
    }
    else{

	    if(($user['sec_email'] == 1 && ($reqfriend->rowCount() > 0))
	      || $user['sec_email'] == 0){
	            
	        $output .= $email;
	    }

	    if(($user['sec_date'] == 1 && ($reqfriend->rowCount() > 0)) 
	      || $user['sec_date'] == 0){ 
	          $output .= $birth;
	    }

	    if(($user['sec_country'] == 1 && ($reqfriend->rowCount() > 0)) 
	      || $user['sec_country'] == 0){
	          $output .= $country;
	    } 

	    if(($user['sec_situation'] == 1 && ($reqfriend->rowCount() > 0)) 
	      || $user['sec_situation'] == 0){
	          $output .= $situation;
	    }

	    if(($user['sec_phone'] == 1 && ($reqfriend->rowCount() > 0)) 
	      || $user['sec_phone'] == 0){
	          $output .= $phone;
	    }
    }
    

    $output .=  '</div>';
       
 	echo $output;


?>