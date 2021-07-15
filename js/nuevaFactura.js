document.addEventListener('DOMContentLoaded', () => {
    const selectoClientes = document.querySelector('#selector-clientes');
    const fecha = new Date();
    const codigo = document.querySelector('#codigo-producto');
    const cantidad = document.querySelector('#cantidad-producto');
    const formularioLinea = document.querySelector('.nueva-factura');
    const accionesLinea = document.querySelector('.tabla-nueva-factura tbody');
    const btnGenerar = document.querySelector('.generar');
    const buscadorProducto = document.querySelector('#buscar-producto');

    document.querySelector('.fecha').append(`${fecha.getDate()}-${fecha.getMonth() + 1}-${fecha.getFullYear()}`)

    $(selectoClientes).select2();
    $('#selector-clientes').on('change', guardarCliente);

    codigo.addEventListener('input', inputProducto);
    cantidad.addEventListener('input', inputCantidad);
    formularioLinea.addEventListener('submit', agregarLinea);
    accionesLinea.addEventListener('click', accionLinea);
    btnGenerar.addEventListener('click', generarFactura);
    buscadorProducto.addEventListener('submit', consultarProducto);

})
let linea = 1;
let lineaSeleccionada;
let nroFactura = 0;
let clienteSeleccionado = '';
let cantidadProducto = document.querySelector('#cantidad-producto').value;
let productosSeleccionados = [];
let totalCompra = 0;
const factura = document.querySelector('table tbody');

const guardarCliente = e => {
    clienteSeleccionado = e.target.value;
    const datos = new FormData();
    datos.append('accion', 'obtener');
    datos.append('idCliente', clienteSeleccionado);
    mostrarCliente(datos);
}

const mostrarCliente = datos => {
    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'includes/modelos/modelo-cliente.php', true);

    xhr.onload = function() {
        if(this.status === 200){
            console.log(xhr.responseText);
            const respuesta = JSON.parse(xhr.responseText);

            const { nombre, direccion, telefono } = respuesta.datos;
            
            document.querySelector('.datos-cliente').innerHTML = `
            <p><span>Cliente:</span></p>
            <p>${nombre}</p>
            <p class="py-2">${direccion}</p>
            <p>${telefono}</p>
            `;
        }
    }

    xhr.send(datos);
}

const inputCantidad = e => {
    cantidadProducto = e.target.value;
    let precio = document.querySelector('#precio-producto').innerHTML;
    let total = document.querySelector('#total-producto');
    if(precio !== ''){
        total.innerHTML = parseFloat(precio) * cantidadProducto;
    }
}

const inputProducto = e => {
    let codigo = e.target.value;

    const datosProducto = new FormData();
    datosProducto.append('accion', 'buscar');
    datosProducto.append('codigo', codigo);

    buscarProducto(datosProducto);
}

const accionLinea = e => {
    if(e.target.classList.contains('fa-pen')){
        const index = productosSeleccionados.findIndex(producto => producto.codigo === e.target.parentElement.parentElement.dataset.codigo);
        document.querySelector('#codigo-producto').value = productosSeleccionados[index].codigo;
        document.querySelector('#cantidad-producto').value = productosSeleccionados[index].cantidad;
        document.querySelector('#accion').value = 'editar';
        lineaSeleccionada = productosSeleccionados[index].linea;

        totalCompra -= parseFloat(productosSeleccionados[index].total);

        const datosProducto = new FormData();
        datosProducto.append('accion', 'buscar');
        datosProducto.append('codigo', productosSeleccionados[index].codigo);
    
        buscarProducto(datosProducto);

    }
    // Para eliminar la linea
    if(e.target.classList.contains('fa-trash')){
        const producto = productosSeleccionados.filter(producto => producto.codigo !== e.target.parentElement.parentElement.dataset.codigo);
        productosSeleccionados = producto;
        totalCompra -= parseFloat(e.target.parentElement.parentElement.parentElement.children[4].innerHTML);
        document.querySelector('#total').innerHTML = totalCompra;
        e.target.parentElement.parentElement.parentElement.remove();
        document.querySelector('#codigo-producto').focus();
        return;
    }
}

const buscarProducto = datos => {
    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'includes/modelos/modelo-producto.php', true);

    xhr.onload = function() {
        if(this.status === 200){
            const respuesta = JSON.parse(xhr.responseText);

            if(respuesta.respuesta === 'correcto'){
                const { codigo, nombre, precio } = respuesta.datos;
                const cantidad = parseInt(cantidadProducto);
                const total = cantidad * parseFloat(precio);
                
                document.querySelector('#nombre-producto').innerHTML = nombre;
                document.querySelector('#precio-producto').innerHTML = precio;
                document.querySelector('#total-producto').innerHTML = total;
            }else{
                document.querySelector('#nombre-producto').innerHTML = '';
                document.querySelector('#precio-producto').innerHTML = '';
                document.querySelector('#total-producto').innerHTML = '';
            }
        }
    }

    xhr.send(datos);
}

const agregarLinea = e => {
    e.preventDefault();

    const accion = document.querySelector('#accion').value;

    if(accion === 'agregar'){
        if(document.querySelector('#nombre-producto').innerHTML !== ''){
            const codigo = document.querySelector('#codigo-producto').value;
            const nombre = document.querySelector('#nombre-producto').innerHTML;
            const precio = document.querySelector('#precio-producto').innerHTML;
            const total = document.querySelector('#total-producto').innerHTML;
            
            if(productosSeleccionados.filter(producto => producto.codigo === codigo).length > 0){
                Swal.fire(
                    'Error!',
                    'El producto seleccionado ya está ingresado!',
                    'error'
                );
                return;
            }
            productosSeleccionados.push({
                'linea': linea,
                'codigo': codigo,
                'cantidad': cantidadProducto,
                'precio': precio,
                'total': total
            })
            const nuevaLinea = document.createElement('tr');
            nuevaLinea.dataset.linea = linea;
            nuevaLinea.innerHTML = `
                <td>${codigo}</td>
                <td>${nombre}</td>
                <td>${cantidadProducto}</td>
                <td>${precio}</td>
                <td>${total}</td>
                <td class="acciones" data-codigo="${codigo}">
                    <button class="btn btn-azul" id="editar-linea"><i class="fas fa-pen"></i></button>
                    <button class="btn btn-danger" id="eliminar-linea"><i class="fas fa-trash"></i></button>
                </td>
            `;
            factura.appendChild(nuevaLinea);
            totalCompra += parseFloat(total);
            document.querySelector('#codigo-producto').value = '';
            document.querySelector('#codigo-producto').focus();
            document.querySelector('#nombre-producto').innerHTML = '';
            document.querySelector('#cantidad-producto').value = '1';
            document.querySelector('#precio-producto').innerHTML = '';
            document.querySelector('#total-producto').innerHTML = '';
            cantidadProducto = '1';
            linea++;

            document.querySelector('#total').innerHTML = totalCompra;
        }
        return;
    }
    if(accion === 'editar'){
        const codigo = document.querySelector('#codigo-producto').value;
        const nombre = document.querySelector('#nombre-producto').innerHTML;
        const precio = document.querySelector('#precio-producto').innerHTML;
        const total = document.querySelector('#total-producto').innerHTML;
        const producto = productosSeleccionados.findIndex(producto => producto.linea === lineaSeleccionada);
        productosSeleccionados[producto].codigo = codigo;
        productosSeleccionados[producto].cantidad = cantidadProducto;
        productosSeleccionados[producto].precio = precio;
        productosSeleccionados[producto].total = total;
        document.querySelectorAll('tbody tr').forEach(element => {
            if(parseInt(element.dataset.linea) === lineaSeleccionada){
                element.children[0].innerHTML = codigo;
                element.children[1].innerHTML = nombre;
                element.children[2].innerHTML = cantidadProducto;
                element.children[3].innerHTML = precio;
                element.children[4].innerHTML = total;
            }
        });
        document.querySelector('#accion').value = 'agregar';
        totalCompra += parseFloat(total);
        document.querySelector('#codigo-producto').value = '';
        document.querySelector('#codigo-producto').focus();
        document.querySelector('#nombre-producto').innerHTML = '';
        document.querySelector('#cantidad-producto').value = '1';
        document.querySelector('#precio-producto').innerHTML = '';
        document.querySelector('#total-producto').innerHTML = '';
        cantidadProducto = '1';

        document.querySelector('#total').innerHTML = totalCompra;
    }
}

const generarFactura = () => {
    if(clienteSeleccionado === '' || productosSeleccionados.length === 0){
        Swal.fire(
            'Error!',
            'No seleccionó ningun producto o no seleccionó un cliente!',
            'error'
        );
        return;
    }

    const datosFactura = new FormData();
    datosFactura.append('accion', 'generar');
    datosFactura.append('cliente', clienteSeleccionado);
    datosFactura.append('total', totalCompra);

    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'includes/modelos/modelo-factura.php', true);

    xhr.onload = function() {
        if(this.status === 200){
            const respuesta = JSON.parse(xhr.responseText);
            if(respuesta.respuesta === 'correcto'){
                let linea = 1;

                productosSeleccionados.forEach(producto => {
                    const datosDetalle = new FormData();
                    datosDetalle.append('accion', 'agregar');
                    datosDetalle.append('idfactura', respuesta.factura_id);
                    datosDetalle.append('linea', linea);
                    datosDetalle.append('codigo', producto.codigo);
                    datosDetalle.append('cantidad', producto.cantidad);
                    datosDetalle.append('precio', producto.precio);
                    datosDetalle.append('total', producto.total);
                    agregarDetalle(datosDetalle);
                    linea++;
                });
                
                Swal.fire(
                    'Success!',
                    'La factura se generó correctamente!',
                    'success'
                );
                setTimeout(() => {
                    location.reload();
                }, 3000);
            }else if(respuesta.respuesta === 'completo'){
                Swal.fire(
                    'Error!',
                    'Alcanzó el número máximo de facturas disponibles!',
                    'error'
                );
            }
        }
    }

    xhr.send(datosFactura);    
}

const agregarDetalle = datos => {
    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'includes/modelos/modelo-factura.php', true);

    xhr.onload = function() {
        if(this.status === 200){
            console.log(xhr.responseText)
        }
    }

    xhr.send(datos);
}

const consultarProducto = e => {
    e.preventDefault();
    const inputBuscador = document.querySelector('#buscador-producto').value;
    
    const datos = new FormData();
    datos.append('accion', 'consultar');
    datos.append('producto', inputBuscador);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'includes/modelos/modelo-producto.php', true);
    xhr.onload = function() {
        if(this.status === 200){
            const respuesta = JSON.parse(xhr.responseText);

            if(respuesta.respuesta === 'correcto'){
                const {datos} = respuesta;

                let aux = {};
                let inputOptions = [];

                datos.forEach(producto => {

                    inputOptions.push(`Código: ${producto[0]}, Nombre: ${producto[1]}\n`);
                });

                alert(inputOptions)
                
            } else{
                Swal.fire(
                    'Error!',
                    'El producto ingresado no existe!',
                    'error'
                );
            }
        }
    }
    xhr.send(datos);
}