document.addEventListener('DOMContentLoaded', function () {
    var menuToggle = document.getElementById('menuToggle');
    var menu = document.getElementById('menu');

    menuToggle.onclick = function () {
        menu.style.opacity = (menu.style.opacity === '' || menu.style.opacity === '0' ? '1' : '0');
    };
});
