document.addEventListener('DOMContentLoaded', () => {
    const buscadorCliente = document.querySelector('#buscador-cliente-factura');
    const buscardorFecha = document.querySelector('#buscador-fecha-factura');
    const buscardorMes = document.querySelector('#buscador-mes');
    const listadoFacturas = document.querySelector('.tabla tbody');
    const btnGenerar = document.querySelector('#generar');

    const meses = ['Enero','Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio','Agosto','Septiembre','Octubre','Noviembre', 'Diciembre'];

    for (let index = 0; index < meses.length; index++) {
        const element = document.createElement('option');
        element.value = index + 1;
        element.innerHTML = meses[index];
        buscardorMes.appendChild(element)
    }

    buscadorCliente.addEventListener('input', buscarFacturaCliente);
    buscardorFecha.addEventListener('change', buscarFacturaFecha);
    buscardorMes.addEventListener('change', buscarFacturasMes);
    listadoFacturas.addEventListener('click', seleccionarFactura);
    btnGenerar.addEventListener('click', generarFactura);
})
let facturasSeleccionadas = [];

const buscarFacturaCliente = e => {
    const resultado = new RegExp(e.target.value);

    document.querySelectorAll('.tabla tbody tr').forEach(element => {
        if(!resultado.exec(element.children[1].innerHTML.toLowerCase())){
            element.style.display = 'none';
            return;
        }
        element.style.display = 'table-row'
    });
    
}

const buscarFacturaFecha = e => {
    const resultado = e.target.value;

    document.querySelectorAll('.tabla tbody tr').forEach(element => {
        if(resultado !== element.children[4].innerHTML){
            element.style.display = 'none';
            return;
        }
        element.style.display = 'table-row'
    });

    document.querySelector('#buscador-mes').value = '';
}

const buscarFacturasMes = e => {
    document.querySelector('#buscador-fecha-factura').value = '';
    const mes = e.target.value;
    const datos = new FormData();
    datos.append('accion', 'mostrar');
    datos.append('mes', mes);
    
    mostrarFacturasMes(datos);
}

const mostrarFacturasMes = datos => {
    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'includes/modelos/modelo-factura.php', true);

    xhr.onload = function() {
        if(this.status === 200){
            const respuesta = JSON.parse(xhr.responseText);
            const total = parseFloat(respuesta.venta).toFixed(2);
            if(respuesta.respuesta === 'correcto'){
                eliminarLineas();
                if(document.querySelector('.mensaje p')){
                    document.querySelector('.mensaje p').remove();
                }
                const { datos } = respuesta;
                datos.forEach(element => {
                    const tabla = document.createElement('tr');
                    tabla.innerHTML = `
                        <td>#${element[0]}</td>
                        <td>${element[5]}</td>
                        <td>${element[6]}</td>
                        <td>$${element[3]}</td>
                        <td>${element[2]}</td>
                        <td>
                            <a href="factura.php?id=${element[0]}" class="d-block w-100 mb-1 btn btn-success">Ver</a>
                            <button class="d-block w-100 btn btn-danger">Anular</button>
                        </td>
                    `;
                    document.querySelector('.tabla tbody').append(tabla);
                });
                document.querySelector('#venta-mes').innerHTML = `$${total}`;
                document.querySelector('#diferencia-mes').innerHTML = `$${total * 0.75}`;
                document.querySelector('#ganancia-mes').innerHTML = `$${total * 0.25}`;
            }else{
                eliminarLineas();
                if(document.querySelector('.mensaje p')){
                    document.querySelector('.mensaje p').remove();
                }
                const mensaje = document.createElement('p');
                mensaje.innerHTML = 'No hay resultados para este mes';
                document.querySelector('.mensaje').append(mensaje);
                document.querySelector('#venta-mes').innerHTML = '$0';
                document.querySelector('#diferencia-mes').innerHTML = '$0';
                document.querySelector('#ganancia-mes').innerHTML = '$0';
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

const seleccionarFactura = e => {
    if(!e.target.classList.contains('btn')){
        if(e.target.parentElement.classList.contains('activo')){
            e.target.parentElement.classList.remove('activo');
            facturasSeleccionadas = facturasSeleccionadas.filter(factura => factura !== e.target.parentElement.dataset.id)
            eliminarFactura(e.target.parentElement.dataset.id)
            return;
        }
        e.target.parentElement.classList.add('activo');
        facturasSeleccionadas.push(e.target.parentElement.dataset.id);
        agregarFactura(e.target.parentElement.dataset.id)
    }
    if(e.target.classList.contains('btn-danger')){
        Swal.fire({
            title: 'Está seguro?',
            text: "Una factura eliminada no se puede recuperar!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar!',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {
                const id = e.target.parentElement.parentElement.dataset.id;
                const datos = new FormData();
                datos.append('accion', 'eliminar');
                datos.append('id', id);

                anularFactura(datos);
            }
          })
    }
}

const agregarFactura = id => {
    const xhr = new XMLHttpRequest();

    xhr.open('GET', `factura.php?id=${id}`, true);

    xhr.onload = function(){
        if(this.status === 200){
            const resultado = xhr.responseText;
            const inicio = resultado.indexOf('<div class="caja p-3">')
            const fin = resultado.indexOf('</div><!-- Fin factura -->')
            const factura = document.createElement('DIV');
            factura.dataset.id = id;
            factura.classList.add('factura');
            factura.innerHTML = resultado.substr(inicio, fin - inicio + 6)
            document.querySelector('#vista-previa').appendChild(factura)
            document.querySelector('#vista-previa').classList.add('mostrar-factura')
        }
    }

    xhr.send(id);
}

const eliminarFactura = id => {
    const facturas = document.querySelectorAll('#vista-previa .factura');
    facturas.forEach(factura => {
        if(factura.dataset.id === id){
            document.querySelector('#vista-previa').removeChild(factura);
        }
    });
    if(document.querySelector('#vista-previa').childElementCount < 2){
        document.querySelector('#vista-previa').classList.remove('mostrar-factura')
    }
}

const generarFactura = () => {
    if(facturasSeleccionadas.length === 0){
        Swal.fire(
            'Error!',
            'Seleccione al menos una factura!',
            'error'
        )
        return;
    }
    const pdf = document.querySelector('#vista-previa');
    html2pdf()
        .set({filename: `Facturas${new Date().getDate()}-${new Date().getMonth() + 1}-${new Date().getFullYear()}`, pagebreak: {mode: 'avoid-all'}})
        .from(pdf)
        .save();
    
    setTimeout(() => {
        location.reload();
    }, 6000);
}

const anularFactura = datos => {
    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'includes/modelos/modelo-factura.php', true);

    xhr.onload = function(){
        if(this.status === 200){
            console.log(xhr.responseText);
            const respuesta = JSON.parse(xhr.responseText);
            if(respuesta.respuesta === 'correcto'){
                Swal.fire(
                    'Good job!',
                    'La factura se eliminó correctamente!',
                    'success'
                )
            }else{
                Swal.fire(
                    'Error!',
                    'Ocurrió un error!',
                    'error'
                )
            }
        }
    }

    xhr.send(datos);
}