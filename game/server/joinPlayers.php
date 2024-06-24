<?php

    include_once('../../config.php');

    if (!isset($_SERVER['HTTP_MY_CUSTOM_HEADER']) || $_SERVER['HTTP_MY_CUSTOM_HEADER'] !== '40028922') {
        header("HTTP/1.0 403 Forbidden");
        echo "Acesso proibido!";
        exit();
    }

    if (isset($_POST['name'])){
        $userName = $_POST['name'];
        $userType = $_GET['type'];
        $quizID = $_GET['qid'];

        if ($userType == 2){
            // Pegar o maior FakeId
            $sql = "SELECT MAX(fakePlayerID) FROM playersonline WHERE quizID = '$quizID'";
            $result = $conexao->query($sql);
            $row = $result->fetch_assoc();
            $fakeMax = $row['MAX(fakePlayerID)'];
            $nextFake = intval($fakeMax + 1);

            // Join no player "falso"
            $sql = "INSERT INTO playersonline(quizID,playerName,playerType,fakePlayerID,respostaEscolhida) VALUES ('$quizID','$userName','$userType','$nextFake',0)";
            $result = $conexao->query($sql);
            echo $nextFake;
        }
        else if ($userType == 1) {
            session_start();
            $userId = $_SESSION['user_id'];

            $sql = "INSERT INTO playersonline(quizID,playerName,playerType,idPlayer,respostaEscolhida) VALUES ('$quizID','$userName','$userType','$userId',0)";
            $result = $conexao->query($sql);

            $sql = "SELECT * FROM kickedregplayers WHERE userId = '$userId' AND quizId = '$quizID'";
            $result = $conexao->query($sql);

            if (mysqli_num_rows($result) > 0) {
                $row = $result->fetch_assoc();
                $points = $row['pontuation'];

                $sql = "UPDATE playersonline SET tempPoints = '$points' WHERE idPlayer = '$userId' AND quizID = '$quizID'";
                $result = $conexao->query($sql);
                $sql = "DELETE FROM kickedregplayers WHERE userId = '$userId' AND quizId = '$quizID'";
                $result = $conexao->query($sql);
            }
        }
    }

?>