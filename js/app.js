document.addEventListener('DOMContentLoaded', () => {
    const menuBtn = document.querySelector('.fa-bars');
    const cerrarBtn = document.querySelector('.close-menu');
    menuBtn.addEventListener('click', toggleMenu);
    cerrarBtn.addEventListener('click', toggleMenu);
    
})

const toggleMenu = () => {
    const aside = document.querySelector('.aside');
    if(aside.classList.contains('mostrar')){
        aside.classList.remove('mostrar');
    }else{
        aside.classList.add('mostrar');
    }
}