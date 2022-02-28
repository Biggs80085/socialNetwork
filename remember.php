<?php
    include("function.php");
    include("cnxpdo.php");
    $idcom = connexpdo("network", "myparam");

    $q1 = "Quel est le nom de votre premier animal domestique ?";
    $q2 = "Où êtes-vous parti en lune de miel ?";
    $q3 = "Qui est votre livre préféré ?";
    
    $error = array();
    if(isset($_POST['valider'])){
        if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $error['email'] = "Votre Email n'est pas valide
            exemple : example@example.com";
        }if(empty($_POST['qsts'])){
            $error['qsts'] = "Veuillez choisir une question secréte";
        }if(empty($_POST['ansq'])){
            $error['ansq'] = "Veuillez donnez une réponse à votre question secréte";
        }
        else{
            $reqremember = $idcom->prepare('SELECT * FROM user WHERE (Email = ?) AND (question = ?) AND (ansewer = ?)');
            $reqremember->execute(array($_POST['email'], $_POST['qsts'], $_POST['ansq']));
            $user = $reqremember->fetch();
            if($user){
                $token_reset = str_random(30);
                $idcom->prepare("UPDATE user SET token_reset = ?, reset_at = NOW() WHERE Id = ?")->execute(array($token_reset, $user['Id']));
                header("Location: reset.php?id=".$user['Id']."&token=$token_reset");
                exit();
            }else{
                $error['user'] = "Aucun compte ne corespond à vos entré";
            }
        }
    }

?>


<!DOCTYPE html>
<html lang="fr">
  <head>
    <title>MySocialNetwork</title>
    <meta NAME="AUTHOR" CONTENT="ISMAIL">
    <meta NAME="KEYWORDS" CONTENT="PROJET MSN">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="CSS/remember2.css">
  </head>

  <body>
    <div class="header">
        <img class="logo" src="">
    </div>

    <div id="def">
        <h1>Recupération du Mot de passe</h1>
        <p> Remplir les champs à coté pour réinitialiser votre mot de passe </p>
    </div>

    <div>
        <img class="back" src="./img/back.png" alt="">
    </div>
    
    

    <div class="signup">
        <form action="" method="POST">

                <div class="form__div2">
                    <?php if (isset($_POST['valider'])){
                            if (isset($error['email'])){ ?>
                                <input type="text" class="form__input" style="border:1px solid red" placeholder=" " name="email">
                                <label for="" style="color:red" class="form__label">Email</label>
                            <?php }else{ ?>
                                <input type="text" class="form__input" placeholder=" " name="email" value="<?= $_POST['email'] ?>">
                                <label for="" class="form__label">Email</label>
                            <?php }}else{ ?>
                                <input type="text" class="form__input" placeholder=" " name="email">
                                <label for="" class="form__label">Email</label>
                            <?php } ?>
                </div>

              
                
                <?php if (isset($_POST['valider'])){
                            if (isset($error['qsts'])){ ?>
                                <div class="question" style="border:1px solid red">
                                    <select name="qsts">
                                        <option value="" selected="selected">--Question secréte--</option>
                                        <option value=<?= $q1 ?>><?= $q1 ?></option>
                                        <option value=<?= $q2 ?>><?= $q2 ?></option>
                                        <option value=<?= $q3 ?>><?= $q3 ?></option>
                                    </select>
                                </div>
                            <?php }else{ ?>
                                <div class="question">
                                    <select name="qsts">
                                        <option value="" <?php if($_POST['qsts'] == ''){ echo "selected='selected'";} ?>>--Question secréte--</option>
                                        <option value=<?php echo $q1; if($_POST['qsts'] == $q1){ echo "selected='selected'";} ?>><?= $q1 ?></option>
                                        <option value=<?php echo $q2; if($_POST['qsts'] == $q2){ echo "selected='selected'";} ?>><?= $q2 ?></option>
                                        <option value=<?php echo $q3; if($_POST['qsts'] == $q3){ echo "selected='selected'";} ?>><?= $q3 ?></option>
                                    </select>
                                </div>
                            <?php }}else{ ?>
                                <div class="question">
                                    <select name="qsts">
                                        <option value="" selected="selected">--Question secréte--</option>
                                        <option value=<?= $q1 ?>><?= $q1 ?></option>
                                        <option value=<?= $q2 ?>><?= $q2 ?></option>
                                        <option value=<?= $q3 ?>><?= $q3 ?></option>
                                    </select>
                                </div>
                            <?php } ?>


                
                <div class="answer">
                    <?php if (isset($_POST['valider'])){
                            if (isset($error['ansq'])){ ?>
                                <input type="text" class="form__input" style="border:1px solid red" placeholder=" " name="ansq">
                                <label for="" class="form__label" style="color:red">Réponse</label>
                            <?php }else{ ?>
                                <input type="text" class="form__input" placeholder=" " name="ansq">
                                <label for="" class="form__label">Réponse</label>
                            <?php }}else{ ?>
                                <input type="text" class="form__input" placeholder=" " name="ansq">
                                <label for="" class="form__label">Réponse</label>
                            <?php } ?>
                </div>
            </div>

            <div class="btn">
                <input type="submit" class="button" name="valider" value="Valider">
            </div>

        </form>
    </div>
    
    <div class="error">
        <?php
            foreach($error as $error => $key){
                echo "<p style='color:red; font-size:15px;'> $key </p>";
            }
        ?>
    </div>
    
  </body>
</html>
	



