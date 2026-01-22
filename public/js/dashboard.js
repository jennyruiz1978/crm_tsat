if(window.location.pathname.includes('/Inicio')){ 


    urlCompleta = $('#ruta').val();

    //cargo los gráficos al inicio tab-generales
    graficoDonaModalidades();
    graficoIncidenciasPorEstado();
    graficoHorasSegunModalidadContratada();
    
    //cargo gráficos y datos cuando se busca por fecha
    $('#btnBuscarDashboard').on('click', function (e) {
      e.preventDefault();
      fechaIni = $('#fechaIni').val();
      fechaFin = $('#fechaFin').val();

      //detecto la pastilla activa      
      var pastillaActiva = $('.pastillDashboard.block');
      idPastilla = pastillaActiva.attr('id');

      if (idPastilla == 'tab-generales') {

        if (fechaIni != '' && fechaFin  !='') {

          graficoDonaModalidades(fechaIni,fechaFin);
          graficoIncidenciasPorEstado(fechaIni,fechaFin);
          graficoHorasSegunModalidadContratada(fechaIni,fechaFin);

          $.ajax({
            type: 'POST',
            url: urlCompleta + '/Inicio/recargarPastillasDashboardGeneral',
            dataType: "json",
              data: {'fechaIni':fechaIni, 'fechaFin': fechaFin}
          }).done(function(resp){                    
            console.log(resp.incidenciasTotales);                                                            
            $('#incidenciasTotales').html(resp.incidenciasTotales)
            $('#horasRealizadas').html(resp.tiemposRealizados)
            $('#incidenciasPendientes').html(resp.incidenciasPendientes)
            $('#clientesAtendidos').html(resp.clientesAtendidos)
            $('#equiposAtendidos').html(resp.equiposAtendidos)
            
          }); 
        }

      }

    });

    //cargo gráficos y datos cuando se busca por año
    $('#anioRentabilidad').on('change', function (e) {
      e.preventDefault();
      anio = $(this).val();
      
      datosGraficosApartadoRentabilidad(anio);
      construirDatosTablasApartadoRendimientos(anio);      

    });    
    $('#anioClientes').on('change', function (e) {
      e.preventDefault();
      anio = $(this).val();                
      datosGraficosApartadoClientes(anio);
      construirDatosTablasApartadoClientes(anio);

    });
    $('#anioTecnicos').on('change', function (e) {
      e.preventDefault();
      anio = $(this).val();                
      datosGraficosApartadoTecnicos(anio);
    });    
    $('#anioEquipos').on('change', function (e) {
      e.preventDefault();
      anio = $(this).val();                
      datosGraficosApartadoEquipos(anio);
    });

    //cargo gráficos y tablas según se haga click en cada tab-pane
    $(document).on('click', '.tab-dashboard', function (e) {              
      e.preventDefault();

      tab = $(this).data('tab');        
      metodo = $(this).data('metodo');
          
      activadorTabActivoDashboard(e, tab);  

      //detecto la pastilla activa      
      var pastillaActiva = $('.pastillDashboard.block');
     
      idPastilla = pastillaActiva.attr('id');
      
      if (idPastilla == 'tab-generales') {

      }else if(idPastilla == 'tab-clientes'){

        let anioActual = new Date().getFullYear();
        $('#anioClientes').val(anioActual);
        datosGraficosApartadoClientes();
        construirDatosTablasApartadoClientes();

      }else if(idPastilla == 'tab-tecnicos'){
        
        datosGraficosApartadoTecnicos();

      }else if(idPastilla == 'tab-equipos'){

        datosGraficosApartadoEquipos();

      }else if(idPastilla == 'tab-rentabilidad'){
        
        datosGraficosApartadoRentabilidad();
        construirDatosTablasApartadoRendimientos();                
        
      }

    });

    
    function datosGraficosApartadoRentabilidad(anio='') {
      $.ajax({
        type: 'POST',
        url: urlCompleta + '/Inicio/datosGraficosApartadoRentabilidad',
        dataType: "json",
          data: {'anio':anio}
      }).done(function(resp){    

        //pinto todos los gráficos

        var rent1 = c3.generate({
          bindto: '#rendimientoClientesBolsa',
          data: {      
            json: resp[0],  
            x: 'meses',             
            type : 'bar',
            colors: {
              'Rend.(%)': '#be185d',
              'H. Realiz.': '#d069ac',
              'H. Contrat.': '#632b65'
          },        
          },
          axis: {
            x: {
                  type: 'timeseries',
                  tick: {
                    format: '%b%'
                  }
            },            
          }           
        });

        var rent2 = c3.generate({
          bindto: '#rendimientoClientesPrecioFijo',
          data: {      
            json: resp[1],  
            x: 'meses',             
            type : 'bar',
            colors: {
              'Rend.(%)': '#be185d',
              'Coste(€)': '#d069ac',
              'Contrat.(€)': '#632b65'
          },        
          },
          axis: {
            x: {
                  type: 'timeseries',
                  tick: {
                    format: '%b%'
                  }
            },            
          }           
        });

        var rent3 = c3.generate({
          bindto: '#ingresosNetosMensuales',
          data: {      
            json: resp[2],  
            x: 'meses',             
            type: 'spline',
            colors: {
              'Rentab.(€)': '#be185d',
              'coste(€)': '#d069ac',
              'Contrat.(€)': '#632b65'
            },        
          },
          axis: {
            x: {
                  type: 'timeseries',
                  tick: {
                    format: '%b%'
                  }
            },            
          }           
        });


      }); 
    }    

    function graficoDonaModalidades(fecha1='',fecha2='') {

      if (fecha1=='' && fecha2 =='') {
        var chart = c3.generate({
          bindto: '#donaModalidades',
          data: {                         
            url:  urlCompleta + '/Inicio/consolidadoAtencionesPorModalidadRango',    
            mimeType: 'json',       
              type : 'donut',              
          },
          color: {
            pattern: ['#632b65','#673ab7', '#be185d', '#d069ac', '#cbb9c6' ]
          },    
        });
      }else{
        $.ajax({
          type: 'POST',
          url: urlCompleta + '/Inicio/consolidadoAtencionesPorModalidadRango',
          dataType: "json",
            data: {'fechaIni':fechaIni, 'fechaFin': fechaFin}
        }).done(function(resp){                    
          var chart = c3.generate({
            bindto: '#donaModalidades',
            data: {      
              json: resp,              
                type : 'donut'               
            },
            color: {
              pattern: ['#632b65','#673ab7', '#be185d', '#d069ac', '#cbb9c6' ]
            },
          });
        }); 
      }

    }

    function graficoIncidenciasPorEstado(fecha1='',fecha2='') {
      
      if (fecha1=='' && fecha2 =='') {
        var chart = c3.generate({
          bindto: '#pieIncidenciasEstado',
          data: {    
            url:  urlCompleta + '/Inicio/consolidadoIncidenciasPorEstadoRango',    
            mimeType: 'json',    
              type : 'pie',
              colors: {
                'pendiente': '#be185d',
                'en curso': '#d069ac',
                'terminada': '#632b65'                
              },
          }     
        });
    
      }else{
        $.ajax({
          type: 'POST',
          url: urlCompleta + '/Inicio/consolidadoIncidenciasPorEstadoRango',
          dataType: "json",
            data: {'fechaIni':fechaIni, 'fechaFin': fechaFin}
        }).done(function(resp){                    
          var chart = c3.generate({
            bindto: '#pieIncidenciasEstado',
            data: {      
              json: resp,              
                type : 'donut',
                colors: {
                  'pendiente': '#be185d',
                  'en curso': '#d069ac',
                  'terminada': '#632b65'                
                },
            }            
          });
        }); 
      }

    }

   
    function graficoHorasSegunModalidadContratada(fecha1='',fecha2='') {

      if (fecha1=='' && fecha2 =='') {
        var gen3 = c3.generate({
          bindto: '#horasSegunModalidadConstratada',
          data: {                         
            url:  urlCompleta + '/Inicio/horasSegunModalidadadContratada',    
            mimeType: 'json',       
              type : 'donut',
              colors: {
                'Bolsa horas': '#673ab7',              
                'Mantenimiento': '#632b65'
              },
          }    
        });
      }else{
        $.ajax({
          type: 'POST',
          url: urlCompleta + '/Inicio/horasSegunModalidadadContratada',
          dataType: "json",
            data: {'fechaIni':fechaIni, 'fechaFin': fechaFin}
        }).done(function(resp){                    
          var chart = c3.generate({
            bindto: '#horasSegunModalidadConstratada',
            data: {      
              json: resp,              
                type : 'donut',                
                colors: {
                  'Bolsa horas': '#673ab7',                 
                  'Mantenimiento': '#632b65'
                },
            }
          });
        }); 
      }

    }


    function activadorTabActivoDashboard(event,tabID){
      let element = event.target;
      while(element.nodeName !== "A"){
      element = element.parentNode;
      }
      ulElement = element.parentNode.parentNode;
      aElements = ulElement.querySelectorAll("li > a");
      tabContents = document.getElementById("tabs-dashboard").querySelectorAll(".tab-content > div");
      for(let i = 0 ; i < aElements.length; i++){
        aElements[i].classList.remove("text-white");
        aElements[i].classList.remove("bg-violeta-oscuro");
        aElements[i].classList.add("texto-violeta-oscuro");
        aElements[i].classList.add("bg-white");
        tabContents[i].classList.add("hidden");
        tabContents[i].classList.remove("block");
      }
      element.classList.remove("texto-violeta-oscuro");
      element.classList.remove("bg-white");
      element.classList.add("text-white");
      element.classList.add("bg-violeta-oscuro");
      document.getElementById(tabID).classList.remove("hidden");
      document.getElementById(tabID).classList.add("block");
    }

    function construirDatosTablasApartadoRendimientos(anio='') {
      $.ajax({
        type: 'POST',
        url: urlCompleta + '/Inicio/construirDatosTablasApartadoRendimientos',
        dataType: "json",
          data: {'anio':anio}
      }).done(function(resp){    
        $('#tablaIngresosClientesConBolsa tbody').html('');
        $('#tablaIngresosClientesPrecioFijo tbody').html('');
        //pinto la tabla        
        if (resp) {          
          $('#tablaIngresosClientesConBolsa tbody').html(resp['clientesbolsa']);
          $('#tablaIngresosClientesPrecioFijo tbody').html(resp['clientesfijo']);
        }

      });
    }

        
    function datosGraficosApartadoClientes(anio='') {
      $.ajax({
        type: 'POST',
        url: urlCompleta + '/Inicio/datosGraficosApartadoClientes',
        dataType: "json",
          data: {'anio':anio}
      }).done(function(resp){    
        
        var cli1 = c3.generate({
          bindto: '#historaialNumeroIncidencias',
          data: {  
            json: resp[0],  
            x: 'meses', 
            //poniento todos los meses se distorsiona la imagen en dsipositivo movil
            /*
            columns: [
               resp[0].incidencias,
               resp[0].clientes,
            ],   */         
            type : 'spline',
            colors: {
              'Incidenc.': '#be185d',
              'Clientes': '#632b65'        
            },        
          },
          axis: {
            x: {
                  type: 'timeseries',
                  tick: {
                    format: '%b%'
                  }
            },            
          } 
          /*axis: {
            x: {
                type: 'category',
                categories: ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic']
            }
          }     */ 
        });

        var cli2 = c3.generate({
          bindto: '#historialHorasClientesBolsaHoras',
          data: {                
            json: resp[1],  
            x: 'meses',           
            type : 'bar',
            colors: {
              'Hrs. Cons.': '#632b65',              
            },
          },
          axis: {
            x: {
                  type: 'timeseries',
                  tick: {
                    format: '%b%'
                  }
            },            
          }         
        });       

        var cli2 = c3.generate({
          bindto: '#historialHorasEquiposMantenimiento',
          data: {      
            json: resp[2],  
            x: 'meses',              
            type : 'bar',
            colors: {
              'Hrs. Cons.': '#be185d',              
            },        
          },
          axis: {
            x: {
                  type: 'timeseries',
                  tick: {
                    format: '%b%'
                  }
            },            
          }    
        });     

                
        $('#contenedorTablaHrasClientes').html('');        
        if (resp[3]) {          
          $('#contenedorTablaHrasClientes').html(resp[3]);
        }

      }); 
    }    

    function construirDatosTablasApartadoClientes(anio='') {
      $.ajax({
        type: 'POST',
        url: urlCompleta + '/Inicio/construirDatosTablasApartadoClientes',
        dataType: "json",
          data: {'anio':anio}
      }).done(function(resp){    
        $('#tablaHorasConsumidasClientesAmbasModalidadesContrato tbody').html('');
        
        //pinto la tabla        
        if (resp) {          
          $('#tablaHorasConsumidasClientesAmbasModalidadesContrato tbody').html(resp['horas']);
          
        }

      });
    }

    
    function datosGraficosApartadoTecnicos(anio='') {
      $.ajax({
        type: 'POST',
        url: urlCompleta + '/Inicio/datosGraficosApartadoTecnicos',
        dataType: "json",
          data: {'anio':anio}
      }).done(function(resp){    

        //pinto todos los gráficos
      
        var tec1 = c3.generate({
          bindto: '#numeroIncidenciasPorTecnico',
          data: {      
            json: resp[0],  
            x: 'meses',             
            type : 'spline'            
          },
          color: {
            pattern: ['#632b65','#673ab7', '#be185d', '#d069ac' ]
          },
          axis: {
            x: {
                  type: 'timeseries',
                  tick: {
                    format: '%b%'
                  }
            },            
          }           
        });

        
        var tec2 = c3.generate({
          bindto: '#numeroHorasPorTecnico',
          data: {      
            json: resp[1],  
            x: 'meses'            
          },
          color: {
            pattern: ['#632b65','#673ab7', '#be185d', '#d069ac' ]
          },
          axis: {
            x: {
                  type: 'timeseries',
                  tick: {
                    format: '%b%'
                  }
            },            
          }           
        });

        var tec3 = c3.generate({
          bindto: '#costeHorasRealizadasPorTecnico',
          data: {      
            json: resp[2],  
            x: 'meses',             
            type : 'bar',           
          },
          color: {
            pattern: ['#632b65','#673ab7', '#be185d', '#d069ac' ]
          },
          axis: {
            x: {
                  type: 'timeseries',
                  tick: {
                    format: '%b%'
                  }
            },            
          }           
        });        

      });
 
    }    
        
    function datosGraficosApartadoEquipos(anio='') {
      $.ajax({
        type: 'POST',
        url: urlCompleta + '/Inicio/datosGraficosApartadoEquipos',
        dataType: "json",
          data: {'anio':anio}
      }).done(function(resp){    

        //pinto todos los gráficos
      
        var eq1 = c3.generate({
          bindto: '#numeroEquiposAtendidos',
          data: {      
            json: resp[0],  
            x: 'meses',             
            type : 'spline'            
          },
          color: {
            pattern: ['#632b65','#673ab7', '#be185d', '#d069ac' ]
          },
          axis: {
            x: {
                  type: 'timeseries',
                  tick: {
                    format: '%b%'
                  }
            },            
          }           
        });

        
        var eq2 = c3.generate({
          bindto: '#numeroEquiposAtendidosPorModalContratada',
          data: {      
            json: resp[1],  
            x: 'meses',
            type : 'bar'            
          },
          color: {
            pattern: ['#632b65','#673ab7', '#be185d', '#d069ac' ]
          },
          axis: {
            x: {
                  type: 'timeseries',
                  tick: {
                    format: '%b%'
                  }
            },            
          }           
        });

        
        $('#contenedorTablaYScript').html('');        
        if (resp[2]) {          
          $('#contenedorTablaYScript').html(resp[2]);
        }
        
              

      });
 
    }   
}