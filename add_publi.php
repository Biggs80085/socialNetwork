<?php
    session_start();
    require 'function.php';
    include("cnxpdo.php");
    $idcom = connexpdo("network", "myparam");

    if(!isset($_SESSION['auth'])){
        exit();
    }
    
    /** INSERE UNE NOUVELLE PUBLICATION */
    $confi = 0;
    if($_POST['confi'] == "Amis"){
      $confi = 1;
    }elseif($_POST['confi'] == "Prive"){
      $confi = 2;
    }
    if(!empty($_FILES['addimg']['name'])){
      $maxWeigth = 2000000;
      if($_FILES['addimg']['size'] < $maxWeigth){
        $fileExt = explode('.', $_FILES['addimg']['name']);
        $fileActualExt = strtolower(end($fileExt));
        $validExtension = array('jpg', 'jpeg', 'gif', 'png');
        
        if(in_array($fileActualExt, $validExtension)){
          $fileNameNew = uniqid('', true).".".$fileActualExt;
          $fileDestintion = 'img/post/'.$fileNameNew;
          move_uploaded_file($_FILES['addimg']['tmp_name'], $fileDestintion);
        }
      }
    }
  
    $post_date = date("Y-m-d H:i:s");
    $reqinsert = $idcom->prepare("INSERT INTO publication (id_poster, content, photo, post_date, confi_post) VALUES (?, ?, ?, ?, ?)");
    $reqinsert->execute(array($_SESSION['auth']['Id'], $_POST['whatsup'], $fileNameNew, $post_date, $confi));
    /** FIN INSERE UNE NOUVELLE PUBLICATION */

?>


