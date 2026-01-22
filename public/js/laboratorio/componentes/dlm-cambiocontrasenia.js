class botonCambiarContrasenia extends HTMLElement {

    constructor() {
        super();
        this.nombre = this.getAttribute("nombre");
    }

    accion() {
        const cambiar = document.getElementById("cambiarPassword");
        
        cambiar.addEventListener("click", (e) => {
            e.preventDefault();
            let id = document.getElementById('user').value;
            let password = document.getElementById('password').value;
            let repite = document.getElementById('repite').value;

            if (id != "" && password != "" && repite != "") {
                if (password == repite) {

                    let ruta = document.getElementById('ruta').value + "/Login/ejecutarCambioContrasenia";
                    console.log(ruta);

                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                                                    
                            let update = this.responseText;                                  

                            if (update == 1) {
                                //window.open(urlCompleta + '/Login/Acceder/13/' + idcab);                  
                                document.getElementById("formCambioPassword").submit();
                            }else{
                                document.getElementById("mensajeValidacion").innerHTML = `No se ha podido actualizar la contraseña. Verique los datos ingresados.`;    
                            }   

                        }
                    };
                    xhr.open("POST", ruta, true);
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.send(
                        `id=${id}&password=${password}&repite=${repite}`                        
                    );

                }else{
                    document.getElementById("mensajeValidacion").innerHTML = "Las contraseñas no son iguales.";
                }
            }else{                                
                document.getElementById("mensajeValidacion").innerHTML = "No has ingresado una contraseña.";

            }

        })
    }

    connectedCallback() {
        this.innerHTML = `<button id="cambiarPassword" class="w-full px-4 py-2 text-lg font-semibold text-white transition-colors duration-300 bg-pink-600 rounded-md shadow hover:bg-pink-700 focus:outline-none focus:ring-blue-200 focus:ring-4">${this.nombre}</button>`;
        this.accion();
    }    

}

window.customElements.define("dlm-cambiocontrasenia", botonCambiarContrasenia);