'use strict';

{
  
  const sp_menu = document.querySelector('.sp_menu');
  const overlay = document.querySelector('.sp_overlay');
  const m = document.querySelector('main');

  sp_menu.addEventListener('click', () =>{
    overlay.classList.toggle('show');
  });

  m.addEventListener('click', () =>{
    overlay.classList.remove('show');
  });
  
}