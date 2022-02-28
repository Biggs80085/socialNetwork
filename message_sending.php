<?php    
    session_start();
    require 'function.php';
	include("cnxpdo.php");
	$idcom = connexpdo("network", "myparam");

    if(!isset($_SESSION['auth'])){
       exit();
    }

    $id_friend = (int) $_POST['id'];
    $get_msg = (String) trim($_POST['message']);

    if($id_friend < 0 || $id_friend == $_SESSION['auth']['Id'] || empty($get_msg)){
        exit();
    }

    $reqfriend = $idcom->prepare("SELECT id
    FROM friend 
    WHERE (id_request, id_receive) = (:id, :id1) 
    OR (id_request, id_receive) = (:id1, :id) AND state = 0");
    $reqfriend->execute(array(
        'id' => $_SESSION['auth']['Id'],
        'id1' => $id_friend));
    $reqfriend = $reqfriend->fetch();

    if(!isset($reqfriend['id'])){
        exit();
    }

    $sending_date = date("Y-m-d H:i:s");
    $reqinsrtmsg = $idcom->prepare("INSERT INTO messaging (id_from, id_to, message, sending_date) VALUES (?, ?, ?, ?)");
    $reqinsrtmsg->execute(array($_SESSION['auth']['Id'], $id_friend, $get_msg, $sending_date));
    
?>

<div class="text_right">
    <?= nl2br($get_msg )?>
    

</div>
