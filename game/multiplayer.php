<?php

    include_once("../config.php");

    if (isset($_GET['quizid'])) {
        session_start();
        $quizID = $_GET['quizid'];

        // Pegar dados do Quiz
        $sql = "SELECT * FROM freequizes WHERE id = '$quizID'";
        $result = $conexao->query($sql);
        while ($row = $result->fetch_assoc()) {
            $quizName = $row['quiz_name'];
            $quizImage = $row['quizImage'];
            $quizLevel = $row['onlineAtualQuestion'];
            $onlineQuestion = $row['quizIsOpenOnline'];
        }

        if ($quizImage != null){
            $quizImageDataUrl = 'data:image/jpeg;base64,' . base64_encode($quizImage);
        }
        else {
            $quizImageDataUrl = '../assets/images/classroom.jpg';
        }

        // Pegar número de Perguntas
        $sql = "SELECT MAX(questionNUM) FROM freequizquestions WHERE quizID = '$quizID'";
        $result = $conexao->query($sql);
        $row = $result->fetch_assoc();
        $questionQuant = $row['MAX(questionNUM)'];

        // Detectar sessão
        if (isset($_SESSION['user_id'])){
            $userId = $_SESSION['user_id'];
        }
        else{
            $userId = 0;
        }

?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $quizName?></title>

    <link rel="stylesheet" href="../assets/css/gamestyle.css">
    <link rel="stylesheet" href="../assets/css/style.css?v=1.1">
    <link rel="website icon" href="../assets/images/Logo256.png">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</head>
<body>
    <script>
        let ImgUrl = '<?php echo $quizImageDataUrl?>';
        let quizName = '<?php echo $quizName?>';
        let questionsQuant = <?php echo $questionQuant?>;
    </script>
    <script src="../assets/js/multiPlayerGame.js?v=1.1"></script>

    <script>
        // Wait Screen

        //Manipular informação dos Players
        let fakeIdStorage;

        const sendPlayerInfo = (name,type) => {
            $.ajax({
                url: `server/joinPlayers.php?type=${type}&qid=<?php echo $quizID?>`,
                headers: { 'My-Custom-Header': '40028922' },
                type: 'POST',
                data: {
                    name: name
                },
                success: function (data) {
                    if (data != ''){
                        fakeIdStorage = parseInt(data);
                    }
                }
            })
        }
        const removePlayerInfo = (id,type) => {
            $.ajax({
                url: `server/leavePlayers.php?id=${id}&type=${type}`,
                headers: { 'My-Custom-Header': '40028922' },
            })
        }
        let doubledQuit = false;
        window.addEventListener('beforeunload', () => {
            if (<?php echo $userId?> == 0) {removePlayerInfo(fakeIdStorage,2);}
            else {
                if (doubledQuit == false){
                    removePlayerInfo(<?php echo $userId?>,1);
                }
            }
        })

        //Detectar se o quiz foi fechado
        const closeDetect = () => {
            $.ajax({
                url: `server/closeQuiz.php?detectClose&qid=<?php echo $quizID?>`,
                headers: { 'My-Custom-Header': '40028922' },
                success: function (data) {
                    if (data == 0) {
                        if (<?php echo $userId?> == 0) {removePlayerInfo(fakeIdStorage,2);}
                        else {removePlayerInfo(<?php echo $userId?>,1);}

                        window.location.href = '../';
                    }
                }
            })
        }
        setInterval(() => {
            closeDetect();
        }, 500);

        // Detectar player duplicado
        const doublePlayerDetect = (sendName,sendType) => {
            $.ajax({
                url: `server/multiplayerGame.php?detectDoublePlayer&qid=<?php echo $quizID?>&player=<?php if (isset($_SESSION['user_id'])){echo $_SESSION['user_id'];} else {echo '0';}?>`,
                headers: { 'My-Custom-Header': '40028922' },
                success: function (data) {
                    if (data == 1) {
                        doubledQuit = true;
                        alert('Você já está conectado em outro dispositivo!');
                        window.location.href = '../';
                    }
                    else if (data == 0){
                        sendPlayerInfo(sendName,sendType);
                    }
                }
            })
        }

        // Declarar online
        const imNotPhantom = () => {
            if (<?php if (isset($_SESSION['user_id'])){echo $_SESSION['user_id'];} else {echo '0';}?> != 0){
                $.ajax({
                    url: `server/multiplayerGame.php?phantomImNot&qid=<?php echo $quizID?>&plTy=1&plid=<?php if (isset($_SESSION['user_id'])){echo $_SESSION['user_id'];} else {echo '0';}?>`,
                    headers: { 'My-Custom-Header': '40028922' },
                })
            }
            else {
                $.ajax({
                    url: `server/multiplayerGame.php?phantomImNot&qid=<?php echo $quizID?>&plTy=2&plid=${fakeIdStorage}`,
                    headers: { 'My-Custom-Header': '40028922' },
                })
            }
        }
        setInterval(() => {
            imNotPhantom();
        }, 50);

        // Desconectado
        const imDisconnected = () => {
            if (<?php if (isset($_SESSION['user_id'])){echo $_SESSION['user_id'];} else {echo '0';}?> != 0){
                $.ajax({
                    url: `server/multiplayerGame.php?youDisconnected&qid=<?php echo $quizID?>&plTy=1&plid=<?php if (isset($_SESSION['user_id'])){echo $_SESSION['user_id'];} else {echo '0';}?>`,
                    headers: { 'My-Custom-Header': '40028922' },
                    dataType: 'json',
                    success: function (data) {
                        if (data.connected == false){
                            const showAlertMsg = document.createElement('div');
                            showAlertMsg.className = 'alertArea';
                            showAlertMsg.innerHTML = '<div class="alertContainer"><i class="gg-close-r" onclick="window.location.href = \'../\'"></i><h1>Desconectado!</h1><p>Você foi desconetado<br>Deseja reconectar?</p><div class="Bts"><button class="good" onclick="location.reload()">Sim</button><button class="good" onclick="window.location.href = \'../\'">Não</button></div></div>';

                            document.body.appendChild(showAlertMsg);

                            clearInterval(discoTest);
                        }
                    }
                })
            }
            else {
                $.ajax({
                    url: `server/multiplayerGame.php?youDisconnected&qid=<?php echo $quizID?>&plTy=2&plid=${fakeIdStorage}`,
                    headers: { 'My-Custom-Header': '40028922' },
                    dataType: 'json',
                    success: function (data) {
                        if (data.connected == false){
                            const showAlertMsg = document.createElement('div');
                            showAlertMsg.className = 'alertArea';
                            showAlertMsg.innerHTML = '<div class="alertContainer"><i class="gg-close-r" onclick="window.location.href = \'../\'"></i><h1>Desconectado!</h1><p>Você foi desconetado<br>Deseja reconectar?</p><div class="Bts"><button class="good" onclick="location.reload()">Sim</button><button class="good" onclick="window.location.href = \'../\'">Não</button></div></div>';

                            document.body.appendChild(showAlertMsg);

                            clearInterval(discoTest);
                        }
                    }
                })
            }
        }

        let discoTest = setInterval(() => {
            imDisconnected();
        }, 1000);
    </script>

    <script>
        // Game
        let playerPontuation = 0;
        let questionTime;
        let timeDown;
        let timerCDL;

        // Realizar Mudança
        const getQuestions = () => {
            $.ajax({
                url: `server/multiplayerGame.php?getQuestionInfo&question=${atualLevel}&qid=<?php echo $quizID?>`,
                headers: { 'My-Custom-Header': '40028922' },
                dataType: 'json',
                success: function (data) {
                    questionTime = parseInt(data.questTime);
                    timeDown = parseInt(data.questTime);
                    timerCLD = setInterval(() => {timeDown -= 1}, 1000);
                    showAnswers(data.respType,data.resp1,data.resp2,data.resp3,data.resp4);

                    // Mostrar perguntas
                    const questionTxtHintContainer = document.createElement('div');
                    questionTxtHintContainer.className = 'hints';
                    questionTxtHintContainer.innerHTML = `<h3>Pergunta</h3>
                    <div class="htsConts">
                        <p>${data.questTxt}</p>
                    </div>`;
                    document.body.appendChild(questionTxtHintContainer);
                }
            })
        }
        const setPLPontuation = (pont) => {
            if (<?php if (isset($_SESSION['user_id'])){echo $_SESSION['user_id'];} else {echo '0';}?> != 0) {
                $.ajax({
                    url: `server/multiplayerGame.php?setPontuation&pont=${pont}&plId=<?php if (isset($_SESSION['user_id'])){echo $_SESSION['user_id'];} else {echo '0';}?>&qid=<?php echo $quizID?>&playerTp=1`,
                    headers: { 'My-Custom-Header': '40028922' },

                })
            }
            else {
                $.ajax({
                    url: `server/multiplayerGame.php?setPontuation&pont=${pont}&plId=${fakeIdStorage}&qid=<?php echo $quizID?>&playerTp=2`,
                    headers: { 'My-Custom-Header': '40028922' },
                })
            } 
        }
    
        // Detectar mudança de níveis
        let runningState = <?php echo $onlineQuestion?>;
        let atualLevel = <?php echo $quizLevel?>;
        const detectNewQuestion = () => {
            $.ajax({
                url: `server/multiplayerGame.php?testLevel&qid=<?php echo $quizID?>`,
                headers: { 'My-Custom-Header': '40028922' },
                dataType: 'json',
                success: function (data) {
                    if (data.state != runningState && data.state == 2) {
                        runningState = parseInt(data.state);

                        atualLevel = parseInt(data.atualLevel) + 1
                        getReadyAnimation(1);

                        document.body.style.overflow = 'visible';
                    }
                    else if (data.state != runningState && data.state == 3){
                        runningState = parseInt(data.state);

                        checkAnswer();
                    }
                    else if (data.state != runningState && data.state == 4){
                        console.log(data)
                        runningState = parseInt(data.state);

                        document.querySelector('.singlePlayerGameContainer1').remove();
                        document.body.style.overflow = 'visible';
                        const singlePlayerGameContainer = document.createElement('div');
                        singlePlayerGameContainer.className = 'singlePlayerGameContainer';
                        singlePlayerGameContainer.innerHTML = '<h1>Quiz Finalizado!</h1><div class="answerStatus finish"><span>:D</span></div><p class="answerStatusP">Você fez '+playerPontuation+' pontos jogando esse quiz!</p>';
                        document.body.appendChild(singlePlayerGameContainer);

                        setPLPontuation(playerPontuation);
                        setTimeout(() => {
                            window.location.href = '../create/tables.php?qid=<?php echo $quizID?>&multResult';
                        }, 5000);
                    }
                    else if (data.state != runningState && data.state == 5){
                        runningState = parseInt(data.state);

                        document.body.style.overflow = 'hidden';

                        const singlePlayerGameContainer = document.createElement('div');
                        singlePlayerGameContainer.className = 'singlePlayerGameContainer1';
                        singlePlayerGameContainer.innerHTML = `<h1>Boa!</h1>
                        <div class="answerStatus right">
                            <span>
                                <svg style="width: 3vh; transform: translate(.7vh,0);" height="1792" viewBox="0 0 1792 1792" width="1792" xmlns="http://www.w3.org/2000/svg"><path d="M320 1344q0-26-19-45t-45-19q-27 0-45.5 19t-18.5 45q0 27 18.5 45.5t45.5 18.5q26 0 45-18.5t19-45.5zm160-512v640q0 26-19 45t-45 19h-288q-26 0-45-19t-19-45v-640q0-26 19-45t45-19h288q26 0 45 19t19 45zm1184 0q0 86-55 149 15 44 15 76 3 76-43 137 17 56 0 117-15 57-54 94 9 112-49 181-64 76-197 78h-129q-66 0-144-15.5t-121.5-29-120.5-39.5q-123-43-158-44-26-1-45-19.5t-19-44.5v-641q0-25 18-43.5t43-20.5q24-2 76-59t101-121q68-87 101-120 18-18 31-48t17.5-48.5 13.5-60.5q7-39 12.5-61t19.5-52 34-50q19-19 45-19 46 0 82.5 10.5t60 26 40 40.5 24 45 12 50 5 45 .5 39q0 38-9.5 76t-19 60-27.5 56q-3 6-10 18t-11 22-8 24h277q78 0 135 57t57 135z"/></svg>
                            </span>
                        </div>
                        <p class="answerStatusP">Você tem ${playerPontuation} pontos!</p>`;
                        document.body.appendChild(singlePlayerGameContainer);
                    }
                }
            })
        }
        setInterval(() => {
            detectNewQuestion();
        }, 100);
        // Mostrar se acertou
        const checkAnswer = () => {
            $.ajax({
                url: `server/multiplayerGame.php?checkRight&qid=<?php echo $quizID?>&lvl=${atualLevel}`,
                headers: { 'My-Custom-Header': '40028922' },
                success: function (data) {
                    if (parseInt(playerAlt) === 0) {RunNextQuestion(3)}
                    else if (parseInt(playerAlt) === parseInt(data)){
                        // Acertou
                        RunNextQuestion(1);
                        playerPontuation = playerPontuation + parseInt((100*timeDown)/questionTime);
                        sendTempPoints();
                    }
                    else {RunNextQuestion(2)}
                }
            })
        }

        const sendTempPoints = () => {
            if (<?php if (isset($_SESSION['user_id'])){echo $_SESSION['user_id'];} else {echo '0';}?> > 0){
                $.ajax({
                    url: `server/multiplayerGame.php?sendTempPoints&qid=<?php echo $quizID?>&points=${playerPontuation}&plTy=1&plid=<?php if (isset($_SESSION['user_id'])){echo $_SESSION['user_id'];} else {echo '0';}?>&qid=<?php echo $quizID?>`,
                    headers: { 'My-Custom-Header': '40028922' },
                })
            }
            else{
                $.ajax({
                    url: `server/multiplayerGame.php?sendTempPoints&qid=<?php echo $quizID?>&points=${playerPontuation}&plTy=2&plid=${fakeIdStorage}&qid=<?php echo $quizID?>`,
                    headers: { 'My-Custom-Header': '40028922' },
                })
            }
        }

        // Armazenar alternativa dos players
        let playerAlt = 0;
        const setPlayerAlt = (alt) => {
            playerAlt = parseInt(alt);
            if (<?php if (isset($_SESSION['user_id'])){echo $_SESSION['user_id'];} else {echo '0';}?> > 0){
                $.ajax({
                    url: `server/multiplayerGame.php?storePlayerAlt&alt=${alt}&playerTp=1&plid=<?php if (isset($_SESSION['user_id'])){echo $_SESSION['user_id'];} else {echo '0';}?>&qid=<?php echo $quizID?>`,
                    headers: { 'My-Custom-Header': '40028922' },
                })
            }
            else {
                $.ajax({
                    url: `server/multiplayerGame.php?storePlayerAlt&alt=${alt}&playerTp=2&plid=${fakeIdStorage}&qid=<?php echo $quizID?>`,
                    headers: { 'My-Custom-Header': '40028922' },
                })
            }
        }
    </script>
</body>
</html>

<?php
    // Detectar sessão
        if (isset($_SESSION['user_id'])){
            $userId = $_SESSION['user_id'];

            // Pegar nome do player
            $sql = "SELECT username FROM users WHERE userid = '$userId'";
            $result = $conexao->query($sql);
            $row = $result->fetch_assoc();
            $username = $row['username'];

            echo '<script>showWaitScreen(ImgUrl,quizName,questionsQuant,\''.$username.'\',false)</script>';
        }
        else {
            echo '<script>joinFakeName();</script>';
        }
    }
    else{
        header('Location: ../');
    }
?>