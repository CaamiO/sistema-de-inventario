<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Obtenga todos los elementos de barra de navegación
        const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

        // Añade un evento de clic en cada uno de ellos
        $navbarBurgers.forEach( el => {
            el.addEventListener('click', () => {
                const target = el.dataset.target;
                const $target = document.getElementById(target);
        // Alternar la clase "está activa" 
        el.classList.toggle('is-active');
        $target.classList.toggle('is-active');
            });
        });
    });
</script>
<script src="./js/ajax.js"></script>