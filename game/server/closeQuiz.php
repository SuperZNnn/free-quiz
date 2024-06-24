<?php

    include_once("../../config.php");

    if (!isset($_SERVER['HTTP_MY_CUSTOM_HEADER']) || $_SERVER['HTTP_MY_CUSTOM_HEADER'] !== '40028922') {
        header("HTTP/1.0 403 Forbidden");
        echo "Acesso proibido!";
        exit();
    }

    if (isset($_GET['closeQuiz'])){
        
        $quizID = $_GET['qid'];
        echo $quizID;

        // Forçar reset nos players Online
        $sql = "UPDATE freequizes SET quizIsOpenOnline = 0 WHERE id = '$quizID'";
        $result = $conexao->query($sql);

        $sql = "DELETE FROM playersonline WHERE quizID = '$quizID'";
        $result = $conexao->query($sql);

        $sql = "DELETE FROM kickedregplayers WHERE quizId = '$quizID'";
        $result = $conexao->query($sql);
    }

    if (isset($_GET['detectClose'])){
        $quizID = $_GET['qid'];

        $sql = "SELECT quizIsOpenOnline FROM freequizes WHERE id = '$quizID'";
        $result = $conexao->query($sql);
        $row = $result->fetch_assoc();
        $isOpen = $row['quizIsOpenOnline'];

        echo $isOpen;
    }
?>