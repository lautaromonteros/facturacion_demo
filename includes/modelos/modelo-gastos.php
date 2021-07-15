<?php 

if($_POST['accion'] === 'crear'){
    require '../config/database.php';
    $db = conectarDB();

    $cantidad = mysqli_fetch_array(mysqli_query($db, "SELECT COUNT(*) FROM gastos"))[0];

    if($cantidad < 3){
        $nombre = mysqli_real_escape_string($db, $_POST['nombre']);
        $tipo = mysqli_real_escape_string($db, $_POST['tipo']);
        $total = mysqli_real_escape_string($db, $_POST['total']);
    
        $query = "INSERT INTO gastos (nombre, fecha, tipo, total) VALUES ('${nombre}', CURRENT_TIMESTAMP, ${tipo}, '${total}')";
    
        $resultado = mysqli_query($db, $query);
    
        if($resultado){
            $id = mysqli_insert_id($db);
            $tipoGasto = mysqli_fetch_assoc($db->query("SELECT gasto FROM tipo_gasto where id = ${tipo};"));
            $respuesta = array(
                'respuesta' => 'correcto',
                'datos' => array(
                    'nombre' => $nombre,
                    'tipo' => $tipo,
                    'tipoGasto' => $tipoGasto['gasto'],
                    'total' => $total,
                    'fecha' => date('Y-m-d'),
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
    $tipo = mysqli_real_escape_string($db, $_POST['tipo']);
    $total = mysqli_real_escape_string($db, $_POST['total']);
    $idGasto = mysqli_real_escape_string($db, $_POST['idGasto']);

    $query = "UPDATE gastos SET nombre = '${nombre}', tipo = '${tipo}', total = '${total}' WHERE idgasto = ${idGasto}";

    $resultado = mysqli_query($db, $query);

    if($resultado){
        $tipoGasto = mysqli_fetch_assoc($db->query("SELECT gasto FROM tipo_gasto where id = ${tipo};"));
        $respuesta = array(
            'respuesta' => 'correcto',
            'datos' => array(
                'nombre' => $nombre,
                'tipoGasto' => $tipoGasto['gasto'],
                'total' => $total
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

    $idGasto = mysqli_real_escape_string($db, $_POST['idGasto']);

    $query = "DELETE FROM gastos WHERE idgasto = ${idGasto}";

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

if($_POST['accion'] === 'mostrar'){

    require '../config/database.php';

    $db = conectarDB();
    $db->set_charset("utf8");
    $mes = mysqli_real_escape_string($db, $_POST['mes']);

    $query = "SELECT * FROM gastos INNER JOIN tipo_gasto on gastos.tipo = tipo_gasto.id WHERE MONTH(fecha) = ${mes};";
    $resultado = mysqli_query($db, $query);

    if($resultado->num_rows > 0){
        $respuesta = array(
            'respuesta' => 'correcto',
            'datos' => mysqli_fetch_all($resultado),
            'gastos' => mysqli_fetch_array(mysqli_query($db, "select sum(total) from gastos where MONTH(fecha) = ${mes};"))[0],
            'gastosPersonales' => mysqli_fetch_array($db->query("select sum(total) from gastos where MONTH(fecha) = ${mes} and tipo = 1 "))[0]
        );
    }else{
        $respuesta = array(
            'respuesta' => 'error'
        );
    }

    echo json_encode($respuesta);
}