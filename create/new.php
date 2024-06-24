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
    
?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create</title>

    <link rel="icon" href="../assets/images/Logo256.png">
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
                <img src="../assets/images/classroom.jpg" alt="sala">
                <h2>Quiz 001</h2>
            </div>

            <form action="" method="post">
                <div class="ConfigCard">
                    <input type="text" name="quizName" id="quizName" placeholder="Nome do Quiz" required>
                    
                    <div class="imageControl">
                        <label for="image">Foto do Quiz</label>
                        <input type="file" name="image" id="image" accept="image/*" style="border: none;">
                    </div>

                    <span>Tipo do Quiz</span>
                    <select name="quizType" id="quizType">
                        <option value="1">Online</option>
                        <option value="2">Singleplayer</option>
                        <option value="3" selected>Fechado</option>
                    </select>
                    <button type="reset">Reset</button>
                </div>
            </form>

        </div>

        <div class="changeForm changeFormMob">
            <form action="" method="post">
                <abbr title="Salvar">
                    <button type="button" id="newQbt" onclick="saveButton(this)">
                        <span class="glyphicon glyphicon-floppy-disk"></span>
                    </button>
                </abbr>

                <div class="qtscontainer">    
                    <div class="Questions" id="qt1">
                        <h1>Pergunta 1</h1>
                        <textarea name="questionText1" id="questionText1" cols="30" rows="10"></textarea>
                        <select name="questionType1" id="questionType1">
                            <option value="T-F">Verdadeiro ou Falso</option>
                            <option value="3-opts">3 Opções</option>
                            <option value="4-opts">4 Opções</option>
                        </select>

                        <div class="Options" id="opt1">
                            <div class="T-FOptions">
                                <input type="checkbox" name="valueTrue1" id="valueTrue1">
                                <p class="opt1">Verdadeiro</p>
                            </div>
                            <div class="T-FOptions">
                                <input type="checkbox" name="valueFalse1" id="valueFalse1">
                                <p class="opt2">Falso</p>
                            </div>
                        </div>

                        <div class="timerQuestion">
                            <p>Tempo da pergunta:</p>
                            <input type="number" name="timerQuestionipt1" id="timerQuestionipt1" min="1">
                        </div>

                        <div id="rm1">
                            <i class="gg-remove-r" onclick="RemoveQuestion(1,'.Questions#qt1')"></i>
                        </div>
                        
                    </div>
                    
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
        const NameBeforeModification = 'Quiz 001'  
        const ImgLinkBeforeModification = '../assets/images/classroom.jpg'
    </script>

    <script src="../assets/js/configQuiz.js"></script>

    <script>
        let NewCreated;
        const sendUpdate = (qNum,qType,qTxt,respC,tfC,resp1,resp2,resp3,resp4,qTime,supQuant) => {
            $.ajax({
                url: `../server/updateQuestions.php?quizid=${NewCreated}`,
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
        const createQuiz = (qiName,qiType,element) => {
            $.ajax({
                url: `../server/createNewQuiz.php?&ownerID=<?php echo $user_id;?>`,
                headers: { 'My-Custom-Header': '40028922' },
                type: 'POST',
                data: {
                    qName: qiName,
                    qType: qiType
                },
                success: function (data) {
                    NewCreated = data;
                    continueUpdate(element);
                    updateImage(parseInt(data));
                }
            })
        }
        const updateImage = (qid) => {
            if (document.querySelector('.imageControl input').files[0] != null){
                const formData = new FormData();
                formData.append('image', document.querySelector('.imageControl input').files[0]);

                $.ajax({
                    url: `../server/updateFrontQuiz.php?imageUpdate&quizid=${qid}`,
                    headers: { 'My-Custom-Header': '40028922' },
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                });
            }
        }
        const sendToConfigPage = () => {
            setTimeout(() => {
                window.location.href = `./config.php?qid=${parseInt(NewCreated)}`;
            }, 2000);
        }
    </script>
</body>
</html>