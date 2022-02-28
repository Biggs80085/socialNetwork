<?php 

    session_start();

    if(!isset($_SESSION['auth'])){
        header('Location: index.php');
        exit();
    }
  
    require_once 'cnxpdo.php';
    $idcom = connexpdo("network", "myparam");

    $req_last = $idcom->prepare("SELECT IF(f.id_request = :id, f.id_receive, f.id_request) AS id
    FROM friend f
    WHERE f.id_request IN(
    SELECT IF(id_from = :id, id_to, id_from) 
        FROM messaging
        WHERE (id_from = :id OR id_to = :id)
        ORDER BY sending_date DESC)
        OR f.id_receive IN(SELECT IF(id_from = :id, id_to, id_from) 
        FROM messaging
        WHERE (id_from = :id OR id_to = :id)
        ORDER BY sending_date DESC)
    LIMIT 1");
    $req_last->execute(array('id' => $_SESSION['auth']['Id']));
    $last = $req_last->fetch();

    $notif_msg = $idcom->prepare("SELECT COUNT(*) AS notif 
    FROM messaging 
    WHERE id_to = :id AND state = 1");
    $notif_msg->execute(array('id' => $_SESSION['auth']['Id']));
    $nbmsg = $notif_msg->fetch();

    $output = '';

    if($last){
        $output .= '<a href="message.php?id='.$last['id'].'" class="right_message">';
        if($nbmsg['notif'] > 0){
            $output .= '<div class="nbMsg"><p class="nbMsg-para">'.$nbmsg['notif'].'</p></div>';
        }
        if($_SESSION['auth']['mode'] == 0){
            $output .= '<img src="img/chat-dots-fill.svg" height="25" width="25" ></a>';
        }else{
            $output .= '<img src="img/chat-dots-fill.png" height="25" width="25" ></a>';
        }
    }else{
        $output .= '<a href="message.php" class="right_message">';
        if($_SESSION['auth']['mode'] == 0){
            $output .= '<img src="img/chat-dots-fill.svg" height="25" width="25" ></a>';
        }else{
            $output .= '<img src="img/chat-dots-fill.png" height="25" width="25" ></a>';
        }
    }


    echo $output;
    
?>




