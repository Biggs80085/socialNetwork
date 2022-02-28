<?php

    session_start();
    require 'function.php';
    include("cnxpdo.php");
    $idcom = connexpdo("network", "myparam");

    if(!isset($_SESSION['auth'])){
        exit();
    }
    if(!empty($_POST['comment_content'])){
        $req = $idcom->prepare("SELECT * FROM publication WHERE id_pub = ?");
        $req->execute(array($_POST['post_id']));
        $req = $req->fetch();

        $reqCom = $idcom->prepare("INSERT INTO comment(id_post, id_bywho, content, comment_date) VALUES (?, ?, ?, ?)");
        $reqCom->execute(array($_POST['post_id'], $_SESSION['auth']['Id'], $_POST['comment_content'], date('Y-m-d H:i:s')));

        if($req['id_poster'] <> $_SESSION['auth']['Id']){
            $subject = 'Comment';
            $link = 'post.php?id='. $_POST['post_id'] .'';

            $insertNotif = $idcom->prepare("INSERT INTO notification (id_receive, id_send, subject, link, date_notif) VALUES(?, ?, ?, ?, ?)");
            $insertNotif->execute(array($req['id_poster'], $_SESSION['auth']['Id'], $subject, $link, date('Y-m-d H:i:s')));
        }
    }
?>
        