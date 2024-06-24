<?php

    include_once("../../config.php");
    require "../../vendor/autoload.php";

    use Endroid\QrCode\QrCode;
    use Endroid\QrCode\Writer\PngWriter;

    if (isset($_GET['quizid'])) {
        session_start();
        $quizID = $_GET['quizid'];

        // Detectar se quem entrou é o dono
        if (isset($_SESSION['user_id'])){
            $user = $_SESSION['user_id'];
            $sql = "SELECT * FROM freequizes WHERE id = '$quizID' AND ownerID = '$user'";
            $result = $conexao->query($sql);
            if (mysqli_num_rows($result) <= 0) {
                header('Location: ../../');
                exit();
            }
        }
        else{
            header('Location: ../../');
            exit(); 
        }

        // Pegar dados do Quiz
        $sql = "SELECT * FROM freequizes WHERE id = '$quizID'";
        $result = $conexao->query($sql);
        $row = $result->fetch_assoc();
        $quizName = $row['quiz_name'];

        // Pegar ultima pergunta
        $sql = "SELECT MAX(questionNUM) FROM freequizquestions WHERE quizID = '$quizID'";
        $result = $conexao->query($sql);
        $row = $result->fetch_assoc();
        $lastQuestion = $row['MAX(questionNUM)'];

        // Forçar reset
        $sql = "UPDATE freequizes SET onlineAtualQuestion = 0 WHERE id = '$quizID'";
        $result = $conexao->query($sql);

        // Liberar entrada dos players e Forçar reset nos players Online
        $sql = "UPDATE freequizes SET quizIsOpenOnline = 1 WHERE id = '$quizID'";
        $result = $conexao->query($sql);

        // Gerar Qr code
        $text = "play.localhost:8080/?c=$quizID";

        $qr_code = QrCode::create($text);
        $writer = new PngWriter;

        $result = $writer->write($qr_code);
        $base64 = base64_encode($result->getString());
        $imgData = 'data:image/png;base64,' . $base64;
    }
    else {
        header('Location: ../../create/');
        exit();
    }

?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $quizName?> - ADM</title>

    <link rel="stylesheet" href="../../assets/css/gamestyle.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="website icon" href="../../assets/images/Logo256.png">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <div class="roomInfo">
        <div class="txt">
            <h1 class="code">Código da Sala: <u><?php echo $quizID?></u></h1>
            <h1><u><?php echo $text?></u></h1>
        </div>
        <img src="<?php echo $imgData; ?>" alt="qr-code" class="qr-code-room">
    </div>

    
    <div class="playersContainer"></div>
    <button class="continueButton" onclick="continueGame();">Continuar</button>

    <div class="statusMessagesContainer"></div>    

    <script>
        // Geral
        const removeStatusMessage = (elemento) => {
            elemento.parentNode.classList.remove('inAnimation');
            elemento.parentNode.classList.add('outAnimation');
            setTimeout(() => {
                elemento.parentNode.remove();
            }, 600);
        }
    </script>
    <script>
        // Player List

        // Quitar do Quiz
        const closeQuiz = () => {
            $.ajax({
                url: `../server/closeQuiz.php?closeQuiz&qid=<?php echo $quizID?>`,
                headers: { 'My-Custom-Header': '40028922' },
                success: function (data) {
                    window.location.href = '../../create/tables.php?qid=<?php echo $quizID?>';
                }
            })
        }
        window.addEventListener('beforeunload', closeQuiz);

        // Show Players (Tela de Espera)
        // Pegar Quantidade de Players
        let minQuant;
        let maxQuant;
        let playersQuant;
        const getPlayersQuant = () => {
            $.ajax({
                url: `../server/showWaitPlayers.php?getMaxMin&qid=<?php echo $quizID?>`,
                headers: { 'My-Custom-Header': '40028922' },
                dataType: 'json',
                success: function (data) {
                    minQuant = data.playersOnlineMin;
                    maxQuant = data.playersOnlineMax;
                    playersQuant = data.playersOnlineQuant;
                    tryUpdatePlayersList();
                }
            })
        }
        // Mostrar players na tela de adm
        const showPlayers = () => {
            document.querySelector('.playersContainer').innerHTML = '';

            for (let i = minQuant; i <= maxQuant; i++){
                $.ajax({
                    url: `../server/showWaitPlayers.php?playerExists&qid=<?php echo $quizID?>&suppID=${i}`,
                    headers: { 'My-Custom-Header': '40028922' },
                    dataType: 'json',
                    success: function (data) {
                        if (data.type == 1 && data.descrip != null){
                            showPlayerCard(true,data.name,data.descrip,data.profileImg)
                        }
                        else if (data.type == 1 && data.descrip == null){
                            showPlayerCard(false,data.name,data.descrip,data.profileImg)
                        }
                        else{
                            showPlayerCard(false,data.name)
                        }
                    }
                })
            }
        }
        let fakeMax = 0;
        const tryUpdatePlayersList = () => {
            if (playersQuant != fakeMax){
                fakeMax = playersQuant;
                showPlayers();
            }
        }
        let waitScreenLoop = setInterval(() => {
            getPlayersQuant();
        }, 1000);

        // Remover players fantasmas
        const removePhantomReq = () => {
            // Enviar requisição
            $.ajax({
                url: `../server/multiplayerGame.php?phantomSend&qid=<?php echo $quizID?>`,
                headers: { 'My-Custom-Header': '40028922' },
                dataType: 'json',
                success: function (data){
                    if (data.done === true){
                        setTimeout(() => {finalRemovePhantom()}, 750);
                    }
                }
            })
        }
        const finalRemovePhantom = () => {
            $.ajax({
                url: `../server/multiplayerGame.php?phantomDelete&qid=<?php echo $quizID?>`,
                headers: { 'My-Custom-Header': '40028922' },
                success: function (data) {
                    console.log(data)
                }
            })
        }

        setInterval(() => {
            removePhantomReq();
        }, 1500);
    </script>

    <script>
        // Game

        const topTenPlayers = () =>{
            // Enviar para os jogadores
            $.ajax({
                url: `../server/multiplayerGame.php?showActualPoints&qid=<?php echo $quizID?>`,
                headers: { 'My-Custom-Header': '40028922' },
            })

            // Mostrar
            $.ajax({
                url: `../server/multiplayerGame.php?topTenActualPlayers&qid=<?php echo $quizID?>`,
                headers: { 'My-Custom-Header': '40028922' },
                dataType: 'json',
                success: function (data) {
                    const topPlayersContainer = document.createElement('div');
                    topPlayersContainer.className = 'actualRankingContainer';
                    topPlayersContainer.innerHTML = `<h1>Ranking</h1><div class="pointsContainer"></div>`;
                    document.body.appendChild(topPlayersContainer);

                    let rowsLeft = data.rowCount;
                    const pointsContainer = topPlayersContainer.querySelector('.pointsContainer');

                    // Iterar sobre os jogadores
                    for (let key in data.players) {
                        if (data.players.hasOwnProperty(key)) {
                            const player = data.players[key];
                            
                            const playerTopInfo = document.createElement('div');
                            playerTopInfo.className = 'pointsRow';
                            playerTopInfo.style.animationDelay = (rowsLeft * 0.25) + "s";
                            rowsLeft -= 1;
                            
                            playerTopInfo.innerHTML = `<div class="pos">${player.Pos}º</div>
                            <div class="name">${player.Name}</div>
                            <div class="points">${player.Points}</div>`;
                            
                            pointsContainer.appendChild(playerTopInfo);
                        }
                    }


                    // Mostra o botão de continuar
                    const continueButton = document.createElement('button');
                    continueButton.className = 'continueButton';
                    continueButton.innerHTML = 'Continuar';
                    continueButton.onclick = () => {continueGame()};

                    document.body.appendChild(continueButton);
                }
            })
        }

        let levelPlaying = 1;
        const continueGame = () => {
            if ((levelPlaying - 1) < <?php echo $lastQuestion?>){
                getReadyAnimation(2);

                // Avançar jogo
                $.ajax({
                    url: `../server/multiplayerGame.php?qid=<?php echo $quizID?>&advance`,
                    headers: { 'My-Custom-Header': '40028922' },
                });
            }
            else {
                // Finalizar
                $.ajax({
                    url: `../server/multiplayerGame.php?qid=<?php echo $quizID?>&setEndGame`,
                    headers: { 'My-Custom-Header': '40028922' },
                })

                if (document.querySelector('.actualRankingContainer')){
                    document.querySelector('.actualRankingContainer').remove();
                }

                const singlePlayerGameContainer = document.createElement('div');
                singlePlayerGameContainer.className = 'singlePlayerGameContainer';
                singlePlayerGameContainer.innerHTML = '<h1>Quiz Finalizado!</h1><div class="answerStatus finish"><span>:D</span></div>';
                document.body.appendChild(singlePlayerGameContainer);

                if (document.querySelector('.continueButton')){
                    document.querySelector('.continueButton').remove();
                }

                setTimeout(() => {
                    closeQuiz();
                }, 5000);
            }
        }
        const revealChooses = () => {
            $.ajax({
                url: `../server/multiplayerGame.php?qid=<?php echo $quizID?>&revealChooses`,
                headers: { 'My-Custom-Header': '40028922' },
                dataType: 'json',
                success: function (data) {
                    if (document.querySelector('.continueButton')){
                        document.querySelector('.continueButton').remove();
                    }

                    // Mostrar escolhas dos jogadores
                    createContainerChooses(data.red,1)
                    createContainerChooses(data.blue,2)
                    if (document.querySelector('.qt3')){createContainerChooses(data.green,3)}
                    if (document.querySelector('.qt4')){createContainerChooses(data.yellow,4)}

                    clearTimeout(timerCountdownLoop);

                    document.querySelector('.numLoader1 .content').innerHTML = '5';
                    setTimeout(() => {document.querySelector('.numLoader1 .content').innerHTML = '4'}, 1000);
                    setTimeout(() => {document.querySelector('.numLoader1 .content').innerHTML = '3'}, 2000);
                    setTimeout(() => {document.querySelector('.numLoader1 .content').innerHTML = '2'}, 3000);
                    setTimeout(() => {document.querySelector('.numLoader1 .content').innerHTML = '1'}, 4000);
                    setTimeout(() => {
                        document.querySelector('.admQuestionInfo').remove();

                        topTenPlayers();
                    }, 5000);
                }
            })
        }
        

        const getQuestionsInformation = () => {
            // Pegar questão
            $.ajax({
                url: `../server/multiplayerGame.php?qid=<?php echo $quizID?>&getQuestionInfo&question=${levelPlaying}&admRequest`,
                headers: { 'My-Custom-Header': '40028922' },
                dataType: 'json',
                success: function (data) {
                    admShowQuestion(data.respType,data.resp1,data.resp2,data.resp3,data.resp4,data.questTime,levelPlaying,data.questTxt);
                    levelPlaying += 1;

                    testPlayerClock = setInterval(() => {
                        testPlayersAnswers();
                    }, 1000);
                }
            })

        }

        const testPlayersAnswers = () => {
            $.ajax({
                url: `../server/multiplayerGame.php?testPlayersAlt&qid=<?php echo $quizID?>`,
                headers: { 'My-Custom-Header': '40028922' },
                success: function (data) {
                    if (data == 0) {
                        // Mostrar Alerta
                        const warnAlertMsg = document.createElement('div');
                        warnAlertMsg.className = 'statusMessages inAnimation warn';
                        warnAlertMsg.innerHTML = '<h3 style="top: 0;">Atenção!</h3><p style="top: 0;">Todos os jogadores escolheram suas respostas!</p><i class="gg-close-r" onclick="removeStatusMessage(this)"></i>';

                        document.querySelector('.statusMessagesContainer').appendChild(warnAlertMsg);
                        setTimeout(() => {removeStatusMessage(warnAlertMsg.querySelector('.gg-close-r'))}, 5000);
                        clearInterval(testPlayerClock);

                        // Mostra o botão de continuar
                        const continueButton = document.createElement('button');
                        continueButton.className = 'continueButton';
                        continueButton.innerHTML = 'Continuar';
                        continueButton.onclick = () => {revealChooses()};

                        document.body.appendChild(continueButton);
                    }
                }
            })
        }
        let testPlayerClock; 
    </script>

    <script src="../../assets/js/multiPlayerGame.js"></script>
</body>
</html>