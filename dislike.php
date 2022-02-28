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

    $requpdate = $idcom->prepare("UPDATE publication SET likes = ? WHERE id_pub = ?")->execute(array($inc-1, $_POST['id']));
    $deletelike = $idcom->prepare("DELETE FROM liker WHERE id_bywho = ? AND id_post = ?");
    $deletelike->execute(array($_SESSION['auth']['Id'], $_POST['id']));

?>