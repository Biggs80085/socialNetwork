<?php 

	session_start();
    require_once 'cnxpdo.php';
  	$idcom = connexpdo("network", "myparam");

    if(!isset($_SESSION['auth'])){
        header('Location: index.php');
    	exit();
    }

    $reqPhoto = $idcom->prepare("SELECT * 
    	FROM cover_photo c
    	WHERE c.id_adder = :id
    	UNION
    	SELECT * FROM profil_photo p
		WHERE p.id_adder = :id");
    $reqPhoto->execute(array('id' => $_POST['id']));
    $photo = $reqPhoto->fetchAll();

    $output = '<div class="form_img">';

    if($reqPhoto->rowCount() > 0){
    	foreach ($photo as $photo) {
    		$output .= '<img src="img/profil/'.$photo["photo"].'" width="300" height="196">';
    	}
    }

    $output .= '</div>';

    echo $output;
?>