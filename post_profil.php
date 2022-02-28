<?php
	include('function.php');
	session_start();
    require_once 'cnxpdo.php';
  	$idcom = connexpdo("network", "myparam");

    if(!isset($_SESSION['auth'])){
        header('Location: index.php');
    exit();
    }

    if($_POST['id'] == $_SESSION['auth']['Id']){

    	$reqPostes = $idcom->prepare("SELECT * FROM publication p, user u 
		WHERE p.id_poster = u.Id 
		AND id_poster = :id 
		ORDER BY post_date DESC");
  		$reqPostes->execute(array("id" => $_SESSION["auth"]["Id"]));
  		$post=$reqPostes->fetchAll();
    }

    else{
    	$reqPostes = $idcom->prepare("SELECT * FROM publication p
		WHERE (p.id_poster = :id1 AND p.confi_post = 0)
		OR p.id_poster IN(SELECT IF(f.id_request = :id, f.id_receive, f.id_request)
						FROM friend f
						WHERE ((f.id_request = :id AND f.id_receive = :id1)
						OR (f.id_request = :id1 AND f.id_receive = :id))
						AND f.state = 0
						AND f.id_blocker IS NULL)
		AND p.confi_post <> 2 ORDER BY post_date DESC");
	    $reqPostes->execute(array("id1" => $_POST['id'],"id" => $_SESSION['auth']['Id']));
	    $post=$reqPostes->fetchAll();
	}

	$nblikeM = ''; $nbcommM = ''; $commM = ''; 
	$likeM = ''; $nameM = ''; $pubM = '';
 
	$output = '';

	if($reqPostes->rowCount() > 0){
		foreach ($post as $post) {
			$photo = '';
			/** PHOTO */
			if(!empty($post['photo'])){
				$photo = '<img src="img/post/'. $post['photo'] .'" class="user_pub_img">';
			}
			
			$reqlikes = $idcom->prepare("SELECT * FROM liker WHERE id_bywho = ? AND id_post = ?");
			$reqlikes->execute(array($_SESSION['auth']['Id'], $post['id_pub']));
			$likes = $reqlikes->rowCount();

			$reqNbLike = $idcom->prepare("SELECT likes FROM publication WHERE id_pub = ?");
			$reqNbLike->execute(array($post['id_pub']));
			$nbLike = $reqNbLike->fetch();

			$reqNbComment = $idcom->prepare("SELECT COUNT(*) AS nbc FROM comment WHERE id_post = ?");
			$reqNbComment->execute(array($post['id_pub']));
			$nbComment = $reqNbComment->fetch();

			$reqinfo = $idcom->query("SELECT * FROM user WHERE Id = ".$post['id_poster']."")->fetch();

			if($_SESSION['auth']['mode'] == 0) {
				$nblikeM = '<img src="img/hand-thumbs-up-fill.svg" width="15" height="15" ><p>'.$post['likes'].'</p>';
				$nbcommM = '<img src="img/chat-quote-fill.svg" width="15" height="15"><p style="position:absolute;top:-20px;left:20px;">'.$nbComment['nbc'].'</p>';
				$commM = '<img src="img/chat-quote-fill.svg" width="22" height="22"><p>Commentaires</p>';
				$nameM = '<span class="user_name_pub">'. $reqinfo['Name'] . " " . $reqinfo['LastName'] .'</span>';
				$pubM = '<p>'. nl2br($post['content']) .'</p>';
				$likeM = '<img src="img/hand-thumbs-up-fill.svg" width="22" height="22" ><p>J\'aime</p>';
			}else{
				$nblikeM = '<img src="img/hand-thumbs-up-fill-Récupéré.png" width="15" height="15" ><p style="color:white">'.$post['likes'].'</p>';
				$nbcommM = '<img src="img/chat-quote-fill.png" width="15" height="15"><p style="position:absolute;top:-20px;left:20px;color:white">'.$nbComment['nbc'].'</p>';
				$commM = '<img src="img/chat-quote-fill.png" width="22" height="22"><p style="color:white">Commentaires</p>';
				$nameM = '<span class="user_name_pub" style="color:white;">'. $reqinfo['Name'] . " " . $reqinfo['LastName'] .'</span>';
				$pubM = '<p style="color:white;">'. nl2br($post['content']) .'</p>';
				$likeM = '<img src="img/hand-thumbs-up-fill-Récupéré.png" width="22" height="22" ><p style="color:white">J\'aime</p>';
			}

			$like = '<button type="button" class="like" name="like" data-id="'.$post['id_pub'].'">'. $likeM .'</button>';

			if($likes == 1){ 
				$like = '<button type="button" class="unlike" name="unlike" data-id="'.$post['id_pub'].'"><img src="img/hand-thumbs-up-fill.png" width="22" height="22" ><p>J\'aime</p></button>';
			}

			$output .= '
			<div class="publ">

			<div class="user_pub">
				<a href="profil.php?id='.$reqinfo['Id'].'"><img class="user_photo_pub" src="img/profil/'.$reqinfo['profil_photo'].'">'.$nameM.'</a>
				<p class="user_date_pub">'.disDate($post['post_date']).'<p> 
			</div>
			<div class="user_pub_text">
			'.$pubM.'
			'.$photo.'
			
			</div>
			<div class="user_pub_nbjaime">'.$nblikeM.'</div>
			<div class="user_pub_nbcommentaire">'.$nbcommM.'</div>
			<div class="user_pub_separateur"></div>
			'.$like.'
			<button class="user_pub_commentaire"><a href="post.php?id='.$post['id_pub'].'">'.$commM.'</a></button>
		</div>';
		}
	}
	if($_SESSION['auth']['mode']==0){ 
		$nada = '<div style="position:absolute;left:40%;bottom:15%;"><p>Pas de posts</p></div>';
	}else{
		$nada = '<div style="position:absolute;left:40%;bottom:15%;color:white"><p>Pas de posts</p></div>';
	}
	if(strlen($output) > 0){
		echo $output;
	}else{
		echo $nada;
	}


?>