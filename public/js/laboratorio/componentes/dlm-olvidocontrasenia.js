class botonCambiarContrasenia extends HTMLElement {

    constructor() {
        super();
        this.nombre = this.getAttribute("nombre");

    }

    accion() {
        const cambiar = document.getElementById("recuperarPassword");
        
        cambiar.addEventListener("click", (e) => {
            e.preventDefault();
            let email = document.getElementById('email').value;

            if (email !='') {
                                
                let ruta = document.getElementById('ruta').value + "/Login/validaEmailParaRecuperarContrasenia";
                console.log(ruta);

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                                                
                        let valida = this.responseText;                                  

                        if (valida ==1) {                  
                            document.getElementById("mensajeValidacion").innerHTML = `Se ha enviado un email con la contraseña.`;    
                            document.getElementById('email').value = '';
                        }else{
                            document.getElementById("mensajeValidacion").innerHTML = `La cuenta de correo ingresada no es válida! `;    
                        }   

                    }
                };
                xhr.open("POST", ruta, true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.send(
                    `email=${email}`
                );
                                     
            }else{                                
                document.getElementById("mensajeValidacion").innerHTML = "No has ingresado una  cuenta de correo.!";

            }

        })
    }

    connectedCallback() {
        this.innerHTML = `<button id="recuperarPassword" class="w-full px-4 py-2 text-lg font-semibold text-white transition-colors duration-300 bg-pink-600 rounded-md shadow hover:bg-pink-700 focus:outline-none focus:ring-blue-200 focus:ring-4">${this.nombre}</button>`;
        this.accion();
    }    

}

window.customElements.define("dlm-olvidocontrasenia", botonCambiarContrasenia);