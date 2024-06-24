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
            <h1>Login</h1>

            <div class="inputGroup" id="emailGroup">
                <input type="email" name="email" id="email" placeholder="E-mail">
                <div class="ErrorMessage">
                        
                </div>
            </div>
            

            <div class="inputGroup" id="passwordGroup">
                <input type="password" name="password" id="password" placeholder="Senha">
                <div class="ErrorMessage">
                        
                </div>
            </div>
                
            <a href="../forgotpassword/" class="subir">
                <span>Esqueci minha senha</span>
            </a>

            <div class="formButtons">
                <button type="button" id="EnterBT">Entrar</button>
                <a href="../register/">
                    <button type="button">Criar conta</button>
                </a>
                
            </div>
            
        </div>
    </form>

    <a href="../../">
        <div class="createRedirect">
            <i class="gg-play-button-r"></i>
        </div>
    </a>

    <script src="../../assets/js/validationLogin.js"></script>

    <script>
        document.getElementById('EnterBT').addEventListener('click',() =>{
            if (EmailValidationStatus === true && PasswordValidationStatus === true){
                tryLogin(document.getElementById('email').value,document.getElementById('password').value)
            }
        })

        const tryLogin = (email,senha) => {
            $.ajax({
                url: `../../server/getLogin.php`,
                headers: { 'My-Custom-Header': '40028922' },
                type: 'POST',
                data: {
                    email: email,
                    senha: senha
                },
                success: function (data) {
                    if (data === 'noEmail'){
                        document.getElementById('email').style.borderColor = '#ff3333'
                        document.getElementById('email').value = ''
                        document.getElementById('password').style.borderColor = '#ff3333'
                        document.getElementById('password').value = ''
                        document.querySelector('#emailGroup .ErrorMessage').innerHTML = '<p>E-mail não cadastrado</p>'
                    }
                    else if (data === 'noPassword'){
                        document.getElementById('password').style.borderColor = '#ff3333'
                        document.getElementById('password').value = ''
                        document.querySelector('#passwordGroup .ErrorMessage').innerHTML = '<p>Senha Incorreta</p>'
                    }
                    else if (data === 'logged'){
                        window.location.href = '../'
                    }
                }
            })
        }
    </script>
</body>
</html>

<?php exit();?>