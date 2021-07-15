document.addEventListener('DOMContentLoaded', () => {
    const buscadorGasto = document.querySelector('#buscador-gastos');
    const buscardorMes = document.querySelector('#buscador-mes');
    const formularioGasto = document.querySelector('.formulario-gasto');
    const tablaGastos = document.querySelector('.tabla-gastos');

    const meses = ['Enero','Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio','Agosto','Septiembre','Octubre','Noviembre', 'Diciembre'];

    for (let index = 0; index < meses.length; index++) {
        const element = document.createElement('option');
        element.value = index + 1;
        element.innerHTML = meses[index];
        buscardorMes.appendChild(element)
    }

    formularioGasto.addEventListener('submit', guardarRegistro);
    tablaGastos.addEventListener('click', cambiarAccion);
    buscadorGasto.addEventListener('input', buscarGasto);
    buscardorMes.addEventListener('change', buscarGastosMes);
})
let idGasto;
const nombre = document.querySelector('#nombre-gasto');
const total = document.querySelector('#total-gasto');
const tipo = document.querySelector('#tipo-gasto');
const accion = document.querySelector('#accion');

const guardarRegistro = e => {
    e.preventDefault();
    
    //Validación de datos
    if(nombre.value.trim() === '' || total.value.trim() === '' || tipo.value === '0'){
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Todos los campos son obligatorios!'
        });
        return;
    }
    const accion = document.querySelector('#accion').value;
    
    const datosGasto = new FormData();
    datosGasto.append('nombre', nombre.value);
    datosGasto.append('total', total.value);
    datosGasto.append('tipo', tipo.value);
    datosGasto.append('accion', accion);

    if(accion === 'crear'){
        crearRegistro(datosGasto);
    }else{
        datosGasto.append('idGasto', idGasto);
        actualizarRegistro(datosGasto);
    }
}

const cambiarAccion = e => {
    if(e.target.id === 'editar-gasto'){
        idGasto = e.target.parentElement.dataset.id;
        console.log(idGasto)
        document.querySelector('.formulario-gasto legend').innerHTML = 'Editar Gasto';
        document.querySelector('#accion').value = 'editar';
        document.querySelector('#value').value = 'Actualizar';
        const valores = e.target.parentElement.parentElement.children;
        document.querySelector('#nombre-gasto').value = valores[1].innerHTML;
        document.querySelector('#total-gasto').value = valores[2].innerHTML.replace('$', '');
        document.querySelector('#tipo-gasto').value = valores[3].id;
        return;
    }
    if(e.target.id === 'eliminar-gasto'){
        Swal.fire({
            title: 'Está seguro?',
            text: "Una vez eliminado el registro no se puede recuperar!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                idGasto = e.target.parentElement.dataset.id;
                const datosEliminar = new FormData();
                datosEliminar.append('accion', 'eliminar');
                datosEliminar.append('idGasto', idGasto);
                eliminarRegistro(datosEliminar);
            }
        })
        return;
    }
    if(accion.value === 'editar'){
        idGasto = '';
        document.querySelector('.formulario-cliente legend').innerHTML = 'Agregar un nuevo gasto';
        document.querySelector('#accion').value = 'crear';
        document.querySelector('#value').value = 'Crear';
        document.querySelector('#nombre-gasto').value = '';
        document.querySelector('#precio-gasto').value = '';
        document.querySelector('#tipo-gasto').value = '';
        return;
    }
}

const buscarGasto = e => {
    const resultado = new RegExp(e.target.value);

    document.querySelectorAll('.tabla tbody tr').forEach(element => {
        if(!resultado.exec(element.children[1].innerHTML.toLowerCase())){
            element.style.display = 'none';
            return;
        }
        element.style.display = 'table-row'
    });
    
}

const buscarGastosMes = e => {
    const mes = e.target.value;
    const datos = new FormData();
    datos.append('accion', 'mostrar');
    datos.append('mes', mes);
    
    mostrarGastosMes(datos);
}

const mostrarGastosMes = datos => {
    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'includes/modelos/modelo-gastos.php', true);

    xhr.onload = function() {
        if(this.status === 200){
            const respuesta = JSON.parse(xhr.responseText);
            if(respuesta.respuesta === 'correcto'){
                const total = parseFloat(respuesta.gastos).toFixed(2);
                const gastoPersonal = parseFloat(respuesta.gastosPersonales).toFixed(2);
                const gastoMercaderia = (total - gastoPersonal).toFixed(2);
                eliminarLineas();
                if(document.querySelector('.mensaje p')){
                    document.querySelector('.mensaje p').remove();
                }
                const { datos } = respuesta;
                datos.forEach(element => {
                    const tabla = document.createElement('tr');
                    tabla.innerHTML = `
                        <td>${element[3]}</td>
                        <td>${element[1]}</td>
                        <td>$${element[2]}</td>
                        <td>${element[6]}</td>
                        <td data-id="${element[0]}">
                            <button class="d-block w-100 mb-1 btn btn-success" id="editar-gasto">Editar</button>
                            <button class="d-block w-100 btn btn-danger" id="eliminar-gasto">Eliminar</button>
                        </td>
                    `
                    document.querySelector('.tabla tbody').append(tabla);
                });
                document.querySelector('#gasto-mes').innerHTML = `$${total}`;
                document.querySelector('#gasto-mercaderia').innerHTML = `$${gastoMercaderia}`;
                document.querySelector('#gasto-personal').innerHTML = `$${gastoPersonal}`;
            }else{
                eliminarLineas();
                if(document.querySelector('.mensaje p')){
                    document.querySelector('.mensaje p').remove();
                }
                const mensaje = document.createElement('p');
                mensaje.innerHTML = 'No hay resultados para este mes';
                document.querySelector('.mensaje').append(mensaje);
                document.querySelector('#gasto-mes').innerHTML = `$0`;
                document.querySelector('#gasto-mercaderia').innerHTML = '$0';
                document.querySelector('#gasto-personal').innerHTML = '$0';
            }
        }
    }

    xhr.send(datos); 
}

const eliminarLineas = () => {
    document.querySelectorAll('.tabla tbody tr').forEach(element => {
        element.remove();
    });
}

const crearRegistro = datos => {
    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'includes/modelos/modelo-gastos.php', true);

    xhr.onload = function(){
        if(this.status === 200){
            const respuesta = JSON.parse(xhr.responseText);
            if(respuesta.respuesta === 'correcto'){
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'El gasto se generó correctamente!'
                });
                const {fecha, nombre, tipo, tipoGasto, total, id} = respuesta.datos;
                document.querySelector('.formulario-gasto').reset();
                const nuevoGasto = document.createElement('tr');
                nuevoGasto.innerHTML = `
                    <td>${fecha}</td>
                    <td>${nombre}</td>
                    <td>$${total}</td>
                    <td id="${tipo}">${tipoGasto}</td>
                    <td data-id="${id}">
                        <button class="d-block w-100 mb-1 btn btn-success" id="editar-gasto">Editar</button>
                        <button class="d-block w-100 btn btn-danger" id="eliminar-gasto">Eliminar</button>
                    </td>
                `;
                (tipo === '1') ?
                    document.querySelector('#gasto-personal').innerHTML = `$${parseFloat(document.querySelector('#gasto-personal').innerHTML.replace('$','')) + parseFloat(total)}`                
                :
                    document.querySelector('#gasto-mercaderia').innerHTML = `$${parseFloat(document.querySelector('#gasto-mercaderia').innerHTML.replace('$','')) + parseFloat(total)}`                
                ;
                document.querySelector('#gasto-mes').innerHTML = `$${parseFloat(document.querySelector('#gasto-mes').innerHTML.replace('$','')) + parseFloat(total)}`
                document.querySelector('.tabla-gastos tbody').appendChild(nuevoGasto);
            }else if(respuesta.respuesta==='completo'){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Alcanzó el número máximo de gastos'
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

const actualizarRegistro = datos => {
    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'includes/modelos/modelo-gastos.php', true);

    xhr.onload = function(){
        if(this.status === 200){
            const respuesta = JSON.parse(xhr.responseText);
            if(respuesta.respuesta === 'correcto'){
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'El gasto se ingresó correctamente!'
                });
                const {nombre, tipoGasto, total} = respuesta.datos;
                document.querySelectorAll('.tabla-gastos tbody tr').forEach(element => {
                    if(element.dataset.gasto === `${idGasto}`){
                        element.children[1].innerHTML = nombre;
                        element.children[2].innerHTML = `$${total}`;
                        element.children[3].innerHTML = tipoGasto;
                    }
                });
                document.querySelector('.formulario-gasto').reset();
                idGasto = '';
                document.querySelector('.formulario-gasto legend').innerHTML = 'Agregar un nuevo gasto';
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

    xhr.open('POST', 'includes/modelos/modelo-gastos.php', true);

    xhr.onload = function(){
        if(this.status === 200){
            const respuesta = JSON.parse(xhr.responseText);
            if(respuesta.respuesta === 'correcto'){
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'El gasto se eliminó correctamente!'
                });
                let totalEliminar;
                document.querySelectorAll('.tabla-gastos tbody tr').forEach(element => {
                    if(element.dataset.gasto === `${idGasto}`){
                        totalEliminar = parseFloat(element.children[2].innerHTML.replace('$', ''));
                        /* if(element.children[3].id === '1'){
                            const gastoPersonal = parseFloat(document.querySelector('#gasto-personal').innerHTML.replace('$', ''));
                            document.querySelector('#gasto-personal').innerHTML = `$${gastoPersonal - totalEliminar}`;
                        }else{
                            const gastoMercaderia = (document.querySelector('#gasto-mercaderia').innerHTML.replace('$', ''));
                            console.log(gastoMercaderia)
                            document.querySelector('#gasto-mercaderia').innerHTML = `$${gastoMercaderia - totalEliminar}`;
                        } */
                        element.remove();
                    }
                });
                const gasto = parseFloat(document.querySelector('#gasto-mes').innerHTML.replace('$', ''));
                document.querySelector('#gasto-mes').innerHTML = `$${gasto - totalEliminar}`;
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

