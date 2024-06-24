<?php

    include_once('../../config.php');

    if (!isset($_SERVER['HTTP_MY_CUSTOM_HEADER']) || $_SERVER['HTTP_MY_CUSTOM_HEADER'] !== '40028922') {
        header("HTTP/1.0 403 Forbidden");
        echo "Acesso proibido!";
        exit();
    }

    if (isset($_GET['getMaxMin'])){
        $quizID = $_GET['qid'];
    
        $sql = "SELECT MIN(id), MAX(id), COUNT(id) FROM playersonline WHERE quizID = '$quizID'";
        $result = $conexao->query($sql);
        $row = $result->fetch_assoc();
        $playersOnlineMin = $row['MIN(id)'];
        $playersOnlineMax = $row['MAX(id)'];
        $playersOnlineQuant = $row['COUNT(id)'];
    
        $data = array(
            "playersOnlineMin" => $playersOnlineMin,
            "playersOnlineMax" => $playersOnlineMax,
            "playersOnlineQuant" => $playersOnlineQuant
        );
    
        echo json_encode($data);
    }

    if (isset($_GET['playerExists'])){
        $quizID = $_GET['qid'];
        $suppID = $_GET['suppID'];

        $sql = "SELECT * FROM playersonline WHERE id = '$suppID' AND quizID = '$quizID'";
        $result = $conexao->query($sql);
        
        if (mysqli_num_rows($result) >= 1) {
            // Detectar tipo do player
            while ($row = $result->fetch_assoc()){
                $playerType = $row['playerType'];
                $playerName = $row['playerName'];
                $playerDesc = "";
                $profileImgDataUrl = '../../assets/images/profile.jpg';
                if ($playerType == '1'){
                    // Pegar desc
                    $playerID = $row['idPlayer'];

                    $sql = "SELECT * FROM users WHERE userid = '$playerID'";
                    $resultDesc = $conexao->query($sql);
                    $row = $resultDesc->fetch_assoc();
                    $playerDesc = $row['userdescrip'];
                    $playerProfile = $row['userprofile'];

                    if ($playerProfile != null){
                        $profileImgDataUrl = 'data:image/jpeg;base64,' . base64_encode($playerProfile);
                    }
                    else {
                        $profileImgDataUrl = '../../assets/images/profile.jpg';
                    }
                }
            }

            $data = array(
                "type" => $playerType,
                "name" => $playerName,
                "descrip" => $playerDesc,
                "profileImg" => $profileImgDataUrl
            );

            echo json_encode($data);
        }
    }
?>