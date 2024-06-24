const ChangeTitle = () => {
    let Frases = [
        "3 + 4 = 34??",
        "Açaí não tem gosto de terra",
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
        "Se eu fosse um vegetal, seria um 'rúcula-vo'.",
        "A última vez que olhei, não havia nada de 'normal' em ser um donut, mas todos parecem amar.",
        "Se a prática leva à perfeição, estou praticando ser imperfeito com maestria.",
    ];
    
    let titleElement = document.querySelector('.loadingPage h1');
    let currentIndex = parseInt(titleElement.getAttribute('data-index'));
    let newIndex;
    do {
        newIndex = Math.floor(Math.random() * Frases.length);
    } while (newIndex === currentIndex);

    titleElement.setAttribute('data-index', newIndex);
    titleElement.innerHTML = Frases[newIndex];
}
document.querySelector('.loadingPage h1').setAttribute('data-index', -1);

ChangeTitle();
setInterval(ChangeTitle, 3000);