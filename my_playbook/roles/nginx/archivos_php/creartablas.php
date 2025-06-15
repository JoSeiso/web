<?php 
$servidor = 'localhost:3333';
$host = 'root';
$password = '';
$database = 'forobmw';

//Creo la conexion
$conexion = new mysqli($servidor,$host,$password,$database);

//verifico la conexion
if ($conexion -> connect_error){
    echo "La conexion a la base de datos ha fallado ". $conexion->connect_error;
}else{
    echo "La conexion a la base de datos fue un exito";
}

//creo la tabla usuarios
$sql = "CREATE TABLE USUARIOS (
        ID_USUARIO INT AUTO_INCREMENT PRIMARY KEY,
        NOMBRE_USUARIO VARCHAR(50) NOT NULL,
        EMAIL VARCHAR(100) NOT NULL UNIQUE,
        PASSWORD VARCHAR(255) NOT NULL,
        FECHA_REGISTRO TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        ROL ENUM('ADMIN', 'USUARIO') DEFAULT 'USUARIO',
        IMAGEN_PERFIL VARCHAR(255) DEFAULT 'uploads/default.jpg'
)";

//Ejecuto la consulta 
if ($conexion->query($sql) === TRUE){
    echo "La tabla USUARIOS ha sido creada <br>";
} else {
    echo "Ha ocurrido un error al crear la tabla: <br>" . $conexion->error;
}

//creo la tabla temas 
$sql2 = "CREATE TABLE TEMAS (
        ID_TEMA INT AUTO_INCREMENT PRIMARY KEY,
        TITULO VARCHAR(255) NOT NULL,
        DESCRIPCION TEXT NOT NULL,
        FECHA_CREACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        ID_USUARIO INT NOT NULL,
        FOREIGN KEY (ID_USUARIO) REFERENCES USUARIOS(ID_USUARIO) ON DELETE CASCADE
)";

if ($conexion->query($sql2) === TRUE){
    echo "La tabla temas se añadio correctamente <br>";
} else {
    echo "La tabla temas no se pudo añadir <br>" . $conexion->error;
}

//creo la tabla temas 
$sql3 = "CREATE TABLE COMENTARIOS (
        ID_COMENTARIO INT AUTO_INCREMENT PRIMARY KEY,
        CONTENIDO TEXT NOT NULL,
        FECHA_EDICION TIMESTAMP NULL DEFAULT NULL,
        FECHA_PUBLICACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        ID_USUARIO INT NOT NULL,
        ID_TEMA INT NOT NULL,
        FOREIGN KEY (ID_USUARIO) REFERENCES USUARIOS(ID_USUARIO) ON DELETE CASCADE,
        FOREIGN KEY (ID_TEMA) REFERENCES TEMAS(ID_TEMA) ON DELETE CASCADE
)";

if ($conexion->query($sql3) === TRUE){
echo "La tabla comentarios se añadio correctamente <br>";
} else {
echo "La tabla comentarios no se pudo añadir <br>" . $conexion->error;
}

//creo la tabla MODELO_BMW
$sql4 = "CREATE TABLE MODELO_BMW (
        ID_MODELO INT AUTO_INCREMENT PRIMARY KEY,
        NOMBRE_MODELO VARCHAR(100) NOT NULL,
        AÑO_INICIO_PRODUCCION INT NOT NULL,
        AÑO_FIN_PRODUCCION INT DEFAULT NULL 
)";
if ($conexion->query($sql4) === TRUE){
    echo "La tabla MODELO_BMW se añadio correctamente <br>";
} else {
    echo "La tabla MODELO_BMW no se pudo añadir <br>" . $conexion->error;
}

// Creo la tabla TEMA_MODELO_BMW
$sql5 = "CREATE TABLE TEMA_MODELO_BMW (
    ID_TEMA INT NOT NULL,
    ID_MODELO INT NOT NULL,
    PRIMARY KEY (ID_TEMA, ID_MODELO),
    FOREIGN KEY (ID_TEMA) REFERENCES TEMAS(ID_TEMA) ON DELETE CASCADE,
    FOREIGN KEY (ID_MODELO) REFERENCES MODELO_BMW(ID_MODELO) ON DELETE CASCADE
)";

if ($conexion->query($sql5) === TRUE) {
    echo "La tabla TEMA_MODELO_BMW se añadió correctamente <br>";
} else {
    echo "La tabla TEMA_MODELO_BMW no se pudo añadir <br>" . $conexion->error;
}

// Creo la tabla PRODUCTOS para la tienda
$sql6 = "CREATE TABLE PRODUCTOS (
    ID_PRODUCTO INT AUTO_INCREMENT PRIMARY KEY,
    NOMBRE VARCHAR(255) NOT NULL,
    DESCRIPCION TEXT NOT NULL,
    PRECIO DECIMAL(10,2) NOT NULL,
    IMAGEN VARCHAR(255) NOT NULL,
    STOCK INT DEFAULT 0,
    FECHA_CREACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conexion->query($sql6) === TRUE) {
    echo "La tabla PRODUCTOS se añadió correctamente <br>";
} else {
    echo "La tabla PRODUCTOS no se pudo añadir <br>" . $conexion->error;
}

// Creo la tabla pedidos
$sql7 = "CREATE TABLE PEDIDOS (
    ID_PEDIDO INT AUTO_INCREMENT PRIMARY KEY,
    NOMBRE_CLIENTE VARCHAR(255) NOT NULL,
    EMAIL_CLIENTE VARCHAR(255) NOT NULL,
    DIRECCION_ENVIO TEXT NOT NULL,
    FECHA_PEDIDO TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conexion->query($sql7) === TRUE) {
    echo "La tabla PEDIDOS se añadió correctamente <br>";
} else {
    echo "La tabla PEDIDOS no se pudo añadir <br>" . $conexion->error;
}


// Creo la tabla detalle_pedido
$sql8 = "CREATE TABLE DETALLE_PEDIDO (
    ID_DETALLE INT AUTO_INCREMENT PRIMARY KEY,
    ID_PEDIDO INT NOT NULL,
    ID_PRODUCTO INT NOT NULL,
    CANTIDAD INT NOT NULL,
    PRECIO_UNITARIO DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (ID_PEDIDO) REFERENCES PEDIDOS(ID_PEDIDO) ON DELETE CASCADE,
    FOREIGN KEY (ID_PRODUCTO) REFERENCES PRODUCTOS(ID_PRODUCTO) ON DELETE CASCADE
)";

if ($conexion->query($sql8) === TRUE) {
    echo "La tabla DETALLE_PEDIDO se añadió correctamente <br>";
} else {
    echo "La tabla DETALLE_PEDIDO no se pudo añadir <br>" . $conexion->error;
}

$sql9 = "ALTER TABLE PEDIDOS ADD ID_USUARIO INT NOT NULL, 
         ADD FOREIGN KEY (ID_USUARIO) REFERENCES USUARIOS(ID_USUARIO) ON DELETE CASCADE";

if ($conexion->query($sql9) === TRUE) {
    echo "La columna ID_USUARIO se añadió correctamente a PEDIDOS <br>";
} else {
    echo "Error al añadir ID_USUARIO a PEDIDOS: " . $conexion->error . "<br>";
}

//cierro la conexion
$conexion->close();
?>