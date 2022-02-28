<?php
    include("function.php");
    include("cnxpdo.php");
    $idcom = connexpdo("network", "myparam");
    session_start();

    $q1 = "Quel est le nom de votre premier animal domestique ?";
    $q2 = "Où êtes-vous parti en lune de miel ?";
    $q3 = "Qui est votre livre préféré ?";


    $error = array();
    if(isset($_POST['signin'])){
        if(empty($_POST['name']) || (regExpName($_POST['name']) == false)){
            $error['name'] = "Votre Nom n'est pas valide
                            Il dois contenir que des lettre A-Z a-z";
        }
        if(empty($_POST['lastname']) || (regExpName($_POST['lastname']) == false)){
            $error['lastname'] = "Votre Prenom n'est pas valide
                            Il dois contenir que des lettre A-Z a-z";
        }
        if(empty($_POST['sexe'])){
            $error['sexe'] = "Choisir votre sexe";
        }
        else{
            if($_POST['sexe'] == 'Homme'){
                $avatar = "default_men.png";
                $cover_photo = "default_cover.png";
            }elseif($_POST['sexe'] == 'Femme'){
                $avatar = "default_women.png";
                $cover_photo = "default_cover.png";
            }
        }

        
        if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $error['email'] = "Votre Email n'est pas valide
            exemple : example@example.com";
        }
        else{
            $set = $idcom->prepare('SELECT id FROM user WHERE Email = ?');
            $set->execute(array($_POST['email']));
            $emailset = $set->rowCount();
            if($emailset){
                $error['email'] = "Un compte existe deja";
            }
        }
        if(empty($_POST['pwd']) || ($_POST['pwd'] != $_POST['cpwd']) || (regExpPwd($_POST['pwd']) == false)){
            $error['pwd'] = "Erreur mot de passe
            le mot de passe doit être entre 8-12 contenir au minimune majuscule, une minuscule, un chiffre, un caractère spéciale";
        } 

        if(empty($_POST['date']) || (old($_POST['date']) < 13)){
            $error['date'] = "L'age dois étre supérieur à 13 ans";
        }

        if(empty($_POST['qsts'])){
            $error['qsts'] = "Veuillez choisir une question secréte";
        }

        if(empty($_POST['ansq']) || strlen($_POST['ansq']) > 30){
            $error['ansq'] = "Veuillez donnez une réponse à votre question secréte
                                Nombre de caractéres maximum : 30";
        }
        
        if(empty($error)){ 
            $pwd = password_hash($_POST['pwd'], PASSWORD_BCRYPT);
            $date = strtotime($_POST['date']);
            if($date){
                $birth = date('Y-m-d', $date);
            }
            $insertuser = $idcom->prepare("INSERT INTO user(Name, LastName, Birth, Sexe, Email, Pw, profil_photo, cover_photo, question, ansewer) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insertuser->execute(array(strtoupper($_POST['name']), ucfirst(strtolower($_POST['lastname'])), $birth, $_POST['sexe'], $_POST['email'], $pwd, $avatar, $cover_photo, $_POST['qsts'], $_POST['ansq']));
            $user_id = $idcom->lastInsertId();
        
            header('Location: index.php');
            exit();
        }      
    }
?>

<html lang="fr">
  <head>
    <title>MySocialNetwork-Inscription</title>
    <meta NAME="AUTHOR" CONTENT="ISMAIL">
    <meta NAME="KEYWORDS" CONTENT="PROJET MSN">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="CSS/register.css">
  </head>
  <body>
    <div class="header">
        <img class="logo" src="">
    </div>

    <div id="def">
        <h1>Inscription</h1>
    </div>

    <div>
        <img class="back" src="./img/back.png" alt="">
    </div>
    
    <div class="signup">
        <form action="" method="POST">

            <div class="name_last">
                <div class="form__div">
                    <?php if (isset($_POST['signin'])){
                            if (isset($error['name'])){ ?>
                                <input type="text" class="form__input" style="border:1px solid red" placeholder=" " name="name">
                                <label for="" class="form__label" style="color:red">Nom</label>
                                
                           <?php }else{ ?>
                                <input type="text" class="form__input" placeholder=" " name="name" value="<?= $_POST['name'] ?>">
                                <label for="" class="form__label">Nom</label>
                            <?php }}else{ ?>
                                <input type="text" class="form__input" placeholder=" " name="name">
                                <label for="" class="form__label">Nom</label>
                            <?php } ?>
                </div>
                
                <div class="form__div">
                     <?php if (isset($_POST['signin'])){
                            if (isset($error['lastname'])){ ?>
                                <input type="text" class="form__input" style="border:1px solid red" id="pre" placeholder=" " name="lastname">
                                <label for="" class="form__label" style="color:red">Prenom</label>
                            <?php }else{ ?>
                                <input type="text" class="form__input" id="pre" placeholder=" " name="lastname" value="<?= $_POST['lastname'] ?>">
                                <label for="" class="form__label">Prenom</label>
                            <?php }}else{ ?>
                                <input type="text" class="form__input" id="pre" placeholder=" " name="lastname">
                                <label for="" class="form__label">Prenom</label>
                            <?php } ?>
                </div>

            </div>
            
            <div class="date_sexe">
                <div class="form__div">
                    <?php if (isset($_POST['signin'])){
                            if (isset($error['date'])){ ?>
                                <input type="date" class="form__input" style="border:1px solid red" name="date" min="1921-01-01" max="<?= date('Y-m-d') ?>">
                                <label for="" class="form__label" style="color:red">Date de naissance</label>
                            <?php }else{ ?>
                                <input type="date" class="form__input" name="date" min="1921-01-01" max="<?= date('Y-m-d') ?>" value="<?= $_POST['date'] ?>">
                                <label for="" class="form__label">Date de naissance</label>
                            <?php }}else{ ?>
                                <input type="date" class="form__input" name="date" min="1921-01-01" max="<?= date('Y-m-d') ?>">
                                <label for="" class="form__label">Date de naissance</label>
                            <?php } ?>
                </div>
                <?php if (isset($_POST['signin'])){
                            if (isset($error['sexe'])){ ?>
                                <div class="sex" style="border:1px solid red">
                                    <select name="sexe">
                                        <option value="" selected="selected">--Sexe--</option>
                                        <option value="Femme">Femme</option>
                                        <option value="Homme">Homme</option> 
                                    </select>
                                </div>
                            <?php }else{ ?>
                                <div class="sex">
                                    <select name="sexe">
                                        <option value="" <?php if($_POST['sexe'] == ''){ echo "selected='selected'";} ?>>--Sexe--</option>
                                        <option value="Femme" <?php if($_POST['sexe'] == 'Femme'){ echo "selected='selected'";} ?>>Femme</option>
                                        <option value="Homme" <?php if($_POST['sexe'] == 'Homme'){ echo "selected='selected'";} ?>>Homme</option>
                                    </select>
                                </div>
                            <?php }}else{ ?>
                                <div class="sex">
                                    <select name="sexe">
                                        <option value="" selected="selected">--Sexe--</option>
                                        <option value="Femme">Femme</option>
                                        <option value="Homme">Homme</option>
                                    </select>
                                </div>
                            <?php } ?>
            </div>

            <div class="identifiant">
                <div class="form__div2">
                    <?php if (isset($_POST['signin'])){
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

                <div class="form__div2">
                    <?php if (isset($_POST['signin'])){
                            if (isset($error['pwd'])){ ?>
                                <input type="password" class="form__input" style="border:1px solid red" placeholder=" " name="pwd">
                                <label for="" class="form__label" style="color:red">Mot de passe</label>
                            <?php }else{ ?>
                                <input type="password" class="form__input" placeholder=" " name="pwd">
                                <label for="" class="form__label">Mot de passe</label>
                            <?php }}else{ ?>
                                <input type="password" class="form__input" placeholder=" " name="pwd">
                                <label for="" class="form__label">Mot de passe</label>
                            <?php } ?>
                </div>
                <div class="form__div2">
                    <?php if (isset($_POST['signin'])){
                            if (isset($error['pwd'])){ ?>
                                <input type="password" class="form__input" style="border:1px solid red" placeholder=" " name="cpwd">
                                <label for="" class="form__label" style="color:red">Conformation mot de passe</label>
                            <?php }else{ ?>
                                <input type="password" class="form__input" placeholder=" " name="cpwd">
                                <label for="" class="form__label">Conformation mot de passe</label>
                            <?php }}else{ ?>
                                <input type="password" class="form__input" placeholder=" " name="cpwd">
                                <label for="" class="form__label">Conformation mot de passe</label>
                            <?php } ?>
                </div>
            </div>
                <?php   if (isset($_POST['signin'])){
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
                            <?php }
                        }else{ ?>
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
                <?php if (isset($_POST['signin'])){
                        if (isset($error['ansq'])){ ?>
                            <input type="text" class="form__input" style="border:1px solid red" placeholder=" " name="ansq">
                            <label for="" class="form__label" style="color:red">Réponse</label>
                        <?php }else{ ?>
                            <input type="text" class="form__input" placeholder=" " name="ansq">
                            <label for="" class="form__label">Réponse</label>
                        <?php }
                        }else{ ?>
                            <input type="text" class="form__input" placeholder=" " name="ansq">
                            <label for="" class="form__label">Réponse</label>
                        <?php } ?>
                </div>
            </div>

            <div class="btn">
                <input type="submit" class="button" name="signin" value="S'inscrire">
                <a href="index.php" class="btnann">Annuler</a>
            </div>

        </form>
    </div>
    
    <div class="error">
        <?php
            foreach($error as $error => $key){
                echo "<p style='color:red; font-size:13px;'> $key </p>";
            }
        ?>
    </div>
    
  </body>
</html>
	



