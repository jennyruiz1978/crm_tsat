 if(window.location.pathname.includes('/Login')){

    var urlInicio = $('#rutaIni').val();
    
    
    var respuestafuncion = '';

    function validar(email,password) {              
        $.ajax({
            type: "POST",          
            data: {'email':email,'password':password},
            async: false, //necesario                
            url: urlInicio + "/Login/validarCorreoYPasword",            
            dataType: "json",
            success: function (res) {
                respuestafuncion = res;
            }
        });
        return respuestafuncion;
    }

    //submit login
    $("#formLogin").submit(function (event) {
                
        email = $('#email').val();   
        password = $('#password').val();

        if (email !='' && password != '') {                        
            validar(email,password);
            var respuesta = respuestafuncion;

            if ( respuesta['respuesta'] == 0 ){              
                $("#mensajeLogin").text("No tiene permiso para acceder.").show().fadeOut(3000);                  
                event.preventDefault();     
            }else if ( respuesta['respuesta'] == 2 ){
                $("#mensajeLogin").text('El correo ingresado no es válido').show().fadeOut(3000);                  
                event.preventDefault();     
            }else if ( respuesta['respuesta'] == 3 ){
                $("#mensajeLogin").text('La contraseña ingresada no es válida').show().fadeOut(3000);                  
                event.preventDefault();     
            }
        }else{
            $("#mensajeLogin").text('Debe completar correo y contraseña').show().fadeOut(3000);            
            event.preventDefault();     
        }
                
    });    

    $(document).on('keydown', '.inputLogin', function (evento) {
            
        if (evento.key == "Enter") {            
            // Prevenir
            evento.preventDefault();
            return false;
        }
    });

    




}


