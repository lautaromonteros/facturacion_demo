<?php 

if($_POST['accion'] === 'crear'){
    require '../config/database.php';
    $db = conectarDB();

    $codigo = mysqli_real_escape_string($db, $_POST['codigo']);
    $nombre = mysqli_real_escape_string($db, $_POST['nombre']);
    $precio = mysqli_real_escape_string($db, $_POST['precio']);

    $query = "SELECT COUNT(*) FROM productos";
    $cantidad = floatval(mysqli_fetch_array(mysqli_query($db, $query))[0]);

    if($cantidad < 4){
        $query = "INSERT INTO productos VALUES ('${codigo}', '${nombre}', '${precio}', 1)";

        $resultado = mysqli_query($db, $query);

        if($resultado){
            $respuesta = array(
                'respuesta' => 'correcto',
                'datos' => array(
                    'codigo' => $codigo,
                    'nombre' => $nombre,
                    'precio' => $precio,
                    'estado' => 1
                )
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

if($_POST['accion'] === 'editar'){
    require '../config/database.php';
    $db = conectarDB();

    $codigo = mysqli_real_escape_string($db, $_POST['codigo']);
    $nombre = mysqli_real_escape_string($db, $_POST['nombre']);
    $precio = mysqli_real_escape_string($db, $_POST['precio']);

    $query = "UPDATE productos SET nombre = '${nombre}', precio = '${precio}' WHERE codigo = '${codigo}'";

    $resultado = mysqli_query($db, $query);

    if($resultado){
        $respuesta = array(
            'respuesta' => 'correcto',
            'datos' => array(
                'codigo' => $codigo,
                'nombre' => $nombre,
                'precio' => $precio
            )
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

    $codigo = mysqli_real_escape_string($db, $_POST['codigo']);

    $query = "DELETE FROM productos WHERE codigo = '${codigo}'";

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

if($_POST['accion'] === 'estado'){
    require '../config/database.php';
    $db = conectarDB();

    $codigo = mysqli_real_escape_string($db, $_POST['codigo']);
    $estado = mysqli_real_escape_string($db, $_POST['estado']);

    $query = "UPDATE productos SET estado = ${estado}  WHERE codigo = '${codigo}'";

    $resultado = mysqli_query($db, $query);

    if($resultado){
        $respuesta = array(
            'respuesta' => 'correcto',
            'codigo' => $codigo,
            'estado' => $estado
        );
    }else{
        $respuesta = array(
            'respuesta' => 'error'
        );
    }

    echo json_encode($respuesta);
}

if($_POST['accion'] === 'buscar'){
    require '../config/database.php';
    $db = conectarDB();

    $codigo = mysqli_real_escape_string($db, $_POST['codigo']);

    $query = "SELECT * FROM productos WHERE codigo = '${codigo}'";

    $resultado = mysqli_query($db, $query);

    if($resultado->num_rows > 0){
        $respuesta = array(
            'respuesta' => 'correcto',
            'datos' => mysqli_fetch_assoc($resultado)
        );
    }else{
        $respuesta = array(
            'respuesta' => 'error'
        );
    }

    echo json_encode($respuesta);
}

if($_POST['accion'] === 'obtener'){
    require '../config/database.php';
    $db = conectarDB();

    $mes = mysqli_real_escape_string($db, $_POST['mes']);

    $query = "SELECT producto FROM detalle inner join factura on detalle.nrofactura = factura.idfactura where month(factura.fecha) = ${mes}";

    $resultado = mysqli_query($db, $query);

    $listadoProductos = $resultado->fetch_all();
    $productosVendidos = [];

    foreach ($listadoProductos as $producto) {
        if(!in_array($producto[0], $productosVendidos)){
            $productosVendidos[] = $producto[0];
        }
    }
    $respuesta = obtenerCantidades($productosVendidos, $db, $mes);
    echo json_encode($respuesta);
    exit;

    if($resultado->num_rows > 0){
        $respuesta = array(
            'respuesta' => mysqli_fetch_all($resultado)
        );
    }else{
        $respuesta = array(
            'respuesta' => 'error'
        );
    }

    echo json_encode($respuesta);
}

function obtenerCantidades($arreglo, $db, $mes){
    $listado = [];
    foreach ($arreglo as $row) {
        $query = "SELECT sum(cantidad) FROM detalle inner join factura on detalle.nrofactura = factura.idfactura where month(factura.fecha) = ${mes} and producto = '${row}'";

        $resultado = mysqli_query($db, $query);
        $nombre = mysqli_fetch_assoc(mysqli_query($db, "SELECT nombre FROM productos where codigo = '${row}'"));
        $cantidad = mysqli_fetch_array($resultado)[0];

        $listado[] = array(
            'producto' => $row,
            'nombre' => $nombre['nombre'],
            'cantidad' => $cantidad
        );
    }
    return $listado;
}

if($_POST['accion'] === 'consultar'){
    require '../config/database.php';
    $db = conectarDB();

    $producto = mysqli_real_escape_string($db, $_POST['producto']);

    $query = "SELECT * FROM productos WHERE nombre like '${producto}%'";

    $resultado = mysqli_query($db, $query);

    if($resultado->num_rows > 0){
        $respuesta = array(
            'respuesta' => 'correcto',
            'datos' => mysqli_fetch_all($resultado)
        );
    }else{
        $respuesta = array(
            'respuesta' => 'error'
        );
    }

    echo json_encode($respuesta);
}