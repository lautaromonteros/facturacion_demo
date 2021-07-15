<?php 
require 'includes/funciones.php';

if(!estaAutenticado()){
    header('Location: login.php');
}
incluirTemplate('header');
incluirTemplate('sidebar');
incluirTemplate('menu');

$clientes = mostrarClientes();
?>
<section class="contenedor">
    <h2>Clientes</h2>
    <div class="d-lg-flex">
        <div class="col-lg-6">
            <div class="buscador">
                <label class="label" for="buscador-cliente"><i class="fas fa-search"></i></label>
                <input type="text" name="buscador-cliente" class="input" id="buscador-cliente" placeholder="Buscar cliente">
            </div>

            <div class="contenedor-tabla">
                <table class="tabla tabla-clientes caja mt-3">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($clientes)) : ?>
                        <tr data-cliente="<?php echo $row['id'] ?>">
                            <td><?php echo $row['nombre'] ?></td>
                            <td><?php echo $row['direccion'] ?></td>
                            <td><?php echo $row['telefono'] ?></td>
                            <td data-id="<?php echo $row['id'] ?>">
                                <button class="d-block w-100 mb-1 btn btn-success" id="editar-cliente">Editar</button>
                                <button class="d-block w-100 btn btn-danger" id="eliminar-cliente">Eliminar</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-6">
            <form class="formulario formulario-cliente ms-lg-3" action="#" method="post" id="nuevo-cliente">
                <legend>Agregar un nuevo Cliente</legend>
                <input class="input" type="text" name="nombre-cliente" id="nombre-cliente" placeholder="Nombre del Cliente">
                <input class="input" type="text" name="direccion-cliente" id="direccion-cliente" placeholder="Dirección del Cliente">
                <input class="input" type="text" name="telefono-cliente" id="telefono-cliente" placeholder="Teléfono del Cliente">
                <div class="d-flex-right" id="boton-cliente">
                    <input type="hidden" id="accion" value="crear">
                    <input class="btn btn-azul" id="value" type="submit" value="Crear">
                </div>
            </form>
        </div>
    </div>
</section>
<?php 
incluirTemplate('footer');