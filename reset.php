<?php
    
    if(isset($_GET['id']) && isset($_GET['token'])){
        $id = htmlentities($_GET['id']);
        $token = htmlentities($_GET['token']);

        include("function.php");
        include("cnxpdo.php");
        $idcom = connexpdo("network", "myparam");

        $reqverif = $idcom->prepare("SELECT * FROM user
        WHERE Id = ? AND token_reset = ?
        AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)");
        $reqverif->execute(array($id, $token));
        $verif = $reqverif->fetch();

        if($verif){
            if(!empty($_POST)){
                if(!empty($_POST['pwd']) && $_POST['pwd'] == $_POST['cpwd'] && regExpPwd($_POST['pwd'])){
            
                    $pwd = password_hash($_POST['pwd'], PASSWORD_BCRYPT);
                    $requp = $idcom->prepare("UPDATE user SET Pw = ?, token_reset = NULL, reset_at = NULL
                    WHERE Id = ?");
                    $requp->execute(array($pwd, $id));
                    session_start();
                    $_SESSION['auth'] = $verif;
                    header('Location: account.php');
                    exit;
                }else{
                    $error = "Erreur mot de passe
					le mot de passe doit être entre 8-12 contenir <br> au minimune majuscule, une minuscule,  un chiffre, un caractère spéciale";
                }
            }
        }else{
            header('Location: index.php');
            exit();
        }
        
    }else{
        header('Location: index.php');
        exit();
    }


?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>Réinitialiser Mot de passe</title>
    <link rel="stylesheet" href="CSS/reset.css">
  </head>
  <body>
    <div id="header">
        <img class="logo" src="">
    </div>

    <div id="def">
        <h1>Réinitialiser Mot de passe</h1>
        <p>
            Réinitialiser votre mot de passe,<br>
            remplire les champs a coté
        </p>
    </div>

        <img class="back" src="./img/back.png">

    <div id="id">
        <form method="POST">
            <h2>Réinitialiser</h2>
            <?php if(isset($_POST['valider'])){
                if(isset($error)){ ?>
                    <div class="form__div">
                        <input type="password" class="form__input"  name="pwd" style="border:1px solid red">
                        <label for="" style="color:red" class="form__label">Nouveau mot de passe</label>
                    </div>

                    <div class="form__div">
                        <input type="password" class="form__input" style="border:1px solid red" name="cpwd">
                        <label for="" style="color: red" class="form__label">Confirmation du mot de passe</label>
                    </div>
                <?php }else{ ?>
                    <div class="form__div">
                        <input type="password" class="form__input"  name="pwd" >
                        <label for=""  class="form__label">Nouveau mot de passe</label>
                    </div>

                    <div class="form__div">
                        <input type="password" class="form__input"  name="cpwd">
                        <label for=""  class="form__label">Confirmation du mot de passe</label>
                    </div>
            <?php } }else{ ?>
                <div class="form__div">
                        <input type="password" class="form__input"  name="pwd" >
                        <label for=""  class="form__label">Nouveau mot de passe</label>
                    </div>

                    <div class="form__div">
                        <input type="password" class="form__input"  name="cpwd">
                        <label for=""  class="form__label">Confirmation du mot de passe</label>
                    </div>
            <?php } ?>

            <input class="rippel" type="submit" name="valider" value="Changer">

        </form>
    </div>
    <?php if(isset($error)){ ?>
    <div class="error">
        <p style='color:red; font-size:13px;'> <?= $error ?> </p>
    </div>
    <?php } ?>
    
  </body>
</html>
