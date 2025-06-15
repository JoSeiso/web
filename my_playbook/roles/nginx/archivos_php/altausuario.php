<html>
<head>
    <meta charset="utf-8">
    <title>Iniciar Sesión - BMW Forum</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/alta.css">
</head>
<body>
    <div id="container">
        <h1>Alta Usuario</h1>
        <form action="insertar_usuario.php" method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>Registro</legend>
                <label for="nombre">Nombre de usuario:</label><br>
                <input type="text" name="nombre_usuario" id="nombre" required><br><br>
                
                <label for="email">Correo electrónico:</label><br>
                <input type="email" name="email" id="email" required><br><br>
                
                <label for="password">Contraseña:</label><br>
                <input type="password" name="password" id="password" required><br><br>

                <label for="imagen">Foto de Perfil: </label>
                <input type="file" name="imagen" accept="image/*">
            </fieldset>
            <div id="form-buttons">
                <input type="submit" value="Registrar">
                <input type="reset" value="Borrar">
            </div>
        </form>
    </div>
</body>
</html>