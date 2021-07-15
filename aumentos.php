<?php 
require 'includes/funciones.php';

if(!estaAutenticado()){
    header('Location: login.php');
}
incluirTemplate('header');
incluirTemplate('sidebar');
incluirTemplate('menu');

$aumentos = mostrarAumentos();
?>
<section class="contenedor">
    <a href="productos.php"><i class="fas fa-arrow-left"></i></a>

    <h2 class="my-3">Aumentos</h2>

    <div class="caja p-3">
        <div class="p-3">
            <p><span>Fecha</span></p>
            <p><?php echo date('d-m-Y') ?></p>
        </div>

        <div class="contenedor-tabla">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Precio Anterior</th>
                        <th>Precio Nuevo</th>
                    </tr>
                </thead>
                <?php if($aumentos !== 'error') : ?>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($aumentos)) : ?>
                        <tr>
                            <td><?php echo $row['producto'] ?></td>
                            <td><?php echo $row['nombre'] ?></td>
                            <td>$<?php echo $row['precio_anterior'] ?></td>
                            <td>$<?php echo $row['precio_actual'] ?></td>
                        </tr>
                        <?php endwhile; ?>
                        <tr>
                    </tbody>
                    <?php else: ?>
                    <div class="mensaje error text-center">No hay aumentos</div>
                <?php endif ?>
            </table>
        </div>
    </div>

    <button class="btn btn-azul btn-full mt-3"><i class="fas fa-file-pdf"></i>Generar PDF</button>
    
</section>
<?php 
incluirTemplate('footer');