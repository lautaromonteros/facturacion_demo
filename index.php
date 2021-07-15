<?php 

require 'includes/funciones.php';

if(!estaAutenticado()){
    header('Location: login.php');
}

incluirTemplate('header');
incluirTemplate('sidebar');
incluirTemplate('menu');
?>
<section class="contenedor">
    <h2>Dashboard</h2>
    
    <div class="row mt-3 g-3">
        <div class="col-lg-3 col-md-6">
            <div class="caja p-4">
                <div class="d-flex justify-content-between">
                    <div class="texto-caja">
                        <h3><?php echo cantidadClientes() ?></h3>
                        <p>Clientes</p>
                    </div>
                    <div class="bg-azul">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div><!-- Caja dashboard -->
        <div class="col-lg-3 col-md-6">
            <div class="caja p-4">
                <div class="d-flex justify-content-between">
                    <div class="texto-caja">
                        <h3><?php echo cantidadProductos() ?></h3>
                        <p>Productos</p>
                    </div>
                    <div class="bg-azul">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
            </div>
        </div><!-- Caja dashboard -->
        <div class="col-lg-3 col-md-6">
            <div class="caja p-4">
                <div class="d-flex justify-content-between">
                    <div class="texto-caja">
                        <h3>$<?php echo gananciasMes(date('m')) ?></h3>
                        <p>Ventas del Mes</p>
                    </div>
                    <div class="bg-azul">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div><!-- Caja dashboard -->
        <div class="col-lg-3 col-md-6">
            <div class="caja p-4">
                <div class="d-flex justify-content-between">
                    <div class="texto-caja">
                        <h3>$<?php echo gastosMes(date('m')) ?></h3>
                        <p>Gastos del Mes</p>
                    </div>
                    <div class="bg-azul">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
            </div>
        </div><!-- Caja dashboard -->
    </div>

    <div class="row g-3 mt-3">
        <div class="col-lg-8">
            <div class="caja p-2">
                <canvas id="myChart"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
                <h3 class="vendidos">Productos más vendidos del mes</h3>
                
                <div class="buscador">
                    <label class="label" for="buscador-producto"><i class="fas fa-search"></i></label>
                    <input type="text" name="buscador-producto" class="input" id="buscador-producto" placeholder="Buscar producto">
                </div>

                <div class="contenedor-tabla caja">
                    <table class="tabla">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
        </div>
    </div>

</section>
<?php 
incluirTemplate('footer');