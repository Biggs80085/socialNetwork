<?php

    session_start();
    require 'function.php';
    include("cnxpdo.php");
    $idcom = connexpdo("network", "myparam");

    if(!isset($_SESSION['auth'])){
        header('Location: index.php');
    exit();
    }

    $output = ' <div class="notification_bloc">';
    
    $reqnotif = $idcom->prepare("SELECT *
    FROM notification n, user u
    WHERE n.id_send=u.Id AND id_receive = ?
    AND view = 1
    ORDER BY date_notif DESC");
    $reqnotif->execute(array($_SESSION['auth']['Id']));
    $notif = $reqnotif->fetchAll();
    
    if($reqnotif->rowCount() > 0){
        foreach($notif as $notif){
            if($notif['subject']=='Like'){
                $output .= '
                <a href="'.$notif['link'].'">
                    <div class="notificationStyle">
                    <div class="infoNotif">
                        <p class="infoNotifType">Réactions</p>
                        <p class="infoNotifPerso">'. $notif['LastName']." ".$notif['Name'] .'<span style="font-size:9px"> à aimé votre poste</span></p>
                    </div>
                    <div class="infoNotifStyle"><img src="img/hand-thumbs-up-fill-Récupéré.png" width="20" height="20"></div>
                    </div>
                </a>
                ';
            }elseif($notif['subject']=='Comment'){
                $output .= '
                <a href="'.$notif['link'].'">
                    <div class="notificationStyle">
                    <div class="infoNotif">
                        <p class="infoNotifType">Réactions</p>
                        <p class="infoNotifPerso">'. $notif['LastName']." ".$notif['Name'] .'<span style="font-size:9px"> à commenté votre poste</span></p>
                    </div>
                    <div class="infoNotifStyle"><img src="img/chat-quote-fill.png" width="20" height="20"></div>
                    </div>
                </a>
                ';
            
            }elseif($notif['subject']=='Invitation'){
                $output .= '
                <a href="'.$notif['link'].'">
                    <div class="notificationStyle">
                    <div class="infoNotif">
                        <p class="infoNotifType">Invitation</p>
                        <p class="infoNotifPerso">'. $notif['LastName']." ".$notif['Name'] .'<span style="font-size:9px"> vous a envoyé une demande d\'ami</span></p>
                    </div>
                    <div class="infoNotifStyle"><img src="img/person-plus-fill.png" width="20" height="20"></div>
                    </div>
                </a>
                ';
            }
            elseif($notif['subject']=='Message'){
                $output .= '
                <a href="'.$notif['link'].'">
                    <div class="notificationStyle">
                    <div class="infoNotif">
                        <p class="infoNotifType">Message</p>
                        <p class="infoNotifPerso">'. $notif['LastName']." ".$notif['Name'] .'<span style="font-size:9px"> vous a envoyé un message</span></p>
                    </div>
                    <div class="infoNotifStyle"><img src="img/chat-dots-fill.png" width="20" height="20"></div>
                    </div>
                </a>
                ';
            }
        }
    }


    
    $output .= '</div>';
    
    echo $output;

    $req = $idcom->prepare("UPDATE notification 
    SET view = 0 
    WHERE id_receive = ? 
    AND view = 1");
    $req->execute(array($_SESSION['auth']['Id']));


?>