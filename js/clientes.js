document.addEventListener('DOMContentLoaded', () => {
    const formularioCliente = document.querySelector('.formulario-cliente');
    const tablaClientes = document.querySelector('.tabla-clientes');

    formularioCliente.addEventListener('submit', guardarRegistro);
    tablaClientes.addEventListener('click', cambiarAccion);
})

let idCliente;
const nombre = document.querySelector('#nombre-cliente');
const direccion = document.querySelector('#direccion-cliente');
const telefono = document.querySelector('#telefono-cliente');
const accion = document.querySelector('#accion');

const guardarRegistro = e => {
    e.preventDefault();
    
    //Validación de datos
    if(nombre.value.trim() === '' || direccion.value.trim() === ''){
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'El nombre y la dirección del cliente son obligatorios!'
        });
        return;
    }
    const accion = document.querySelector('#accion').value;
    
    const datosCliente = new FormData();
    datosCliente.append('nombre', nombre.value);
    datosCliente.append('direccion', direccion.value);
    datosCliente.append('telefono', telefono.value);
    datosCliente.append('accion', accion);

    if(accion === 'crear'){
        crearRegistro(datosCliente);
    }else{
        datosCliente.append('idCliente', idCliente);
        actualizarRegistro(datosCliente);
    }
}

const cambiarAccion = e => {
    if(e.target.id === 'editar-cliente'){
        idCliente = e.target.parentElement.dataset.id;
        document.querySelector('.formulario-cliente legend').innerHTML = 'Editar Cliente';
        document.querySelector('#accion').value = 'editar';
        document.querySelector('#value').value = 'Actualizar';
        const valores = e.target.parentElement.parentElement.children;
        document.querySelector('#nombre-cliente').value = valores[0].innerHTML;
        document.querySelector('#direccion-cliente').value = valores[1].innerHTML;
        document.querySelector('#telefono-cliente').value = valores[2].innerHTML;
        return;
    }
    if(e.target.id === 'eliminar-cliente'){
        Swal.fire({
            title: 'Está seguro?',
            text: "Una vez eliminado el registro no se puede recuperarr!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                idCliente = e.target.parentElement.dataset.id;
                const datosEliminar = new FormData();
                datosEliminar.append('accion', 'eliminar');
                datosEliminar.append('idCliente', idCliente);
                eliminarRegistro(datosEliminar);
            }
        })
        return;
    }
    if(accion.value === 'editar'){
        idCliente = '';
        document.querySelector('.formulario-cliente legend').innerHTML = 'Agregar un nuevo Cliente';
        document.querySelector('#accion').value = 'crear';
        document.querySelector('#value').value = 'Crear';
        document.querySelector('#nombre-cliente').value = '';
        document.querySelector('#direccion-cliente').value = '';
        document.querySelector('#telefono-cliente').value = '';
        return;
    }
}

const crearRegistro = datos => {
    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'includes/modelos/modelo-cliente.php', true);

    xhr.onload = function(){
        if(this.status === 200){
            const respuesta = JSON.parse(xhr.responseText);
            if(respuesta.respuesta === 'correcto'){
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'El cliente se ingresó correctamente!'
                });
                const {nombre, direccion, telefono, id} = respuesta.datos;
                document.querySelector('.formulario-cliente').reset();
                const nuevoCliente = document.createElement('tr');
                nuevoCliente.innerHTML = `
                    <td>${nombre}</td>
                    <td>${direccion}</td>
                    <td>${telefono}</td>
                    <td data-id="${id}">
                        <button class="d-block w-100 mb-1 btn btn-success" id="editar-cliente">Editar</button>
                        <button class="d-block w-100 btn btn-danger" id="eliminar-cliente">Eliminar</button>
                    </td>
                `;
                document.querySelector('.tabla-clientes tbody').appendChild(nuevoCliente);
            }else if(respuesta.respuesta === 'error'){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Ha ocurrido un problema'
                });
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Ya alcanzó el máximo de clientes disponibles'
                });
            }
        }
    }

    xhr.send(datos);
}

const actualizarRegistro = datos => {
    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'includes/modelos/modelo-cliente.php', true);

    xhr.onload = function(){
        if(this.status === 200){
            const respuesta = JSON.parse(xhr.responseText);
            if(respuesta.respuesta === 'correcto'){
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'El cliente se ingresó correctamente!'
                });
                const {nombre, direccion, telefono} = respuesta.datos;
                document.querySelectorAll('.tabla-clientes tbody tr').forEach(element => {
                    if(element.dataset.cliente === `${idCliente}`){
                        element.children[0].innerHTML = nombre;
                        element.children[1].innerHTML = direccion;
                        element.children[2].innerHTML = telefono;
                    }
                });
                document.querySelector('.formulario-cliente').reset();
                idCliente = '';
                document.querySelector('.formulario-cliente legend').innerHTML = 'Agregar un nuevo Cliente';
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

const eliminarRegistro = datos => {
    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'includes/modelos/modelo-cliente.php', true);

    xhr.onload = function(){
        if(this.status === 200){
            const respuesta = JSON.parse(xhr.responseText);
            if(respuesta.respuesta === 'correcto'){
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'El cliente se eliminó correctamente!'
                });
                document.querySelectorAll('.tabla-clientes tbody tr').forEach(element => {
                    if(element.dataset.cliente === `${idCliente}`){
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