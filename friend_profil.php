<?php

	session_start();
    require_once 'cnxpdo.php';
  	$idcom = connexpdo("network", "myparam");

    if(!isset($_SESSION['auth'])){
        header('Location: index.php');
    	exit();
    }

	$reqinfo = $idcom->query("SELECT * FROM user
	WHERE Id = ".$_POST['id']."")->fetch();

    $reqfriend = $idcom->prepare("SELECT u.* 
    	FROM user u, friend f
		WHERE u.Id = IF(f.id_request = :id, f.id_receive, f.id_request)
		AND (f.id_request = :id OR f.id_receive = :id)
		AND f.state = 0");
    $reqfriend->execute(array('id' => $_POST['id']));
    $friend = $reqfriend->fetchAll();

    $output = '<div class="form_amis">';


    if($reqfriend->rowCount() > 0){
    	foreach ($friend as $friend) {
    		$output .= '<a href="profil.php?id='.$friend['Id'].'" ><div class="affiche_amis">
					        <img src="img/profil/'.$friend['profil_photo'].'" width="70" height="70">
					        <p>'.$friend['Name'] .'<br>'.$friend['LastName'].'</p>
					      </div></a>
    					';
    	}
    }

    $output .= '</div>';

  	if($reqfriend->rowCount() > 0){
  		echo $output;
  	}else{
		if($_SESSION['auth']['mode']==0){ 
			echo '<div style="position:absolute;left:35%;bottom:15%"><p>'.$reqinfo['Name'] ." ".$reqinfo['LastName'].' n\'a pas d\'amis</p></div>';
		}else{
			echo '<div style="position:absolute;left:35%;bottom:15%;color:white"><p>'.$reqinfo['Name'] ." ".$reqinfo['LastName'].' n\'a pas d\'amis</p></div>';
		}
  		
  	}
	
?>
   

   