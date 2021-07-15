<?php 

if($_POST['accion'] === 'agregar'){
    require '../config/database.php';

    $db = conectarDB();

    $linea = mysqli_real_escape_string($db, $_POST['linea']);
    $idfactura = mysqli_real_escape_string($db, $_POST['idfactura']);
    $codigo = mysqli_real_escape_string($db, $_POST['codigo']);
    $cantidad = mysqli_real_escape_string($db, $_POST['cantidad']);
    $precio = mysqli_real_escape_string($db, $_POST['precio']);
    $total = mysqli_real_escape_string($db, $_POST['total']);

    $query = "INSERT INTO detalle (linea, nrofactura, producto, cantidad, precio, total) VALUES ('${linea}', '${idfactura}', '${codigo}', '${cantidad}', '${precio}', '${total}')";

    $resultado = mysqli_query($db, $query);

    if($resultado){
        $respuesta = array(
            'respuesta' => 'correcto'
        );
    }else{
        $respuesta = array(
            'respuesta' => 'error'
        );
    }

    echo json_encode($respuesta);
} 

if($_POST['accion'] === 'generar'){
    require '../config/database.php';

    $db = conectarDB();

    $cantidad = mysqli_fetch_array(mysqli_query($db, "SELECT COUNT(*) FROM factura"))[0];

    if($cantidad < 3){
        $cliente = mysqli_real_escape_string($db, $_POST['cliente']);
        $total = mysqli_real_escape_string($db, $_POST['total']);
    
        $query = "INSERT INTO factura (cliente, fecha, total) VALUES ('${cliente}', CURRENT_TIMESTAMP, '${total}')";
    
        $resultado = mysqli_query($db, $query);
    
        if($resultado){
            $respuesta = array(
                'respuesta' => 'correcto',
                'factura_id' => mysqli_insert_id($db)
            );
        }else{
            $respuesta = array(
                'respuesta' => 'error'
            );
        }
    }else{
        $respuesta = array(
            'respuesta' => 'completo'
        );
    }

    echo json_encode($respuesta);
}

if($_POST['accion'] === 'mostrar'){

    require '../config/database.php';

    $db = conectarDB();
    $mes = mysqli_real_escape_string($db, $_POST['mes']);

    $query = "SELECT * FROM factura INNER JOIN clientes on factura.cliente = clientes.id WHERE MONTH(fecha) = ${mes} ORDER BY idfactura DESC;";
    $resultado = mysqli_query($db, $query);

    if($resultado->num_rows > 0){
        $respuesta = array(
            'respuesta' => 'correcto',
            'datos' => mysqli_fetch_all($resultado),
            'venta' => mysqli_fetch_array(mysqli_query($db, "select sum(total) from factura where MONTH(fecha) = ${mes};"))[0]
        );
    }else{
        $respuesta = array(
            'respuesta' => 'error'
        );
    }

    echo json_encode($respuesta);
}

if($_POST['accion'] === 'eliminar'){

    require '../config/database.php';

    $db = conectarDB();
    $id = mysqli_real_escape_string($db, $_POST['id']);

    $query = "DELETE FROM factura WHERE idfactura = ${id};";
    $resultado = mysqli_query($db, $query);

    if($resultado){
        $respuesta = array(
            'respuesta' => 'correcto'
        );
    }else{
        $respuesta = array(
            'respuesta' => 'error'
        );
    }

    echo json_encode($respuesta);
}

if($_POST['accion'] === 'ganancias'){

    require '../config/database.php';

    $db = conectarDB();
    $ganancias = [];
    for ($i=1; $i < 13; $i++) { 
        $ganancias[] = obtenerGanancia($db, $i);
    }

    echo json_encode($ganancias);
}

function obtenerGanancia($db, $mes){

    $sql = "SELECT sum(total) FROM factura WHERE MONTH(fecha) = ${mes}";
    $resultado = floatval(mysqli_fetch_array(mysqli_query($db, $sql))[0]);
    
    return $resultado;
}