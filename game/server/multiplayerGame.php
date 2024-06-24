<?php

    include_once('../../config.php');

    if (!isset($_SERVER['HTTP_MY_CUSTOM_HEADER']) || $_SERVER['HTTP_MY_CUSTOM_HEADER'] !== '40028922') {
        header("HTTP/1.0 403 Forbidden");
        echo "Acesso proibido!";
        exit();
    }
    
    if (isset($_GET['qid'])){
        $quizID = $_GET['qid'];
    }

    if (isset($_GET['resetBt'])){
        // Resetar Info
        $sql = "UPDATE freequizes SET quizIsOpenOnline = 1 WHERE id = '$quizID'";
        $result = $conexao->query($sql);

        $sql = "UPDATE freequizes SET onlineAtualQuestion = 0 WHERE id = '$quizID'";
        $result = $conexao->query($sql);
    }
    if (isset($_GET['advance'])){
        // Avançar quiz
        $sql = "UPDATE freequizes SET quizIsOpenOnline = 2 WHERE id = '$quizID'";
        $result = $conexao->query($sql);

        $sql = "UPDATE playersonline SET respostaEscolhida = 0 WHERE quizID = '$quizID'";
        $result = $conexao->query($sql);


    }
    if (isset($_GET['revealChooses'])){
        // Mostrar para players
        $sql = "UPDATE freequizes SET quizIsOpenOnline = 3 WHERE id = '$quizID'";
        $result = $conexao->query($sql);

        // Mostrar quantidade no adm
        $sql = "SELECT respostaEscolhida, COUNT(*) AS count FROM playersonline WHERE quizID = '$quizID' AND respostaEscolhida IN (1, 2, 3, 4) GROUP BY respostaEscolhida";
        $result = $conexao->query($sql);
        $counts = array();

        while ($row = $result->fetch_assoc()) {
            $respostaEscolhida = $row['respostaEscolhida'];
            $count = $row['count'];
            
            switch ($respostaEscolhida) {
                case 1:
                    $counts['red'] = $count;
                    break;
                case 2:
                    $counts['blue'] = $count;
                    break;
                case 3:
                    $counts['green'] = $count;
                    break;
                case 4:
                    $counts['yellow'] = $count;
                    break;
            }
        }
        $counts = array_merge(['red' => 0, 'blue' => 0, 'green' => 0, 'yellow' => 0], $counts);
        echo json_encode($counts);

    }
    if (isset($_GET['testLevel'])){
        // Retornar nível atual
        $sql = "SELECT quizIsOpenOnline, onlineAtualQuestion FROM freequizes WHERE id = '$quizID'";
        $result = $conexao->query($sql);
        $row = $result->fetch_assoc();
        $returnLevel = $row['quizIsOpenOnline'];
        $atualQuestion = $row['onlineAtualQuestion'];

        $returnInfo = array(
            "state" => $returnLevel,
            "atualLevel" => $atualQuestion
        );

        echo json_encode($returnInfo);
    }
    if (isset($_GET['getQuestionInfo'])){
        $question = $_GET['question'];
        
        // Update info
        if (isset($_GET['admRequest'])){
            $sql = "UPDATE freequizes SET onlineAtualQuestion = '$question' WHERE id = '$quizID'";
            $result = $conexao->query($sql);
        }

        // Retornar informações
        $sql = "SELECT * FROM freequizquestions WHERE quizID = '$quizID' AND questionNUM = $question";
        $result = $conexao->query($sql);
        $row = $result->fetch_assoc();
        $questionType = $row['questionTYPE'];
        $questionTxt = $row['questionTXT'];
        $questionTime = $row['questionTIME'];

        if ($questionType == 1){
            $resp1 = $row['questionRESPOSTA1'];
            $resp2 = $row['questionRESPOSTA2'];
            $resp3 = $row['questionRESPOSTA3'];
            $resp4 = $row['questionRESPOSTA4'];
        }
        else if ($questionType == 2){
            $resp1 = $row['questionRESPOSTA1'];
            $resp2 = $row['questionRESPOSTA2'];
            $resp3 = $row['questionRESPOSTA3'];
            $resp4 = "";
        }
        else if ($questionType == 3){
            $resp1 = "";
            $resp2 = "";
            $resp3 = "";
            $resp4 = "";
        }

        $data = array(
            "respType" => $questionType,
            "resp1" => $resp1,
            "resp2" => $resp2,
            "resp3" => $resp3,
            "resp4" => $resp4,
            "questTxt" => $questionTxt,
            "questTime" => $questionTime
        );

        echo json_encode($data);
    }
    if (isset($_GET['storePlayerAlt'])){
        // Armazenar alternativas
        $alternativa = $_GET['alt'];
        $playerID = $_GET['plid'];
        
        if ($_GET['playerTp'] == 1) {
            $sql = "UPDATE playersonline SET respostaEscolhida = '$alternativa' WHERE idPlayer = '$playerID' AND quizID = '$quizID'";
            $result = $conexao->query($sql);
        }
        else if ($_GET['playerTp'] == 2) {
            $sql = "UPDATE playersonline SET respostaEscolhida = '$alternativa' WHERE fakePlayerID = '$playerID' AND quizID = '$quizID'";
            $result = $conexao->query($sql);
        }
    }
    if (isset($_GET['testPlayersAlt'])){
        $sql = "SELECT quizIsOpenOnline FROM freequizes WHERE id = '$quizID'";
        $result = $conexao->query($sql);
        $row = $result->fetch_assoc();
        

        if ($row['quizIsOpenOnline'] == 2) {
            
            $sql = "SELECT * FROM playersonline WHERE quizID = '$quizID'";
            $result = $conexao->query($sql);

            if (mysqli_num_rows($result) > 0) {
                $sql = "SELECT COUNT(respostaEscolhida) FROM playersonline WHERE quizID = '$quizID' AND respostaEscolhida = 0";
                $result = $conexao->query($sql);

                $row = $result->fetch_assoc();
                $quant = $row['COUNT(respostaEscolhida)'];

                echo $quant;
            }
            else{
                echo 'Ninguem jogando';
            }
        }
        else{
            echo 'Wait Screen';
        }
    }
    if (isset($_GET['checkRight'])){
        $level = $_GET['lvl'];

        $sql = "SELECT questionRESPOSTACERTA FROM freequizquestions WHERE quizID =  '$quizID' AND questionNUM = '$level'";
        $result = $conexao->query($sql);

        $row = $result->fetch_assoc();
        $respostaCerta = $row['questionRESPOSTACERTA'];

        echo $respostaCerta;
    }
    if (isset($_GET['setEndGame'])){
        $sql = "UPDATE freequizes SET quizIsOpenOnline = 4 WHERE id = '$quizID'";
        $result = $conexao->query($sql);
    }
    if (isset($_GET['setPontuation'])){
        $pontuation = $_GET['pont'];
        $playerID = $_GET['plId'];
        $playerType = $_GET['playerTp'];

        if ($playerType == 1) {$sql = "SELECT playerName FROM playersonline WHERE playerType = 1 AND idPlayer  = '$playerID' AND quizID = '$quizID'";}
        if ($playerType == 2) {$sql = "SELECT playerName FROM playersonline WHERE playerType = 2 AND fakePlayerID  = '$playerID' AND quizID = '$quizID'";}
        $result = $conexao->query($sql);
        $row = $result->fetch_assoc();
        $playerName = $row['playerName'];
        echo $playerName;

        if ($playerType == 1){
            $sql = "INSERT INTO pontuation (quizID,userName,userID,quizType,userPontuation) VALUES ('$quizID','$playerName','$playerID',1,'$pontuation')";
            $result = $conexao->query($sql);
        }
        if ($playerType == 2){
            $sql = "INSERT INTO pontuation (quizID,userName,quizType,userPontuation) VALUES ('$quizID','$playerName',1,'$pontuation')";
            $result = $conexao->query($sql);
        }
    }
    if (isset($_GET['detectDoublePlayer'])) {
        $playerID = $_GET['player'];

        if ($playerID > 0){
            $sql = "SELECT * FROM playersonline WHERE quizID = $quizID AND idPlayer = $playerID";
            $result = $conexao->query($sql);

            if ($result->num_rows > 0){
                echo 1;
            }
            else{
                echo 0;
            }
        }
        else {
            echo 0;
        }
    } 
    if (isset($_GET['phantomSend'])){
        $sql = "UPDATE playersonline SET onlineTest = 0 WHERE quizID = '$quizID'";
        $result = $conexao->query($sql);

        echo json_encode(array("done" => true));
    }
    if (isset($_GET['phantomDelete'])){
        $sql = "SELECT * FROM playersonline WHERE quizID = ? AND onlineTest = 0";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $quizID);
        $stmt->execute();
        $result = $stmt->get_result();

        $insertSql = "INSERT INTO kickedregplayers (userId, quizId, pontuation) VALUES (?, ?, ?)";
        $insertStmt = $conexao->prepare($insertSql);

        while ($row = $result->fetch_assoc()) {
            if ($row['playerType'] == 1) {
                $idUser = $row['idPlayer'];
                $idQuiz = $row['quizID'];
                $pontuation = $row['tempPoints'];

                $insertStmt->bind_param("iii", $idUser, $idQuiz, $pontuation);
                if (!$insertStmt->execute()) {
                    echo "Erro ao inserir jogador removido: " . $conexao->error;
                }
            }
        }

        $sql = "DELETE FROM playersonline WHERE quizID = ? AND onlineTest = 0";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $quizID);

        if ($stmt->execute()) {
            echo "Jogadores removidos com sucesso.";
        } else {
            echo "Erro ao remover jogadores: " . $conexao->error;
        }

        $stmt->close();
        $insertStmt->close();
        $conexao->close();
    }
    if (isset($_GET['phantomImNot'])){
        $player = $_GET['plid'];
        $playerType = $_GET['plTy'];

        if ($playerType == 1){$sql = "UPDATE playersonline SET onlineTest = 1 WHERE idPlayer = $player";}
        if ($playerType == 2){$sql = "UPDATE playersonline SET onlineTest = 1 WHERE fakePlayerID = $player";}
        $result = $conexao->query($sql);
    }
    if (isset($_GET['youDisconnected'])){
        $player = $_GET['plid'];
        $playerType = $_GET['plTy'];

        if ($playerType == 1){$sql = "SELECT * FROM playersonline WHERE quizID = '$quizID' AND idPlayer = $player";}
        if ($playerType == 2){$sql = "SELECT * FROM playersonline WHERE quizID = '$quizID' AND fakePlayerID = $player";}
        $result = $conexao->query($sql);

        if (mysqli_num_rows($result) > 0){echo json_encode(array("connected" => true));}
        else {echo json_encode(array("connected" => false));}
    }
    if (isset($_GET['sendTempPoints'])){
        $player = $_GET['plid'];
        $playerType = $_GET['plTy'];
        $points = $_GET['points'];

        if ($playerType == 1){$sql = "UPDATE playersonline SET tempPoints = '$points' WHERE quizID = '$quizID' AND idPlayer = $player";}
        if ($playerType == 2){$sql = "UPDATE playersonline SET tempPoints = '$points' WHERE quizID = '$quizID' AND fakePlayerID = $player";}
        $result = $conexao->query($sql);
    }
    if (isset($_GET['topTenActualPlayers'])){
        $sql = "SELECT * FROM playersonline WHERE quizID = '$quizID' ORDER BY tempPoints DESC LIMIT 10";
        $result = $conexao->query($sql);

        $index = 0;
        $players = array();
        while($row = $result->fetch_assoc()){
            $index += 1;
            $players[$index] = array(
                "Name" => $row['playerName'],
                "Pos" => $index,
                "Points" => $row['tempPoints']
            );
        }

        $response = array(
            "players" => $players,
            "rowCount" => $result->num_rows
        );
    
        echo json_encode($response);
    }
    if (isset($_GET['showActualPoints'])){
        $sql = "UPDATE freequizes SET quizIsOpenOnline = 5 WHERE id = '$quizID'";
        $result = $conexao->query($sql);
    }
?>