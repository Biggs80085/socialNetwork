<?php

    session_start();
    require 'function.php';
    include("cnxpdo.php");
    $idcom = connexpdo("network", "myparam");

    if(!isset($_SESSION['auth'])){
        exit();
    }

    $req = $idcom->prepare("SELECT * FROM publication WHERE id_pub = ?");
    $req->execute(array($_POST['id']));
    $req = $req->fetch();
    $inc = $req['likes'];

    $requpdate = $idcom->prepare("UPDATE publication SET likes = ? WHERE id_pub = ?")->execute(array($inc+1, $_POST['id']));
    $date = date('Y-m-d H:i:s');
    $reqinsert = $idcom->prepare("INSERT INTO liker(id_bywho, id_post, date_like) VALUES(?, ?, ?)");
    $reqinsert->execute(array($_SESSION['auth']['Id'], $_POST['id'], $date));

    
    if($req['id_poster'] <> $_SESSION['auth']['Id']){
        $subject = 'Like';
        $link = 'post.php?id='. $_POST['id'] .'';
        $date = date('Y-m-d H:i:s');

        $insertNotif = $idcom->prepare("INSERT INTO notification (id_receive, id_send, subject, link, date_notif) VALUES(?, ?, ?, ?, ?)");
        $insertNotif->execute(array($req['id_poster'], $_SESSION['auth']['Id'], $subject, $link, $date));
    }

?>