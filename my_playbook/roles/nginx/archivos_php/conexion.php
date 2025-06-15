<?php 
        $servidor = "mysql-service"; // El nombre del servicio MySQL
        $host = 'jose.marbae'; //jose.marbae
        $password = 'Jm742569'; //Jm742569
        $database = 'forobmw';
        $conexion = new mysqli($servidor, $host, $password, $database);
        /*if ($conexion -> connect_error){
            echo "La conexion a la base de datos ha fallado ". $conexion->connect_error;
        }else{
            echo "La conexion a la base de datos fue un exito";
        }*/
    ?>