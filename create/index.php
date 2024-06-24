<?php

    include_once('../config.php');
    session_start();

    if (isset($_SESSION["user_id"])) {
        $user_id = $_SESSION['user_id'];
    }
    else{
        header('Location: login/');
        exit();
    }

    $sqlMax = "SELECT MAX(id) FROM freequizes WHERE ownerID = '$user_id'";
    $resultMax = $conexao->query($sqlMax);
    $sqlMin = "SELECT MIN(id) FROM freequizes WHERE ownerID = '$user_id'";
    $resultMin = $conexao->query($sqlMin);

    $minId = 0;
    $maxId = 0;

    if ($resultMax && $resultMin) {
        $rowMax = $resultMax->fetch_assoc();
        $maxId = $rowMax['MAX(id)'];
        
        $rowMin = $resultMin->fetch_assoc();
        $minId = $rowMin['MIN(id)'];
    } else {
        echo "Erro na consulta: " . $conexao->error;
    }

?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/images/Logo256.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <header>
        <h1>Free Quiz</h1>
    </header>

    <div class="gmsBody">
        <h1>Seus Jogos</h1>

        <div class="plusButton" title="Novo Quiz">
            <a href="new.php">
                <span>+</span>
            </a>
        </div>
        
        <div class="InformationContainer">
        <?php
            if ($minId != 0 && $maxId != 0) { 
                for ($i = $minId; $i <= $maxId; $i++) {
                    $sql = "SELECT * FROM freequizes WHERE id = '$i' AND ownerID = '$user_id'";
                    $result = $conexao->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $quizId = $row['id'];
                            $quizName = $row['quiz_name'];
                            $playedTimes = $row['playedTimes'];
                            $quizImage = $row['quizImage'];
                        }

                        if ($quizImage != null){
                            $quizImageDataUrl = 'data:image/jpeg;base64,' . base64_encode($quizImage);
                        }
                        else {
                            $quizImageDataUrl = '../assets/images/classroom.jpg';
                        }

                        // Pegar informações do quiz
                        $sql = "SELECT MAX(questionNUM) FROM freequizquestions WHERE quizID = '$quizId'";
                        $result = $conexao->query($sql);
                        $row = $result->fetch_assoc();
                        $perguntasNum = $row['MAX(questionNUM)'];

                        // Pegar tipo do quiz
                        $sql = "SELECT quiz_type FROM freequizes WHERE id = '$quizId'";
                        $result = $conexao->query($sql);
                        $row = $result->fetch_assoc();
                        $quizType = $row['quiz_type'];

                        // Tratar playButton
                        if ($quizType == 1){
                            $buttonAction = "href=\"../game/multiplayer/adm.php?quizid=$quizId\"";
                            $buttonAction1 = "1";
                        }
                        else if ($quizType == 2){
                            $buttonAction = "href=\"../game/singleplayer.php?quizid=$quizId\"";
                            $buttonAction1 = "2";
                        }
                        else if ($quizType == 3){
                            $buttonAction = "onclick=\"alert('Quiz Fechado!')\"";
                            $buttonAction1 = "3";
                        }

                        if ($quizType == 2 || $quizType == 3) {
                            echo '<div class="ConfigCard">
                                    <img src="'.$quizImageDataUrl.'" alt="sala">
                                    <h2>'.$quizName.'</h2>
                                    <p>Foi jogado <span>'.$playedTimes.'</span> vezes.</p>
                                    <p><span>'.$perguntasNum.'</span> perguntas</p>
                                    <p>Código: <span><u>'.$quizId.'</u></span></p>
                                    <div class="configActions">
                                        <a href="config.php?qid='.$quizId.'">
                                            <div class="action" title="Configurar">
                                                <i class="fa fa-gear" style="font-size:24px"></i>
                                            </div>
                                        </a>
                                        
                                        <a href="tables.php?qid='.$quizId.'">
                                            <div class="action" title="Pontuações">
                                                <i class="fa fa-table" style="font-size:24px"></i>
                                            </div>
                                        </a>

                                        <a>
                                            <div class="action" id="'.$quizId.'" title="Apagar Quiz" onclick="showAlert(this)" style="width: 24px; height: 24px; display: flex; justify-content: center; align-items: center;">
                                                <i class="gg-close-r"></i>
                                            </div>
                                        </a>

                                        <a onclick="copyQuizLink('.$buttonAction1.','.$quizId.')">
                                            <div class="action" title="Compartilhar Quiz" style="width: 24px; height: 24px; display: flex; justify-content: center; align-items: center;">
                                                <i class="gg-share" style="transform: translate(-100%);"></i>
                                            </div>
                                        </a>

                                        <a '.$buttonAction.'>
                                            <div class="action" title="Jogar" style="width: 24px; height: 24px; display: flex; justify-content: center; align-items: center;">
                                                <i class="gg-play-button-r"></i>
                                            </div>
                                        </a>
                                    </div>
                                </div>'
                            ;
                        }
                        else if ($quizType == 1) {
                            echo '<div class="ConfigCard">
                                    <img src="'.$quizImageDataUrl.'" alt="sala">
                                    <h2>'.$quizName.'</h2>
                                    <p>Foi jogado <span>'.$playedTimes.'</span> vezes.</p>
                                    <p><span>'.$perguntasNum.'</span> perguntas</p>
                                    <p>&ThinSpace;</p>
                                    <div class="configActions">
                                        <a href="config.php?qid='.$quizId.'">
                                            <div class="action" title="Configurar">
                                                <i class="fa fa-gear" style="font-size:24px"></i>
                                            </div>
                                        </a>
                                        
                                        <a href="tables.php?qid='.$quizId.'">
                                            <div class="action" title="Pontuações">
                                                <i class="fa fa-table" style="font-size:24px"></i>
                                            </div>
                                        </a>

                                        <a>
                                            <div class="action" id="'.$quizId.'" title="Apagar Quiz" onclick="showAlert(this)" style="width: 24px; height: 24px; display: flex; justify-content: center; align-items: center;">
                                                <i class="gg-close-r"></i>
                                            </div>
                                        </a>

                                        <a '.$buttonAction.'>
                                            <div class="action" title="Jogar" style="width: 24px; height: 24px; display: flex; justify-content: center; align-items: center;">
                                                <i class="gg-play-button-r"></i>
                                            </div>
                                        </a>
                                    </div>
                                </div>'
                            ;
                        }
                    }
                }
            }
            else{
                echo '<h2>Você ainda não criou nenhum quiz!</h2>';
            }
        ?>
        </div>
    </div>

    <a href="../">
        <div class="createRedirect">
            <i class="gg-play-button-r"></i>
        </div>
    </a>
    <a href="../profile/">
        <div class="exitRedirect" title="Editar perfil">
            <i class="gg-profile"></i>
        </div>
    </a>

    <div class="statusMessagesContainer"></div>
    
    <script>
        const showAlert = (element) => {
            let elementID = element.id
            const showAlertMsg = document.createElement('div');
            showAlertMsg.className = 'alertArea';
            showAlertMsg.innerHTML = '<div class="alertContainer"><i class="gg-close-r" onclick="closeAlert(this)"></i><h1>Atenção!</h1><p>Tem certeza que<br>deseja deletar esse quiz?</p><div class="Bts"><button class="bad" onclick="deleteQuiz('+elementID+')">Sim</button><button class="good" onclick="closeAlert1(this)">Não</button></div></div>';

            document.body.appendChild(showAlertMsg);
        }

        const closeAlert = (element) => {element.parentNode.parentNode.remove();}
        const closeAlert1 = (element) => {element.parentNode.parentNode.parentNode.remove();}

        const deleteQuiz = (element) => {
            $.ajax({
                url: `../server/deleteQuiz.php?qid=${element}`,
                headers: { 'My-Custom-Header': '40028922' },
                success: function (data) {
                    location.reload();
                }
            })
        }

        const copyQuizLink = (mode,qid) => {
            if (mode === 1 || mode === 2){
                copyMsg(`play.localhost:8080/?c=${qid}`);
            }
            else if (mode === 3){
                const alertMsg = document.createElement('div');
                alertMsg.className = 'statusMessages inAnimation';
                alertMsg.classList.add('bad')
                alertMsg.innerHTML = `<h3 style="margin-top: 1.8vh;">Oops!</h3>
                <p style="margin-top: 1.5vh;">Seu quiz está fechado</p>
                <i class="gg-close-r" onclick="removeStatusMessage(this)"></i>`;

                document.querySelector('.statusMessagesContainer').appendChild(alertMsg);
                setTimeout(() => {removeStatusMessage(alertMsg.querySelector('.gg-close-r'))}, 5000);
            }
        }

        const copyMsg = async (link) => {
            try {
                await navigator.clipboard.writeText(link);

                const alertMsg = document.createElement('div');
                alertMsg.className = 'statusMessages inAnimation';
                alertMsg.classList.add('good')
                alertMsg.innerHTML = `<h3 style="margin-top: 1.8vh;">Boa!</h3>
                <p style="margin-top: 1.5vh;">Link copiado para sua area de transferência</p>
                <i class="gg-close-r" onclick="removeStatusMessage(this)"></i>`;

                document.querySelector('.statusMessagesContainer').appendChild(alertMsg);
                setTimeout(() => {removeStatusMessage(alertMsg.querySelector('.gg-close-r'))}, 5000);
            }
            catch (err) {
                console.error('Falha ao copiar texto: ', err);
            }
        }
        const removeStatusMessage = (elemento) => {
                elemento.parentNode.classList.remove('inAnimation');
                elemento.parentNode.classList.add('outAnimation');
                setTimeout(() => {
                    elemento.parentNode.remove();
                }, 600);
            }
    </script>
</body>
</html>