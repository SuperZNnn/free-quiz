<?php

    session_start();
    if (isset($_SESSION['user_id'])){
        header('Location: ../../');

        echo $_SESSION['$user_id'];
    }

?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create</title>

    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="icon" href="../../assets/images/Logo256.png">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <header>
        <h1>Free Quiz</h1>
    </header>

    <form action="" method="post">
        <div class="formContainer">
            <h1>Registrar</h1>

            <div class="inputGroup" id="nickGroup">
                <input type="text" name="nick" id="nick" placeholder="Apelido" required>
                
                <div class="ErrorMessage">

                </div>
            </div>
            
            <div class="inputGroup" id="emailGroup">
                <input type="email" name="email" id="email" placeholder="E-mail" required>
                <input style="margin-top: 1vh;" type="email" name="c-email" id="c-email" placeholder="Confirme seu E-mail" required>

                <div class="ErrorMessage">
                    
                </div>
            </div>

            <div class="inputGroup" id="passwordGroup">
                <input type="password" name="password" id="password" placeholder="Senha" required>
                <input style="margin-top: 1vh;" type="password" name="c-password" id="c-password" placeholder="Confirme sua Senha" required>
                
                <div class="ErrorMessage">
                    
                </div>
            </div>

            <a href="../login/">
                <span>Já possuo uma conta!</span>
            </a>

            <div class="formButtons">
                <button type="button" id="RegButton">Registrar</button>
            </div>
            
        </div>
    </form>

    <div class="statusMessagesContainer"></div>

    <a href="../../">
        <div class="createRedirect">
            <i class="gg-play-button-r"></i>
        </div>
    </a>

    <script src="../../assets/js/validationRegister.js"></script>

    <script>
        document.getElementById('RegButton').addEventListener('click',() => {
            if (NickValidationStatus === true && EmailValidationStatus === true && PasswordLenghtStatus === true && PasswordValidationStatus === true){
                tryRegister(document.getElementById('nick').value,document.getElementById('email').value,document.getElementById('password').value)
            }
        })

        const tryRegister = (nick,email,password) => {
            $.ajax({
                url: `../../server/registerPeople.php`,
                headers: { 'My-Custom-Header': '40028922' },
                type: 'POST',
                data: {
                    nick: nick,
                    email: email,
                    password: password
                },

                success: function (data){
                    if (data === 'jaCadastrado'){
                        const showMessageStatus = document.createElement('div');
                        showMessageStatus.classList.add('statusMessages');
                        showMessageStatus.classList.add('bad');
                        showMessageStatus.classList.add('inAnimation');

                        showMessageStatus.innerHTML = '<h3 style="top: 0;">Oops!</h3><p style="top: 0;">E-mail já cadastrado</p><i class="gg-close-r" onclick="removeStatusMessage(this)"></i>';
                        document.querySelector('.statusMessagesContainer').appendChild(showMessageStatus);
                        setTimeout(() => {removeStatusMessage(showMessageStatus.querySelector('.gg-close-r'))}, 2500);
                        
                        document.getElementById('email').value = '';
                        document.getElementById('c-email').value = '';
                    }
                    else if (data === 'cadastradoSucesso') {
                        const showMessageStatus = document.createElement('div');
                        showMessageStatus.classList.add('statusMessages');
                        showMessageStatus.classList.add('good');
                        showMessageStatus.classList.add('inAnimation');

                        showMessageStatus.innerHTML = '<h3 style="top: 0;">Boa!</h3><p style="top: 0;">Cadastrado com sucesso</p><i class="gg-close-r" onclick="removeStatusMessage(this)"></i>';
                        document.querySelector('.statusMessagesContainer').appendChild(showMessageStatus);
                        setTimeout(() => {removeStatusMessage(showMessageStatus.querySelector('.gg-close-r'))}, 2500);

                        document.getElementById('nick').value = '';
                        document.getElementById('email').value = '';
                        document.getElementById('c-email').value = '';
                        document.getElementById('password').value = '';
                        document.getElementById('c-password').value = '';

                        setTimeout(() => {
                            window.location.href = '../../profile/';
                        }, 1500);
                    }
                    else if (data === 'algoInvalido') {
                        const showMessageStatus = document.createElement('div');
                        showMessageStatus.classList.add('statusMessages');
                        showMessageStatus.classList.add('bad');
                        showMessageStatus.classList.add('inAnimation');

                        showMessageStatus.innerHTML = '<h3 style="top: 0;">Oops!</h3><p style="top: 0;">Você esqueceu alguma informação</p><i class="gg-close-r" onclick="removeStatusMessage(this)"></i>';
                        document.querySelector('.statusMessagesContainer').appendChild(showMessageStatus);
                        setTimeout(() => {removeStatusMessage(showMessageStatus.querySelector('.gg-close-r'))}, 2500);

                        document.getElementById('nick').value = '';
                        document.getElementById('email').value = '';
                        document.getElementById('c-email').value = '';
                        document.getElementById('password').value = '';
                        document.getElementById('c-password').value = '';
                    }
                }
            })
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