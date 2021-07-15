<?php 
require 'includes/funciones.php';

if(!estaAutenticado()){
    header('Location: login.php');
}
incluirTemplate('header');
incluirTemplate('sidebar');
incluirTemplate('menu');

$id = $_GET['id'];
if(isset($id)){
    $cabecera = cabeceraFactura($id);
    $detalle = detalleFactura($id);
}
?>
<section class="contenedor">
    <a href="facturas.php"><i class="fas fa-arrow-left"></i></a>
    <h2 class="py-3">Factura #<?php echo $cabecera['idfactura'] ?></h2>
    <div class="caja p-3">
        <div class="cabecera d-md-flex">
            <div class="datos-cliente col-md-6">
                <p><span>Cliente</span></p>
                <p><?php echo $cabecera['nombre'] ?></p>
                <p><?php echo $cabecera['direccion'] ?></p>
                <p><?php echo $cabecera['telefono'] ?></p>
            </div>
            <div class="fecha col-md-6">
                <p><span>Fecha</span></p>
                <p><?php echo date('d-m-Y', strtotime($cabecera['fecha'])) ?></p>
            </div>
        </div>

        <div class="contenedor-tabla">
            <table class="tabla mt-3">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($detalle)) : ?>
                    <tr>
                        <td><?php echo $row['producto'] ?></td>
                        <td><?php echo $row['nombre'] ?></td>
                        <td><?php echo $row['cantidad'] ?></td>
                        <td>$<?php echo $row['precio'] ?></td>
                        <td>$<?php echo $row['total'] ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Total</td>
                        <td>$<?php echo $cabecera['total'] ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div><!-- Fin factura -->
    

    <div class="d-lg-flex justify-content-lg-around flex-lg-row-reverse mt-3">
        <button class="d-block btn btn-full btn-azul anular">Generar</button>
        <button class="d-block btn btn-full btn-danger anular">Anular</button>
    </div>
</section>
<?php 
incluirTemplate('footer');