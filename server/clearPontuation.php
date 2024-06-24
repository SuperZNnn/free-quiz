<?php

    include_once('../config.php');

    if (!isset($_SERVER['HTTP_MY_CUSTOM_HEADER']) || $_SERVER['HTTP_MY_CUSTOM_HEADER'] !== '40028922') {
        header("HTTP/1.0 403 Forbidden");
        echo "Acesso proibido!";
        exit();
    }

    if (isset($_GET['clearPontuation'])){
        $quizID = $_GET['qid'];

        $sql = "DELETE FROM pontuation WHERE quizID = '$quizID'";
        $result = $conexao->query($sql);
    }

?>