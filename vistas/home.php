<style>
   .fondo {
    position: fixed;
      top: 0;
      left: 0;
      opacity: 0.2; 
    }
  </style>
<div class="container is-fluid has-text-centered mb-3 mt-3">
    <img class="fondo" src="./img/fondo.jpg" alt="Fondo">
    <h1 class="title">Pagina Principal</h1>
    <h2 class="subtitle">¡Bienvenido a StockWear, <?php echo $_SESSION['nombre']?>!</h2>
</div>

