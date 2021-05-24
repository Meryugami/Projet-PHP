let switchColor = document.getElementById('switchColor');
let container = document.getElementById('bodyContainer');
let navbar = document.getElementById('navbar');

if (switchColor !== null) {
    switchColor.addEventListener('click', function (e) {
        if (switchColor.checked) {
            container.classList.remove('light-theme');
            container.classList.add('dark-theme');

            navbar.classList.remove('navbar-light', 'bg-light');
            navbar.classList.add('navbar-dark', 'bg-dark');
        } else {
            container.classList.remove('dark-theme');
            container.classList.add('light-theme');

            navbar.classList.remove('navbar-dark', 'bg-dark');
            navbar.classList.add('navbar-light', 'bg-light');
        }
    });
}