export class creartabla {
    ruta = "";
    destino = "";
    filas = 20;
    pagina = "";
    campoOrden = "";
    tipoOrden = "";
    rutaTotalRegistros = "";
    destinobuscador = "";
    busqueda = "";
    segmento = [];
    contador = 0;
    clasesTabla = "";
    destinoPaginador = "";
    boton = [];
    rutaApp = "";
    id = "";

    constructor(ruta, destino, orden, tipoOrden, pagina, destinobuscador, rutaTotalRegistros, segmento, clasesTabla, destinoPaginador, boton, rutaApp, id) {
        this.ruta = ruta;
        this.rutaApp = rutaApp;
        this.id = id;
        this.destino = destino;
        this.campoOrden = orden;
        this.tipoOrden = tipoOrden;
        this.pagina = pagina;
        this.rutaTotalRegistros = rutaTotalRegistros;
        this.destinobuscador = destinobuscador;
        this.segmento = segmento;
        this.clasesTabla = clasesTabla;
        this.destinoPaginador = destinoPaginador;
        this.boton = boton;
        this.buscador(this.destinobuscador, this.segmento);
        this.tabla(this.ruta, this.destino, this.busqueda, this.campoOrden, this.tipoOrden, this.filas, this.pagina, this.clasesTabla, this.boton, this.rutaApp, this.id);
        this.paginador(this.rutaTotalRegistros, this.filas, this.pagina, this.destinoPaginador, this.busqueda, this.id);
        

    }

    rendertabla(busqueda = "", filas = 20, pagina = 0, campoOrdenar = this.campoOrden, tipoOrden = this.tipoOrden, clases = this.clasesTabla, destinopaginador = this.destinoPaginador, boton = this.boton) {
            this.tabla(this.ruta, this.destino, busqueda, campoOrdenar, tipoOrden, filas, pagina, clases, boton, this.rutaApp, this.id);
            this.paginador(this.rutaTotalRegistros, filas, pagina, destinopaginador, busqueda, this.id);
    } // fin metodo rendertabla


    tabla(ruta, destino, busqueda, orden, tipoOrden, filas, pagina, clases, boton, rutaApp, id) {        
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {

                if (this.readyState == 4 && this.status == 200) {

                    let datos = [];
                    if (this.responseText != "" ) {                                                
                        datos = JSON.parse(this.responseText);                    
                    }    
                    
                    //1- inicio de contrucción del contenido
                    var contenido = "";
                    
                    for (var i = 0; i < datos.length; i++) {

                        var titulos = Object.keys(datos[i]);                    

                        contenido += `
                                <tr class="rows">`;                        

                        for (var j in titulos) {


                                //estilos cuando se manejan estado
                                
                                if (titulos[j] == 'Estado') {
                                    var claseTd = "";

                                    var nombreEstado =  '';
                                    if (datos[i][titulos[j]] == 'pendiente') {
                                        nombreEstado = 'pendiente';
                                        claseTd = 'font-bold text-white bg-red-600 text-center border rounded-lg p-1';
                                    } else if (datos[i][titulos[j]] == 'en curso') {
                                        nombreEstado = 'en curso';
                                        claseTd = 'font-bold text-white bg-yellow-400 text-center border rounded-lg p-1';
                                    } else if (datos[i][titulos[j]] == 'terminada') {
                                        nombreEstado = 'terminada';
                                        claseTd = 'font-bold text-white bg-green-600 text-center border rounded-lg p-1';                                        
                                    }   else if (datos[i][titulos[j]] == 'terminadasinvalorar') {
                                        nombreEstado = 'terminada';
                                        claseTd = 'font-bold text-white bg-green-600 text-center border rounded-lg p-1';                                        
                                    }
                                                                                                        
                                    contenido += `
                                        <td class="px-2 py-2 border-b border-gray-200 bg-white text-sm"><div class="${claseTd}">${nombreEstado}</div></td>`;

                                }else if (titulos[j] == 'Atención') {
                                    var icono = '<i class="fas fa-ban mr-2 fill-current text-black-500 text-2xl"></i>';
                                    var estadoaccion = 'detenido';

                                    if (datos[i][titulos[j]] !='' && datos[i][titulos[j]] >0 ) {
                                        icono = '<i class="fas fa-user-clock mr-2 fill-current text-red-500 text-2xl"></i>';
                                        estadoaccion = 'trabajando';
                                    }

                                    contenido += `
                                        <td title="${estadoaccion}" class="px-2 py-2 border-b border-gray-200 bg-white text-center">${icono}</td>`;

                                
                                }else{

                                    contenido += `
                                    <td class="px-2 py-2 border-b border-gray-200 bg-white text-sm text-center">${datos[i][titulos[j]]}</td>`;
                                }

                                        
                        }

                        if (boton != "") {
                            contenido += `
                                    <td class="botones px-2 py-2 border-b border-gray-200 bg-white text-sm"><div class="flex">`;
                                                                                    
                            //LISTADO DE BOTONES
                            for (let x = 0; x < boton.length; x++) {                               
                                if (boton[x] == 'editar') {
                                    contenido += `<a href="" class="mx-1 editar" title="Editar"><i class="fas fa-edit mr-2 fill-current text-yellow-500 text-2xl"></i></a>`;
                                }
                                if (boton[x] == 'ver') {                                   
                                    contenido += `
                                    <div class="flex-1"><form action="${rutaApp}" method="POST" title="ver">
                                    <input type="number" class="hidden" name="id" value="${datos[i]['Nº']}">
                                    <button type="submit" class="btnActualizar"><i class="fas fa-eye mr-2 fill-current text-yellow-500 text-2xl"></i></button>
                                    </form></div>`;

                                }                       
                                if (boton[x] == 'eliminar') {
                                    contenido += `<a href="" class="mx-1 eliminar" title="Eliminar"><i class="fas fa-trash-alt mr-2 fill-current text-red-600 text-2xl"></i></a>`;
                                }
                                if (boton[x] == 'validar') {
                                    var verValidar = 'none';
                                    if (datos[i]['Estado'] == 'terminadasinvalorar') {
                                        verValidar = 'block';
                                    }
                                    contenido += `<a href="" class="mx-1 validar" title="Validar"><i class="fas fa-thumbs-up mr-2 fill-current text-blue-600 text-2xl" style="display:${verValidar}"></i></a>`;
                                }
                                if (boton[x] == 'terminar') {
                                    var verTerminar = 'block';
                                    if (datos[i]['Estado'] == 'terminada' || datos[i]['Estado'] == 'terminadasinvalorar') {
                                        verTerminar = 'none';
                                    }
                                    contenido += `<a href="" class="mx-1 terminar" title="Terminar"><i class="fas fa-calendar-check mr-2 fill-current text-blue-600 text-2xl" style="display:${verTerminar}"></i></a>`;
                                }  
                                if (boton[x] == 'comentario') {
                                    contenido += `<a href="" class="mx-1 comentario" title="Comentarios"><i class="fas fa-comment-dots mr-2 fill-current text-red-600 text-2xl"></i></a>`;
                                }
                                if (boton[x] == 'modificar') {
                                    contenido += `<a href="" class="mx-1 modificar" title="Modificar"><i class="fas fa-shopping-cart mr-2 fill-current text-red-700 text-2xl"></i></a>`;
                                }
                                if (boton[x] == 'modificarBolsa') {
                                    contenido += `<a href="" class="mx-1 modificarBolsa" title="Modificar"><i class="fas fa-shopping-cart mr-2 fill-current text-red-700 text-2xl"></i></a>`;
                                }
                                if (boton[x] == 'historial') {
                                    contenido += `<a href="" class="mx-1 historial" title="ver historial"><i class="fas fa-history mr-2 fill-current text-gray-600 text-2xl"></i></a>`;
                                }                                                                
                                if (boton[x] == 'verEditar') {
                                    contenido += `
                                    <div class="flex-1"><form action="${rutaApp}" method="POST" title="editar">
                                    <input type="number" class="hidden" name="id" value="${datos[i]['Nº']}">
                                    <button type="submit" class="btnActualizar"><i class="fas fa-user-edit mr-2 fill-current text-yellow-500 text-2xl"></i></button>
                                    </form></div>`;
                                }
                                         
                                if (boton[x] == 'estadoatencion') {      
                                    
                                    var verPlayStop = 'block';
                                    if (datos[i]['Estado'] == 'terminada' || datos[i]['Estado'] == 'terminadasinvalorar') {
                                        verPlayStop = 'none';
                                    }

                                    var iconAtencion = 'far fa-play-circle text-red-600';
                                    var accion = 'iniciar';
                                    if (datos[i]['Atención'] !='' && datos[i]['Atención'] > 0) {
                                        iconAtencion = 'far fa-stop-circle text-green-600'; 
                                        accion = 'detener';
                                    }
                                    contenido += `<a href="" class="mx-1 ${accion}" title="${accion} atención" data-atencion="${datos[i]['Atención']}" style="display:${verPlayStop}"><i class="${iconAtencion} mr-2 fill-current text-2xl"></i></a>`;
                                }
                                if (boton[x] == 'reasignar') {
                                    contenido += `<a href="" class="mx-1 reasignar" title="reasignar técnico"><i class="fas fa-random mr-2 fill-current texto-violeta-oscuro text-2xl"></i></a>`;
                                }                
                              
                            }

                            contenido += `</div></td>`;
                        }

                        contenido += `</tr>`;
                    }
                    //fin de contrucción del contenido

                    
                    if (!busqueda) {
                        if (datos[0]) {
                            
                            
                            //2- incio de cabecera titulos
                            var titles = Object.keys(datos[0]) ;
                                            
                            var cabecera = "";
                            //titles.forEach(element => 
                            titles.forEach(function(element){ 


                                var nombreTitulo = element;
                                if (element == 'horascons') {
                                    nombreTitulo = 'HR. CONSUM.';
                                }
                                  
                                cabecera += `<th class="titulos px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs md:text-sm font-semibold texto-violeta-oscuro uppercase tracking-wider text-center">${nombreTitulo}</th>`;
                            });
                        
                            if (boton != "") {
                                cabecera += `<th class="text-center px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold texto-violeta-oscuro uppercase tracking-wider">Acciones</th>`;
                            }
                            
                            //fin de cabecera2 de inputs

                            document.getElementById(destino).innerHTML = `
                                    <table class="${clases}" id="tabla1">
                                        <thead>                                       
                                        <tr>${cabecera}</tr></thead>
                                        <tbody id="tabla1tbody">
                                            ${contenido}
                                        </tbody>
                                    </table>
                                    `;
                        }

                    }else{

                        let tab = document.getElementById("tabla1tbody");
                        tab.innerHTML = "";

                        if (datos.length >0) {                                
                            tab.innerHTML = `${contenido}`;
                        }                       
                        
                    }                   
                }

            };
            xhr.open("POST", ruta, true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send(
                `busqueda=${busqueda}&orden=${orden}&tipoOrden=${tipoOrden}&filas=${filas}&pagina=${pagina}&id=${id}`
            );
    } // fin metodo tabla

    buscador(destinobuscador, segmento) {

            let select = "";
            for (let i = 0; i < segmento.length; i++) {
                select += `<option  value=${segmento[i]}>${segmento[i]}</option>`;
            };
            document.getElementById(destinobuscador).innerHTML = `
        
        <p class="appearance-none h-full block appearance-none w-full text-gray-700 p-1 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">Registros</p>
        <select class="appearance-none h-full rounded-l rounded-r border block appearance-none w-full bg-white border-gray-400 text-gray-700 py-1 px-4 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="registros" id="registros">
        ${select}
        </select></p>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
            </svg>
        </div>
        `;
    } // fin metodo buscador

    paginador(rutaTotalRegistros, fila, pagina, destinoPaginador, busqueda, id) {
        let paginator = "";
        let total = 0;
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {


            if (this.readyState == 4 && this.status == 200) {
                total = parseInt(this.responseText);

              
                // Inicio eb componente

                paginator += `<div class="text-center custom-number-input w-full justify-center">
                <label for="custom-input-number" class="w-35 text-gray-700 text-sm font-semibold ">Página ${parseInt(pagina) + 1} de ${Math.ceil(total / fila)}
                </label>
                <div class="flex flex-row h-auto w-35 rounded-lg relative bg-transparent mt-1 justify-center">`;

                if (pagina > 0) {
                    paginator += `
                <button data-action="decrement" data-elementopaginador="0" class=" bg-gray-300 text-gray-600 hover:text-gray-700 hover:bg-gray-400 h-full w-10 rounded-l cursor-pointer outline-none">
                    <span class="m-auto text-sm lg:text-base font-thin" style="pointer-events: none;"><i  class="fas fa-angle-double-left"></i></span>
                  </button>
                  <button data-action="decrement" data-elementopaginador="${parseInt(pagina) - 1}" class=" bg-gray-300 text-gray-600 hover:text-gray-700 hover:bg-gray-400 h-full w-10  cursor-pointer outline-none">
                    <span class="m-auto text-sm lg:text-base font-thin" style="pointer-events: none;"><i  class="fas fa-angle-left"></i></span>
                  </button>`;
                }
                paginator += `<input type="number" class="outline-none focus:outline-none text-center w-30 bg-gray-300 font-semibold text-md hover:text-black focus:text-black  md:text-basecursor-default flex items-center text-gray-700  outline-none inputPaginador" name="custom-input-number" value="${parseInt(pagina) + 1}" readonly></input>`;
                if (pagina < (Math.ceil(total / fila) - 1)) {
                    paginator += `<button data-action="increment" data-elementopaginador="${parseInt(pagina) + 1}" class="bg-gray-300 text-gray-600 hover:text-gray-700 hover:bg-gray-400 h-full w-10  cursor-pointer">
                  <span class="m-auto text-sm lg:text-base font-thin" style="pointer-events: none;"><i  class="fas fa-angle-right"></i></span>
                </button>
                <button data-action="increment" data-elementopaginador="${Math.ceil(total / fila) - 1}" class="bg-gray-300 text-gray-600 hover:text-gray-700 hover:bg-gray-400 h-full w-10 rounded-r cursor-pointer">
                  <span class="m-auto text-sm lg:text-base font-thin" style="pointer-events: none;"><i class="fas fa-angle-double-right"></i></span>
                </button>
              `;
                }
                paginator += `
                </div>
                <label for="custom-input-number" class="w-35 text-gray-700 text-sm font-semibold ">Total Registros:&nbsp; ${total}</label>
                `;

                // fin web componente
                document.getElementById(`${destinoPaginador}`).innerHTML = `${paginator}`;
            }
        };
        xhr.open("POST", rutaTotalRegistros, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(
            `busqueda=${busqueda}&id=${id}`
        );

}


} // fin de la clase creartabla

/*
TAREAS PENDIENTES:
1.- Añadir el nombre de la tabla a consultar
2.- Añadir los campos de la tabla a consultar, y los nombres de los titulos de la tabla correspondientes
3.- Incluir los iconos de asc y desc en los titulos de la tabla
*/
export default function arrancar(objeto, ruta, destino, orden, tipoOrden, pagina, destinobuscador, rutaTotalRegistros, segmento, clasesTabla, destinoPaginador, boton, rutaApp, id) {
    /*
    PARAMETROS DE LA FUNCION:
    objeto: es el nombre del objeto que instancia al llamar la función, no debe repetirse en la misma pagina html para evitar problemas de identifiocacion del objeto
    ruta: ruta del controlador y metodo que ejecuta la consulta de los datos de la tabla en infraestructura mvc
    destino: id del div destino donde se va a crear el contenido de la tabla
    orden: campo de la tabla de la bbdd por el que se va a ordenar por defecto la primera vista del contenido de la tabla
    tipoOrden: existen dos posibilidades ASC y DESC para colocar que tipo de orden que se enviara a la consulta de la base de datos
    pagina: pagina inicial del contenido de la tabla, por defecto se visualiza la primera pagina, pagina 0
    destinobuscador: id del div destino donde se va a crear el buscador y el select con el numero de registros a visualizar por pagina
    rutaTotalRegistros: ruta del controlador y metodo de la consulta de todos los registros de la tabla que se esta consultando
    segmento: array con los campos que se van a visualizar en el select que maneja los registros por pagina
    clasesTabla: Clases bootstrap para la creación de la tabla
    destinoPaginador: id del div destino para la parte inferior con el paginador y el total registros y paginas
    */
    var objeto = new creartabla(ruta, destino, orden, tipoOrden, pagina, destinobuscador, rutaTotalRegistros, segmento, clasesTabla, destinoPaginador, boton, rutaApp, id);

   
    let prueba1 = document.getElementById(destino);    
    
    prueba1.addEventListener("keyup", function(e) {

        var inputs = document.getElementsByClassName("inputKeyup");

        let algo = {};
        Array.prototype.forEach.call(inputs, function(element, index) {
        
            if (element.value !='') {
    
              let obj = element.value;
              let obj2 = element.getAttribute('data-nombre');
    
              algo[obj2] = obj;
    
            }
            
        });
       
        let output = JSON.stringify(algo);

        objeto.busqueda = output;

        objeto.rendertabla(objeto.busqueda);

    })

    let selectFilas = document.getElementById('registros');
    selectFilas.addEventListener("change", function() {
        objeto.filas = selectFilas.value
            //console.log(selectFilas.value);
        objeto.rendertabla(objeto.busqueda, objeto.filas);
    }); // fin del addEventListener para select registros pagina


    let paginadores = document.getElementById(objeto.destinoPaginador);
    paginadores.addEventListener("click", function(e) {
        //  console.log(e.target.dataset.elementopaginador);
        objeto.pagina = e.target.dataset.elementopaginador;
        objeto.rendertabla(objeto.busqueda, objeto.filas, objeto.pagina)

    }); // fin del addEventListener para paginar


    let ordenador = document.getElementById(objeto.destino);
    ordenador.addEventListener("click", function(e) {

        let ord = "";

        if (e.target.className == "titulos") {
            //  console.log(e.target.innerHTML);
            objeto.contador += 1;
            objeto.tipoOrden = ((objeto.contador % 2) == 0) ? "ASC" : "DESC";
            // console.log(objeto.tipoOrden);
            objeto.campoOrden = e.target.innerHTML;
            objeto.rendertabla(objeto.busqueda, objeto.filas, objeto.pagina, objeto.campoOrden, objeto.tipoOrden)
        }
    }); // fin del addEventListener para ordenar

}; // fin de la funcion arrancar