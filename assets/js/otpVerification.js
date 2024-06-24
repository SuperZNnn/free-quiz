const inputs = document.querySelectorAll('.opt-inputs input');
const button = document.querySelector('#otp-button');

inputs.forEach(input => {
    let lastInputStatus = 0;
    input.onkeyup = (e) => {
        if (document.querySelector('.ErrorMessage p')) {
            document.querySelector('.ErrorMessage p').remove();
        }

        const currentElement = e.target;
        const nextElement = input.nextElementSibling;
        const previusElement = input.previousElementSibling;

        if (previusElement && e.keyCode === 8) {
            if (lastInputStatus === 1){
                previusElement.value = '';
                previusElement.focus();
            }
            button.setAttribute('disabled',true)
            lastInputStatus = 1
        }
        else{
            const reg = /^[0-9]+$/;
            if (!reg.test(currentElement.value)){
                currentElement.value = currentElement.value.replace(/\D/g, '');
            }
            else if (currentElement.value){
                if (nextElement){
                    nextElement.focus();
                }
                else{
                    button.removeAttribute('disabled');
                    lastInputStatus = 0;
                }
            }
        }
    }
})

function getOTPString() {
    const otpDigits = [];
    inputs.forEach(input => {
        otpDigits.push(input.value);
    });
    return otpDigits.join('');
}