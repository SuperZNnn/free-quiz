const HandleResize = () => {
    //Config Page
    if (window.innerWidth <= 850) {
        document.querySelector('.changeForm').classList.add('changeFormMob')
        document.querySelector('.changeForm').classList.remove('changeFormNormal')
    }
    else {
        document.querySelector('.changeForm').classList.remove('changeFormMob')
        document.querySelector('.changeForm').classList.add('changeFormNormal')
    }
}

window.addEventListener('resize', HandleResize)
document.addEventListener('DOMContentLoaded', HandleResize);