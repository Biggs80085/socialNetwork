<?php

    session_start();
    require 'function.php';
    include("cnxpdo.php");
    $idcom = connexpdo("network", "myparam");

    if(!isset($_SESSION['auth'])){
        exit();
    }

    /** AFFICHIER LES PUBLICATIONS */
    $reqdisplay = $idcom->prepare("SELECT * 
    FROM publication p, user u
    WHERE u.Id = p.id_poster 
    AND (p.id_poster in (
        SELECT IF (f.id_request = :id, f.id_receive, f.id_request)
        FROM friend f, publication p
        WHERE p.id_poster = IF (f.id_request = :id, f.id_receive, f.id_request)
        AND (f.id_request = :id OR f.id_receive = :id)
        AND f.state = 0 AND f.id_blocker IS NULL)
        OR p.confi_post = 0)
    AND p.confi_post <> 2
    OR (p.id_poster = :id AND u.Id = :id)
    ORDER BY post_date DESC");
    $reqdisplay->execute(array('id' => $_SESSION['auth']['Id']));
    $dispayPubli = $reqdisplay->fetchAll();
    /** FIN AFFICHIER LES PUBLICATIONS */

    $nblikeM = ''; $nbcommM = ''; $commM = '';
    $likeM = ''; $nameM = ''; $pubM = '';
    
    $output = '';

    if($reqdisplay->rowCount() > 0){

        foreach($dispayPubli as $publication){
            $photo = '';
            
            /** PHOTO */
        if(!empty($publication['photo'])){
            $photo = '<img src="img/post/'. $publication['photo'] .'" class="user_pub_img">';
            }

            if($_SESSION['auth']['mode'] == 0) {
                
                $likeM = '<img src="img/hand-thumbs-up-fill.svg" width="22" height="22" ><p>J\'aime</p>';
            }else{
                
                $likeM = '<img src="img/hand-thumbs-up-fill-Récupéré.png" width="22" height="22" ><p style="color:white">J\'aime</p>';
            }

            /** LIKES */
            $reqlikes = $idcom->prepare("SELECT * FROM liker WHERE id_bywho = ? AND id_post = ?");
            $reqlikes->execute(array($_SESSION['auth']['Id'], $publication['id_pub']));
            $likes = $reqlikes->rowCount();
            $like = '<button type="button" class="like" name="like" data-id="'.$publication['id_pub'] .'">'. $likeM .'</button>';
            if($likes == 1){ 
            $like = '<button type="button" class="unlike" name="unlike" data-id="'.$publication['id_pub'] .'"><img src="img/hand-thumbs-up-fill.png" width="22" height="22" ><p>J\'aime</p></button>';
            }

            /** COMMENTS*/
            $reqComment = $idcom->prepare("SELECT COUNT(*) AS nbcmt FROM comment WHERE id_post = ?");
            $reqComment->execute(array($publication['id_pub']));
            $comment = $reqComment->fetch();

            if($_SESSION['auth']['mode'] == 0) {
                $nblikeM = '<img src="img/hand-thumbs-up-fill.svg" width="15" height="15" ><p>'.$publication['likes'].'</p>';
                $nbcommM = '<img src="img/chat-quote-fill.svg" width="15" height="15"><p>'.$comment['nbcmt'].'</p>';
                $commM = '<img src="img/chat-quote-fill.svg" width="22" height="22"><p>Commentaires</p>';
                $nameM = '<span class="user_name_pub">'. $publication['Name'] . " " . $publication['LastName'] .'</span>';
                $pubM = '<p>'. nl2br($publication['content']) .'</p>';
            }else{
                $nblikeM = '<img src="img/hand-thumbs-up-fill-Récupéré.png" width="15" height="15" ><p style="color:white">'.$publication['likes'].'</p>';
                $nbcommM = '<img src="img/chat-quote-fill.png" width="15" height="15"><p style="color:white">'.$comment['nbcmt'].'</p>';
                $commM = '<img src="img/chat-quote-fill.png" width="22" height="22"><p style="color:white">Commentaires</p>';
                $nameM = '<span class="user_name_pub" style="color:white;">'. $publication['Name'] . " " . $publication['LastName'] .'</span>';
                $pubM = '<p style="color:white;">'. nl2br($publication['content']) .'</p>';
            }

            $output .= '
            <div class="publication">
                <div class="user_pub">
                    <a href="profil.php?id='.$publication['Id'].'"><img class="user_photo_pub" src="img/profil/'.$publication['profil_photo'].'">'. $nameM .'</a>
                    <p class="user_date_pub">'.disDate($publication['post_date']).'<p>	
                </div>
                <div class="user_pub_text">
                    '. $pubM .'
                    '. $photo .'
                </div>
                
                '. $like .'
                <div class="user_pub_nbjaime">'. $nblikeM .'
                </div>
                <div class="user_pub_nbcommentaire">'. $nbcommM .'</div>
                <div class="user_pub_separateur"></div>
                <button class="user_pub_commentaire"><a href="post.php?id='.$publication['id_pub'].'">'. $commM .'</a></button>
            </div>
        ';
        }
    }
    echo $output;
?>