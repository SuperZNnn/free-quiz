<?php
    if (!isset($_SERVER['HTTP_MY_CUSTOM_HEADER']) || $_SERVER['HTTP_MY_CUSTOM_HEADER'] !== '40028922') {
        header("HTTP/1.0 403 Forbidden");
        echo "Acesso proibido!";
        exit();
    }

    include_once("../../config.php");

    if (isset($_GET["quiz_id"]) && isset($_GET["button"]) && isset($_GET["question"])) {
        $quiz_id = $_GET['quiz_id'];
        $button = $_GET['button'];
        $question = $_GET['question'];

        $sql = "SELECT * FROM freequizquestions WHERE quizID = '$quiz_id' AND questionNUM = '$question' AND questionRESPOSTACERTA = '$button'";
        $result = $conexao->query($sql);

        if (mysqli_num_rows($result) < 1) {
            echo "false";
        } else {
            echo "true";
        }
    } else {
        echo "Erro";
    }
    
?>