<?php

    session_start();
    require 'function.php';
    include("cnxpdo.php");
    $idcom = connexpdo("network", "myparam");

    if(!isset($_SESSION['auth'])){
        header('Location: index.php');
        exit();
    }

    $current_time = date('Y-m-d H:i:s');
    $statement = $idcom->prepare("UPDATE user 
    SET connected = ?
    WHERE Id = ?");
    $statement->execute(array($current_time, $_SESSION['auth']['Id']));

?>