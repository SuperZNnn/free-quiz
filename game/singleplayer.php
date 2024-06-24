<?php
    include_once('../config.php');

    if(isset($_GET['quizid'])){
        session_start();
        //Pegar Id do Player
        $user_id = $_SESSION['user_id'];

        //Pegar ID do quiz
        $quiz_id = $_GET['quizid'];

        //Detectar se o usuário já jogou
        $sql = "SELECT * FROM pontuation WHERE quizID = '$quiz_id' AND userID = '$user_id' AND quizType = 2";
        $result = $conexao->query($sql);

        if(mysqli_num_rows($result) > 0){
            // Já jogou
            echo '<script>alert(\'Você já jogou esse quiz!\');window.location.href = \'../\'</script>';
            exit();
        }
        
        //Verificar se a sessão é válida
        $sql = "SELECT quiz_type FROM freequizes WHERE id = '$quiz_id'";
        $resul = $conexao->query($sql);
        $row = $resul->fetch_assoc();
        $suppostID = $row["quiz_type"];

        if ($suppostID == 2) {
            //sessão válida

            //Selecionar número de perguntas
            $sql = "SELECT questionNUM FROM freequizquestions WHERE quizID = '$quiz_id' ORDER BY questionNUM DESC LIMIT 1";
            $resultMax = $conexao->query($sql);
            $maxID = 0;

            $sql = "SELECT questionNUM FROM freequizquestions WHERE quizID = '$quiz_id' ORDER BY questionNUM ASC LIMIT 1";
            $resultMin = $conexao->query($sql);
            $minID = 0;

            if($resultMax && $resultMin){
                $rowMax = $resultMax->fetch_assoc();
                $maxID = $rowMax['questionNUM'];

                $rowMin = $resultMin->fetch_assoc();
                $minID = $rowMin['questionNUM'];
            }
            else{
                echo '<script>console.log("Erro na consulta (Número de Perguntas)")</script>';
            }

            $sql = "SELECT * FROM freequizquestions WHERE questionNUM = 1 AND quizID = '$quiz_id'";
            $result = $conexao->query($sql);

            if(mysqli_num_rows($result) < 1){
                header('Location: ../');
            }
            else{
                while($row = $result->fetch_assoc()){
                    $questionNUM = $row['questionNUM'];
                    $questionTXT = $row['questionTXT'];
                    $questionTYPE = $row['questionTYPE'];
                    $questionRESPOSTA1 = $row['questionRESPOSTA1'];
                    $questionRESPOSTA2 = $row['questionRESPOSTA2'];
                    $questionRESPOSTA3 = $row['questionRESPOSTA3'];
                    $questionRESPOSTA4 = $row['questionRESPOSTA4'];
                    $questionTIME = $row['questionTIME'];
                }
            }
        }
        else {
            header('Location: ../');
        }
    }
    else{
        header('Location: ../');
    }
?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Singleplayer</title>

    <link rel="stylesheet" href="../assets/css/gamestyle.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="website icon" href="../assets/images/Logo256.png">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body> 
    <script>
        let PerguntaNum = [<?php echo $questionNUM?>, <?php echo $maxID?>];
        let PerguntaTXT = "<?php echo $questionTXT?>";
        let PerguntaType = <?php echo $questionTYPE?>;
        let PerguntaResposta = ["<?php echo $questionRESPOSTA1?>", "<?php echo $questionRESPOSTA2?>", "<?php echo $questionRESPOSTA3?>", "<?php echo $questionRESPOSTA4?>"];
        let PerguntaTimer = <?php echo $questionTIME?>;

        function gerarPerguntas() {
            const type = PerguntaType;
            const secs = PerguntaTimer;
            ShowQuestions(type, secs, [PerguntaNum[0],<?php echo $maxID?>], PerguntaTXT, [PerguntaResposta[0],PerguntaResposta[1],PerguntaResposta[2],PerguntaResposta[3]]);
        }
    </script>
    <script src="../assets/js/singlePlayerGame.js"></script>
    <script>
        let quizID = <?php echo $quiz_id?>;
        let perguntaID = 1;
        let totalQuizPoints = 0;

        const checkAnswer = (botao) => {
            pergID = parseInt(perguntaID) + 1;
            $.ajax({
                url: `server/checkAnswer.php?quiz_id=${quizID}&button=${botao}&question=${perguntaID}`,
                headers: { 'My-Custom-Header': '40028922' },
                success: function (data) {
                    if (data === 'true') {
                        let elapsedTime = parseInt(document.querySelector('.questionTL .rotation .content').innerHTML);
                        totalQuizPoints = totalQuizPoints + parseInt((100*elapsedTime)/PerguntaTimer)
                        RunNextQuestion(1);
                    } else if (data === 'false') {
                        RunNextQuestion(2);
                    } else {
                        console.log("Erro de conexão (Checagem de resposta)");
                    }
                    setTimeout(() => {queryInformation()}, 5010);
                }
            })
        };

        let pergID;
        let newQuestionTIMER;

        const queryInformation = () => {
            if (pergID <= <?php echo $maxID?>) {
                getQuestionInformation(pergID);
            } else {
                const singlePlayerGameContainer = document.createElement('div');
                singlePlayerGameContainer.className = 'singlePlayerGameContainer';
                singlePlayerGameContainer.innerHTML = '<h1>Quiz Finalizado!</h1><div class="answerStatus finish"><span>:D</span></div><p class="answerStatusP">Você fez '+totalQuizPoints+' pontos jogando esse quiz!</p>';
                document.body.appendChild(singlePlayerGameContainer);
                setPontuation(totalQuizPoints)
            }
        };

        const getQuestionInformation = (pergID) => {
            $.ajax({
                url: `server/getQuestionInformation.php?quiz_id=${quizID}&question_id=${pergID}`,
                headers: { 'My-Custom-Header': '40028922' },
                dataType: 'json',
                success: function (data) {
                    perguntaID += 1;
                    perguntaTimer = parseInt(data.time);
                    ShowQuestions(data.type, data.time, [pergID, <?php echo $maxID?>], data.txt, [data.resp1, data.resp2, data.resp3, data.resp4]);
                }
            })
        };
    </script>
    <script>
        const setPontuation = (pontuation) => {
            let userid = <?php echo $user_id?>;
            let quizid = <?php echo $quiz_id?>;
            $.ajax({
                url: `server/setPontuation.php?userid=${userid}&points=${pontuation}&quizid=${quizid}&quiztype=2`,
                headers: { 'My-Custom-Header': '40028922' },
                success: function (data) {
                    setTimeout(() => {window.location.href = '../create/tables.php?qid=<?php echo $quiz_id?>&singResult';}, 5000);
                }
            })
        }
    </script>
</body>
</html>