<?php

require 'app.php';

function incluirTemplate( string $nombre ){
    include TEMPLATES_URL . "/${nombre}.php";
}

function estaAutenticado() : bool {
    session_start();

    if($_SESSION['login']){
        return true;
    }
    return false;
}

function mostrarClientes() {
    require 'config/database.php';

    $db = conectarDB();

    $query = "SELECT * FROM clientes";
    $respuesta = mysqli_query($db, $query);

    return $respuesta;
}

function cantidadClientes(){
    require 'config/database.php';

    $db = conectarDB();

    $query = "SELECT count(*) FROM clientes";
    $respuesta = mysqli_query($db, $query);

    return mysqli_fetch_array($respuesta)[0];
}

function mostrarProductos() {
    require 'config/database.php';

    $db = conectarDB();

    $query = "SELECT * FROM productos inner join estado_producto on productos.estado = estado_producto.id";
    $respuesta = mysqli_query($db, $query);

    return $respuesta;
}

function cantidadProductos(){
    $db = conectarDB();

    $query = "SELECT count(*) FROM productos";
    $respuesta =mysqli_fetch_array(mysqli_query($db, $query))[0];

    return $respuesta;
}

function mostrarAumentos() {
    require 'config/database.php';

    $db = conectarDB();

    $query = "SELECT fecha FROM aumentos order by fecha DESC limit 1";
    $resultado = mysqli_query($db, $query);
    if($resultado->num_rows > 0){
        $fecha = $resultado->fetch_assoc()['fecha'];
    
        $query = "SELECT * FROM aumentos inner join productos on productos.codigo = aumentos.producto WHERE fecha = '${fecha}'";
        $respuesta = mysqli_query($db, $query);
    
    }else{
        $respuesta = 'error';
    }
    return $respuesta;
}

function mostrarFacturas($mes){
    require 'config/database.php';

    $db = conectarDB();

    $query = "SELECT * FROM factura INNER JOIN clientes on factura.cliente = clientes.id WHERE MONTH(fecha) = ${mes} ORDER BY idfactura DESC;";
    $resultado = mysqli_query($db, $query);

    return $resultado;
}

function cabeceraFactura($id){
    require 'config/database.php';

    $db = conectarDB();

    $query = "SELECT * from factura inner join clientes on clientes.id = factura.cliente where factura.idfactura = ${id} ;";
    $resultado = mysqli_query($db, $query);

    return mysqli_fetch_assoc($resultado);
}

function detalleFactura($id){

    $db = conectarDB();

    $query = "SELECT detalle.producto, productos.nombre, detalle.cantidad, detalle.precio, detalle.total from detalle LEFT JOIN productos on productos.codigo = detalle.producto where detalle.nrofactura = ${id} ;";
    $resultado = mysqli_query($db, $query);

    return $resultado;
}

function gananciasMes($mes){

    $db = conectarDB();

    $query = "SELECT SUM(total) FROM factura WHERE MONTH(fecha) = ${mes};";
    $resultado = mysqli_query($db, $query);

    return mysqli_fetch_array($resultado)[0];
}

function mostrarGastos($mes){
    require 'config/database.php';

    $db = conectarDB();

    $query = "SELECT * FROM gastos INNER JOIN tipo_gasto on gastos.tipo = tipo_gasto.id WHERE MONTH(fecha) = ${mes};";
    $resultado = mysqli_query($db, $query);

    return $resultado;
}

function gastosMes($mes){
    $db = conectarDB();

    $query = "SELECT sum(total) FROM gastos WHERE MONTH(fecha) = ${mes};";
    $resultado = mysqli_query($db, $query);

    return mysqli_fetch_array($resultado)[0];
}

function gastosPersonalesMes($mes){
    $db = conectarDB();

    $query = "SELECT sum(total) FROM gastos WHERE MONTH(fecha) = ${mes} and tipo = 1;";
    $resultado = mysqli_query($db, $query);

    return mysqli_fetch_array($resultado)[0];
}

function debuguear(array $arreglo){
    echo '<pre>';
    var_dump($arreglo);
    echo '</pre>';
    //exit;
}