<?php

	session_start();
    require 'function.php';
    include("cnxpdo.php");
    $idcom = connexpdo("network", "myparam");

	$reqselect = $idcom->prepare("SELECT * 
	FROM comment c, user u 
	WHERE c.id_bywho = u.Id
	AND id_post = ?");
	$reqselect->execute(array($_POST['id_post']));
	$display = $reqselect->fetchAll();

	$likeM=''; $nblikeM = ''; $nbcommM = '';

	if($_SESSION['auth']['mode'] == 0) {	
		$likeM = '<img src="img/hand-thumbs-up-fill.svg" width="22" height="22" ><p>J\'aime</p>';
	}else{
		$likeM = '<img src="img/hand-thumbs-up-fill-Récupéré.png" width="22" height="22" ><p style="color:white">J\'aime</p>';
	}

	$reqlikes = $idcom->prepare("SELECT * FROM liker WHERE id_bywho = ? AND id_post = ?");
	$reqlikes->execute(array($_SESSION['auth']['Id'], $_POST['id_post']));
	$likes = $reqlikes->rowCount();

	$reqNbLike = $idcom->prepare("SELECT likes FROM publication WHERE id_pub = ?");
	$reqNbLike->execute(array($_POST['id_post']));
	$nbLike = $reqNbLike->fetch();

	$reqNbComment = $idcom->prepare("SELECT COUNT(*) AS nbc FROM comment WHERE id_post = ?");
	$reqNbComment->execute(array($_POST['id_post']));
	$nbComment = $reqNbComment->fetch();

	$like = '<button type="button" class="likes" name="likes" data-id="'.$_POST['id_post'].'">'.$likeM.'</button>';
	if($likes == 1){ 
		$like = '<button type="button" class="unlikes" name="unlikes" data-id="'.$_POST['id_post'].'"><img src="img/hand-thumbs-up-fill.png" width="22" height="22" ><p>J\'aime</p></button>';
	}

	$output = '';

	if($reqselect->rowCount() > 0){
		foreach ($display as $comment) {
			$output .='
			<div class="champ_comment">
				<a href="profil.php?id='.$comment['Id'].'" ><img src="img/profil/'. $comment['profil_photo'] .'" width="40" height="40">
				<div class="comment_text"></a>
				
				<p class="comment_comment">'.$comment["LastName"]." ".$comment["Name"].'<br><br>'.nl2br($comment["content"]).'</p>
				<p style="position:absolute;top:0px;right:10px;font-size:12px;color:white">'.disDate($comment["comment_date"]).'</p>
				</div>
			</div>';
		}
	}

	if($_SESSION['auth']['mode'] == 0) {
		$nblikeM = '<img src="img/hand-thumbs-up-fill.svg" width="15" height="15" ><p>'.$nbLike['likes'].'</p>';
		$nbcommM = '<img src="img/chat-quote-fill.svg" width="15" height="15"><p>'.$nbComment['nbc'].'</p>';
	}else{
		$nblikeM = '<img src="img/hand-thumbs-up-fill-Récupéré.png" width="15" height="15" ><p style="color:white">'.$nbLike['likes'].'</p>';
		$nbcommM = '<img src="img/chat-quote-fill.png" width="15" height="15"><p style="color:white">'.$nbComment['nbc'].'</p>';
	}

	$outputButton = '<div class="user_pub_nbjaime">'.$nblikeM.'</div>
			<div class="user_pub_nbcommentaire">'.$nbcommM.'</div>
			<div class="user_pub_separateur"></div>
			'.$like.'';

	$data = array(
		'comment' => $output,
		'button' => $outputButton
		);

	echo json_encode($data);

?>

       