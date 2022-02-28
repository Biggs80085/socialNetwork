<?php
    session_start();
    require 'function.php';
	include("cnxpdo.php");
	$idcom = connexpdo("network", "myparam");

    if(!isset($_SESSION['auth'])){
        exit();
    }

    $limit = (int) trim($_POST['limit']);

    /** RECUPERER LES AMIS */
    $nbfriend_max = (int) 3;
    $nbfriend = (int) 0;
    $reqnbfriend = $idcom->prepare("SELECT COUNT(IF (f.id_request = :id, f.id_receive, f.id_request)) AS nbfriend 
    FROM friend f
    WHERE (f.id_request = :id OR f.id_receive = :id)
    AND f.state = 0");
    $reqnbfriend->execute(array('id' => $_SESSION['auth']['Id']));
    $reqnbfriend = $reqnbfriend->fetch();

    $reqfriend = $idcom->prepare("SELECT DISTINCT u.* FROM friend f, user u 
    WHERE (u.Id = f.id_request OR u.Id = f.id_receive)
    AND (id_request = :id OR id_receive = :id)
    AND state = 0
    LIMIT $limit, $nbfriend_max");
    $reqfriend->execute(array('id' => $_SESSION['auth']['Id']));
    $nbfriend = $reqnbfriend['nbfriend'];
    $friend = $reqfriend->fetchAll();
    /** FIN RECUPERER LES AMIS */
    echo '<div>' . $limit . '>=' . $nbfriend . '</div>';
    if($limit >= $nbfriend){ 
        echo '<div>' . $limit . '>=' . $nbfriend . '</div>';
        ?>
    
    <div>
        <script>
            var elm = document.getElementById('seefriend');
            elm.classList.add('afficher_contacts_mask');
        </script>
    </div>
    <?php } 

    foreach($friend as $friend){ ?>
    <div class="afficher_contacts_ami1">
        <img src="gonXneferpitou.jpg" width="30" height="30" class="afficher_contacts_ami_img">
        <p class="afficher_contacts_ami_name"><?= $friend['Name'] .' '. $friend['LastName'] ?></p>
    </div>
    <?php 
    } 
?>
    <div id="friend_display"></div>
