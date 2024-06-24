<?php

    include_once('../../config.php');
    session_start();

    if (!isset($_SERVER['HTTP_MY_CUSTOM_HEADER']) || $_SERVER['HTTP_MY_CUSTOM_HEADER'] !== '40028922') {
        header("HTTP/1.0 403 Forbidden");
        echo "Acesso proibido!";
        exit();
    }

    if (isset($_GET['userid']) && isset($_GET['points']) && isset($_GET['quizid']) && isset($_GET['quiztype'])) {
        $userid = $_GET['userid'];
        $points = $_GET['points'];
        $quiz_id = $_GET['quizid'];
        $quiztype = $_GET['quiztype'];

        //Pegar username
        $sql = "SELECT * FROM users WHERE userID = '$userid'";
        $result = $conexao->query($sql);
        $row = $result->fetch_assoc();
        $username = $row["username"];

        $result = mysqli_query($conexao, "UPDATE freequizes SET playedTimes = (playedTimes + 1) WHERE id = '$quiz_id'");
        $result = mysqli_query($conexao, "INSERT INTO pontuation(quizID,userName,userID,quizType,userPontuation) VALUES ('$quiz_id','$username','$userid','$quiztype','$points')");
        echo "done";

    }

?>