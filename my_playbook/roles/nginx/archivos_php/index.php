<?php session_start(); ?>
<?php include("cabecera.php"); ?>
<body>
<div id="container">
    <img id="logo" src="img/bmw-logo.png" alt="Logo BMW">
    <nav>
        <ul id="menu">
            <li><a href="tienda/tienda.php">Tienda</a></li>
            <li><a href="crear_tema/listar_temas.php">Foros</a></li>
            <li><a href="">¿Quiénes somos?</a></li>

            <?php if (isset($_SESSION['ID_USUARIO'])): ?>
                <li><a href="editar_perfil.php">Mi Perfil</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            <?php else: ?>
                <li><a href="login.html">Login</a></li>
                <li><a href="altausuario.php">Registrarse</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div id="contenedor-logo-texto">
        <div id="texto-logo">
            <h1>BMW Forum</h1>
            <p>Conecta con otros entusiastas de BMW y comparte tu pasión.</p>
        </div>
        <img id="imagen-logo2" src="img/bmw-e302.png" alt="BMW Logo">
    </div>
    <h1>Los modelos con los temas mas destacados</h1>
    <section id="categories">
        <div class="category">
            <img src="img/x5.jpg" alt="BMW X5">
            <h2>BMW X5</h2>
            <p>Discusiones</p>
        </div>
        <div class="category">
            <a href="crear_modelo/pagina_modelos.php"><img src="img/e36.jpg" alt="BMW E36"></a>
            <h2>BMW e36</h2>
            <p>Colaboraciones</p>
        </div>
        <div class="category">
            <img src="img/g80.jpg" alt="BMW Z4">
            <h2>BMW G80</h2>
            <p>Explorando</p>
        </div>
        <div class="category">
            <img src="img/f90.jpg" alt="BMW X5">
            <h2>BMW F90</h2>
            <p>Lecciones</p>
        </div>
        <div class="category">
            <img src="img/e92.jpg" alt="BMW i8">
            <h2>BMW E92</h2>
            <p>Avanzado</p>
        </div>
        <div class="category">
            <img src="img/g87.jpg" alt="BMW M3">
            <h2>BMW G87</h2>
            <p>Actualizado</p>
        </div>
        
    </section>
    
    </div>
</body>
<?php include("footep.php")?>