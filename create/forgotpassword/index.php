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

    <form action="" method="post" id="fst-EmailForm">
        <div class="formContainer">
            <h1 style="text-align: center;">Esqueci minha senha</h1>

            <h2 class="otp-hidden">Código foi enviado para<br><u>(email)</u></h2>

            <div class="opt-inputs otp-hidden">
                <input type="text" maxlength="1">
                <input type="text" maxlength="1">
                <input type="text" maxlength="1">
                <input type="text" maxlength="1">
                <input type="text" maxlength="1">
                <input type="text" maxlength="1">
                <input type="text" maxlength="1">
                <input type="text" maxlength="1">
            </div>
            <div class="ErrorMessage"></div>

            <button type="button" disabled id="otp-button" class="otp-button otp-hidden" onclick="verifyOtp()">Enviar</button>

            <div class="inputGroup" id="emailGroup">
                <input type="text" name="email" id="email" placeholder="Seu E-mail para recuperação" style="width: 100%;">
                <div class="ErrorMessage"></div>
                <div class="loadingAlert"></div>
            </div>

            <div class="formButtons">
                <button type="button" id="goBT">Enviar código</button>
            </div>
        </div>
    </form>

    <a href="../../">
        <div class="createRedirect">
            <i class="gg-play-button-r"></i>
        </div>
    </a>

    <div class="statusMessagesContainer"></div>

    <script src="../../assets/js/otpVerification.js"></script>

    <script>
        // Validação da senha
        const inputPasswordVerify = () => {
            if (document.getElementById('newPassword').value.length >= 8){
                $.ajax({
                    url: `mailSend.php?getPassword&email=${encodeURIComponent(suppEmail)}&password=${encodeURIComponent(document.getElementById('newPassword').value)}`,
                    headers: { 'My-Custom-Header': '40028922' },
                    dataType: 'json',
                    success: function (data) {
                        if (data.password == "equals"){
                            document.querySelector('.ErrorMessage').innerHTML = '<p>A senha não pode ser identica a anterior</p>';
                            document.getElementById('newPassword').style.borderColor = '#ff3333';
                            document.getElementById('c_newPassword').style.borderColor = '#ff3333';

                            document.querySelector('.formContainer button').setAttribute('disabled', true);
                        }
                        else if (document.getElementById('newPassword').value !== document.getElementById('c_newPassword').value){
                            document.getElementById('newPassword').style.borderColor = 'var(--thirdColor)';
                            document.getElementById('c_newPassword').style.borderColor = '#ff3333';
                            document.querySelector('.ErrorMessage').innerHTML = '<p>As senhas não coincidem</p>';

                            document.querySelector('.formContainer button').setAttribute('disabled', true);
                        }
                        else {
                            document.querySelector('.ErrorMessage').innerHTML = '';
                            document.getElementById('newPassword').style.borderColor = 'var(--thirdColor)';
                            document.getElementById('c_newPassword').style.borderColor = 'var(--thirdColor)';

                            document.querySelector('.formContainer button').removeAttribute('disabled');
                        }
                    }
                })
            }
            else {
                document.querySelector('.ErrorMessage').innerHTML = '<p>A senha precisa ter mais de 8 caractéres</p>';
                document.getElementById('newPassword').style.borderColor = '#ff3333';
                document.getElementById('c_newPassword').style.borderColor = '#ff3333';
            }
        }

        const submitPassword = () => {
            $.ajax({
                url: `mailSend.php?resetPassword`,
                headers: { 'My-Custom-Header': '40028922' },
                type: 'POST',
                dataType: 'json',
                data: {
                    email: suppEmail,
                    password: document.getElementById('newPassword').value,
                },
                success: function (data) {
                    if (data.done === true){
                        const showMessageStatus = document.createElement('div');
                        showMessageStatus.classList.add('statusMessages');
                        showMessageStatus.classList.add('good');
                        showMessageStatus.classList.add('inAnimation');
                        showMessageStatus.style.padding = "1vh 0";

                        showMessageStatus.innerHTML = '<h3 style="top: 0;">Boa!</h3><p style="top: 0;">Senha redefinida!</p><i class="gg-close-r" onclick="removeStatusMessage(this)"></i>';
                        document.querySelector('.statusMessagesContainer').appendChild(showMessageStatus);

                        setTimeout(() => {
                            window.location.href = '../../profile/';
                        }, 2000);
                    }
                }
            })
        }
    </script>

    <script>
        // Verificar se o e-mail é cadastrado
        if (document.getElementById('email')){
            document.getElementById('email').addEventListener('input', () => {
                document.querySelector('#emailGroup .ErrorMessage').innerHTML = '';
            })
        }
        
        const validateEmail = () => {
            // Não deixar clicar
            document.getElementById('goBT').removeEventListener('click', validateEmail);

            const enrLoadingBar = document.createElement('div');
            enrLoadingBar.className = "loadingBar1 st";
            enrLoadingBar.innerHTML = "<div class=\"movingBar\">";
            document.querySelector('#emailGroup .loadingAlert').style.marginTop = '1vh';
            document.querySelector('#emailGroup .loadingAlert').style.backgroundColor = 'rgba(87, 255, 168,.4)';
            document.querySelector('#emailGroup .loadingAlert').style.borderRadius = '1vh';
            document.querySelector('#emailGroup .loadingAlert').appendChild(enrLoadingBar);

            setTimeout(() => {
                $.ajax({
                    url: `mailSend.php?sendEmail&email=${document.getElementById('email').value}`,
                    dataType: 'json',
                    headers: { 'My-Custom-Header': '40028922' },
                    success: function (data) {
                        if (data.status == false) {
                            document.querySelector('.loadingBar1.st').className = "loadingBar1 end";

                            setTimeout(() => {
                                document.querySelector('.loadingBar1.end').remove();
                                document.querySelector('#emailGroup .loadingAlert').style.marginTop = '0';
                                document.querySelector('#emailGroup .ErrorMessage').innerHTML = '<p>E-mail não cadastrado</p>';
                                document.querySelector('#emailGroup .loadingAlert').style.backgroundColor = 'transparent';
                                document.querySelector('#emailGroup .loadingAlert').style.borderRadius = '1vh';

                                // Deixa clicar dnv
                                document.getElementById('goBT').addEventListener('click', validateEmail);
                            }, 500);
                        }
                        else if (data.status == "send") {
                            document.querySelector('.loadingBar1.st').className = "loadingBar1 end";
                            setTimeout(() => {
                                document.querySelector('#emailGroup .loadingAlert').style.backgroundColor = 'transparent';
                                document.querySelector('#emailGroup .loadingAlert').style.borderRadius = '1vh';
                                
                                const showMessageStatus = document.createElement('div');
                                showMessageStatus.classList.add('statusMessages');
                                showMessageStatus.classList.add('good');
                                showMessageStatus.classList.add('inAnimation');
                                showMessageStatus.style.padding = "1vh 0"

                                showMessageStatus.innerHTML = '<h3 style="top: 0;">E-mail enviado!</h3><p style="top: 0;">Por favor verifique sua caixa de entrada, não se esqueça de olhar a caixa spam</p><i class="gg-close-r" onclick="removeStatusMessage(this)"></i>';
                                document.querySelector('.statusMessagesContainer').appendChild(showMessageStatus);

                                setTimeout(() => {removeStatusMessage(showMessageStatus.querySelector('.gg-close-r'));}, 5000);

                                setIptsContainer(document.getElementById('email').value);
                            }, 500);
                        }
                        else if (data.status == "noSend"){
                            document.querySelector('.loadingBar1.st').className = "loadingBar1 end";
                            setTimeout(() => {
                                document.querySelector('#emailGroup .loadingAlert').style.backgroundColor = 'transparent';
                                document.querySelector('#emailGroup .loadingAlert').style.borderRadius = '1vh';
                                
                                const showMessageStatus = document.createElement('div');
                                showMessageStatus.classList.add('statusMessages');
                                showMessageStatus.classList.add('good');
                                showMessageStatus.classList.add('inAnimation');
                                showMessageStatus.style.padding = "1vh 0"

                                showMessageStatus.innerHTML = '<h3 style="top: 0;">Código já existe!</h3><p style="top: 0;">Verifique sua caixa de entrada o código mais recente, não se esqueça de olhar a caixa spam</p><i class="gg-close-r" onclick="removeStatusMessage(this)"></i>';
                                document.querySelector('.statusMessagesContainer').appendChild(showMessageStatus);

                                setTimeout(() => {removeStatusMessage(showMessageStatus.querySelector('.gg-close-r'));}, 5000);

                                setIptsContainer(document.getElementById('email').value);
                            }, 500);
                        }
                    }
                })
            }, 2000);
            
        }
        if (document.getElementById('goBT')){
            document.getElementById('goBT').addEventListener('click', validateEmail);
        }
        const removeStatusMessage = (elemento) => {
            elemento.parentNode.classList.remove('inAnimation');
            elemento.parentNode.classList.add('outAnimation');
            setTimeout(() => {
                elemento.parentNode.remove();
            }, 600);
        }

        let suppEmail;
        const setIptsContainer = (email) => {
            // Remover
            document.querySelector('#emailGroup').remove();
            document.querySelector('.formButtons').remove();

            // Remove hidden class
            document.querySelector('.formContainer h2').classList.remove('otp-hidden');
            document.querySelector('.opt-inputs').classList.remove('otp-hidden');
            document.querySelector('.otp-button').classList.remove('otp-hidden');

            document.querySelector('.formContainer h2 u').innerHTML = email;

            suppEmail = email;
        }
        
        const verifyOtp = () => {
            $.ajax({
                url: `mailSend.php?compareCodes&email=${suppEmail}&code=${getOTPString()}`,
                headers: { 'My-Custom-Header': '40028922' },
                dataType: 'json',
                success: function (data) {
                    if (data.isValid === false){
                        const errorMessage = document.createElement('p');
                        errorMessage.innerHTML = 'Código Inválido!';
                        document.querySelector('.ErrorMessage').appendChild(errorMessage);
                    }
                    else if (data.isValid === true){
                        // Show sucesso
                        const showMessageStatus = document.createElement('div');
                        showMessageStatus.className = 'statusMessages good inAnimation';
                        showMessageStatus.style.padding = "1vh 0"

                        showMessageStatus.innerHTML = '<h3 style="top: 0;">Boa!</h3><p style="top: 0;">Agora redefina sua senha!</p><i class="gg-close-r" onclick="removeStatusMessage(this)"></i>';
                        document.querySelector('.statusMessagesContainer').appendChild(showMessageStatus);

                        setTimeout(() => {removeStatusMessage(showMessageStatus.querySelector('.gg-close-r'));}, 5000);

                        // Remover 
                        document.querySelector('.formContainer h2').remove();
                        document.querySelector('.opt-inputs').remove();
                        document.querySelector('.ErrorMessage').remove();
                        document.querySelector('#otp-button').remove();

                        // Mostrar inputs de redefinição de senha
                        document.querySelector('.formContainer h1').innerHTML = "Redefina sua senha";

                        const redefIpts = document.createElement('div');
                        redefIpts.className = "inputGroup";
                        redefIpts.id = "emailGroup";
                        redefIpts.style.gap = "1vh";
                        redefIpts.innerHTML = `<input style="width: 100%;" type="password" id="newPassword" name="newPassword" placeholder="Digite sua nova senha" oninput="inputPasswordVerify()">
                        <input style="width: 100%;" type="password" id="c_newPassword" name="c_newPassword" placeholder="Confirme sua nova senha" oninput="inputPasswordVerify()">
                        
                        <i class="gg-eye-alt" style="position: absolute; right: 1vh; transform: translate(0,3.5vh); opacity: 0; transition: .2s;"></i>
                        <i class="gg-eye" style="position: absolute; right: 1vh; transform: translate(0,3.5vh); transition: .2s;"></i>
                        
                        <div class="ErrorMessage"></div>
                        <button type="button" disabled onclick="submitPassword();">Salvar Senha</button>`;
                        
                        document.querySelector('.formContainer').appendChild(redefIpts);

                        // Adicionar events listeners
                        document.querySelector('.gg-eye').addEventListener('mousedown', () => {
                            document.querySelector('#newPassword').type = 'text';
                            document.querySelector('#c_newPassword').type = 'text';

                            document.querySelector('.gg-eye').style.opacity = '0';
                            document.querySelector('.gg-eye-alt').style.opacity = '1';
                        })
                        document.querySelector('.gg-eye').addEventListener('mouseup', () => {
                            document.querySelector('#newPassword').type = 'password';
                            document.querySelector('#c_newPassword').type = 'password';

                            document.querySelector('.gg-eye').style.opacity = '1';
                            document.querySelector('.gg-eye-alt').style.opacity = '0';
                        })
                    }
                }
            })
        }
    </script>
</body>
</html>