document.addEventListener('DOMContentLoaded', () => {

  const buscador = document.querySelector('#buscador-producto');

  const ganancias = new FormData();
  ganancias.append('accion', 'ganancias');

  obtenerGanancias(ganancias);
  
  const datos = new FormData();
  datos.append('accion', 'obtener');
  datos.append('mes', new Date().getMonth() + 1);

  obtenerProductos(datos);

  buscador.addEventListener('input', buscarProducto);
})

const obtenerProductos = datos => {
  const xhr = new XMLHttpRequest();

    xhr.open('POST', 'includes/modelos/modelo-producto.php', true);

    xhr.onload = function(){
      if(this.status === 200){
        const respuesta = JSON.parse(xhr.responseText);
        respuesta.forEach(element => {
          const {producto, nombre, cantidad} = element;
          const row = document.createElement('tr');
          row.innerHTML = `
            <td>${producto}</td>
            <td>${nombre}</td>
            <td>${cantidad}</td>
          `;
          document.querySelector('.tabla tbody').append(row);
        });
      }
    }

    xhr.send(datos);
}

const obtenerGanancias = datos => {
  const xhr = new XMLHttpRequest();

    xhr.open('POST', 'includes/modelos/modelo-factura.php', true);

    xhr.onload = function(){
      if(this.status === 200){
        const respuesta = JSON.parse(xhr.responseText);
        const labels = ['Enero','Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio','Agosto','Septiembre','Octubre','Noviembre', 'Diciembre'];
        const data = {
          labels: labels,
          datasets: [{
            label: 'Ganancias por Mes',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: respuesta,
          }]
        };
        
        const config = {
          type: 'line',
          data,
          options: {}
        };
        
        var myChart = new Chart(
          document.getElementById('myChart'),
          config
        );
      }
    }

    xhr.send(datos);
}

const buscarProducto = e => {
  const resultado = new RegExp(e.target.value);

  document.querySelectorAll('.tabla tbody tr').forEach(element => {
    if(!resultado.exec(element.children[1].innerHTML.toLowerCase())){
      element.style.display = 'none';
    }else{
      element.style.display = 'table-row';
    }
  })
}

