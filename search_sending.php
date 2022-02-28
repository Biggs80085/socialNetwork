<?php
    session_start();
    require 'function.php';
    include("cnxpdo.php");
    $idcom = connexpdo("network", "myparam");

    if(!isset($_SESSION['auth'])){
        header('Location: index.php');
        exit();
    }

    if(isset($_GET['search'])){
        $res = (String) trim($_GET['search']);
        $type = trim($_GET['type']);
        if($type == 1){
            $requete_search = $idcom->query("SELECT * FROM user 
            WHERE Name LIKE '%".$res."%'
            OR LastName LIKE '%".$res."%'
            OR CONCAT(Name, ' ', LastName) LIKE '%".$res."%'
            LIMIT 5"); 
            $requete_search = $requete_search->fetchAll();

            foreach($requete_search as $res){ ?>
                <a href="profil.php?id=<?=$res['Id']?>" style="text-decoration:none;"><div class="resultat_searchBar">
                    <?php if($_SESSION['auth']['mode']==0){ ?>
                        <img src='img/profil/<?= $res['profil_photo'] ?>' width="40" height="40">
                        <p style="margin-top:-35px;margin-left:50px;color:black"><?=$res['Name'] . " " . $res['LastName'] ?></p>
                    <?php }else{ ?>
                        <img src='img/profil/<?= $res['profil_photo'] ?>' width="40" height="40">
                        <p style="color:white"><?=$res['Name'] . " " . $res['LastName'] ?></p>
                    <?php } ?>
                </div></a>
            <?php
            }

        }else{
            $requete_search = $idcom->prepare("SELECT * FROM publication p, user u
            WHERE u.Id = p.id_poster 
            AND (p.id_poster in (
                SELECT IF (f.id_request = :id, f.id_receive, f.id_request)
                FROM friend f, publication p
                WHERE p.id_poster = IF (f.id_request = :id, f.id_receive, f.id_request)
                AND (f.id_request = :id OR f.id_receive = :id)
                AND f.state = 0 AND f.id_blocker IS NULL)
                OR p.id_poster = :id)
            AND p.content LIKE '%".$res."%'
            AND (p.confi_post <> 2 OR p.id_poster = :id)
            LIMIT 5");
            $requete_search->execute(array('id' => $_SESSION['auth']['Id'])); 
            $requete_search = $requete_search->fetchAll();

            foreach($requete_search as $res){
                
            ?>
                <a href="post.php?id=<?=$res['id_pub']?>" style="text-decoration:none;"><div class="resultat_searchBar">
                    <?php if($_SESSION['auth']['mode']==0){ ?>
                        <img src='img/profil/<?= $res['profil_photo'] ?>' width="40" height="40">
                        <p style="position:relative;margin-top:-30px;left:50px;color:black;"><?= $res['Name']." ".$res['LastName'] ?></p>
                        <p style="margin-left:50px;color:black;"><?=$res['content']?></p>
                    <?php }else{ ?>
                        <img src='img/profil/<?= $res['profil_photo'] ?>' width="40" height="40">
                        <p style="position:relative;margin-top:-30px;left:50px;color:white"><?= $res['Name']." ".$res['LastName'] ?></p>
                        <p style="margin-left:50px;color:white"><?=$res['content']?></p>
                    <?php } ?>
                    
                </div></a>
            <?php
            }
        }
        
    }


    


?>