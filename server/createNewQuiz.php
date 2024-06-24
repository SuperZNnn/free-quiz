<?php

    include_once('../config.php');

    if (!isset($_SERVER['HTTP_MY_CUSTOM_HEADER']) || $_SERVER['HTTP_MY_CUSTOM_HEADER'] !== '40028922') {
        header("HTTP/1.0 403 Forbidden");
        echo "Acesso proibido!";
        exit();
    }

    if (isset($_GET['ownerID'])){
        $qName = $_POST['qName'];
        $qType = $_POST['qType'];
        $ownerID = $_GET['ownerID'];

        $result = mysqli_query($conexao, "INSERT INTO freequizes (quiz_name,quiz_type,ownerID) VALUES ('$qName','$qType','$ownerID')");

        $sql = "SELECT id FROM freequizes WHERE quiz_name = '$qName' AND quiz_type = '$qType' AND ownerID = '$ownerID'";
        $result = $conexao->query($sql);

        $row = $result->fetch_assoc();
        $createdID = $row['id'];

        echo $createdID;
    }

?>