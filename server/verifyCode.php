<?php

    include_once('../config.php');

    if (!isset($_SERVER['HTTP_MY_CUSTOM_HEADER']) || $_SERVER['HTTP_MY_CUSTOM_HEADER'] !== '40028922') {
        header("HTTP/1.0 403 Forbidden");
        echo "Acesso proibido!";
        exit();
    }

    if (isset($_GET['supCode'])){
        $suppostID = $_GET['supCode'];

        $sql = "SELECT * FROM freequizes WHERE id = '$suppostID'";
        $result = $conexao->query($sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = $result->fetch_assoc()) {
                $quizType = $row['quiz_type'];
                $quizIsOpenOnline = $row['quizIsOpenOnline'];
            }

            if ($quizType == 1 && $quizIsOpenOnline >= 1) {echo "goMultiplayer"; }
            else if ($quizType == 1 && $quizIsOpenOnline == 0) { echo "unavailable"; }
            else if ($quizType == 3) { echo "unavailable"; }
            else if ($quizType == 2) { echo "askLogin"; }
        }
        else {
            echo "false";
        }
    }
    else{
        echo "Erro";
    }

?>