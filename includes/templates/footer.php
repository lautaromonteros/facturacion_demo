</div>
</div>

<script src="js/app.js"></script>
<?php if($_SERVER['PHP_SELF'] === '/facturacion_demo/index.php'){ ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/dashboard.js"></script>
<?php } ?>
<?php if($_SERVER['PHP_SELF'] === '/facturacion_demo/clientes.php'){ ?>
    <script src="js/clientes.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php } ?>
<?php if($_SERVER['PHP_SELF'] === '/facturacion_demo/productos.php'){ ?>
    <script src="js/productos.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php } ?>
<?php if($_SERVER['PHP_SELF'] === '/facturacion_demo/nueva-factura.php'){ ?>
    <script src="js/nuevaFactura.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php } ?>
<?php if($_SERVER['PHP_SELF'] === '/facturacion_demo/facturas.php'){ ?>
    <script src="js/html2pdf.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/facturas.js"></script>
<?php } ?>
<?php if($_SERVER['PHP_SELF'] === '/facturacion_demo/gastos.php'){ ?>
    <script src="js/gastos.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php } ?>
</body>
</html>