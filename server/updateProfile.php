<?php

    include_once('../config.php');
    session_start();

    if (!isset($_SERVER['HTTP_MY_CUSTOM_HEADER']) || $_SERVER['HTTP_MY_CUSTOM_HEADER'] !== '40028922') {
        header("HTTP/1.0 403 Forbidden");
        echo "Acesso proibido!";
        exit();
    }

    if (isset($_GET['updateName'])){
        $userID = $_SESSION['user_id'];

        $value = $_POST['value'];
        $sql = "UPDATE users SET username = '$value' WHERE userid = '$userID'";
        $result = $conexao->query($sql);

        echo 'done';
    }
    if (isset($_GET['updateDesc'])){
        $userID = $_SESSION['user_id'];

        $value = $_POST['value'];
        $sql = "UPDATE users SET userdescrip = '$value' WHERE userid = '$userID'";
        $result = $conexao->query($sql);

        echo 'done';
    }
    if (isset($_GET['updateProfile']) && isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK){
        $userID = $_SESSION['user_id'];
        $file = $_FILES['image']['tmp_name'];
        $fileContent = file_get_contents($file);

        $stmt = $conexao->prepare("UPDATE users SET userprofile = ? WHERE userid = ?");
        $stmt->bind_param("si", $fileContent, $userID);

        if ($stmt->execute()) {
            echo 'Profile picture updated successfully.';
        } else {
            echo 'Error: ' . $stmt->error;
        }

        $stmt->close();
    }

?>