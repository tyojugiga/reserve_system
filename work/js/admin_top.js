'use strict';

{
  // adminのmenuアコーディオン
  const service = document.querySelector('.kokyaku');
  const ac_arrow = document.querySelector('.ac_arrow');
  const ac_content = document.querySelectorAll('.ac_content');

  service.addEventListener('click', () =>{
    ac_arrow.classList.toggle('is_active');

    ac_content.forEach(function (element) {
      element.classList.toggle('is_active');
    });
  });

  
  const scedit = document.querySelector('.scedit');
  const alert = document.querySelector('.alert');
  const alert_window = document.querySelector('.alert_window');
  const cancel = document.querySelector('.cancel');
  // const m = document.querySelector('main');
  
  scedit.addEventListener('click', () =>{
    alert.classList.remove('nodis');
    alert_window.classList.remove('nodis');
    // main.classList.add('no_scroll');
  });
  
  cancel.addEventListener('click', () =>{
    alert.classList.add('nodis');
    alert_window.classList.add('nodis');
    // main.classList.remove('no_scroll');
  });

  
}