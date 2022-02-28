<?php
    session_start();
    require 'function.php';
    include("cnxpdo.php");
    $idcom = connexpdo("network", "myparam");

    if(!isset($_SESSION['auth'])){
        header('Location: index.php');
        exit();
    }

    $id_friend = (int) $_POST['id'];
    if($id_friend < 0 || $id_friend == $_SESSION['auth']['Id']){
        header('Location: account.php');
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

    $req = $idcom->prepare("SELECT *
    FROM messaging
    WHERE id_to = ? AND id_from = ? AND state = 1");
    $req->execute(array($_SESSION['auth']['Id'], $id_friend));
    $display_msg = $req->fetchAll();

    $req = $idcom->prepare("UPDATE messaging
    SET state = 0 WHERE id_to = ? AND id_from = ?");
    $req->execute(array($_SESSION['auth']['Id'], $id_friend));


    foreach($display_msg as $msg){ ?>
        <div class="text_left">
            <?= nl2br($msg['message']) ?>
            
        </div>
    <?php } 
?>