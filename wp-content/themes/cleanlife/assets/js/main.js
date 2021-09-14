function myFunction(x) {
  x.classList.toggle('change');
  document.getElementById("menu").classList.toggle("menu_show");
  var menuText = document.getElementById('menu_btn_text');
  if (menuText.textContent == 'MENU') {
    menuText.textContent = 'CLOSE';
  }
  else {
    menuText.textContent = 'MENU';
  }
  var menuService = document.getElementById('menu-service-container');
  if (menuService.classList.contains('menu-service-show')){
    menuService.classList.remove('menu-service-show');
  }
}

window.onscroll = function () {
  var serviceSubmenu = document.getElementById('service-submenu');
  if (serviceSubmenu.classList.contains('service-submenu-show')){
    serviceSubmenu.classList.remove('service-submenu-show');
  }
}

window.onclick = function () {
  var serviceSubmenu = document.getElementById('service-submenu');
  if (serviceSubmenu.classList.contains('service-submenu-show')){
    serviceSubmenu.classList.remove('service-submenu-show');
  }
}

function navService() {
  var navService = document.getElementById('service-submenu')
  if (navService.classList.contains('service-submenu-show')) {
    navService.classList.remove('service-submenu-show');
  }
  else {
    navService.classList.add('service-submenu-show');
  }
}

function menuService() {
  var menuLink01 = document.getElementById('menu_link01');
  if (menuLink01.classList.contains('menu-link01')) {
    menuLink01.classList.remove('menu-link01');
  }
  else {
    menuLink01.classList.add('menu-link01');
  }
  var menuService = document.getElementById('menu-service-container');
  if (menuService.classList.contains('menu-service-show')) {
    menuService.classList.remove('menu-service-show');
  }
  else {
    menuService.classList.add('menu-service-show');
  }
}

var ezTocToggle = document.getElementsByClassName('ez-toc-toggle')[0];
if (ezTocToggle) {
	ezTocToggle.innerHTML = "[<span>✖</span>]";
}

CurrentTime();
function CurrentTime() {
  var d = new Date(),
  h = (d.getHours()<10?'0':'') + d.getHours(),
  m = (d.getMinutes()<10?'0':'') + d.getMinutes();
  var currentTime = document.getElementById('currentTime');
  currentTime.innerHTML = h + '時' + m + '分';
  var currentTimeAll = document.querySelectorAll('.current-time-all');
  var i;
  for (i=0; i<currentTimeAll.length; i++) {
    currentTimeAll[i].innerHTML = h + '：' + m;
  }
  setTimeout(CurrentTime, 1000);
}


jQuery(function($){
 
  var $window = $(window);
  var window_height = $window.height();
  var header_height = $('header').height();
  var footer_height = $('.footer-foot').height();
  // var margin_top_wrap = $('.l-wrap').css('margin-top');
  // alert(margin_top_wrap)
  var sidebar_height = window_height - header_height - footer_height - 50;
  $(".l-aside").css('height' , sidebar_height);


});

jQuery(document).ready(function($){
  $('.ez-toc-toggle').on('click',function(){
    if($(this).attr('data-click-state') == 1) {
      $(this).attr('data-click-state', 0);
      $(this).html("[<span>✖</span>]");
    }
    else {
      $(this).attr('data-click-state', 1);
      $(this).html("[<span>▼</span>]");
    }
  });
});