//Quiz Name Change
const QuizNameInput = document.getElementById('quizName')

const NameChange = () => {
    if (QuizNameInput.value.length > 0) {
        document.querySelector('.ConfigCard h2').innerHTML = QuizNameInput.value
    }
    else {
        document.querySelector('.ConfigCard h2').innerHTML = NameBeforeModification
    }
}

QuizNameInput.addEventListener('input', NameChange)

//Image Change
const ImgContainer = document.querySelector('.imageControl input');
ImgContainer.addEventListener('change',() => {
    if (ImgContainer.files.length > 0) {
        const file = ImgContainer.files[0];
        let imgReader = new FileReader;
        imgReader.onload = (e) => {
            document.querySelector('.ConfigCard img').src = e.target.result;
        }
        imgReader.readAsDataURL(file);
    }
})

//Reset Button
document.querySelector('.ConfigCard button').addEventListener('click', () => {
    setTimeout(() => {
        ImgChange()
        NameChange()
    }, 2);
})

//Question Type Change
const ChangeQuestionType = (opt, qtype, nPergunta) => {
    if (document.getElementById(qtype).value === "T-F") {
        document.getElementById(opt).innerHTML = '<div class="T-FOptions"><input type="checkbox" name="valueTrue'+ nPergunta +'" id="valueTrue'+ nPergunta +'" oninput="HandleCheckBoxClick(\''+ nPergunta +'\', \'T-F\', \'1\')"><p class="opt1">Verdadeiro</p></div><div class="T-FOptions"><input type="checkbox" name="valueFalse'+ nPergunta +'" id="valueFalse'+ nPergunta +'" oninput="HandleCheckBoxClick(\''+ nPergunta +'\', \'T-F\', \'2\')"><p class="opt2">Falso</p></div>'
    }
    else if (document.getElementById(qtype).value === "3-opts") {
        document.getElementById(opt).innerHTML = '<div class="textOptions"><div class="ipts_group"><input required type="text" name="quest'+ nPergunta +'opt1" id="quest'+ nPergunta +'opt1" placeholder="Resposta 1"><div class="ipts_group1"><input type="checkbox" name="quest'+ nPergunta +'opt1correct" id="quest'+ nPergunta +'opt1correct" oninput="HandleCheckBoxClick(\''+nPergunta+'\', \'3-opts\', \'1\')"><p class="opt1">Correta</p></div></div><div class="ipts_group"><input required type="text" name="quest'+ nPergunta +'opt2" id="quest'+ nPergunta +'opt2" placeholder="Resposta 2"><div class="ipts_group1"><input type="checkbox" name="quest'+ nPergunta +'opt2correct" id="quest'+ nPergunta +'opt2correct" oninput="HandleCheckBoxClick(\''+nPergunta+'\', \'3-opts\', \'2\')"><p class="opt2">Correta</p></div></div><div class="ipts_group"><input required type="text" name="quest'+ nPergunta +'opt3" id="quest'+ nPergunta +'opt3" placeholder="Resposta 3"><div class="ipts_group1"><input type="checkbox" name="quest'+ nPergunta +'opt3correct" id="quest'+ nPergunta +'opt3correct" oninput="HandleCheckBoxClick(\''+nPergunta+'\', \'3-opts\', \'3\')"><p class="opt3">Correta</p></div></div></div>'
    }
    else if (document.getElementById(qtype).value === "4-opts") {
        document.getElementById(opt).innerHTML = '<div class="textOptions"><div class="ipts_group"><input required type="text" name="quest'+ nPergunta +'opt1" id="quest'+ nPergunta +'opt1" placeholder="Resposta 1"><div class="ipts_group1"><input type="checkbox" name="quest'+ nPergunta +'opt1correct" id="quest'+ nPergunta +'opt1correct" oninput="HandleCheckBoxClick(\''+nPergunta+'\', \'3-opts\', \'1\')"><p class="opt1">Correta</p></div></div><div class="ipts_group"><input required type="text" name="quest'+ nPergunta +'opt2" id="quest'+ nPergunta +'opt2" placeholder="Resposta 2"><div class="ipts_group1"><input type="checkbox" name="quest'+ nPergunta +'opt2correct" id="quest'+ nPergunta +'opt2correct" oninput="HandleCheckBoxClick(\''+nPergunta+'\', \'3-opts\', \'2\')"><p class="opt2">Correta</p></div></div><div class="ipts_group"><input required type="text" name="quest'+ nPergunta +'opt3" id="quest'+ nPergunta +'opt3" placeholder="Resposta 3"><div class="ipts_group1"><input type="checkbox" name="quest'+ nPergunta +'opt3correct" id="quest'+ nPergunta +'opt3correct" oninput="HandleCheckBoxClick(\''+nPergunta+'\', \'3-opts\', \'3\')"><p class="opt3">Correta</p></div></div><div class="ipts_group"><input required type="text" name="quest'+ nPergunta +'opt4" id="quest'+ nPergunta +'opt4" placeholder="Resposta 4"><div class="ipts_group1"><input type="checkbox" name="quest'+ nPergunta +'opt4correct" id="quest'+ nPergunta +'opt4correct" oninput="HandleCheckBoxClick(\''+nPergunta+'\', \'3-opts\', \'4\')"><p class="opt4">Correta</p></div></div></div>'
    }
}

// Permitir só uma alternativa
const HandleCheckBoxClick = (opt, type, num) => {
    if (type === "T-F") {
        if (num === "1") {
            document.getElementById('valueFalse'+opt).checked = false
        }
        else if (num === "2") {
            document.getElementById('valueTrue'+opt).checked = false
        }
    }

    else if (type === "3-opts" || type === "4-opts") {
        if (num === "1"){
            document.getElementById('quest'+ opt +'opt2correct').checked = false
            document.getElementById('quest'+ opt +'opt3correct').checked = false
            document.getElementById('quest'+ opt +'opt4correct').checked = false
        }
        else if (num === "2"){
            document.getElementById('quest'+ opt +'opt1correct').checked = false
            document.getElementById('quest'+ opt +'opt3correct').checked = false
            document.getElementById('quest'+ opt +'opt4correct').checked = false
        }
        else if (num === "3"){
            document.getElementById('quest'+ opt +'opt1correct').checked = false
            document.getElementById('quest'+ opt +'opt2correct').checked = false
            document.getElementById('quest'+ opt +'opt4correct').checked = false
        }
        else if (num === "4"){
            document.getElementById('quest'+ opt +'opt1correct').checked = false
            document.getElementById('quest'+ opt +'opt2correct').checked = false
            document.getElementById('quest'+ opt +'opt3correct').checked = false
        }
    }
}

// Reorganizar Scripts
const removeScripts = () => {
    const scriptsToRemove = document.querySelectorAll('.QuestionsScript');

    scriptsToRemove.forEach(script => {
        script.parentNode.removeChild(script);
    });
}
const ReorgScript = () => {
    let questionsQuant = document.querySelectorAll('.Questions').length

    removeScripts();

    for (let i = 1; i <= questionsQuant; i++) {
        let QuestionsScript = document.createElement('script')
        QuestionsScript.className = 'QuestionsScript'
        QuestionsScript.textContent = `document.querySelector('#qt${i} select').addEventListener('change', () => {ChangeQuestionType('opt${i}','questionType${i}', '${i}')});document.querySelector('.Options#opt${i} #valueTrue${i}').addEventListener('input', () => {HandleCheckBoxClick('${i}', 'T-F', '1')});document.querySelector('.Options#opt${i} #valueFalse${i}').addEventListener('input', () => {HandleCheckBoxClick('${i}', 'T-F', '2')});document.querySelector('.Options#opt${i} #quest${i}opt1correct').addEventListener('input', () => {HandleCheckBoxClick('${i}', '3-opts', '1')});document.querySelector('.Options#opt${i} #quest${i}opt2correct').addEventListener('input', () => {HandleCheckBoxClick('${i}', '3-opts', '2')});document.querySelector('.Options#opt${i} #quest${i}opt3correct').addEventListener('input', () => {HandleCheckBoxClick('${i}', '3-opts', '3')});document.querySelector('.Options#opt${i} #quest${i}opt4correct').addEventListener('input', () => {HandleCheckBoxClick('${i}', '4-opts', '4')})`
        document.body.appendChild(QuestionsScript);
    }
    
}
document.addEventListener('DOMContentLoaded', ReorgScript);

// Remover Pergunta
const RemoveQuestion = (num,divRemoved) => {
    let questionsQuant = document.querySelectorAll('.Questions').length

    if (num < questionsQuant) {
        for (let i = num + 1; i <= questionsQuant; i++) {
            //Mudar valor das Perguntas
            document.querySelector('#qt'+i+' h1').innerHTML = 'Pergunta ' + (i-1)
    
            document.querySelector('#qt'+i+' textarea').name = 'questionText' + (i-1)
            document.querySelector('#qt'+i+' textarea').id = 'questionText' + (i-1)
    
            document.querySelector('#qt'+i+' select').name = 'questionType' + (i-1)
            document.querySelector('#qt'+i+' select').id = 'questionType' + (i-1)
    
            //Mudar valor das respostas
            if (document.querySelector('#qt'+i+' select').value === "T-F") {
                document.querySelector('.Options#opt'+i+' #valueTrue'+i).name = 'valueTrue' + (i-1)
                document.querySelector('.Options#opt'+i+' #valueTrue'+i).id = 'valueTrue' + (i-1)
                
                document.querySelector('.Options#opt'+i+' #valueFalse'+i).name = 'valueFalse' + (i-1)
                document.querySelector('.Options#opt'+i+' #valueFalse'+i).id = 'valueFalse' + (i-1)
    
            }
            else if (document.querySelector('#qt'+i+' select').value === "3-opts" || document.querySelector('#qt'+i+' select').value === "4-opts") {
                document.querySelector('.Options#opt'+i+' #quest'+i+'opt1').name = 'quest'+(i-1)+'opt1'
                document.querySelector('.Options#opt'+i+' #quest'+i+'opt1').id = 'quest'+(i-1)+'opt1'
                document.querySelector('.Options#opt'+i+' #quest'+i+'opt1correct').name = 'quest'+(i-1)+'opt1correct'
                document.querySelector('.Options#opt'+i+' #quest'+i+'opt1correct').id = 'quest'+(i-1)+'opt1correct'
    
                document.querySelector('.Options#opt'+i+' #quest'+i+'opt2').name = 'quest'+(i-1)+'opt2'
                document.querySelector('.Options#opt'+i+' #quest'+i+'opt2').id = 'quest'+(i-1)+'opt2'
                document.querySelector('.Options#opt'+i+' #quest'+i+'opt2correct').name = 'quest'+(i-1)+'opt2correct'
                document.querySelector('.Options#opt'+i+' #quest'+i+'opt2correct').id = 'quest'+(i-1)+'opt2correct'
    
                document.querySelector('.Options#opt'+i+' #quest'+i+'opt3').name = 'quest'+(i-1)+'opt3'
                document.querySelector('.Options#opt'+i+' #quest'+i+'opt3').id = 'quest'+(i-1)+'opt3'
                document.querySelector('.Options#opt'+i+' #quest'+i+'opt3correct').name = 'quest'+(i-1)+'opt3correct'
                document.querySelector('.Options#opt'+i+' #quest'+i+'opt3correct').id = 'quest'+(i-1)+'opt3correct'
    
                if (document.querySelector('#qt'+i+' select').value === "4-opts") {
                    document.querySelector('.Options#opt'+i+' #quest'+i+'opt4').name = 'quest'+(i-1)+'opt4'
                    document.querySelector('.Options#opt'+i+' #quest'+i+'opt4').id = 'quest'+(i-1)+'opt4'
                    document.querySelector('.Options#opt'+i+' #quest'+i+'opt4correct').name = 'quest'+(i-1)+'opt4correct'
                    document.querySelector('.Options#opt'+i+' #quest'+i+'opt4correct').id = 'quest'+(i-1)+'opt4correct'
                }    
            }
            document.getElementById('rm'+i).innerHTML = '<i class="gg-remove-r" onclick="RemoveQuestion('+(i-1)+',\'.Questions#qt'+(i-1)+'\')"></i>'
            document.getElementById('rm'+i).id = 'rm'+(i-1)

            document.querySelector('.Options#opt'+i).name = 'opt'+(i-1)
            document.querySelector('.Options#opt'+i).id = 'opt'+(i-1)

            document.querySelector('.Questions#qt'+i).id = 'qt'+(i-1)
        }
    }
    document.querySelector(divRemoved).remove()
    ReorgScript()
}

// Adicionar Questão
const AddQuestion = () => {
    let questionsQuant = document.querySelectorAll('.Questions').length

    const newQuestion = document.createElement('div')
    newQuestion.innerHTML = '<div class="Questions" id="qt'+(questionsQuant+1)+'"><h1>Pergunta '+(questionsQuant+1)+'</h1><textarea name="questionText'+(questionsQuant+1)+'" id="questionText'+(questionsQuant+1)+'" cols="30" rows="10"></textarea><select name="questionType'+(questionsQuant+1)+'" id="questionType'+(questionsQuant+1)+'"><option value="T-F">Verdadeiro ou Falso</option><option value="3-opts">3 Opções</option><option value="4-opts">4 Opções</option></select><div class="Options" id="opt'+(questionsQuant+1)+'"><div class="T-FOptions"><input type="checkbox" name="valueTrue'+(questionsQuant+1)+'" id="valueTrue'+(questionsQuant+1)+'"><p class="opt1">Verdadeiro</p></div><div class="T-FOptions"><input type="checkbox" name="valueFalse'+(questionsQuant+1)+'" id="valueFalse'+(questionsQuant+1)+'"><p class="opt2">Falso</p></div></div><div class="timerQuestion"><p>Tempo da pergunta:</p><input min="1" type="number" name="timerQuestionipt'+(questionsQuant+1)+'" id="timerQuestionipt'+(questionsQuant+1)+'"></div><div id="rm'+(questionsQuant+1)+'"><i class="gg-remove-r" onclick="RemoveQuestion('+(questionsQuant+1)+',\'.Questions#qt'+(questionsQuant+1)+'\')"></i></div></div>'
    document.querySelector('.changeForm .qtscontainer').appendChild(newQuestion);

    ReorgScript();
    reloadTimersInput();
    
}

// Ser maior que 0s
const beMoreThanZero = (e) => {
    if (parseInt(e.target.value) <= 0 || isNaN(parseInt(e.target.value))) {
        e.target.value = '';
    } else {
        e.target.value = e.target.value.replace(/\D/g, '');
    }
};
const addEventListenerOnce = (element, event, callback) => {
    const eventKey = `__hasEventListener_${event}`;

    if (!element[eventKey]) {
        element.addEventListener(event, callback);
        element[eventKey] = true;
    } else {
        element.removeEventListener(event, callback);
        element.addEventListener(event, callback);
    }
};
const reloadTimersInput = () => {
    const timerInputs = document.querySelectorAll('.timerQuestion input');
    timerInputs.forEach(input => {
        addEventListenerOnce(input, 'input', beMoreThanZero);
    });
};
reloadTimersInput();

// Status Messages
const removeStatusMessage = (elemento) => {
    elemento.parentNode.classList.remove('inAnimation');
    elemento.parentNode.classList.add('outAnimation');
    setTimeout(() => {
        elemento.parentNode.remove();
    }, 600);
}

// Validate Update
let allowQuestions = true;

const saveButton = (element) => {
    //Pegar Quiz Information
    const QuizNameInput = document.getElementById('quizName');
    const QuizTypeSelect = document.getElementById('quizType');

    if (QuizNameInput.value.length < 1) {
        allowQuestions = false;
        QuizNameInput.style.borderColor = '#ff3333'
    }
    else {QuizNameInput.style.borderColor = 'var(--thirdColor)'}

    //Pegar Perguntas
    let questionQuant = document.querySelectorAll('.Questions').length;

    let allowImageUpdate = true;
    if (document.querySelector('.imageControl input').files.length > 0) {
        if (document.querySelector('.imageControl input').files[0].size < (2 * 1024 * 1024)) {
            allowImageUpdate = true;
        }
        else {
            allowQuestions = false;
            allowImageUpdate = false;
            const showMessageStatus = document.createElement('div');
            showMessageStatus.classList.add('statusMessages');
            showMessageStatus.classList.add('bad');
            showMessageStatus.classList.add('inAnimation');

            showMessageStatus.innerHTML = '<h3>Oops!</h3><p>Sua imagem possui mais de 2mb</p><i class="gg-close-r" onclick="removeStatusMessage(this)"></i>';
            document.querySelector('.statusMessagesContainer').appendChild(showMessageStatus);

            setTimeout(() => {removeStatusMessage(showMessageStatus.querySelector('.gg-close-r'))}, 5000);
        }
    }
    

    for (let i = 1; i <= questionQuant; i++){
        let questionType = document.getElementById('questionType'+i).value;
        let questionTXT = document.getElementById('questionText'+i).value;
        let rightAnswer;
        let checked;

        const TextAreaIpt = document.getElementById('questionText'+i);showWrongButton(TextAreaIpt);
        const TimeAreaIpt = document.getElementById('timerQuestionipt'+i);showWrongButton(TimeAreaIpt);

        if (questionType === 'T-F'){
            const testTrue = document.getElementById('valueTrue'+i);
            const testFalse = document.getElementById('valueFalse'+i);
            
            if (testTrue.checked === true){checked = 'True';document.querySelector('#opt'+i+' p.opt1').style.color = 'var(--thirdColor)';document.querySelector('#opt'+i+' p.opt2').style.color = 'var(--thirdColor)';}
            else if (testFalse.checked === true){checked = 'False';document.querySelector('#opt'+i+' p.opt1').style.color = 'var(--thirdColor)';document.querySelector('#opt'+i+' p.opt2').style.color = 'var(--thirdColor)';}
            else if (testFalse.checked === false && testTrue.checked === false) {
                document.querySelector('#opt'+i+' p.opt1').style.color = '#ff3333';
                document.querySelector('#opt'+i+' p.opt2').style.color = '#ff3333';
                allowQuestions = false;
            }

            rightAnswer = 'NoMulti';
        }
        else if (questionType === '4-opts'){
            const testValue1 = document.getElementById('quest'+i+'opt1');showWrongButton(testValue1);
            const testValue2 = document.getElementById('quest'+i+'opt2');showWrongButton(testValue2);
            const testValue3 = document.getElementById('quest'+i+'opt3');showWrongButton(testValue3);
            const testValue4 = document.getElementById('quest'+i+'opt4');showWrongButton(testValue4);

            const checkBoxValue1 = document.getElementById('quest'+i+'opt1correct');
            const checkBoxValue2 = document.getElementById('quest'+i+'opt2correct');
            const checkBoxValue3 = document.getElementById('quest'+i+'opt3correct');
            const checkBoxValue4 = document.getElementById('quest'+i+'opt4correct');

            if (checkBoxValue1.checked === true){rightAnswer = 1;document.querySelector('#opt'+i+' p.opt1').style.color = 'var(--thirdColor)';document.querySelector('#opt'+i+' p.opt2').style.color = 'var(--thirdColor)';document.querySelector('#opt'+i+' p.opt3').style.color = 'var(--thirdColor)';document.querySelector('#opt'+i+' p.opt4').style.color = 'var(--thirdColor)';}
            else if (checkBoxValue2.checked === true){rightAnswer = 2;document.querySelector('#opt'+i+' p.opt1').style.color = 'var(--thirdColor)';document.querySelector('#opt'+i+' p.opt2').style.color = 'var(--thirdColor)';document.querySelector('#opt'+i+' p.opt3').style.color = 'var(--thirdColor)';document.querySelector('#opt'+i+' p.opt4').style.color = 'var(--thirdColor)';}
            else if (checkBoxValue3.checked === true){rightAnswer = 3;document.querySelector('#opt'+i+' p.opt1').style.color = 'var(--thirdColor)';document.querySelector('#opt'+i+' p.opt2').style.color = 'var(--thirdColor)';document.querySelector('#opt'+i+' p.opt3').style.color = 'var(--thirdColor)';document.querySelector('#opt'+i+' p.opt4').style.color = 'var(--thirdColor)';}
            else if (checkBoxValue4.checked === true){rightAnswer = 4;document.querySelector('#opt'+i+' p.opt1').style.color = 'var(--thirdColor)';document.querySelector('#opt'+i+' p.opt2').style.color = 'var(--thirdColor)';document.querySelector('#opt'+i+' p.opt3').style.color = 'var(--thirdColor)';document.querySelector('#opt'+i+' p.opt4').style.color = 'var(--thirdColor)';}
            else if (checkBoxValue1.checked === false && checkBoxValue2.checked === false && checkBoxValue3.checked === false && checkBoxValue4.checked === false){
                document.querySelector('#opt'+i+' p.opt1').style.color = '#ff3333';
                document.querySelector('#opt'+i+' p.opt2').style.color = '#ff3333';
                document.querySelector('#opt'+i+' p.opt3').style.color = '#ff3333';
                document.querySelector('#opt'+i+' p.opt4').style.color = '#ff3333';
                allowQuestions = false;
            }

            checked = 'NoTF';
        }
        else if (questionType === '3-opts'){
            const testValue1 = document.getElementById('quest'+i+'opt1');showWrongButton(testValue1);
            const testValue2 = document.getElementById('quest'+i+'opt2');showWrongButton(testValue2);
            const testValue3 = document.getElementById('quest'+i+'opt3');showWrongButton(testValue3);

            const checkBoxValue1 = document.getElementById('quest'+i+'opt1correct');
            const checkBoxValue2 = document.getElementById('quest'+i+'opt2correct');
            const checkBoxValue3 = document.getElementById('quest'+i+'opt3correct');

            if (checkBoxValue1.checked === true){rightAnswer = 1;document.querySelector('#opt'+i+' p.opt1').style.color = 'var(--thirdColor)';document.querySelector('#opt'+i+' p.opt2').style.color = 'var(--thirdColor)';document.querySelector('#opt'+i+' p.opt3').style.color = 'var(--thirdColor)';}
            else if (checkBoxValue2.checked === true){rightAnswer = 2;document.querySelector('#opt'+i+' p.opt1').style.color = 'var(--thirdColor)';document.querySelector('#opt'+i+' p.opt2').style.color = 'var(--thirdColor)';document.querySelector('#opt'+i+' p.opt3').style.color = 'var(--thirdColor)';}
            else if (checkBoxValue3.checked === true){rightAnswer = 3;document.querySelector('#opt'+i+' p.opt1').style.color = 'var(--thirdColor)';document.querySelector('#opt'+i+' p.opt2').style.color = 'var(--thirdColor)';document.querySelector('#opt'+i+' p.opt3').style.color = 'var(--thirdColor)';}
            else if (checkBoxValue1.checked === false && checkBoxValue2.checked === false && checkBoxValue3.checked === false){
                document.querySelector('#opt'+i+' p.opt1').style.color = '#ff3333';
                document.querySelector('#opt'+i+' p.opt2').style.color = '#ff3333';
                document.querySelector('#opt'+i+' p.opt3').style.color = '#ff3333';
                allowQuestions = false;
            }

            checked = 'NoTF';
        }
    }

    if (allowQuestions === true) {
        if (element.id == 'newQbt') {
            createQuiz(QuizNameInput.value,QuizTypeSelect.value,element)
        }
        else{
            updateFrontQuiz(QuizNameInput.value,QuizTypeSelect.value)
            continueUpdate(element);
        }

        const showMessageStatus = document.createElement('div');
        showMessageStatus.classList.add('statusMessages');
        showMessageStatus.classList.add('good');
        showMessageStatus.classList.add('inAnimation');

        showMessageStatus.innerHTML = '<h3>Boa!</h3><p>Perguntas atualizadas</p><i class="gg-close-r" onclick="removeStatusMessage(this)"></i>';
        document.querySelector('.statusMessagesContainer').appendChild(showMessageStatus);

        setTimeout(() => {removeStatusMessage(showMessageStatus.querySelector('.gg-close-r'))}, 5000);
    }
    else if (allowImageUpdate===true) {
        const showMessageStatus = document.createElement('div');
        showMessageStatus.classList.add('statusMessages');
        showMessageStatus.classList.add('bad');
        showMessageStatus.classList.add('inAnimation');

        showMessageStatus.innerHTML = '<h3>Oops!</h3><p>Parece que você esqueceu de preencher alguns campos</p><i class="gg-close-r" onclick="removeStatusMessage(this)"></i>';
        document.querySelector('.statusMessagesContainer').appendChild(showMessageStatus);

        setTimeout(() => {removeStatusMessage(showMessageStatus.querySelector('.gg-close-r'))}, 5000);
    }
    allowQuestions = true;
}


const showWrongButton = (bt) => {
    if (bt.value.length <= 0){
        bt.style.borderColor = '#ff3333'
        allowQuestions = false
    }
    else {
        bt.style.borderColor = 'var(--almostPrimaryColor)'
    }
}

const continueUpdate = (element) => {
    let questionQuant = document.querySelectorAll('.Questions').length;
    for (let i = 1; i <= questionQuant; i++) {
        let questionType = document.getElementById('questionType'+i).value;
        let questionTXT = String(document.getElementById('questionText'+i).value);
        let questionTIME = document.getElementById('timerQuestionipt'+i).value;
        let rightAnswer;
        let checked;
        let resp1;
        let resp2;
        let resp3;
        let resp4;

        if (questionType === 'T-F'){
            resp1 = "";
            resp2 = "";
            resp3 = "";
            resp4 = "";
            
            const testTrue = document.getElementById('valueTrue'+i);
            const testFalse = document.getElementById('valueFalse'+i);
            if (testTrue.checked === true){checked = 'True';}
            else if (testFalse.checked === true){checked = 'False';}

            rightAnswer = 'NoMulti';
        }
        else if (questionType === '4-opts'){
            resp1 = document.getElementById('quest'+i+'opt1').value;
            resp2 = document.getElementById('quest'+i+'opt2').value;
            resp3 = document.getElementById('quest'+i+'opt3').value;
            resp4 = document.getElementById('quest'+i+'opt4').value;

            const testValue1 = document.getElementById('quest'+i+'opt1correct');
            const testValue2 = document.getElementById('quest'+i+'opt2correct');
            const testValue3 = document.getElementById('quest'+i+'opt3correct');
            const testValue4 = document.getElementById('quest'+i+'opt4correct');

            if (testValue1.checked === true){rightAnswer = 1}
            else if (testValue2.checked === true){rightAnswer = 2}
            else if (testValue3.checked === true){rightAnswer = 3}
            else if (testValue3.checked === true){rightAnswer = 4}

            checked = 'NoTF';
        }
        else if (questionType === '3-opts'){
            resp1 = document.getElementById('quest'+i+'opt1').value;
            resp2 = document.getElementById('quest'+i+'opt2').value;
            resp3 = document.getElementById('quest'+i+'opt3').value;
            resp4 = "";

            const testValue1 = document.getElementById('quest'+i+'opt1correct');
            const testValue2 = document.getElementById('quest'+i+'opt2correct');
            const testValue3 = document.getElementById('quest'+i+'opt3correct');

            if (testValue1.checked === true){rightAnswer = 1}
            else if (testValue2.checked === true){rightAnswer = 2}
            else if (testValue3.checked === true){rightAnswer = 3}

            checked = 'NoTF';
        }

        sendUpdate(i,questionType,questionTXT,rightAnswer,checked,resp1,resp2,resp3,resp4,questionTIME,questionQuant);
    }
    if (element.id == 'newQbt') {
        sendToConfigPage();
    }
}