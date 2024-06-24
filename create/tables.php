<?php

    include_once("../config.php");
    session_start();

    if(isset($_GET['qid'])) {
        $quizID = $_GET['qid'];

        // Detectar se o quiz existe
        $sql = "SELECT * FROM freequizes WHERE id = '$quizID'";
        $result = $conexao->query($sql);
        if (mysqli_num_rows($result) <= 0) {
            header('Location: ./');
            exit();
        }

        // Detectar se quem entrou é o dono
        if (isset($_SESSION['user_id'])){
            $user = $_SESSION['user_id'];
            $sql = "SELECT * FROM freequizes WHERE id = '$quizID' AND ownerID = '$user'";
            $result = $conexao->query($sql);
            if (mysqli_num_rows($result) <= 0) {$ownerOn = false;}
            else{$ownerOn = true;}
        }
        else{$ownerOn = false;}

        // Pegar o nome do quiz
        $sql = "SELECT quiz_name FROM freequizes WHERE id = '$quizID'";
        $result = $conexao->query($sql);

        $row = $result->fetch_assoc();
        $quizName = $row['quiz_name'];

    }
    else {
        header('Location: ./');
    }

?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $quizName?> - Tabelas</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/gamestyle.css">
    <link rel="icon" href="../assets/images/Logo256.png">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <header>
        <h1>Free Quiz</h1>
    </header>

    <div class="tablePage">
        <h1><?php echo $quizName?></h1>

        <div class="tables">
            <div class="tableContainer" id="multiplayer">
                <h2>Multiplayer</h2>
                <div class="row">
                    <div class="linePos"><b>Posição</b></div>
                    <div class="lineName1"><b>Nome</b></div>
                    <div class="linePoints"><b>Pontos</b></div>
                </div>
    
                <div class="row1">
                    <?php
                        $sql = "SELECT * FROM pontuation WHERE quizID = '$quizID' AND quizType = 1 ORDER BY userPontuation DESC";
                        $result = $conexao->query($sql);

                        $index = 0;
                        if (mysqli_num_rows($result) > 0){
                            while ($row = $result->fetch_assoc()){
                                if ($row['userID'] != null){
                                    $playerID = $row['userID'];

                                    $getNameSql = "SELECT username FROM users WHERE userid = '$playerID'";
                                    $resultName = $conexao->query($getNameSql);
                                    $rowName = $resultName->fetch_assoc();

                                    $runName = $rowName['username'];
                                }
                                else {
                                    $runName = $row['userName'];
                                }
                                $index += 1;
                                echo '<div class="row">
                                <div class="linePos">'.$index.'º</div>
                                <div class="lineName">'.$runName.'</div>
                                <div class="linePoints">'.$row['userPontuation'].'</div>
                            </div>';
                            }
                        }
                        else{
                            echo '<div class="row">
                                <div class="linePos">404</div>
                                <div class="lineName">Sem Pontuação</div>
                                <div class="linePoints">404</div>
                            </div>';
                        }
                        
                    ?>
                </div>
            </div>

            <div class="tableContainer" id="singleplayer">
                <h2>Singleplayer</h2>
                <div class="row">
                    <div class="linePos"><b>Posição</b></div>
                    <div class="lineName1"><b>Nome</b></div>
                    <div class="linePoints"><b>Pontos</b></div>
                </div>
    
                <div class="row1">
                    <?php
                        $sql = "SELECT * FROM pontuation WHERE quizID = '$quizID' AND quizType = 2 ORDER BY userPontuation DESC";
                        $result = $conexao->query($sql);

                        $index = 0;
                        if (mysqli_num_rows($result) > 0){
                            while ($row = $result->fetch_assoc()){
                                $index += 1;
                                echo '<div class="row">
                                <div class="linePos">'.$index.'º</div>
                                <div class="lineName">'.$row['userName'].'</div>
                                <div class="linePoints">'.$row['userPontuation'].'</div>
                            </div>';
                            }
                        }
                        else{
                            echo '<div class="row">
                                <div class="linePos">404</div>
                                <div class="lineName">Sem Pontuação</div>
                                <div class="linePoints">404</div>
                            </div>';
                        }
                    ?>
                </div>
            </div>
        </div>

        
    </div>

    <a href="../">
        <div class="createRedirect">
            <i class="gg-play-button-r"></i>
        </div>
    </a>
    <?php if ($ownerOn == true){echo '<button class="clearButton" onclick="confirmClearPontuation()">Limpar Dados</button>';}?>

    <script>
        const confirmClearPontuation = () => {
            const showAlertMsg = document.createElement('div');
            showAlertMsg.className = 'alertArea';
            showAlertMsg.innerHTML = '<div class="alertContainer"><i class="gg-close-r" onclick="closeAlert(this)"></i><h1>Atenção!</h1><p>Tem certeza que<br>limpar a pontuação?</p><div class="Bts"><button class="bad" onclick="deletePoints()">Sim</button><button class="good" onclick="closeAlert1(this)">Não</button></div></div>';

            document.body.appendChild(showAlertMsg);
        }

        const closeAlert = (element) => {element.parentNode.parentNode.remove();}
        const closeAlert1 = (element) => {element.parentNode.parentNode.parentNode.remove();}

        const deletePoints = () => {
            $.ajax({
                url: `../server/clearPontuation.php?clearPontuation&qid=<?php echo $quizID?>`,
                headers: { 'My-Custom-Header': '40028922' },
                success: function (data) {
                    location.reload();
                }
            })
        }

        <?php

            if (isset($_GET['multResult'])){echo 'document.querySelector(\'#singleplayer\').remove()';}
            else if (isset($_GET['singResult'])){echo 'document.querySelector(\'#multiplayer\').remove()';}

        ?>
    </script>
</body>
</html>

