//Validação apelido
const NickInput = document.getElementById('nick')

NickInput.addEventListener('input', () => {
    if (NickInput.value.length < 1) {
        document.querySelector('#nickGroup > .ErrorMessage').innerHTML = '<p>Obrigatório</p>'
        NickInput.style.borderColor = '#ff3333'
        NickValidationStatus = false
    }
    else{
        document.querySelector('#nickGroup > .ErrorMessage').innerHTML = ''
        NickInput.style.borderColor = 'var(--thirdColor)'
        NickValidationStatus = true
    }
})

//Validação Email
const ConfirmEmailInput = document.getElementById('c-email')
const EmailInput = document.getElementById('email')
const emailGroup = document.getElementById('emailGroup')

const EmailValidation = () => {
    if (EmailInput.value.length < 1 || ConfirmEmailInput.value.length < 1){
        document.querySelector('#emailGroup > .ErrorMessage').innerHTML = '<p>Obrigatório</p>'
        ConfirmEmailInput.style.borderColor = '#ff3333'
        EmailInput.style.borderColor = '#ff3333'
        EmailValidationStatus = false
    }
    else if (ConfirmEmailInput.value !== EmailInput.value){
        document.querySelector('#emailGroup > .ErrorMessage').innerHTML = '<p>Os campos não coincidem</p>'
        ConfirmEmailInput.style.borderColor = '#ff3333'
        EmailInput.style.borderColor = '#ff3333'
        EmailValidationStatus = false
    }
    else{
        document.querySelector('#emailGroup > .ErrorMessage').innerHTML = ''
        ConfirmEmailInput.style.borderColor = 'var(--thirdColor)'
        EmailInput.style.borderColor = 'var(--thirdColor)'
        EmailValidationStatus = true
    }
}

ConfirmEmailInput.addEventListener('input', EmailValidation)
EmailInput.addEventListener('input', EmailValidation)


//Validação Senha
const PasswordInput = document.getElementById('password')
const ConfirmPasswordInput = document.getElementById('c-password')

const PasswordValidation = () => {
    if (PasswordInput.value.length < 1 || ConfirmPasswordInput.value.length < 1) {
        document.querySelector('#passwordGroup > .ErrorMessage').innerHTML = '<p>Obrigatório</p>'
        ConfirmPasswordInput.style.borderColor = '#ff3333'
        PasswordInput.style.borderColor = '#ff3333'
        PasswordValidationStatus = false
    }
    else if (ConfirmPasswordInput.value !== PasswordInput.value){
        document.querySelector('#passwordGroup > .ErrorMessage').innerHTML = '<p>Os campos não coincidem</p>'
        ConfirmPasswordInput.style.borderColor = '#ff3333'
        PasswordInput.style.borderColor = '#ff3333'
        PasswordValidationStatus = false
    }
    else{
        document.querySelector('#passwordGroup > .ErrorMessage').innerHTML = ''
        ConfirmPasswordInput.style.borderColor = 'var(--thirdColor)'
        PasswordInput.style.borderColor = 'var(--thirdColor)'
        PasswordValidationStatus = true
    }
}

PasswordInput.addEventListener('input', () => {
    if (PasswordInput.value.length < 8) {
        document.querySelector('#passwordGroup > .ErrorMessage').innerHTML = '<p>A senha precisa ter ao<br>menos 8 caracteres</p>'
        PasswordInput.style.borderColor = '#ff3333'
        PasswordLenghtStatus = false
    }
    else{
        document.querySelector('#passwordGroup > .ErrorMessage').innerHTML = ''
        PasswordValidation()
        PasswordLenghtStatus = true
    }
})

ConfirmPasswordInput.addEventListener('input', PasswordValidation)


//Permitir Registro
let NickValidationStatus = false
let EmailValidationStatus = false
let PasswordLenghtStatus = false
let PasswordValidationStatus = false