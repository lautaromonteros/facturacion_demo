<?php 
    require 'includes/config/database.php';
    $db = conectarDB();

    $errores = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $correo = mysqli_real_escape_string($db, filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL));
        $password = mysqli_real_escape_string($db, $_POST['password']);

        if(!$correo){
            $errores[] = 'El email es obligatorio o no es válido';
        }
        if(!$password){
            $errores[] = 'El password es obligatorio';
        }
        /* echo '<pre>';
        var_dump($errores);
        echo '</pre>'; */
        if(empty($errores)){
            $sql = "select * from admin where correo = '${correo}'";
            $resultado = mysqli_query($db, $sql);

            if($resultado->num_rows > 0){
                $usuario = mysqli_fetch_assoc($resultado);
                $auth = password_verify($password, $usuario['password']);
                
                if($auth){
                    session_start();
                    $_SESSION['usuario'] = $usuario['correo'];
                    $_SESSION['login'] = true;
                    header('Location: index.php');
                }else{
                    $errores[] = 'El password es incorrecto';
                }
            }else{
                $errores[] = 'El correo ingresado no existe';
            }
        }

    }
    echo '<br>';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/normalize.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="css/styles.css">
    <title>Facturación App</title>
</head>
<body class="login">
    <section class="contenedor">

        <?php foreach($errores as $error) : ?>
            <p class="mensaje my-4 text-center"><?php echo $error ?></p>
        <?php endforeach; ?>

        <h2 class="text-center">Iniciar Sesión</h2>
        
        <form method="POST" action="#" class="formulario">
            <input type="text" class="input" name="correo" id="correo" placeholder="Tu correo">
            <input type="password" class="input" name="password" id="password" placeholder="Tu password">
            <div class="d-flex-right">
                <input type="submit" class="btn btn-azul" value="Iniciar Sesión">

            </div>
        </form>
    </section>
</body>