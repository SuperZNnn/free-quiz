<?php

    if (!isset($_SERVER['HTTP_MY_CUSTOM_HEADER']) || $_SERVER['HTTP_MY_CUSTOM_HEADER'] !== '40028922') {
        header("HTTP/1.0 403 Forbidden");
        echo "Acesso proibido!";
        exit();
    }

    include_once('../config.php');

    $quizID = $_GET['quizid'];

    if (isset($_GET['full'])){
        // Nome e tipo
        $quizName = $_POST['qName'];
        $quizImageLink = $_POST['Img'];
        $quizType = $_GET['qType'];

        $sql = "UPDATE freequizes SET quiz_name = '$quizName', quiz_type = '$quizType' WHERE id = '$quizID'";
        $result = $conexao->query($sql);
    }
    if (isset($_GET['imageUpdate'])){
        $file = $_FILES['image']['tmp_name'];
        $fileContent = file_get_contents($file);

        $stmt = $conexao->prepare("UPDATE freequizes SET quizImage = ? WHERE id = ?");
        $stmt->bind_param("si", $fileContent, $quizID);

        if ($stmt->execute()) {
            echo 'Profile picture updated successfully.';
        } else {
            echo 'Error: ' . $stmt->error;
        }

        $stmt->close();
    }
    if (isset($_GET['onlyType'])){
        $quizType = $_GET['qType'];

        $sql = "UPDATE freequizes SET quiz_type = '$quizType' WHERE id = '$quizID'";
        $result = $conexao->query($sql);
    }

?>