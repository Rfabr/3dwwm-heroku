const menu = document.querySelector('nav');
const burger = document.querySelector('.burger');
const links = document.querySelectorAll('nav a');

burger.addEventListener('click', ()=>
    {
        menu.classList.toggle('nav-reveal');
        burger.classList.toggle('burger-reveal');
    }
);

links.forEach(link =>
    {
        link.addEventListener('click', ()=>
            {
                menu.classList.remove('nav-reveal');
                burger.classList.remove('burger-reveal');
            }
        );
    }
);