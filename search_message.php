<?php
include('function.php');
session_start();
require_once "cnxpdo.php";
$idcom = connexpdo("network", "myparam");

if (!isset($_SESSION['auth'])) {
    header('Location: index.php');
    exit();
}

if (isset($_GET['msg']) && isset($_GET['id'])) {
    $msg = (string) htmlentities($_GET['msg']);
    $id = (int) htmlentities($_GET['id']);

    $reqmsg = $idcom->prepare("SELECT * FROM messaging m, user u
        WHERE u.Id = m.id_from
        AND (u.Id = id_from OR u.Id = id_to)
        AND ((id_from = :id AND id_to = :id1)
        OR (id_from = :id1 AND id_to = :id))
        AND message LIKE '%" . $msg . "%'
        ORDER BY sending_date DESC");

    $reqmsg->execute(array('id' => $_SESSION['auth']['Id'], 'id1' => $id));
    $res = $reqmsg->fetchAll();

    $output = '';
    if ($reqmsg->rowCount() > 0) {

        foreach ($res as $res) {
            $output .= '<div style="padding:20px;">
                            <img src="img/profil/' . $res['profil_photo'] . '" width="50" height="50" style="border-radius:100%;">
                            <p style="position:relative;margin-left:60px;margin-top:-50px;font-size:13px;color:white">' . $res['Name'] . " " . $res['LastName'] . '<span style="font-size:10px"> &nbsp; '.disDate($res['sending_date']).'</span></p>
                            <div style="position:relative;margin-left:60px;margin-top:-10px;width:80%;height:auto; overflow:hidden">
                             <span style="font-size:13px">' . $res['message'] . '</span>
                            </div>
                            </div>';
        }
    }
    echo $output;
}
