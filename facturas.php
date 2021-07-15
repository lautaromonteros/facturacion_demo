<?php 
require 'includes/funciones.php';

if(!estaAutenticado()){
    header('Location: login.php');
}
incluirTemplate('header');
incluirTemplate('sidebar');
incluirTemplate('menu');

$mes = date('m');

$facturas = mostrarFacturas($mes);
$ganancias = gananciasMes($mes);
?>
<section class="contenedor">
    <h2>Control de Facturas</h2>
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="buscador">
                <label class="label" for="buscador-cliente-factura"><i class="fas fa-search"></i></label>
                <input type="text" name="buscador-cliente-factura" class="input" id="buscador-cliente-factura" placeholder="Buscar cliente">
            </div>
        </div>
        <div class="col-lg-4">
            <div class="buscador">
                <input type="date" name="buscador-fecha-factura" class="input" id="buscador-fecha-factura" placeholder="Buscar fecha">
            </div>
        </div>
        <div class="col-lg-4">
            <div class="buscador">
                <select name="buscador-mes" id="buscador-mes">
                    <option value="0" selected disabled>--Seleccione--</option>
                </select>
            </div>
        </div>
    </div>

    <div class="mensaje my-4 text-center"></div>

    <div class="contenedor-tabla">
        <table class="tabla caja">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Cliente</th>
                    <th>Dirección</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($facturas as $factura) : ?>
                <tr data-id="<?php echo $factura['idfactura'] ?>">
                    <td>#<?php echo $factura['idfactura'] ?></td>
                    <td><?php echo $factura['nombre'] ?></td>
                    <td><?php echo $factura['direccion'] ?></td>
                    <td>$<?php echo $factura['total'] ?></td>
                    <td><?php echo $factura['fecha']; ?></td>
                    <td>
                        <a href="factura.php?id=<?php echo $factura['idfactura']; ?>" class="d-block w-100 mb-1 btn btn-success">Ver</a>
                        <button class="d-block w-100 btn btn-danger">Anular</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <button class="btn btn-azul mt-3" id="generar"><i class="far fa-file-pdf"></i> Generar PDF</button>

    <div id="vista-previa" class="mt-3">
        <h2>Vista Previa</h2>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-lg-4">
            <div class="caja p-4">
                <div class="d-flex justify-content-between">
                    <div class="texto-caja">
                        <h3 id="venta-mes">$<?php echo $ganancias ?></h3>
                        <p>Ventas del Mes</p>
                    </div>
                    <div class="bg-azul">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div><!-- Caja ventas -->
        <div class="col-lg-4">
            <div class="caja p-4">
                <div class="d-flex justify-content-between">
                    <div class="texto-caja">
                        <h3 id="diferencia-mes">$<?php echo floatval($ganancias) * 0.75 ?></h3>
                        <p>Diferencia de Ganancias del Mes</p>
                    </div>
                    <div class="bg-azul">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div><!-- Caja ventas -->
        <div class="col-lg-4">
            <div class="caja p-4">
                <div class="d-flex justify-content-between">
                    <div class="texto-caja">
                        <h3 id="ganancia-mes">$<?php echo floatval($ganancias) * 0.25 ?></h3>
                        <p>Ganancias del Mes</p>
                    </div>
                    <div class="bg-azul">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div><!-- Caja ventas -->
    </div>
</section>
<?php 
incluirTemplate('footer');