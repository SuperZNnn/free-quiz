let HideAlertTimeOut;

const ShowAlert = (type) => {
    if (type === 1){
        const AlertContainer = document.createElement('div');
        AlertContainer.className = 'alertArea';
        AlertContainer.innerHTML = '<div class="alertContainer"><i class="gg-close-r" onclick="HideAlert()"></i><h1>Sala <br>indisponível!</h1><p>Sua sala não está disponível<br>no momento.</p><div class="loadingAlert"><div class="loadingBar"></div></div></div>';
        document.body.appendChild(AlertContainer);

        HideAlertTimeOut = setTimeout(() => {
            HideAlert();
        }, 5500);
    }
    else if (type === 2){
        const AlertContainer = document.createElement('div');
        AlertContainer.className = 'alertArea';
        AlertContainer.innerHTML = '<div class="alertContainer"><i class="gg-close-r" onclick="HideAlert()"></i><h1>Sala <br>indisponível!</h1><p>Código de sala<br>inválido.</p><div class="loadingAlert"><div class="loadingBar"></div></div></div>';
        document.body.appendChild(AlertContainer);

        HideAlertTimeOut = setTimeout(() => {
            HideAlert();
        }, 5500);
    }
    else if (type === 3){
        const AlertContainer = document.createElement('div');
        AlertContainer.className = 'alertArea';
        AlertContainer.innerHTML = '<div class="fastLogin"><i class="gg-close-r" onclick="HideAlert()"></i><h1>Faça Login</h1><p>Para jogar um quiz no modo Singleplayer é necessário fazer Login!</p><form action="" method="post"><input type="email" name="fastEmail" id="fastEmail" placeholder="E-mail" required><input type="password" name="fastPassword" id="fastPassword" placeholder="Senha" required><a href="create/register.html">Não possue conta? Registre-se</a><button id="tryLoginButton" onclick="tryFastLogin()" type="button">Fazer Login</button></form></div>';
        document.body.appendChild(AlertContainer);
    }  
}

const HideAlert = () => {
    document.querySelector('.alertArea').remove();
    clearTimeout(HideAlertTimeOut);
}

// Hints
let lastHintIndex = -1;
const upHint = () => {
    const paragraphs = document.querySelectorAll('.hints .htsConts p');

    let hints = [
        "Players registrados não perdem sua pontuação ao serem desconectados.",
        "Você não precisa se registrar para jogar o modo Online.",
        "Clique <a style=\"cursor: pointer; color: #000;\" href=\"create/login/\"><u>aqui</u></a> para fazer login.",
        "Clique <a style=\"cursor: pointer; color: #000;\" href=\"create/new.php\"><u>aqui</u></a> para criar seu próprio quiz.",
        "Clique <a style=\"cursor: pointer; color: #000;\" href=\"profile/\"><u>aqui</u></a> para ver seu perfil.",
        "Jogadores registrados podem mostrar sua foto de perfil em jogos online.",
        "Jogadores registrados podem mostrar sua descrição em jogos online.",
    ];

    const getNextHintIndex = () => {
        let nextIndex;
        do {
            nextIndex = Math.floor(Math.random() * hints.length);
        } while (nextIndex === lastHintIndex);
        return nextIndex;
    };

    let nextHintIndex = getNextHintIndex();
    let nextHint = hints[nextHintIndex];
    lastHintIndex = nextHintIndex;

    paragraphs[1].innerHTML = nextHint;
    paragraphs.forEach((p) => { p.classList.add('up'); });

    setTimeout(() => {
        paragraphs[0].remove();
        paragraphs.forEach((p) => { p.classList.remove('up'); });

        const newParagraph = document.createElement('p');
        document.querySelector('.hints .htsConts').appendChild(newParagraph);
    }, 1000);
}
upHint();
setInterval(() => {upHint();}, 3500);