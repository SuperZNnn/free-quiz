<?php

    include_once('../../config.php');

    if (!isset($_SERVER['HTTP_MY_CUSTOM_HEADER']) || $_SERVER['HTTP_MY_CUSTOM_HEADER'] !== '40028922') {
        header("HTTP/1.0 403 Forbidden");
        echo "Acesso proibido!";
        exit();
    }

    if (isset($_GET['id'])){
        $playerID = $_GET['id'];
        $playerType = $_GET['type'];

        if ($playerType == 2){
            $sql = "DELETE FROM playersonline WHERE fakePlayerID = '$playerID'";
            $result = $conexao->query($sql);
        }
        else if ($playerType == 1){
            $sql = "DELETE FROM playersonline WHERE idPlayer = '$playerID'";
            $result = $conexao->query($sql);
        }
    }

?>