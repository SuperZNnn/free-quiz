<?php

    include_once('../config.php');
    session_start();

    if (isset($_SESSION['user_id'])){
        $userID = $_SESSION['user_id'];

        $sql = "SELECT * FROM users WHERE userid = '$userID'";
        $result = $conexao->query($sql);
        while ($row = $result->fetch_assoc()){
            $nick = $row['username'];
            $descrip = $row['userdescrip'];
            $profileImg = $row['userprofile'];
        }
        if ($profileImg != null){
            $profileImgDataUrl = 'data:image/jpeg;base64,' . base64_encode($profileImg);
        }
        else {
            $profileImgDataUrl = '../assets/images/profile.jpg';
        }
    }
    else {
        $userID = 0;
    }
?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Free Quiz</title>

    <link rel="website icon" href="../assets/images/Logo256.png">
    <link rel="stylesheet" href="../assets/css/style.css">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <header>
        <h1>Free Quiz</h1>
    </header>

    <?php if($userID == 0) {
        echo '<form action="" method="post">
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
                
            <a href="../create/forgotpassword/" class="subir">
                <span>Esqueci minha senha</span>
            </a>

            <div class="formButtons">
                <button type="button" id="EnterBT">Entrar</button>
                <a href="../create/register/">
                    <button type="button">Criar conta</button>
                </a>
                
            </div>
            
        </div>
    </form>';
    }
    else{
        echo '<div class="profileInfo">
        <div class="previewCard">
            <img src="'.$profileImgDataUrl.'" alt="profile">
            <div class="textInformation">
                <h1>'.$nick.'</h1>
                <p>'.$descrip.'</p>
            </div>
        </div>
        <div class="editForm profile">
            <h1>Edite aqui</h1>
            <input type="text" name="nome" id="nome" placeholder="Seu nome">
            <input type="text" name="desc" id="desc" style="margin-top:1vh;" placeholder="Sua descrição" maxlength="50">
            <div class="imageControl">
                <label for="image">Selecione sua foto de perfil</label>
                <input type="file" name="image" id="image" accept="image/*">
            </div>
            <button>Salvar</button>
        </div>
    </div>';
    }?>

    <div class="statusMessagesContainer" style="margin-top: 5vh;"></div>
    
    <a href="../">
        <div class="createRedirect">
            <i class="gg-play-button-r"></i>
        </div>
    </a>

    <?php if ($userID != 0){ echo '<a href="../server/exit.php"><div class="exitRedirect" title="Sair"><i class="gg-close-o"></i></div></a>';}?>

    <script src="../assets/js/validationLogin.js"></script>

    <script>
        <?php
        if (isset($_SESSION['user_id'])) {
            echo '
            // Configurar Perfil
            let originalNick = "'.$nick.'";
            let originalDesc = "'.$descrip.'";

            const NameInput = document.getElementById(\'nome\');
            const DescInput = document.getElementById(\'desc\');
            const ImgInput = document.getElementById(\'image\');

            NameInput.addEventListener(\'input\', () => {
                if (NameInput.value.length > 0){document.querySelector(\'.textInformation h1\').innerHTML = NameInput.value;}
                else{document.querySelector(\'.textInformation h1\').innerHTML = originalNick;}
            })
            DescInput.addEventListener(\'input\', () => {
                if (DescInput.value.length > 0){document.querySelector(\'.textInformation p\').innerHTML = DescInput.value;}
                else{document.querySelector(\'.textInformation p\').innerHTML = originalDesc;}
            })
            ImgInput.addEventListener(\'change\', () => {
                if (ImgInput.files.length > 0) {
                    const file = ImgInput.files[0];
                    let LeitorImg = new FileReader();
                    LeitorImg.onload = function (e) {
                        document.querySelector(\'.previewCard img\').src = e.target.result;
                    };
                    LeitorImg.readAsDataURL(file);
                }
            });
            ';
        }
        ?>

        if (document.querySelector('.editForm button')){
            document.querySelector('.editForm button').addEventListener('click', () => {
                if (ImgInput.files.length > 0){
                    // Testar viabilidade da imagem
                    if (ImgInput.files[0].size < (2 * 1024 * 1024)){
                        // Prosseguir com o update
                        if (NameInput.value.length > 0){
                            $.ajax({
                                url: `../server/updateProfile.php?updateName`,
                                type: 'POST',
                                data: {
                                    value: NameInput.value
                                },
                                headers: { 'My-Custom-Header': '40028922' },
                            })
                        }
                        if (DescInput.value.length > 0){
                            $.ajax({
                                url: `../server/updateProfile.php?updateDesc`,
                                headers: { 'My-Custom-Header': '40028922' },
                                type: 'POST',
                                data: {
                                    value: DescInput.value
                                }
                            })
                        }

                        const formData = new FormData();
                        formData.append('image', ImgInput.files[0]);

                        $.ajax({
                            url: '../server/updateProfile.php?updateProfile',
                            headers: { 'My-Custom-Header': '40028922' },
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                        });
                        showAlerts(1);
                        setTimeout(() => {location.reload()}, 2000);
                        
                    }
                    else{
                        // Imagem de perfil maior que 2mb
                        showAlerts(2);
                    }
                }
                else{
                    showAlerts(1);
                    setTimeout(() => {location.reload()}, 2000);
                    if (NameInput.value.length > 0){
                        $.ajax({
                            url: `../server/updateProfile.php?updateName&value=${NameInput.value}`,
                            headers: { 'My-Custom-Header': '40028922' },
                            type: 'POST',
                            data: {
                                value: NameInput.value
                            }
                        })
                    }
                    if (DescInput.value.length > 0){
                        $.ajax({
                            url: `../server/updateProfile.php?updateDesc&value=${DescInput.value}`,
                            headers: { 'My-Custom-Header': '40028922' },
                            type: 'POST',
                            data: {
                                value: DescInput.value
                            }
                        })
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

        const showAlerts = (status) => {
            const alertMsg = document.createElement('div');
            alertMsg.className = 'statusMessages inAnimation';
            if (status == 1){
                alertMsg.classList.add('good')
                alertMsg.innerHTML = `<h3 style="margin-top: 1.8vh;">Boa!</h3>
            <p style="margin-top: 1.5vh;">Perfil atualizado</p>
            <i class="gg-close-r" onclick="removeStatusMessage(this)"></i>`;
            }
            else if (status == 2){
                alertMsg.classList.add('bad')
                alertMsg.innerHTML = `<h3 style="margin-top: 1.8vh;">Oops!</h3>
            <p style="margin-top: 1.5vh;">Sua foto de perfil excede o tamanho máximo de 2MB!</p>
            <i class="gg-close-r" onclick="removeStatusMessage(this)"></i>`;
            }
            document.querySelector('.statusMessagesContainer').appendChild(alertMsg);

            setTimeout(() => {removeStatusMessage(alertMsg.querySelector('.gg-close-r'))}, 5000);
        }

        // Validar Login
        if (document.getElementById('EnterBT')){
            document.getElementById('EnterBT').addEventListener('click',() =>{
                if (EmailValidationStatus === true && PasswordValidationStatus === true){
                    tryLogin(document.getElementById('email').value,document.getElementById('password').value)
                }
            })
        }

        const tryLogin = (email,senha) => {
            $.ajax({
                url: `../server/getLogin.php`,
                type: 'POST',
                data:{
                    email: email,
                    senha: senha
                },
                headers: { 'My-Custom-Header': '40028922' },
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
                        location.reload();
                    }
                }
            })
        }
    </script>
</body>
</html>