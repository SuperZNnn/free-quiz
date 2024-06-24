<?php

    include_once('../config.php');

    if (!isset($_SERVER['HTTP_MY_CUSTOM_HEADER']) || $_SERVER['HTTP_MY_CUSTOM_HEADER'] !== '40028922') {
        header("HTTP/1.0 403 Forbidden");
        echo "Acesso proibido!";
        exit();
    }

    if (isset($_GET['qid'])){
        $quizID = $_GET['qid'];

        // Deletar Perguntas
        $sql = "DELETE FROM freequizquestions WHERE quizID = '$quizID'";
        $result = $conexao->query($sql);

        // Deletar Pontuações
        $sql = "DELETE FROM pontuation WHERE quizID = '$quizID'";
        $result = $conexao->query($sql);

        // Deletar Quiz
        $sql = "DELETE FROM freequizes WHERE id = '$quizID'";
        $result = $conexao->query($sql);
    }

?>