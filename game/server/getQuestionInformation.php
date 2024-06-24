<?php
    if (!isset($_SERVER['HTTP_MY_CUSTOM_HEADER']) || $_SERVER['HTTP_MY_CUSTOM_HEADER'] !== '40028922') {
        header("HTTP/1.0 403 Forbidden");
        echo "Acesso proibido!";
        exit();
    }

    include_once("../../config.php");

    if (isset($_GET['quiz_id'])) {
        $quiz_id = $_GET['quiz_id'];
        $question_id = $_GET['question_id'];
        
        $sql = "SELECT * FROM freequizquestions WHERE quizID = '$quiz_id' AND questionNUM = '$question_id'";
        $result = $conexao->query($sql);

        
        
        while ($row = $result->fetch_assoc()){
            $questionTXT = $row['questionTXT'];
            $questionTYPE = $row['questionTYPE'];
            if ($questionTYPE == 1){
                $questionRESPOSTA1 = $row['questionRESPOSTA1'];
                $questionRESPOSTA2 = $row['questionRESPOSTA2'];
                $questionRESPOSTA3 = $row['questionRESPOSTA3'];
                $questionRESPOSTA4 = $row['questionRESPOSTA4'];
            }
            else if ($questionTYPE == 2){
                $questionRESPOSTA1 = $row['questionRESPOSTA1'];
                $questionRESPOSTA2 = $row['questionRESPOSTA2'];
                $questionRESPOSTA3 = $row['questionRESPOSTA3'];
                $questionRESPOSTA4 = "";
            }
            else if ($questionTYPE == 3){
                $questionRESPOSTA1 = "";
                $questionRESPOSTA2 = "";
                $questionRESPOSTA3 = "";
                $questionRESPOSTA4 = "";
            }
            $questionNUM = $row['questionNUM'];
            $questionTIME = $row['questionTIME'];
        }

        $data = array(
            "txt" => $questionTXT,
            "type" => $questionTYPE,
            "resp1" => $questionRESPOSTA1,
            "resp2" => $questionRESPOSTA2,
            "resp3" => $questionRESPOSTA3,
            "resp4" => $questionRESPOSTA4,
            "num" => $questionNUM,
            "time" => $questionTIME
        );

        echo json_encode($data);
    }
?>