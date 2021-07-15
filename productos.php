<?php 
require 'includes/funciones.php';

if(!estaAutenticado()){
    header('Location: login.php');
}
incluirTemplate('header');
incluirTemplate('sidebar');
incluirTemplate('menu');

$productos = mostrarProductos();
?>
<section class="contenedor">
    <h2>Productos</h2>
    <div class="d-lg-flex">
        <div class="col-lg-8">
            <div class="buscador">
                <label class="label" for="buscador-producto"><i class="fas fa-search"></i></label>
                <input type="text" name="buscador-producto" class="input" id="buscador-producto" placeholder="Buscar producto">
            </div>

            <div class="contenedor-tabla">
                <table class="tabla tabla-productos caja mt-3">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th></th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($productos)) : ?>
                        <tr data-producto="<?php echo $row['codigo']; ?>">
                            <td><?php echo $row['codigo']; ?></td>
                            <td><?php echo $row['nombre']; ?></td>
                            <td>$<?php echo $row['precio']; ?></td>
                            <td><p class="<?php echo ($row['estado'] === '1') ? 'btn-success' : 'btn-danger' ; ?> estado" id="<?php echo $row['estado']; ?>"><?php echo $row['nombre_estado']; ?></p></td>
                            <td data-id="<?php echo $row['codigo']; ?>">
                                <button class="d-block w-100 mb-1 btn btn-success" id="editar-producto">Editar</button>
                                <button class="d-block w-100 btn btn-danger" id="eliminar-producto">Eliminar</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <a href="lista-productos.php" class="btn btn-azul mt-3 btn-full" id="generar-lista">Generar Lista de Precios</a>
            <a href="aumentos.php" class="btn btn-azul mt-3 btn-full" id="generar-aumentos">Generar Lista Aumentos</a>
        </div>
        <div class="col-lg-4">
            <form class="formulario formulario-producto ms-lg-3" action="#" method="post">
                <legend>Agregar un nuevo Producto</legend>
                <input class="input" type="text" name="codigo-producto" id="codigo-producto" placeholder="Código del producto">
                <input class="input" type="text" name="nombre-producto" id="nombre-producto" placeholder="Nombre del producto">
                <input class="input" type="number" name="precio-producto" id="precio-producto" placeholder="Precio del producto">
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