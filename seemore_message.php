<?php
    session_start();
    require 'function.php';
	include("cnxpdo.php");
	$idcom = connexpdo("network", "myparam");

    if(!isset($_SESSION['auth'])){
        exit();
    }

    $limit = (int) trim($_POST['limit']);
    $id_friend = (int) trim($_POST['id']);

    if($limit <= 0 || $id_friend <= 0){
        exit();
    }
 
     /** gérer les messages si ya blocker*/

    $reqfriend = $idcom->prepare("SELECT id
    FROM friend 
    WHERE (id_request, id_receive) = (:id, :id1) 
    OR (id_request, id_receive) = (:id1, :id) AND state = 0");
    $reqfriend->execute(array(
        'id' => $_SESSION['auth']['Id'],
        'id1' => $id_friend));
    $reqfriend = $reqfriend->fetch();

    if(!isset($reqfriend['id'])){
        exit();
    }
 
    /**nb msg display */
    $nbmsg_display = 10;
    $limit_min = 0;
    $limit_max = 0;
    /***** le nombre total de message*/
    $req = $idcom->prepare("SELECT COUNT(m.id) as nbmsg
    FROM messaging m
    WHERE (id_from, id_to) = (:id, :id1) 
    OR (id_from, id_to) = (:id1, :id)");
    $req->execute(array('id' => $_SESSION['auth']['Id'], 'id1' => $id_friend));
    $nbmsg = $req->fetch();

    $limit_min = $nbmsg['nbmsg'] - $limit;

    if($limit_min > $nbmsg_display){
        $limit_max = $nbmsg_display;
        $limit_min = $limit_min - $nbmsg_display;
    }
    else{
        if($limit_min > 0){
            $limit_max = $limit_min;
        }
        else{
            $limit_max = 0;
        }
        $limit_min = 0;
    }

    $reqmessage = $idcom->prepare("SELECT * 
    FROM messaging m
    WHERE (id_from, id_to) = (:id, :id1)
    OR (id_from, id_to) = (:id1, :id)
    ORDER BY sending_date
    LIMIT $limit_min, $limit_max");
    $reqmessage->execute(array(
        'id' => $_SESSION['auth']['Id'],
        'id1' => $id_friend
    ));
    $reqmessage = $reqmessage->fetchAll();
    if($limit_min <= 0){ ?>
<div>
    <script>
        var elm = document.getElementById('seemore');
        elm.classList.add('seemore_btn_maske');
    </script>
</div>
<?php } ?>

<div id="seemore_msg"></div>

<?php
    foreach($reqmessage as $seemessage){
    if($seemessage['id_from'] == $_SESSION['auth']['Id']){
        ?>
    <div class="text_right">
        <?= nl2br($seemessage['message']) ?>
    </div>
    <?php } 
    else{ ?>
    <div class="text_left">
        <?= nl2br($seemessage['message']) ?>
    </div>
    <?php }
    }
?>