//Validar Email
const EmailInput = document.getElementById('email')

EmailInput.addEventListener('input', () => {
    if (EmailInput.value.length < 1) {
        document.querySelector('#emailGroup .ErrorMessage').innerHTML = '<p>Obrigatorio</p>'
        EmailInput.style.borderColor = '#ff3333'
        EmailValidationStatus = false
    }
    else if (!EmailInput.value.includes('@') || !EmailInput.value.includes('.com')){
        document.querySelector('#emailGroup .ErrorMessage').innerHTML = '<p>Não é um E-mail</p>'
        EmailInput.style.borderColor = '#ff3333'
        EmailValidationStatus = false
    }
    else {
        document.querySelector('#emailGroup .ErrorMessage').innerHTML = ''
        EmailInput.style.borderColor = 'var(--thirdColor)'
        EmailValidationStatus = true
    }
})

//Validar Senha
const PasswordInput = document.getElementById('password')

PasswordInput.addEventListener('input', () => {
    if (PasswordInput.value.length < 1) {
        document.querySelector('#passwordGroup .ErrorMessage').innerHTML = '<p>Obrigatorio</p>'
        PasswordInput.style.borderColor = '#ff3333'
        PasswordValidationStatus = false
    }
    else if (PasswordInput.value.length < 8) {
        document.querySelector('#passwordGroup .ErrorMessage').innerHTML = '<p>A senha precisa ter ao<br>menos 8 caracteres</p>'
        PasswordInput.style.borderColor = '#ff3333'
        PasswordValidationStatus = false
    }
    else{
        document.querySelector('#passwordGroup .ErrorMessage').innerHTML = ''
        PasswordInput.style.borderColor = 'var(--thirdColor)'
        PasswordValidationStatus = true
    }
})

//Liberar Login
let EmailValidationStatus = false
let PasswordValidationStatus = false