<?php
    session_start();
	require 'function.php';
	include("cnxpdo.php");
	$idcom = connexpdo("network", "myparam");

    if(!isset($_SESSION['auth'])){
        header('Location: index.php');
    	exit();
    }


    $reqfriend = $idcom->prepare("SELECT DISTINCT u.* FROM friend f, user u 
    WHERE (u.Id = f.id_request
    OR u.Id = f.id_receive)
    AND (id_request = :id 
    OR id_receive = :id)
    AND state = 0");

    $reqfriend->execute(array("id" => $_SESSION['auth']['Id']));
    $friend = $reqfriend->fetchAll();
    $name='';
    
    $current_time = strtotime(date("Y-m-d H:i:s"));
    $z = $current_time - 10;
    $current_time = date('Y-m-d H:i:s', $z);

    $output = '<div class="encadrage">';
   
    if($reqfriend->rowCount() > 0){
    foreach($friend as $row){
        if($_SESSION['auth']['mode']==0){
            $name='<p>'.$row['LastName']." ".$row['Name'].'</p>';
        }else{
            $name='<p style="color:white">'.$row['LastName']." ".$row['Name'].'</p>';
        }
        if($row['Id'] != $_SESSION['auth']['Id']){
            if($row['connected'] > $current_time){
                $output .= '
                <a href="message.php?id='.$row['Id'].'">
                <div class="msn_amis" >
                '.$name.' 
                <img src="img/profil/'.$row['profil_photo'] .'" width="30" height="30">
                <div class="ami_enligne"></div>
                </div>
                </a>
                ';
            }
            else{
                $output .= '
                <a href="message.php?id='.$row['Id'].'">
                <div class="msn_amis" >
                '.$name.'  
                <img src="img/profil/'.$row['profil_photo'] .'" width="30" height="30">
                <div class="ami_horligne"></div>
                </div>
                </a>
                ';
            }
        }
    }
}

    $output .= '</div>';

    echo $output;
?>
