//Start Animation
const StartAnimation = () => {
    const singlePlayerGameContainer = document.createElement('div')
    singlePlayerGameContainer.className = 'singlePlayerGameContainer'
    singlePlayerGameContainer.innerHTML = '<h1 class="runAnimation">Prepare-se!</h1><div class="numLoader"><div class="numLoader1"><div class="content">10</div></div></div>';
    document.body.appendChild(singlePlayerGameContainer)

    const h1Element = document.querySelector('.singlePlayerGameContainer h1')
    const numLoaderContent = document.querySelector('.numLoader1 .content')

    if (h1Element && numLoaderContent) {
        setTimeout(() => {
            document.querySelector('.numLoader1 .content').innerHTML = '9'
        }, 1000);
        setTimeout(() => {
            document.querySelector('.singlePlayerGameContainer h1').classList.remove('runAnimation')
        }, 1900);
        setTimeout(() => {
            document.querySelector('.singlePlayerGameContainer h1').classList.add('runAnimation');document.querySelector('.singlePlayerGameContainer h1').innerHTML = 'Se Aqueça!';document.querySelector('.numLoader1 .content').innerHTML = '8';
        }, 2000);
        setTimeout(() => {
            document.querySelector('.numLoader1 .content').innerHTML = '7'
        }, 3000);
        setTimeout(() => {
            document.querySelector('.singlePlayerGameContainer h1').classList.remove('runAnimation')
        }, 3900);
        setTimeout(() => {
            document.querySelector('.singlePlayerGameContainer h1').classList.add('runAnimation');document.querySelector('.singlePlayerGameContainer h1').innerHTML = 'Prepare seus motores!';document.querySelector('.numLoader1 .content').innerHTML = '6';
        }, 4000);
        setTimeout(() => {
            document.querySelector('.numLoader1 .content').innerHTML = '5'
        }, 5000);
        setTimeout(() => {
            document.querySelector('.singlePlayerGameContainer h1').classList.remove('runAnimation')
        }, 5900);
        setTimeout(() => {
            document.querySelector('.singlePlayerGameContainer h1').classList.add('runAnimation');document.querySelector('.singlePlayerGameContainer h1').innerHTML = 'Se concentre!';document.querySelector('.numLoader1 .content').innerHTML = '4';
        }, 6000);
        setTimeout(() => {
            document.querySelector('.numLoader1 .content').innerHTML = '3'
        }, 7000);
        setTimeout(() => {
            document.querySelector('.singlePlayerGameContainer h1').classList.remove('runAnimation')
        }, 7900);
        setTimeout(() => {
            document.querySelector('.singlePlayerGameContainer h1').classList.add('runAnimation');document.querySelector('.singlePlayerGameContainer h1').innerHTML = 'Preparou!';document.querySelector('.numLoader1 .content').innerHTML = '2';
        }, 8000);
        setTimeout(() => {
            document.querySelector('.numLoader1 .content').innerHTML = '1'
        }, 9000);
        setTimeout(() => {
            document.querySelector('.singlePlayerGameContainer h1').classList.remove('runAnimation');
        }, 9900);setTimeout(() => {
            document.querySelector('.singlePlayerGameContainer h1').classList.add('runAnimation');document.querySelector('.singlePlayerGameContainer h1').innerHTML = 'VAI!';document.querySelector('.numLoader').remove();
        }, 10000)

        setTimeout(() => {
            document.querySelector('.singlePlayerGameContainer').remove()
        }, 12000)
        setTimeout(() => {
            gerarPerguntas();
        }, 12100);
    }
}
document.addEventListener('DOMContentLoaded', StartAnimation)

//Perguntas
const ShowQuestions = (type,secs,PerguntaNum,PerguntaTXT,PerguntaResposta) => {
    const singlePlayerGameContainer = document.createElement('div')
    singlePlayerGameContainer.className = 'singlePlayerGameContainer'
    
    if (type == 1) {
        singlePlayerGameContainer.innerHTML = '<div class="questionData"> <div class="questionTL"> <div class="rotation"> <div class="content">'+secs+'</div> </div> </div> <div class="questionNumber"> <div class="content">'+PerguntaNum[0]+'/'+PerguntaNum[1]+'</div> </div> </div> <div class="questionContainer"> <h1>Pergunta '+PerguntaNum[0]+'</h1> <p>'+PerguntaTXT+'</p> </div> <div class="respostasContainer"> <button type="button" id="option1" onclick="checkAnswer(1)"> <div class="resposta vermelho"> <svg id="triangle" viewBox="0 0 100 100"> <polygon points="50 15, 100 100, 0 100"/> </svg> <p>'+PerguntaResposta[0]+'</p> </div> </button> <button type="button" id="option2" onclick="checkAnswer(2)"> <div class="resposta azul"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" id="IconChangeColor" height="200" width="200"> <path d="M0 0H512V512H0V0z" id="mainIconPathAttribute"></path> </svg> <p>'+PerguntaResposta[1]+'</p> </div> </button> <button type="button" id="option3" onclick="checkAnswer(3)"> <div class="resposta verde"> <svg id="picture" preserveAspectRatio="xMidYMid meet" viewBox="0 0 100 100"> <circle cx=50 cy=50 r=50></circle> </svg> <p>'+PerguntaResposta[2]+'</p> </div> </button> <button type="button" id="option4" onclick="checkAnswer(4)"> <div class="resposta amarelo"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hexagon-fill" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"/> </svg> <p>'+PerguntaResposta[3]+'</p> </div> </button> </div>'
    }
    else if (type == 2) {
        singlePlayerGameContainer.innerHTML = '<div class="questionData"> <div class="questionTL"> <div class="rotation"> <div class="content">'+secs+'</div> </div> </div> <div class="questionNumber"> <div class="content">'+PerguntaNum[0]+'/'+PerguntaNum[1]+'</div> </div> </div> <div class="questionContainer"> <h1>Pergunta '+PerguntaNum[0]+'</h1> <p>'+PerguntaTXT+'</p> </div> <div class="respostasContainer"> <button type="button" id="option1" onclick="checkAnswer(1)"> <div class="resposta vermelho"> <svg id="triangle" viewBox="0 0 100 100"> <polygon points="50 15, 100 100, 0 100"/> </svg> <p>'+PerguntaResposta[0]+'</p> </div> </button> <button type="button" id="option2" onclick="checkAnswer(2)"> <div class="resposta azul"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" id="IconChangeColor" height="200" width="200"> <path d="M0 0H512V512H0V0z" id="mainIconPathAttribute"></path> </svg> <p>'+PerguntaResposta[1]+'</p> </div> </button> <button type="button" id="option3" onclick="checkAnswer(3)"> <div class="resposta verde"> <svg id="picture" preserveAspectRatio="xMidYMid meet" viewBox="0 0 100 100"> <circle cx=50 cy=50 r=50></circle> </svg> <p>'+PerguntaResposta[2]+'</p> </div> </button></div>'
    }
    else if (type == 3){
        singlePlayerGameContainer.innerHTML = '<div class="questionData"> <div class="questionTL"> <div class="rotation"> <div class="content">'+secs+'</div> </div> </div> <div class="questionNumber"> <div class="content">'+PerguntaNum[0]+'/'+PerguntaNum[1]+'</div> </div> </div> <div class="questionContainer"> <h1>Pergunta '+PerguntaNum[0]+'</h1> <p>'+PerguntaTXT+'</p> </div> <div class="respostasContainer"> <button type="button" id="option1" onclick="checkAnswer(1)"> <div class="resposta vermelho strech"> <svg id="triangle" viewBox="0 0 100 100"> <polygon points="50 15, 100 100, 0 100"/> </svg> <p>VERDADEIRO</p> </div> </button> <button type="button" id="option2" onclick="checkAnswer(2)"> <div class="resposta azul strech"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" id="IconChangeColor" height="200" width="200"> <path d="M0 0H512V512H0V0z" id="mainIconPathAttribute"></path> </svg> <p>FALSO</p> </div> </button></div>'
    }
    else{
        console.log(type)
    }

    document.body.appendChild(singlePlayerGameContainer)
    RunTime(secs)
}

let QuestionTimeRun
const RunTime = (time) => {
    let TimeLeft = time

    QuestionTimeRun = setInterval(() => {
        if (TimeLeft > 1){
            TimeLeft = TimeLeft - 1
            document.querySelector('.questionTL .rotation .content').innerHTML = TimeLeft
        }
        else {
            RunNextQuestion(3)
            setTimeout(() => {queryInformation()}, 5010)
        }
    }, 1000);
}

const RunNextQuestion = (status) => {
    clearInterval(QuestionTimeRun)
    document.querySelector('.singlePlayerGameContainer').remove()

    const singlePlayerGameContainer = document.createElement('div')
    singlePlayerGameContainer.classList = 'singlePlayerGameContainer1'

    if (status === 1) {
        singlePlayerGameContainer.innerHTML = '<div class="questionData1"><div class="questionTL"><div class="rotation"><div class="content">5</div></div></div></div><h1>Você Acertou!</h1><div class="answerStatus right"><span>V</span></div><p class="answerStatusP">Continue assim!</p>'
    }
    else if (status === 2) {
        singlePlayerGameContainer.innerHTML = '<div class="questionData1"><div class="questionTL"><div class="rotation"><div class="content">5</div></div></div></div><h1>Você Errou!</h1><div class="answerStatus wrong"><span>X</span></div><p class="answerStatusP">Boa sorte da próxima vez!</p>'
    }
    else if (status === 3) {
        singlePlayerGameContainer.innerHTML = '<div class="questionData1"><div class="questionTL"><div class="rotation"><div class="content">5</div></div></div></div><h1>Tempo Esgotado!</h1><div class="answerStatus wrong"><span>X</span></div><p class="answerStatusP">Pense mais rápido da próxima vez!</p>'
    }

    document.body.appendChild(singlePlayerGameContainer)

    const secsRunContent = document.querySelector('.rotation .content')
    setTimeout(() => {secsRunContent.innerHTML = '4'}, 1000);setTimeout(() => {secsRunContent.innerHTML = '3'}, 2000);setTimeout(() => {secsRunContent.innerHTML = '2'}, 3000);setTimeout(() => {secsRunContent.innerHTML = '1'}, 4000);
    setTimeout(() => {document.querySelector('.singlePlayerGameContainer1').remove()}, 5000);
}