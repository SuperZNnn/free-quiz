const joinFakeName = () => {
    const iptsContainer = document.createElement('div');
    iptsContainer.className = 'Container';
    iptsContainer.innerHTML = '<input type="text" placeholder="Digite seu Nome" id="NameIpt"><button type="button" id="doneBt" onclick="showWaitScreen(ImgUrl,quizName,questionsQuant,document.getElementById(\'NameIpt\').value,true)">Pronto</button>';
    document.body.appendChild(iptsContainer);
}

const showWaitScreen = (imgSRC,quizName,questionQuant,name,isFake) => {
    if (document.querySelector('.Container')){
        document.querySelector('.Container').remove();
    }
    
    const waitScreen = document.createElement('div');
    waitScreen.className = 'waitScreen';
    waitScreen.innerHTML = '<div class="InformationContainer"><div class="ConfigCard"><img src="'+imgSRC+'" alt="sala"><h2>'+quizName+'</h2><p><span>'+questionQuant+'</span> perguntas</p></div></div><div class="numLoader" style="position: relative; left: 50%; transform: translate(-50%); margin-top: 2vh;"><div class="numLoader1"></div></div><h1>Esperando pelo Host</h1>';
    document.body.appendChild(waitScreen);

    // join Players
    if (isFake) {
        doublePlayerDetect(name,2);
    }
    else {
        doublePlayerDetect(name,1);
    }
}

const showPlayerCard = (hasDesc,playerName,playerDesc,playerProfile) => {
    const playerCard = document.createElement('div');
    playerCard.className = 'playerInfo';
    if (hasDesc === true){
        playerCard.innerHTML = `<img src="${playerProfile}" alt="icon">
        <div class="textInfo">
            <h1>${playerName}</h1>
            <p>${playerDesc}</p>
        </div>`;
    }
    else {
        playerCard.innerHTML = `<img src="../../assets/images/profile.jpg" alt="icon">
        <div class="textInfo">
            <h1>${playerName}</h1>
        </div>`;
    }
    document.querySelector('.playersContainer').appendChild(playerCard);
}

const getReadyAnimation = (mode) => {
    if (document.querySelector('.singlePlayerGameContainer1')){
        document.querySelector('.singlePlayerGameContainer1').remove();
    }
    // Remover Container de espera
    if (mode == 1){
        if (document.querySelector('.waitScreen')){
            document.querySelector('.waitScreen').remove();
        }
    }
    if (mode == 2){
        if (document.querySelector('.playersContainer')) {
            document.querySelector('.playersContainer').remove();
            document.querySelector('.roomInfo').remove();
            document.querySelector('.continueButton').remove();
        }
        else if (document.querySelector('.actualRankingContainer')){
            document.querySelector('.actualRankingContainer').remove();
            document.querySelector('.continueButton').remove();
        } 
    }

    // Criar container da Animação
    const animationContainer = document.createElement('div');
    animationContainer.className = 'singlePlayerGameContainer';
    animationContainer.innerHTML = `<h1 class="runAnimation">Próxima pergunta em:</h1>
    <div class="numLoader">
        <div class="numLoader1">
            <div class="content">5</div>
        </div>
    </div>`;

    document.body.appendChild(animationContainer)

    setTimeout(() => {document.querySelector('.numLoader1 .content').innerHTML = '4'}, 1000);
    setTimeout(() => {document.querySelector('.numLoader1 .content').innerHTML = '3'}, 2000);
    setTimeout(() => {document.querySelector('.numLoader1 .content').innerHTML = '2'}, 3000);
    setTimeout(() => {document.querySelector('.numLoader1 .content').innerHTML = '1'}, 4000);

    if (mode == 1) {
        setTimeout(() => {document.querySelector('.singlePlayerGameContainer h1').classList.remove('runAnimation')}, 4900);
        setTimeout(() => {
            document.querySelector('.singlePlayerGameContainer h1').classList.add('runAnimation');
            document.querySelector('.singlePlayerGameContainer h1').innerHTML = 'Preste atenção na pergunta!';
            document.querySelector('.numLoader1 .content').innerHTML = '10'
        }, 5000);
        setTimeout(() => {document.querySelector('.numLoader1 .content').innerHTML = '9'}, 6000);
        setTimeout(() => {document.querySelector('.numLoader1 .content').innerHTML = '8'}, 7000);
        setTimeout(() => {document.querySelector('.numLoader1 .content').innerHTML = '7'}, 8000);
        setTimeout(() => {document.querySelector('.numLoader1 .content').innerHTML = '6'}, 9000);
        setTimeout(() => {document.querySelector('.numLoader1 .content').innerHTML = '5'}, 10000);
        setTimeout(() => {document.querySelector('.numLoader1 .content').innerHTML = '4'}, 11000);
        setTimeout(() => {document.querySelector('.numLoader1 .content').innerHTML = '3'}, 12000);
        setTimeout(() => {document.querySelector('.numLoader1 .content').innerHTML = '2'}, 13000);
        setTimeout(() => {document.querySelector('.numLoader1 .content').innerHTML = '1'}, 14000);

        setTimeout(() => {getQuestions();document.querySelector('.singlePlayerGameContainer').remove();}, 15000);
    }
    else if (mode == 2){
        setTimeout(() => {
            document.querySelector('.singlePlayerGameContainer').remove();
            getQuestionsInformation()
        }, 5000);
    }
}

const showAnswers = (type,resp1,resp2,resp3,resp4) => {
    const multiplayerGameAlternativas = document.createElement('div');
    multiplayerGameAlternativas.className = 'MultiplayerGameAlternativas';

    if (type == 1) {
        multiplayerGameAlternativas.innerHTML = `<div class="question qt1" onclick="playerAltSelect(1)">
        <svg id="triangle" viewBox="0 0 100 100"><polygon points="50 15, 100 100, 0 100"/></svg>
        <h1>${resp1}</h1>
    </div>
    <div class="question qt2" onclick="playerAltSelect(2)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" id="IconChangeColor" height="200" width="200"> <path d="M0 0H512V512H0V0z" id="mainIconPathAttribute"></path> </svg>
        <h1>${resp2}</h1>
    </div>
    <div class="question qt3" onclick="playerAltSelect(3)">
        <svg id="picture" preserveAspectRatio="xMidYMid meet" viewBox="0 0 100 100"> <circle cx=50 cy=50 r=50></circle> </svg>
        <h1>${resp3}</h1>
    </div>
    <div class="question qt4" onclick="playerAltSelect(4)">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hexagon-fill" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"/> </svg>
        <h1>${resp4}</h1>        
    </div>`;
    }
    else if (type == 2) {
        multiplayerGameAlternativas.innerHTML = `<div class="question qt1" onclick="playerAltSelect(1)">
        <svg id="triangle" viewBox="0 0 100 100"><polygon points="50 15, 100 100, 0 100"/></svg>
        <h1>${resp1}</h1>
    </div>
    <div class="question qt2" onclick="playerAltSelect(2)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" id="IconChangeColor" height="200" width="200"> <path d="M0 0H512V512H0V0z" id="mainIconPathAttribute"></path> </svg>
        <h1>${resp2}</h1>
    </div>
    <div class="question qt3 strech" onclick="playerAltSelect(3)">
        <svg id="picture" preserveAspectRatio="xMidYMid meet" viewBox="0 0 100 100"> <circle cx=50 cy=50 r=50></circle> </svg>
        <h1>${resp3}</h1>
    </div>`;
    }
    else if (type == 3) {
        multiplayerGameAlternativas.innerHTML = `<div class="question qt1 strech" onclick="playerAltSelect(1)">
        <svg id="triangle" viewBox="0 0 100 100"><polygon points="50 15, 100 100, 0 100"/></svg>
        <h1>Verdadeiro</h1>
    </div>
    <div class="question qt2 strech" onclick="playerAltSelect(2)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" id="IconChangeColor" height="200" width="200"> <path d="M0 0H512V512H0V0z" id="mainIconPathAttribute"></path> </svg>
        <h1>Falso</h1>
    </div>`;
    }

    document.body.appendChild(multiplayerGameAlternativas);
}

const admShowQuestion = (type,resp1,resp2,resp3,resp4,time,num,txt) => {
    const admQuestion = document.createElement('div');
    admQuestion.className = 'admQuestionInfo';
    
    if (type == 1) {
        admQuestion.innerHTML = `<div class="perguntaContainer">
        <h1>Pergunta ${num}</h1>
        <p>${txt}</p>
    </div>

    <div class="numLoader"><div class="numLoader1"><div class="content"></div></div></div>

    <div class="respostaContainer">
        <div class="qt1 question"><span>${resp1}</span></div>
        <div class="qt2 question"><span>${resp2}</span></div>
        <div class="qt3 question"><span>${resp3}</span></div>
        <div class="qt4 question"><span>${resp4}</span></div>
    </div>`;
    }
    else if (type == 2) {
        admQuestion.innerHTML = `<div class="perguntaContainer">
        <h1>Pergunta ${num}</h1>
        <p>${txt}</p>
    </div>

    <div class="numLoader"><div class="numLoader1"><div class="content"></div></div></div>

    <div class="respostaContainer">
        <div class="qt1 question"><span>${resp1}</span></div>
        <div class="qt2 question"><span>${resp2}</span></div>
        <div class="qt3 question strech"><span>${resp3}</span></div>
    </div>`;
    }
    else if (type == 3) {
        admQuestion.innerHTML = `<div class="perguntaContainer">
        <h1>Pergunta ${num}</h1>
        <p>${txt}</p>
    </div>

    <div class="numLoader"><div class="numLoader1"><div class="content"></div></div></div>

    <div class="respostaContainer">
        <div class="qt1 question strech"><span>Verdadeiro</span></div>
        <div class="qt2 question strech"><span>Falso</span></div>
    </div>`;
    }

    document.body.appendChild(admQuestion);

    setTimeout(() => {
        document.querySelector('.numLoader1 .content').innerHTML = time;
        timerCountdownLoop = setInterval(() => {timerCountdown()}, 1000);
    }, 10000);
    setTimeout(() => {
        document.querySelector('.perguntaContainer').classList.add('anim');
        document.querySelector('.respostaContainer').classList.add('anim');
    }, 5000);
}
let timerCountdownLoop;
let numTimer;
const timerCountdown = () => {
    numTimer = parseInt(document.querySelector('.numLoader1 .content').innerHTML);

    if (numTimer > 0){
        document.querySelector('.numLoader1 .content').innerHTML = (numTimer - 1)
    }
    else {
        revealChooses();
        clearInterval(timerCountdownLoop);
        clearInterval(testPlayerClock);
    }
}

// Armazenar alternativa do player
let ChangeTitleLoop;
const playerAltSelect = (alt) => {
    playerAlt = parseInt(alt);

    setPlayerAlt(playerAlt);
    // Alterar as telas
    document.querySelector('.hints').remove();
    document.querySelector('.MultiplayerGameAlternativas').remove();

    const waitScreen = document.createElement('div');
    waitScreen.className = 'waitScreen';
    waitScreen.innerHTML = '<div class="InformationContainer column"><div class="numLoader"><div class="numLoader1"></div></div><h1>Esperando pelo Host</h1>';
    document.body.appendChild(waitScreen);

    document.querySelector('.waitScreen h1').setAttribute('data-index', -1);

    ChangeTitle();
    ChangeTitleLoop = setInterval(ChangeTitle, 3000);
    clearTimeout(timerCLD);
}

// Game Wait Screen
const ChangeTitle = () => {
    let Frases = [
        "3 + 4 = 34??",
        "Terra não tem gosto de Açaí",
        "Eu não sou seu advogado",
        "E se eu fosse um astronauta?",
        "Pizza é redonda, mas a caixa é quadrada. Conspiração da geometria?",
        "Se o tempo voa quando estamos nos divertindo, será que ele rasteja quando estamos na fila do banco?",
        "Nunca entendi por que chamam de 'edifício' se já está construído. Deveria ser 'construído' mesmo, né?",
        "Se a prática leva à perfeição e ninguém é perfeito, por que praticar?",
        "Tomate é uma fruta, ketchup é um smoothie. Estamos todos vivendo no mundo das bebidas saudáveis?",
        "Por que chamam de sanduíche se nunca areia?",
        "Se eu pudesse escolher entre ser invisível ou ter superpoderes, eu escolheria sempre ter superpoderes invisíveis.",
        "A última vez que olhei, não havia nada de 'normal' em ser um donut, mas todos parecem amar.",
        "Se a prática leva à perfeição, estou praticando ser imperfeito com maestria.",
        "Se os gatos sempre caem de pé, os pombos sempre pousam de bico?",
        "O que acontece se um peixe sofre de insônia? Ele fica contando humanos?",
        "Se as galinhas cruzam a rua para chegar do outro lado, o que fazem os patos?",
        "Se os animais falassem, os zoológicos seriam chamados de 'reclamatórios'?",
        "Por que usamos agulhas para tirar sangue, mas usamos algodão para limpar lágrimas?",
        "Você já pensou que todas as gotas de chuva têm suas próprias quedas livres?",
        "Se os olhos são as janelas da alma, as janelas são as portas dos olhos?",
        "Por que é que quanto mais a gente cresce, menos a gente brinca de pega-pega?",
        "Se os pinguins não voam, os icebergs são suas aeronaves?",
        "Por que as teclas de um piano não têm nome de país, mas de música?",
        "Quando você tira um peixe da água, ele acha que está de férias ou que é um voo cancelado?",
        "O que acontece com os sonhos de um travesseiro quando o deixamos sozinho?",
        "Se as nuvens podem fazer sombras, será que fazem também desenhos animados?",
        "Se os sapatos sempre estão nos pés, quem usa eles para passear?"
    ];
    
    let titleElement = document.querySelector('.waitScreen h1');
    let currentIndex = parseInt(titleElement.getAttribute('data-index'));
    let newIndex;
    do {
        newIndex = Math.floor(Math.random() * Frases.length);
    } while (newIndex === currentIndex);

    titleElement.setAttribute('data-index', newIndex);
    titleElement.innerHTML = Frases[newIndex];
}

const createContainerChooses = (quant,type) => {
    const choosesShowContainer = document.createElement('div');
    choosesShowContainer.className = 'finalResult';
    choosesShowContainer.innerHTML = `<p><b>${quant}</b> pessoas escolheram essa opção.</p>`

    switch (type) {
        case 1:
            document.querySelector('.qt1').appendChild(choosesShowContainer);
            break;
        case 2:
            document.querySelector('.qt2').appendChild(choosesShowContainer);
            break;
        case 3:
            document.querySelector('.qt3').appendChild(choosesShowContainer);
            break;
        case 4:
            document.querySelector('.qt4').appendChild(choosesShowContainer);
            break;
    }
}

const RunNextQuestion = (status) => {
    if (document.querySelector('.waitScreen')){
        document.querySelector('.waitScreen').remove();
    }
    if (document.querySelector('.MultiplayerGameAlternativas')){
        document.querySelector('.MultiplayerGameAlternativas').remove();
    }
    

    clearInterval(ChangeTitleLoop);

    const singlePlayerGameContainer = document.createElement('div');
    singlePlayerGameContainer.classList = 'singlePlayerGameContainer1';

    if (status === 1) {
        singlePlayerGameContainer.innerHTML = '<div class="questionData1"><div class="questionTL"><div class="rotation"><div class="content">5</div></div></div></div><h1>Você Acertou!</h1><div class="answerStatus right"><span>V</span></div><p class="answerStatusP">Continue assim!</p>'
    }
    else if (status === 2) {
        singlePlayerGameContainer.innerHTML = '<div class="questionData1"><div class="questionTL"><div class="rotation"><div class="content">5</div></div></div></div><h1>Você Errou!</h1><div class="answerStatus wrong"><span>X</span></div><p class="answerStatusP">Boa sorte da próxima vez!</p>'
    }
    else if (status === 3) {
        singlePlayerGameContainer.innerHTML = '<div class="questionData1"><div class="questionTL"><div class="rotation"><div class="content">5</div></div></div></div><h1>Tempo Esgotado!</h1><div class="answerStatus wrong"><span>X</span></div><p class="answerStatusP">Pense mais rápido da próxima vez!</p>'
    }

    document.body.appendChild(singlePlayerGameContainer);

    setTimeout(() => {document.querySelector('.rotation .content').innerHTML = '4'}, 1000);
    setTimeout(() => {document.querySelector('.rotation .content').innerHTML = '3'}, 2000);
    setTimeout(() => {document.querySelector('.rotation .content').innerHTML = '2'}, 3000);
    setTimeout(() => {document.querySelector('.rotation .content').innerHTML = '1'}, 4000);
    setTimeout(() => {document.querySelector('.singlePlayerGameContainer1').remove()}, 5000);
}