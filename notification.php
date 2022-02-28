<?php

    session_start();
    require 'function.php';
    include("cnxpdo.php");
    $idcom = connexpdo("network", "myparam");

    if(!isset($_SESSION['auth'])){
        header('Location: index.php');
        exit();
    }
    
    $reqnotif = $idcom->prepare("SELECT *
    FROM notification n, user u
    WHERE n.id_send=u.Id AND id_receive = ?
    ORDER BY date_notif DESC");
    $reqnotif->execute(array($_SESSION['auth']['Id']));
    $notif = $reqnotif->fetchAll(); 

    $output = '';

    if($reqnotif->rowCount() > 0){
        foreach($notif as $notif){
            if($notif['subject'] == 'Like'){
                $p = '<span>'.$notif['Name']." ".$notif['LastName'].'</span></br>à aimé votre poste';
            }elseif($notif['subject'] == 'Comment'){
                $p = '<span>'.$notif['Name']." ".$notif['LastName'].'</span></br>à commenté votre poste';

            }else{
                $p = '<span>'.$notif['Name']." ".$notif['LastName'].'</span></br>vous a envoyé une demande d\'ami';
            }
            if($_SESSION['auth']['mode'] == 0){
                $output .='
                    <a href="'.$notif['link'].'">
                        <img src="img/profil/'.$notif['profil_photo'].'" width="40" height="40">
                        <p class="notiftext">'.$p.'</p>
                        <p class="notifdate">'.disDate($notif['date_notif']).'</p>
                    </a>';
            }else{ 
                $output .='
                    <a href="'.$notif['link'].'">
                        <img src="img/profil/'.$notif['profil_photo'].'" width="40" height="40">
                        <p class="notiftext" style="color:white">'.$p.'</p>
                        <p class="notifdate" style="color:white">'.disDate($notif['date_notif']).'</p>
                    </a>';
            }
        }
    }
    else{
        $output .='<p>Pas de Notification</p>';
    }

    $reqstate = $idcom->prepare("SELECT *
    FROM notification
    WHERE state = 1
    AND id_receive = ?");
    $reqstate->execute(array($_SESSION['auth']['Id']));
    $count = $reqstate->rowCount();

    echo $output;

?>