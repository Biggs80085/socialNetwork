<?php

    include('function.php');
    session_start();
    if(isset($_SESSION['auth'])){
        header('Location: account.php');
        exit();
    }
    
    $error = array();
    if(isset($_POST['seconnecter'])){ 
        if(!empty($_POST['email']) && !empty($_POST['mdp'])){
            if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                include("cnxpdo.php");
                $idcom = connexpdo("network", "myparam");
                $reqconct = $idcom->prepare("SELECT * FROM user WHERE email = ?");
                $reqconct->execute(array($_POST['email']));
                $user = $reqconct->fetch();
                if($user){
                    if(password_verify($_POST['mdp'], $user['Pw'])){
                        $_SESSION['auth'] = $user;
                        header('Location: account.php');
                        exit();
                    }else{
                        $error[3] = 1;
                    }
                }else{
                    $error[2] = 1;
                }
            }else{
                $error[1] = 1;
            }
        }else{
            $error[0] = 1;
        }    
    }

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>MySocialNetwork</title>
    <link rel="stylesheet" href="CSS/index.css">
  </head>
  <body>

    <div id="header">
        <img class="logo" src="">
    </div>

    <div id="def">
        <h1>MySocialNetwork</h1>
        <p>
            Avec MySocialNetwork,<br>
            partagez et rester en contacte avec votre entourage.
        </p>
    </div>

    <img class="back" src="./img/back.png">

    <div id="id">
        <form action="" method="POST">
            <h2>Identification</h2>
            <div class="form__div">
                <input type="text" class="form__input" placeholder=" " name="email">
                <label for="" class="form__label">Email</label>
            </div>

            <div class="form__div">
                <input type="password" class="form__input" placeholder=" " name="mdp">
                <label for="" class="form__label">Mot de passe</label>
            </div>

            <button class="rippel" name="seconnecter">Se Connecter</button>

            <?php if(isset($_POST['seconnecter'])){
                    if(isset($error[0])){?>
                        <p style="position:absolute; top:-10px; left:70px;color:red">Vous devez remplir les champs</p>
                    <?php }
                    if(isset($error[1])){?>
                        <p style="position:absolute; top:-30px; color:red">E-mail doit être sous la forme : example@example.com</p>
                    <?php }
                    if(isset($error[2])){?>
                        <p style="position:absolute; top:-10px; left:95px;color:red">Ce compte n'existe pas</p>
                    <?php }
                    if(isset($error[3])){?>
                        <p style="position:absolute; top:-10px; left:30px;color:red">Mot de passe incorrect, Veuillez réessayer</p>
                    <?php }
                 } ?>
        </form>

        <p class="inscription">Pas de compte <a id="l-ins" href="register.php">S'inscrire</a></p>
        <p><a id="restor" href="remember.php">Mot de passe oublier ?</a></p>

    </div>

  </body>
</html>