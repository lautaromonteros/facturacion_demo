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
    <a href="facturas.php"><i class="fas fa-arrow-left"></i></a>
    <h2 class="py-3">Factura #123</h2>

    <div class="row my-5">
        <div class="col-lg-6">
            <select name="selector-clientes" id="selector-clientes">
                <option value="0">--Seleccione--</option>
                <?php while($row = mysqli_fetch_assoc($clientes)) : ?>
                <option value="<?php echo $row['id'] ?>"><?php echo $row['nombre'] . ' - ' . $row['direccion'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-lg-6">
            <form method="post" id="buscar-producto">
                <div class="d-flex">
                    <div class="buscador">
                        <label class="label" for="buscador-producto"><i class="fas fa-search"></i></label>
                        <input type="text" name="buscador-producto" class="input" id="buscador-producto" placeholder="Buscar producto">
                    </div>
                    <input type="submit" class="btn btn-azul" value="Buscar">
                </div>
            </form>
        </div>
    </div>

    <div class="caja p-3">
        <div class="cabecera d-md-flex">
            <div class="datos-cliente col-md-6">
                <p><span>Cliente:</span></p>
            </div>
            <div class="fecha col-md-6">
                <p><span>Fecha: </span></p>
            </div>
        </div>

        <div class="contenedor-tabla">
            <table class="tabla mt-3 tabla-nueva-factura">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <form action="#" method="post" class="nueva-factura">
                        <tr>
                            <td><input type="text" class="codigo-producto" name="codigo-producto" id="codigo-producto"></td>
                            <td id="nombre-producto"></td>
                            <td><input type="number" name="cantidad-producto" id="cantidad-producto" min="1" value="1"></td>
                            <td id="precio-producto"></td>
                            <td id="total-producto"></td>
                        </tr>
                        <input type="hidden" value="agregar" id="accion">
                        <input type="submit" class="hidden" value="">
                    </form>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Total</td>
                        <td id="total"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
    

    <div class="d-lg-flex justify-content-lg-around flex-lg-row-reverse mt-3">
        <button class="d-block btn btn-full btn-azul generar">Generar</button>
        <button class="d-block btn btn-full btn-danger anular">Anular</button>
    </div>
</section>
<?php 
incluirTemplate('footer');