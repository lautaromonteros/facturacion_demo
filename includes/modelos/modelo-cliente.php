<?php 

if($_POST['accion'] === 'crear'){
    require '../config/database.php';
    $db = conectarDB();

    $query = "SELECT COUNT(*) FROM clientes";
    $cantidad = floatval(mysqli_fetch_array(mysqli_query($db, $query))[0]);

    if($cantidad < 3){
        $nombre = mysqli_real_escape_string($db, $_POST['nombre']);
        $direccion = mysqli_real_escape_string($db, $_POST['direccion']);
        $telefono = mysqli_real_escape_string($db, $_POST['telefono']);
    
        $query = "INSERT INTO clientes (nombre, direccion, telefono) VALUES ('${nombre}', '${direccion}', '${telefono}')";
    
        $resultado = mysqli_query($db, $query);
    
        if($resultado){
            $id = mysqli_insert_id($db);
            $respuesta = array(
                'respuesta' => 'correcto',
                'datos' => array(
                    'nombre' => $nombre,
                    'direccion' => $direccion,
                    'telefono' => $telefono,
                    'id' => $id
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

    $nombre = mysqli_real_escape_string($db, $_POST['nombre']);
    $direccion = mysqli_real_escape_string($db, $_POST['direccion']);
    $telefono = mysqli_real_escape_string($db, $_POST['telefono']);
    $idCliente = mysqli_real_escape_string($db, $_POST['idCliente']);

    $query = "UPDATE clientes SET nombre = '${nombre}', direccion = '${direccion}', telefono = '${telefono}' WHERE id = ${idCliente}";

    $resultado = mysqli_query($db, $query);

    if($resultado){
        $respuesta = array(
            'respuesta' => 'correcto',
            'datos' => array(
                'nombre' => $nombre,
                'direccion' => $direccion,
                'telefono' => $telefono
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

    $idCliente = mysqli_real_escape_string($db, $_POST['idCliente']);

    $query = "DELETE FROM clientes WHERE id = ${idCliente}";

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

if($_POST['accion'] === 'obtener'){
    require '../config/database.php';
    $db = conectarDB();

    $idCliente = mysqli_real_escape_string($db, $_POST['idCliente']);

    $query = "SELECT * FROM clientes WHERE id = ${idCliente}";

    $resultado = mysqli_query($db, $query);

    if($resultado){
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