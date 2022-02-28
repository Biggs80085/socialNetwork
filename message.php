<?php
   
    session_start();
    require_once "cnxpdo.php";
    $idcom = connexpdo("network", "myparam");
   
    if(!isset($_SESSION['auth'])){
        header('Location: index.php');
    	exit();
    }

    if(isset($_GET['id'])){
        $id_friend = (int) htmlentities(trim($_GET['id']));
        if($id_friend < 0 || $id_friend == $_SESSION['auth']['Id']){
            header('Location: account.php');
            exit();
        }
    
        /** VERIFIER LIEN DAMITIER */
        $reqfriend1 = $idcom->prepare("SELECT f.id, u.Name, u.LastName
        FROM friend f, user u
        WHERE (u.Id = IF(f.id_request = :id, f.id_receive, f.id_request))
        AND((id_request = :id AND id_receive = :id1)
        OR (id_request = :id1 AND id_receive = :id)) 
        AND state = 0");
        $reqfriend1->execute(array(
            'id' => $_SESSION['auth']['Id'],
            'id1' => $id_friend)
        );

        if($reqfriend1->rowCount() < 1){
            header('Location: account.php');
            exit();
        }
        
        $namefriend = $reqfriend1->fetch();

        /** GERER LES MESSAGES SI LIEN AMITIER ET NON BLOQUE*/
        $reqfriend = $idcom->prepare("SELECT id
        FROM friend 
        WHERE ((id_request = :id AND id_receive = :id1)
        OR (id_request = :id1 AND id_receive = :id)) 
        AND state = 0 AND id_blocker IS NULL");
        $reqfriend->execute(array(
            'id' => $_SESSION['auth']['Id'],
            'id1' => $id_friend)
        );

        if ($reqfriend->rowCount() < 1) {
            $v = '<div class="msn_chat"><p style="position:absolute;bottom:-5px;left:35px">Vous pouvez pas envoyer de message a cette utilisateur</p></div>';
        }
        else{
            if($_SESSION['auth']['mode']==0){
                $v = '<textarea class="msn_chat" id="texto" name="texto" placeholder="Démarrer votre message ici..."></textarea>
                        <input type="submit" class="msn_envoyer" name="send" value="ENVOYER"/>';
            }else{
                $v = '<textarea class="msn_chat" id="texto" name="texto" placeholder="Démarrer votre message ici..." style="color:white"></textarea>
                        <input type="submit" class="msn_envoyer" name="send" value="ENVOYER"/>';
            }
        }

        /** GERER LE NOMBRE DE MESSAGE A AFFICHER */
        $nbmsg_display = 10;
        $req = $idcom->prepare("SELECT COUNT(m.id) as nbmsg
        FROM messaging m
        WHERE (id_from, id_to) = (:id, :id1) 
        OR (id_from, id_to) = (:id1, :id)");
        $req->execute(array('id' => $_SESSION['auth']['Id'], 'id1' => $id_friend));
        $nbmsg = $req->fetch();

        $nbmsg_validate = 0;

        if(($nbmsg['nbmsg'] - $nbmsg_display) > 0){
            $nbmsg_validate = $nbmsg['nbmsg'] - $nbmsg_display;
        }

        $reqmessage = $idcom->prepare("SELECT * 
        FROM messaging m
        WHERE (id_from, id_to) = (:id, :id1)
        OR (id_from, id_to) = (:id1, :id)
        ORDER BY sending_date
        LIMIT $nbmsg_validate, $nbmsg_display");
        $reqmessage->execute(array(
            'id' => $_SESSION['auth']['Id'],
            'id1' => $id_friend
        ));
        $reqmessage = $reqmessage->fetchAll();

        $req = $idcom->prepare("UPDATE messaging
        SET state = 0 WHERE id_to = ? AND id_from = ?");
        $req->execute(array($_SESSION['auth']['Id'], $id_friend));
        
    }else{
        $color='color:black';
        if($_SESSION['auth']['mode']==1){
            $color='color:white';
        }
        $notdiscu = '<div style="position:absolute; top:45%;left:17%; text-align:center;'.$color.'">Aucune conversations</br>Choisir un ami à coté pour démarrer une nouvelle discution</div>';
    }
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Messagerie</title>
		<link rel="stylesheet" type="text/css" href="CSS/message4.css">
        <?php require_once("modeT.php"); ?>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="JS/connect.js"></script>
    </head>
<body>
    <header>
        <?php require_once("header.php"); ?>
	</header>

	<?php 
        require_once("side_left.php");
        require_once("side_right.php"); 
    ?>

	<article>

		<div class="msn">

			<div class="msn_title">
				<p>Messagerie</p>
                <img src="img/play.png" width="10" height="10" style="position:absolute;left:140px;top:38px">
                <p style="position:absolute;left:180px;top:20px;font-size:14px"><?php if(isset($namefriend)){ echo $namefriend['Name']." ".$namefriend['LastName'];} ?></p>
			</div>
            <?php if($_SESSION['auth']['mode']==0){ ?>
                 <input type="text" id="category" placeholder="Recherche un message..." class="search_amis" style="color:black">
            <?php }else{ ?>
                <input type="text" id="category" placeholder="Recherche un message..." class="search_amis">
            <?php } ?>
            <button  class="msgRecher" id="btnMsg">
                <?php if($_SESSION['auth']['mode']==0){ ?>
                <img src="img/loupe.svg" width="15" height="15">
                <?php }else{ ?>
                <img src="img/loupe.png" width="15" height="15">
                <?php } ?>
            </button>
           
            <div id="mesMsg" class="searching_msg">
                <div class="divMsg">
                    <div class="sortirPop">
                        <button id="displayNone">X</button>
                        <p class="nameMsg"><?php if(isset($namefriend)){ echo $namefriend['Name']." ".$namefriend['LastName'];} ?></p>
                    </div>
                    <div class="affichageMsg">
                        <div id='get'></div>
                    </div>
                </div>
            </div>

            <div id="user_details"></div>

			<div class="msn_text" >
                <?php if(isset($notdiscu)){ 
                        echo $notdiscu; 
                } else{ ?>
                <div class="msn_text_bloc" id="msg">
                    <?php
                        if($nbmsg['nbmsg'] > $nbmsg_display){ ?>
                        <button id="seemore" class="seemore_btn">Plus</button>
                    <?php } ?>

                    <div id="seemore_msg"></div>
                    <?php
                    foreach($reqmessage as $seemessage){
                        if($seemessage['id_from'] == $_SESSION['auth']['Id']){ ?>
                            <div class="text_right" id="text_right">
                                <?= nl2br($seemessage['message']) ?>
                                
                            </div>
                            
                        <?php } 
                        else{ ?>
                            <div class="text_left">
                                <?= nl2br($seemessage['message']) ?>
                                
                            </div>
                        <?php }
                    } ?>
                    <div id="display_message"></div>
                </div>
			</div>

			<form method="POST" id="sending">
              <?= $v ?>
			</form>
           <?php } ?>
            
 		</div>

	</article>

    <style>
        .highlight{
            background: yellow;
            font-weight: bold;
        }
    </style>
    
    <script>

        var msg = document.getElementById("mesMsg");
        var btnMsg = document.getElementById("btnMsg");
        var arretMsg = document.getElementById("displayNone");
    
        $(document).ready(function(){

            display_friend();
            setInterval(function(){
              display_friend();
            }, 5000);

            function display_friend(){
              $.ajax({
                url : 'display_friend.php',
                method : "POST",
                success: function(data){
                  $('#user_details').html(data);
                }
              });
            }

            var automsg = 0;
            automsg = clearInterval(automsg);
            automsg = setInterval(autoMsgCharge, 2000);


            <?php if(isset($id_friend)){ ?>

            document.getElementById('msg').scrollTop = document.getElementById('msg').scrollHeight;

            $('#sending').on("submit", function(e){
                e.preventDefault();
                var id;
                var message;
            
                id = <?= $id_friend ?>;
                message = document.getElementById('texto').value;
                document.getElementById('texto').value = '';
                
                if(id > 0 && message != ""){
                    $.ajax({
                        url : 'message_sending.php',
                        method : 'POST',
                        dataType : 'html',
                        data : {id: id, message: message},
                        
                        success : function(data){
                            $('#display_message').append(data); 
                            document.getElementById('msg').scrollTop = document.getElementById('msg').scrollHeight;
                        },
                    });
                }
            });

            
            function autoMsgCharge(){ 
                var id = <?= $id_friend ?>;
                if(id > 0){
                    $.ajax({
                        url : 'message_charging.php',
                        method : 'POST',
                        dataType : 'html',
                        data : {id: id},
                        
                        success : function(data){
                            if(data.trim() != ""){
                                $('#display_message').append(data);
                                document.getElementById('msg').scrollTop = document.getElementById('msg').scrollHeight;
                            }
                        },
                    });
                }
            }

            $('#btnMsg').click(function(){
                var input = $('#category').val();
                if(input != ''){
                    var id = <?= $id_friend ?>;
                    msg.style.display="block";
                    $.ajax({
                        type: 'GET',
                        url: 'search_message.php',
                        data: 'msg=' + input + '&id=' + id,
                        success: function(data){
                            if(data != ''){
                                $('#get').html(data);
                                $('#get div').find('span').each(function(){
                                    var content = $(this).text();
                                    var searchExp = new RegExp(input, 'ig');
                                    var matchs = content.match(searchExp);
                                    if(matchs){
                                    $(this).html(content.replace(searchExp, function(match){
                                        return "<span class='highlight'>" + match + "</span>";
                                    }));
                                    }
                                })
                               
                            }else{
                                document.getElementById('get').innerHTML = "<div>Aucun Resultat</div>"
                            }
                        }
                    });
                    arretMsg.onclick = function(){
                        msg.style.display="none";
                    }
                }
            });

            <?php
                if($nbmsg['nbmsg'] > $nbmsg_display){
            ?>

            var display = 0;

            $('#seemore').click(function(){
                var id;
                var elm;

                display += <?= $nbmsg_display ?>;
                id = <?= $id_friend ?>

                $.ajax({
                    url : 'seemore_message.php',
                    method : 'POST',
                    dataType : 'html',
                    data : {limit: display, id: id},
                    
                    success : function(data){  
                        $(data).hide().appendTo('#seemore_msg').fadeIn(2000);
                        document.getElementById('seemore_msg').removeAttribute('id'); 
                    },    
                }); 
            });

            <?php
                }
            }
            ?>


        });		
    </script>
</body>
</html>


	