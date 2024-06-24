<?php

    if (!isset($_SERVER['HTTP_MY_CUSTOM_HEADER']) || $_SERVER['HTTP_MY_CUSTOM_HEADER'] !== '40028922') {
        header("HTTP/1.0 403 Forbidden");
        echo "Acesso proibido!";
        exit();
    }

    include_once('../config.php');

    if (isset($_GET['quizid'])) {
        //declarar Posts
        $quizid = $_GET['quizid'];
        $questionNum = $_POST['qNum'];
        $questionType = $_POST['qType'];
        $questionTxt = $_POST['qTxt'];
        $questionRespC = $_POST['respC'];
        $questionTrueFalseC = $_POST['tfC'];
        $questionResp1 = $_POST['resp1'];
        $questionResp2 = $_POST['resp2'];
        $questionResp3 = $_POST['resp3'];
        $questionResp4 = $_POST['resp4'];
        $questionTime = $_POST['qTime'];
        $supQuant = $_POST['supQuant'];

        //tratar post
        switch ($questionType) {
            case 'T-F':
                $qValueType = 3;
                break;
            case '3-opts':
                $qValueType = 2;
                break;
            case '4-opts':
                $qValueType = 1;
                break;
        }
        if ($questionRespC != 'NoMulti') {
            $realAnswer = $questionRespC;
        }
        else if ($questionTrueFalseC != 'NoTF') {
            switch ($questionTrueFalseC) {
                case 'True':
                    $realAnswer = 1;
                    break;
                case 'False': 
                    $realAnswer = 2;
                    break;
            }
        }

        //pegar quantidade de perguntas do quiz
        $result = mysqli_query($conexao, "SELECT MAX(questionNUM) FROM freequizquestions WHERE quizID = '$quizid'");
        $row = $result->fetch_assoc();
        $questionNumbers = $row['MAX(questionNUM)'];

        if ($questionNum <= $questionNumbers) {
            $sql = "UPDATE freequizquestions SET questionTYPE = '$qValueType', questionTXT = '$questionTxt', questionRESPOSTA1 = '$questionResp1', questionRESPOSTA2 = '$questionResp2', questionRESPOSTA3 = '$questionResp3', questionRESPOSTA4 = '$questionResp4', questionRESPOSTACERTA = '$realAnswer', questionTIME = '$questionTime' WHERE questionNUM = '$questionNum' AND quizID = '$quizid'";
            $result = $conexao->query($sql);
        }
        else if ($questionNum > $questionNumbers) {
            $sql = "INSERT INTO freequizquestions(quizID,questionNUM,questionTYPE,questionTXT,questionRESPOSTA1,questionRESPOSTA2,questionRESPOSTA3,questionRESPOSTA4,questionRESPOSTACERTA,questionTIME) VALUES ('$quizid','$questionNum','$qValueType','$questionTxt','$questionResp1','$questionResp2','$questionResp3','$questionResp4','$realAnswer','$questionTime')";
            $result = $conexao->query($sql);
        }

        $sql = "DELETE FROM freequizquestions WHERE questionNUM > '$supQuant' AND quizID = '$quizid'";
        $result = $conexao->query($sql);
    }

?>