<?php 

function conectarDB() : mysqli{
    $db = mysqli_connect('localhost', 'root','root', 'facturacion_app');
    
    if(!$db){
        echo 'Error, no se pudo conectar';
        exit;
    }
    return $db;
}
