<?php
    
    include_once('../config.php');

    if (isset($_GET['qid'])){
        $quizID = $_GET['qid'];
    }
    else{
        header('Location: ./');
        exit();
    }

    session_start();
    $userID = $_SESSION['user_id'];

    $sql = "SELECT * FROM freequizes WHERE id = '$quizID' AND ownerID = '$userID'";
    $result = $conexao->query($sql);

    if ($result->num_rows <= 0) {
        header('Location: ../');
        exit();
    }

    //Pegar perguntas do quiz
    $sqlMax = "SELECT MAX(questionNUM) FROM freequizquestions WHERE quizID = '$quizID'";
    $resultMax = $conexao->query($sqlMax);
    $sqlMin = "SELECT MIN(questionNUM) FROM freequizquestions WHERE quizID = '$quizID'";
    $resultMin = $conexao->query($sqlMin);

    $minId = 0;
    $maxId = 0;

    if ($resultMax && $resultMin) {
        $rowMax = $resultMax->fetch_assoc();
        $maxId = $rowMax['MAX(questionNUM)'];
        
        $rowMin = $resultMin->fetch_assoc();
        $minId = $rowMin['MIN(questionNUM)'];
    } else {
        echo "Erro na consulta: " . $conexao->error;
    }

    //Pegar informações do quiz
    $sql = "SELECT MAX(questionNUM) FROM freequizquestions WHERE quizID = '$quizID'";
    $result = $conexao->query($sql);
    $row = $result->fetch_assoc();
    $perguntasNum = $row['MAX(questionNUM)'];

    $sql = "SELECT * FROM freequizes WHERE id = '$quizID'";
    $result = $conexao->query($sql);
    while ($row = $result->fetch_assoc()) {
        $playedTimes = $row['playedTimes'];
        $quizName = $row['quiz_name'];
        $quizImage = $row['quizImage'];
        $quizType = $row['quiz_type'];
    }

    if ($quizImage != null){
        $quizImageDataUrl = 'data:image/jpeg;base64,' . base64_encode($quizImage);
    }
    else {
        $quizImageDataUrl = '../assets/images/classroom.jpg';
    }

?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $quizName;?> - Configurar</title>

    <link rel="website icon" href="../assets/images/Logo256.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <header>
        <h1>Free Quiz</h1>
    </header>

    <div class="configContainer">
        <div class="ConfigInformationContainer">

            <div class="ConfigCard">
                <img src="<?php echo $quizImageDataUrl?>" alt="sala">
                <h2><?php echo $quizName;?></h2>
                <p>Foi jogado <span><?php echo $playedTimes;?></span> vezes</p>
                <p><span><?php echo $perguntasNum;?></span> perguntas</p>
            </div>

            <form action="" method="post">
                <div class="ConfigCard">
                    <input type="text" name="quizName" id="quizName" placeholder="Nome do Quiz" value="<?php echo $quizName?>" required>
                    <div class="imageControl">
                        <label for="image">Foto do Quiz</label>
                        <input type="file" name="image" id="image" accept="image/*" style="border: none;">
                    </div>
                    
                    <span>Tipo do Quiz</span>
                    <select name="quizType" id="quizType">
                        <option value="1" <?php if ($quizType == 1) {echo 'selected';}?>>Multiplayer</option>
                        <option value="2" <?php if ($quizType == 2) {echo 'selected';}?>>Singleplayer</option>
                        <option value="3" <?php if ($quizType == 3) {echo 'selected';}?>>Fechado</option>
                    </select>
                    <button type="reset">Reset</button>
                </div>
            </form>

        </div>

        <div class="changeForm">
            <form action="" method="post">
                <abbr title="Salvar">
                    <button type="button" onclick="saveButton(this)">
                        <span class="glyphicon glyphicon-floppy-disk"></span>
                    </button>
                </abbr>

                <div class="qtscontainer">    
                    <?php
                        for ($i = $minId; $i <= $maxId; $i++){
                            $sql = "SELECT * FROM freequizquestions WHERE quizID = '$quizID' AND questionNUM = '$i'";
                            $result = $conexao->query($sql);
                    
                            while ($row = $result->fetch_assoc()) {
                                $perguntaTYPE = $row['questionTYPE'];
                                $perguntaTXT = $row['questionTXT'];
                                $questionNUM = $row['questionNUM'];
                                $questionTIME = $row['questionTIME'];
                                if ($perguntaTYPE == 1) {
                                    $resposta1 = $row['questionRESPOSTA1'];
                                    $resposta2 = $row['questionRESPOSTA2'];
                                    $resposta3 = $row['questionRESPOSTA3'];
                                    $resposta4 = $row['questionRESPOSTA4'];
                                }
                                else if ($perguntaTYPE == 2) {
                                    $resposta1 = $row['questionRESPOSTA1'];
                                    $resposta2 = $row['questionRESPOSTA2'];
                                    $resposta3 = $row['questionRESPOSTA3'];
                                }
                            }

                            if ($perguntaTYPE == 1) {
                                echo '<div class="Questions" id="qt'.$questionNUM.'">
                                        <h1>Pergunta '.$questionNUM.'</h1>
                                        <textarea name="questionText'.$questionNUM.'" id="questionText'.$questionNUM.'" cols="30" rows="10">'.$perguntaTXT.'</textarea>
                                        <select name="questionType'.$questionNUM.'" id="questionType'.$questionNUM.'">
                                            <option value="T-F">Verdadeiro ou Falso</option>
                                            <option value="3-opts">3 Opções</option>
                                            <option value="4-opts" selected>4 Opções</option>
                                        </select>
                
                                        <div class="Options" id="opt'.$questionNUM.'">
                                            <div class="textOptions">
                                                <div class="ipts_group">
                                                    <input type="text" name="quest'.$questionNUM.'opt1" id="quest'.$questionNUM.'opt1" value="'.$resposta1.'" placeholder="Resposta 1" required>
                                                    <div class="ipts_group1">
                                                        <input type="checkbox" name="quest'.$questionNUM.'opt1correct" id="quest'.$questionNUM.'opt1correct" oninput="HandleCheckBoxClick(\''.$questionNUM.'\', \'3-opts\', \'1\')">
                                                        <p class="opt1">Correta</p>
                                                    </div>
                                                </div>
                                                <div class="ipts_group">
                                                    <input type="text" name="quest'.$questionNUM.'opt2" id="quest'.$questionNUM.'opt2" value="'.$resposta2.'" placeholder="Resposta 2" required>
                                                    <div class="ipts_group1">
                                                        <input type="checkbox" name="quest'.$questionNUM.'opt2correct" id="quest'.$questionNUM.'opt2correct" oninput="HandleCheckBoxClick(\''.$questionNUM.'\', \'3-opts\', \'2\')">
                                                        <p class="opt2">Correta</p>
                                                    </div>
                                                </div>
                                                <div class="ipts_group">
                                                    <input type="text" name="quest'.$questionNUM.'opt3" id="quest'.$questionNUM.'opt3" value="'.$resposta3.'" placeholder="Resposta 3" required>
                                                    <div class="ipts_group1">
                                                        <input type="checkbox" name="quest'.$questionNUM.'opt3correct" id="quest'.$questionNUM.'opt3correct" oninput="HandleCheckBoxClick(\''.$questionNUM.'\', \'3-opts\', \'3\')">
                                                        <p class="opt3">Correta</p>
                                                    </div>
                                                </div>
                                                <div class="ipts_group">
                                                    <input type="text" name="quest'.$questionNUM.'opt4" id="quest'.$questionNUM.'opt4" value="'.$resposta4.'" placeholder="Resposta 4" required>
                                                    <div class="ipts_group1">
                                                        <input type="checkbox" name="quest'.$questionNUM.'opt4correct" id="quest'.$questionNUM.'opt4correct" oninput="HandleCheckBoxClick(\''.$questionNUM.'\', \'3-opts\', \'4\')">
                                                        <p class="opt4">Correta</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <div class="timerQuestion">
                                            <p>Tempo da pergunta:</p>
                                            <input min="1" type="number" name="timerQuestionipt'.$questionNUM.'" id="timerQuestionipt'.$questionNUM.'" value="'.$questionTIME.'">
                                        </div>
                                        
                                        <div id="rm'.$questionNUM.'">
                                            <i class="gg-remove-r" onclick="RemoveQuestion('.$questionNUM.',\'.Questions#qt'.$questionNUM.'\')"></i>
                                        </div>
                                        
                                    </div>'
                                ; 
                            }
                            else if ($perguntaTYPE == 2) {
                                echo '<div class="Questions" id="qt'.$questionNUM.'">
                                        <h1>Pergunta '.$questionNUM.'</h1>
                                        <textarea name="questionText'.$questionNUM.'" id="questionText'.$questionNUM.'" cols="30" rows="10">'.$perguntaTXT.'</textarea>
                                        <select name="questionType'.$questionNUM.'" id="questionType'.$questionNUM.'">
                                            <option value="T-F">Verdadeiro ou Falso</option>
                                            <option value="3-opts" selected>3 Opções</option>
                                            <option value="4-opts">4 Opções</option>
                                        </select>
                
                                        <div class="Options" id="opt'.$questionNUM.'">
                                            <div class="textOptions">
                                                <div class="ipts_group">
                                                    <input type="text" name="quest'.$questionNUM.'opt1" id="quest'.$questionNUM.'opt1" value="'.$resposta1.'" placeholder="Resposta 1" required>
                                                    <div class="ipts_group1">
                                                        <input type="checkbox" name="quest'.$questionNUM.'opt1correct" id="quest'.$questionNUM.'opt1correct" oninput="HandleCheckBoxClick(\''.$questionNUM.'\', \'3-opts\', \'1\')">
                                                        <p class="opt1">Correta</p>
                                                    </div>
                                                </div>
                                                <div class="ipts_group">
                                                    <input type="text" name="quest'.$questionNUM.'opt2" id="quest'.$questionNUM.'opt2" value="'.$resposta2.'" placeholder="Resposta 2" required>
                                                    <div class="ipts_group1">
                                                        <input type="checkbox" name="quest'.$questionNUM.'opt2correct" id="quest'.$questionNUM.'opt2correct" oninput="HandleCheckBoxClick(\''.$questionNUM.'\', \'3-opts\', \'2\')">
                                                        <p class="opt2">Correta</p>
                                                    </div>
                                                </div>
                                                <div class="ipts_group">
                                                    <input type="text" name="quest'.$questionNUM.'opt3" id="quest'.$questionNUM.'opt3" value="'.$resposta3.'" placeholder="Resposta 3" required>
                                                    <div class="ipts_group1">
                                                        <input type="checkbox" name="quest'.$questionNUM.'opt3correct" id="quest'.$questionNUM.'opt3correct" oninput="HandleCheckBoxClick(\''.$questionNUM.'\', \'3-opts\', \'3\')">
                                                        <p class="opt3">Correta</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="timerQuestion">
                                            <p>Tempo da pergunta:</p>
                                            <input min="1" type="number" name="timerQuestionipt'.$questionNUM.'" id="timerQuestionipt'.$questionNUM.'" value="'.$questionTIME.'">
                                        </div>
                
                                        <div id="rm'.$questionNUM.'">
                                            <i class="gg-remove-r" onclick="RemoveQuestion('.$questionNUM.',\'.Questions#qt'.$questionNUM.'\')"></i>
                                        </div>
                                        
                                    </div>'
                                ;
                            }
                            else if ($perguntaTYPE == 3) {
                                echo '<div class="Questions" id="qt'.$questionNUM.'">
                                        <h1>Pergunta '.$questionNUM.'</h1>
                                        <textarea name="questionText'.$questionNUM.'" id="questionText'.$questionNUM.'" cols="30" rows="10">'.$perguntaTXT.'</textarea>
                                        <select name="questionType'.$questionNUM.'" id="questionType'.$questionNUM.'">
                                            <option value="T-F" selected>Verdadeiro ou Falso</option>
                                            <option value="3-opts">3 Opções</option>
                                            <option value="4-opts">4 Opções</option>
                                        </select>
                
                                        <div class="Options" id="opt'.$questionNUM.'">
                                            <div class="T-FOptions">
                                                <input type="checkbox" name="valueTrue'.$questionNUM.'" id="valueTrue'.$questionNUM.'">
                                                <p class="opt1">Verdadeiro</p>
                                            </div>
                                            <div class="T-FOptions">
                                                <input type="checkbox" name="valueFalse'.$questionNUM.'" id="valueFalse'.$questionNUM.'">
                                                <p class="opt2">Falso</p>
                                            </div>
                                        </div>

                                        <div class="timerQuestion">
                                            <p>Tempo da pergunta:</p>
                                            <input min="1" type="number" name="timerQuestionipt'.$questionNUM.'" id="timerQuestionipt'.$questionNUM.'" value="'.$questionTIME.'">
                                        </div>
                
                                        <div id="rm'.$questionNUM.'">
                                            <i class="gg-remove-r" onclick="RemoveQuestion('.$questionNUM.',\'.Questions#qt'.$questionNUM.'\')"></i>
                                        </div>
                                        
                                    </div>'


                                ;
                            }

                        }
                    ?>
                    
                </div>

                <abbr title="Adicionar">
                    <button type="button" onclick="AddQuestion()">
                        <span>+</span>
                    </button>
                </abbr>
            </form>        
        </div>
    </div>

    <div class="statusMessagesContainer"></div>

    <a href="../">
        <div class="createRedirect">
            <i class="gg-play-button-r"></i>
        </div>
    </a>

    <script>
        const NameBeforeModification = '<?php echo $quizName?>';
        
        <?php
            for ($i = $minId; $i <= $maxId; $i++){
                $sql = "SELECT * FROM freequizquestions WHERE quizID = '$quizID' AND questionNUM = '$i'";
                $result = $conexao->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $correctAnswer = $row['questionRESPOSTACERTA'];
                    $questionType = $row['questionTYPE'];
                }

                $row = $result->fetch_assoc();
                if ($questionType == 1 || $questionType == 2) {
                    echo 'document.getElementById(\'quest'.$i.'opt'.$correctAnswer.'correct\').checked = true;';
                }
                else if ($questionType == 3) {
                    if ($correctAnswer == 1) {
                        echo 'document.getElementById(\'valueTrue'.$i.'\').checked = true;';
                    }
                    else if ($correctAnswer == 2) {
                        echo 'document.getElementById(\'valueFalse'.$i.'\').checked = true;';
                    }
                }
            }
        ?>;
        // Auto save tipo
        document.getElementById('quizType').addEventListener('input', () => {
            const QuizTypeSelect = document.getElementById('quizType');

            $.ajax({
                url: `../server/updateFrontQuiz.php?onlyType&quizid=<?php echo $quizID?>&qType=${QuizTypeSelect.value}`,
                headers: { 'My-Custom-Header': '40028922' }
            })
        })
    </script>
    <script src="../assets/js/configQuiz.js"></script>

    <script>
        const sendUpdate = (qNum,qType,qTxt,respC,tfC,resp1,resp2,resp3,resp4,qTime,supQuant) => {
            $.ajax({
                url: `../server/updateQuestions.php?quizid=<?php echo $quizID?>`,
                type: 'POST',
                data: {
                    qNum: qNum,
                    qType: qType,
                    qTxt: qTxt,
                    respC: respC,
                    tfC: tfC,
                    resp1: resp1,
                    resp2: resp2,
                    resp3: resp3,
                    resp4: resp4,
                    qTime: qTime,
                    supQuant: supQuant
                },
                headers: { 'My-Custom-Header': '40028922' }
            })
        }
        const updateFrontQuiz = (qiName,qiType) => {
            $.ajax({
                url: `../server/updateFrontQuiz.php?full&quizid=<?php echo $quizID?>&qType=${qiType}`,
                type: 'POST',
                data: {
                    qName: qiName,
                },
                headers: { 'My-Custom-Header': '40028922' }
            })

            // Imagem
            if (document.querySelector('.imageControl input').files[0] != null){
                const formData = new FormData();
                formData.append('image', document.querySelector('.imageControl input').files[0]);

                $.ajax({
                    url: '../server/updateFrontQuiz.php?imageUpdate&quizid=<?php echo $quizID?>',
                    headers: { 'My-Custom-Header': '40028922' },
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                });
            }
        }
    </script>
</body>
</html>