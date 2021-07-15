document.addEventListener('DOMContentLoaded', () => {
    const formularioProducto = document.querySelector('.formulario-producto');
    const tablaProductos = document.querySelector('.tabla-productos');
    const buscador = document.querySelector('#buscador-producto');

    formularioProducto.addEventListener('submit', validarFormulario);
    tablaProductos.addEventListener('click', cambiarAccion);
    buscador.addEventListener('input', buscarProducto);
})

let codigoProducto;
const codigo = document.querySelector('#codigo-producto');
const nombre = document.querySelector('#nombre-producto');
const precio = document.querySelector('#precio-producto');

const cambiarAccion = e => {
    if(e.target.id === 'editar-producto'){
        document.querySelector('.formulario-producto legend').innerHTML = 'Editar producto';
        document.querySelector('#accion').value = 'editar';
        document.querySelector('#value').value = 'Actualizar';
        const valores = e.target.parentElement.parentElement.children;
        document.querySelector('#codigo-producto').value = valores[0].innerHTML;
        document.querySelector('#nombre-producto').value = valores[1].innerHTML;
        document.querySelector('#precio-producto').value = valores[2].innerHTML.slice(1);
        return;
    }
    if(e.target.id === 'eliminar-producto'){
        Swal.fire({
            title: 'Está seguro?',
            text: "Una vez eliminado el registro no se puede recuperar!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar!'
        }).then((result) => {
            if (result.isConfirmed) {
                codigoProducto = e.target.parentElement.dataset.id;
                const datosEliminar = new FormData();
                datosEliminar.append('accion', 'eliminar');
                datosEliminar.append('codigo', codigoProducto);
                eliminarRegistro(datosEliminar);
            }
        })
        return;
    }
    if(e.target.classList.contains('estado')){
        const idEstado = e.target.id;
        const producto = e.target.parentElement.parentElement.dataset.producto;

        const datos = new FormData();
        datos.append('accion', 'estado');
        datos.append('codigo', producto);

        if(idEstado === '1'){
            datos.append('estado', 2);
            setNoDisponible(datos);
        }else{
            datos.append('estado', 1);
            setDisponible(datos);
        }
        return;
    }
    if(accion.value === 'editar'){
        idCliente = '';
        document.querySelector('.formulario-producto legend').innerHTML = 'Agregar un nuevo Producto';
        document.querySelector('#accion').value = 'crear';
        document.querySelector('#value').value = 'Crear';
        document.querySelector('#codigo-producto').value = '';
        document.querySelector('#nombre-producto').value = '';
        document.querySelector('#precio-producto').value = '';
        return;
    }
}

const buscarProducto = e => {
    const resultado = new RegExp(e.target.value);

    document.querySelectorAll('.tabla-productos tbody tr').forEach( element => {
        if(!resultado.exec(element.children[1].innerHTML.toLocaleLowerCase())){
            element.style.display = 'none';
        }else{
            element.style.display = 'table-row';
        }
    })
}

const validarFormulario = e => {
    e.preventDefault();

    if(codigo.value.trim() === '' || nombre.value.trim() === '' || precio.value.trim() === ''){
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Todos los campos son obligatorios!'
        });
        return;
    }

    const accion = document.querySelector('#accion').value;
    const datosProducto = new FormData();
    datosProducto.append('codigo', codigo.value);
    datosProducto.append('nombre', nombre.value);
    datosProducto.append('precio', precio.value);
    datosProducto.append('accion', accion);

    if(accion === 'crear'){
        crearRegistro(datosProducto);
    }else{
        actualizarRegistro(datosProducto);
    }
}
const crearRegistro = datos => {
    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'includes/modelos/modelo-producto.php', true);

    xhr.onload = function(){
        if(this.status === 200){
            const respuesta = JSON.parse(xhr.responseText);
            if(respuesta.respuesta === 'correcto'){
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'El producto se creó correctamente!'
                });
                const {codigo, nombre, precio} = respuesta.datos;
                document.querySelector('.formulario-producto').reset();
                const nuevoProducto = document.createElement('tr');
                nuevoProducto.innerHTML = `
                    <td>${codigo}</td>
                    <td>${nombre}</td>
                    <td>$${precio}</td>
                    <td><p class="disponible btn-success estado">Disponible</p></td>
                    <td data-id="${codigo}">
                        <button class="d-block w-100 mb-1 btn btn-success" id="editar-producto">Editar</button>
                        <button class="d-block w-100 btn btn-danger" id="eliminar-producto">Eliminar</button>
                    </td>
                `;
                document.querySelector('.tabla-productos tbody').appendChild(nuevoProducto);
                if(document.querySelector('#codigo-producto').classList.contains('error')){
                    document.querySelector('#codigo-producto').classList.remove('error');
                }
            }else if(respuesta.respuesta === 'error'){
                document.querySelector('#codigo-producto').classList.add('error');
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El código ingresado ya existe. Por favor ingrese un código diferente'
                });
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Ya alcanzó el máximo de productos disponibles'
                });
            }
        }
    }

    xhr.send(datos);
}

const actualizarRegistro = datos => {
    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'includes/modelos/modelo-producto.php', true);

    xhr.onload = function(){
        if(this.status === 200){
            const respuesta = JSON.parse(xhr.responseText);
            if(respuesta.respuesta === 'correcto'){
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'El producto se actualizó correctamente!'
                });
                const {codigo, nombre, precio} = respuesta.datos;
                document.querySelectorAll('.tabla-productos tbody tr').forEach(element => {
                    if(element.dataset.producto === `${codigo}`){
                        element.children[0].innerHTML = codigo;
                        element.children[1].innerHTML = nombre;
                        element.children[2].innerHTML = `$${precio}.00`;
                    }
                });
                document.querySelector('.formulario-producto').reset();
                document.querySelector('.formulario-producto legend').innerHTML = 'Agregar un nuevo Producto';
                document.querySelector('#accion').value = 'crear';
                document.querySelector('#value').value = 'Crear';
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Ha ocurrido un problema'
                });
            }
        }
    }

    xhr.send(datos);
}

const setNoDisponible = datos => {
    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'includes/modelos/modelo-producto.php', true);

    xhr.onload = function(){
        if(this.status === 200){
            const respuesta = JSON.parse(xhr.responseText);
            if(respuesta.respuesta === 'correcto'){
                const {codigo, estado} = respuesta;
                const listado = document.querySelectorAll('.tabla-productos tbody tr');
                listado.forEach(producto => {
                    if(producto.dataset.producto == codigo){
                        console.log(producto.children[3].children[0])
                        producto.children[3].children[0].classList.add('btn-danger');
                        producto.children[3].children[0].classList.remove('btn-success');
                        producto.children[3].children[0].id = estado;
                        producto.children[3].children[0].innerHTML = 'No Disponible';
                    }
                });
            }
        }
    }
    xhr.send(datos)
}

const setDisponible = datos => {
    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'includes/modelos/modelo-producto.php', true);

    xhr.onload = function(){
        if(this.status === 200){
            const respuesta = JSON.parse(xhr.responseText);
            if(respuesta.respuesta === 'correcto'){
                const {codigo, estado} = respuesta;
                const listado = document.querySelectorAll('.tabla-productos tbody tr');
                listado.forEach(producto => {
                    if(producto.dataset.producto == codigo){
                        console.log(producto.children[3].children[0])
                        producto.children[3].children[0].classList.remove('btn-danger');
                        producto.children[3].children[0].classList.add('btn-success');
                        producto.children[3].children[0].id = estado;
                        producto.children[3].children[0].innerHTML = 'Disponible';
                    }
                });
            }
        }
    }
    xhr.send(datos)
}

const eliminarRegistro = datos => {
    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'includes/modelos/modelo-producto.php', true);

    xhr.onload = function(){
        if(this.status === 200){
            const respuesta = JSON.parse(xhr.responseText);
            if(respuesta.respuesta === 'correcto'){
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'El producto se eliminó correctamente!'
                });
                document.querySelectorAll('.tabla-productos tbody tr').forEach(element => {
                    if(element.dataset.producto === `${codigoProducto}`){
                        element.remove();
                    }
                });
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Ha ocurrido un problema'
                });
            }
        }
    }

    xhr.send(datos);
}