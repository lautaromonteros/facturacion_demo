<?php 
require 'includes/funciones.php';

if(!estaAutenticado()){
    header('Location: login.php');
}
incluirTemplate('header');
incluirTemplate('sidebar');
incluirTemplate('menu');

$gastos = mostrarGastos(date('m'));
$gastomes = gastosMes(date('m'));
$gastospersonales = gastosPersonalesMes(date('m'));
?>
<section class="contenedor">
    <h2>Control de Gastos</h2>
    <div class="d-lg-flex">
        <div class="col-lg-8">
            <div class="d-lg-flex">
                <div class="col-lg-6">
                    <div class="buscador">
                        <label class="label" for="buscador-gastos"><i class="fas fa-search"></i></label>
                        <input type="text" name="buscador-gastos" class="input" id="buscador-gastos" placeholder="Buscar gasto">
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-2">
                    <div class="mt-3 buscador">
                        <select name="buscador-mes" id="buscador-mes">
                            <option value="0" selected disabled>--Seleccione--</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mensaje my-4 text-center"></div>

            <div class="contenedor-tabla">
                <table class="tabla caja tabla-gastos">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Motivo</th>
                            <th>Total</th>
                            <th>Tipo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = mysqli_fetch_assoc($gastos)) : ?>
                        <tr data-gasto="<?php echo $row['idgasto'] ?>">
                            <td><?php echo $row['fecha'] ?></td>
                            <td><?php echo $row['nombre'] ?></td>
                            <td>$<?php echo $row['total'] ?></td>
                            <td id="<?php echo $row['id'] ?>"><?php echo $row['gasto'] ?></td>
                            <td data-id="<?php echo $row['idgasto'] ?>">
                                <button class="d-block w-100 mb-1 btn btn-success" id="editar-gasto">Editar</button>
                                <button class="d-block w-100 btn btn-danger" id="eliminar-gasto">Eliminar</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                        
                    </tbody>
                </table>
            </div>

        </div>

        <div class="col-lg-4">
            <form class="formulario formulario-gasto ms-lg-3" action="#" method="post">
                <legend>Agregar un nuevo gasto</legend>
                <input class="input" type="text" name="nombre-gasto" id="nombre-gasto" placeholder="Nombre del gasto">
                <input class="input" type="number" name="total-gasto" id="total-gasto" placeholder="Total gastado">
                <select name="tipo-gasto" id="tipo-gasto">
                    <option value="0">--Seleccione--</option>
                    <option value="1">Personal</option>
                    <option value="2">Mercadería</option>
                </select>
                <div class="d-flex-right mt-2" id="boton-cliente">
                    <input type="hidden" id="accion" value="crear">
                    <input class="btn btn-azul" id="value" type="submit" value="Crear">
                </div>
            </form>
        </div>
    </div>
    <div class="row g-3 mt-3">
        <div class="col-lg-4">
            <div class="caja p-4">
                <div class="d-flex justify-content-between">
                    <div class="texto-caja">
                        <h3 id="gasto-mes">$<?php echo number_format($gastomes, 2) ?></h3>
                        <p>Gastos del Mes</p>
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
                        <h3 id="gasto-mercaderia">$<?php echo number_format($gastomes - $gastospersonales, 2)  ?></h3>
                        <p>Gastos en Mercadería del Mes</p>
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
                        <h3 id="gasto-personal">$<?php echo number_format($gastospersonales, 2) ?></h3>
                        <p>Gastos personales del Mes</p>
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