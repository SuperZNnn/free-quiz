<?php
    session_start();
    include_once("config.php");

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    }
    else {
        $user_id = 0;
    }

?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Free Quiz</title>

    <link rel="website icon" href="assets/images/Logo256.png">
    <link rel="stylesheet" href="assets/css/style.css">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <header>
        <h1>Free Quiz</h1>
    </header>

    <form>
        <div class="Container">
            <input type="number" placeholder="Insira o código" id="EnterCode" onkeydown="prevenirEnter(event)">
            <button type="button" id="EnterButton" onclick="VerifyCode()">Entrar</button>    
        </div>
    </form>

    <a href="create/">
        <div class="createRedirect" title="Criar">
            <i class="gg-add-r"></i>
        </div>
    </a>
    <a href="profile/">
        <div class="exitRedirect" title="Editar perfil">
            <i class="gg-profile"></i>
        </div>
    </a>

    <div class="hints">
        <h3>Dicas</h3>
        <div class="htsConts">
            <p></p>
            <p></p>
        </div>
    </div>

    <script src="assets/js/homePage.js?v=1.1"></script>

    <script>
        const Input = document.querySelector('.Container input')
        const VerifyCode = () => {
            if (Input.value.length > 0) {
                $.ajax({
                    url: `server/verifyCode.php?supCode=${Input.value}`,
                    headers: { 'My-Custom-Header': '40028922' },
                    success: function (data) {
                        if (data === 'goMultiplayer') { window.location.href = `game/multiplayer.php?quizid=${Input.value}` }
                        else if (data === 'unavailable') { ShowAlert(1) }
                        else if (data === 'false'){ ShowAlert(2) }
                        else if (data === 'askLogin'){ detectSession() }
                    }
                })
            }
        }

        const detectSession = () => {
            let currentUserId = <?php echo $user_id;?>;
            if (currentUserId > 0) {
                trySessionLogin(currentUserId,Input.value);
            }
            else{
                ShowAlert(3);
            }
        }

        const trySessionLogin = (userid,quizid) => {
            $.ajax({
                url: `server/getLogin.php?userid=${userid}&quizid=${quizid}`,
                headers: { 'My-Custom-Header': '40028922' },
                success: function (data) {
                    if (data === 'played'){
                        alert('Você já jogou esse quiz!')
                    }
                    else if (data === 'allow'){
                        window.location.href = `game/singleplayer.php?quizid=${quizid}`
                    }
                }
            })
        }

        const tryFastLogin = () => {
            const EmailInput = document.getElementById('fastEmail');
            const PasswordInput = document.getElementById('fastPassword');
            
            if (EmailInput.value.length > 0 && PasswordInput.value.length > 0) {
                $.ajax({
                    url: `server/getLogin.php?fastLogin&quizid=${Input.value}`,
                    type: 'POST',
                    data: {
                        email: EmailInput.value,
                        senha: PasswordInput.value,
                    },
                    headers: { 'My-Custom-Header': '40028922' },
                    success: function (data) {
                        if (data === 'played'){
                            alert('Você já jogou esse quiz!');
                            EmailInput.value = '';
                            PasswordInput.value = '';
                        }
                        else if (data === 'noEmail'){
                            alert('E-mail não cadastrado!');
                            EmailInput.value = '';
                            PasswordInput.value = '';
                        }
                        else if (data === 'noPassword'){
                            alert('Senha Incorreta!');
                            PasswordInput.value = '';
                        }
                        else if (data === 'logged'){
                            window.location.href = `game/singleplayer.php?quizid=${Input.value}`;
                        }
                    }
                })
            }
            else{
                alert('Preencha todos os campos!')
            }
        }

        const prevenirEnter = (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        }
    </script>
</body>
</html>