class formLoginVerIncidencia extends HTMLElement {

    constructor() {
        super();
        this.nombre = this.getAttribute("nombre");
        this.rutabase = this.getAttribute("root");           
        this.imagenlogo = this.getAttribute("imglogo");
        this.slogan = this.getAttribute("slogan");        
        this.idusuario = this.getAttribute("idusuario");
        this.idincidencia = this.getAttribute("idincidencia");
        this.email = this.getAttribute("email");
        

    }

    
    verPassword(){

        const passwordToggle = document.querySelector('.js-password-toggle')

        passwordToggle.addEventListener('change', function() {
            
            const password = document.querySelector('.js-password'),
            passwordLabel = document.querySelector('.js-password-label')
    
            if (password.type === 'password') {
                password.type = 'text'
                passwordLabel.innerHTML = "<i class='far fa-eye-slash texto-violeta-oscuro text-base xl:text-xl js-password-label'></i>";
            } else {
                password.type = 'password'
                passwordLabel.innerHTML = "<i class='far fa-eye texto-violeta-oscuro text-base xl:text-xl js-password-label'></i>";
            }
    
            password.focus()
        })        

    }

    connectedCallback() {
        this.innerHTML = `<div class="flex items-center min-h-screen p-4 bg-gray-100 lg:justify-center">
                            <div class="flex flex-col overflow-hidden bg-white rounded-md shadow-lg max md:flex-row md:flex-1 lg:max-w-screen-md">
                                <div class="p-4 py-6 text-white bg-gray-50 md:w-80 md:flex-shrink-0 md:flex md:flex-col md:items-center md:justify-evenly">
                                    <div class="my-3 text-4xl font-bold tracking-wider text-center">
                                        <img src="${ this.imagenlogo}">
                                    </div>
                                    <p class="mt-6 font-normal text-center text-gray-600 md:mt-0">
                                        ${this.slogan}
                                    </p>
                                </div>
                                <div class="p-5 bg-white md:flex-1">
                                    
                                <h3 class="my-2 text-2xl font-semibold text-gray-700">Login</h3>

                                    <form class="flex flex-col space-y-5" id="formLogin" method="POST" action="${this.rutabase}/Login/acceder">         

                                        <input type="hidden" id="ruta" value="${this.rutabase}">                        
                                        <input name="user" id="user" type="hidden" value= "${this.idusuario}" />
                                        <input name="idIncidencia" id="idIncidencia" type="hidden" value= "${this.idincidencia}" />      
                                        <span id="mensajeLogin" class="font-bold font-bold text-pink-600"></span>                        
                                        <div class="flex flex-col space-y-1">
                                            <div class="flex items-center justify-between">
                                                <label for="password" class="text-sm font-semibold text-gray-500">Correo electronico</label>          
                                            </div> 
                                            <input
                                            type="email"
                                            id="email"
                                            name="mail"
                                            class="inputLogin px-4 py-2 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-pink-700" value= "${this.email}"
                                            />
                                        </div>
                                        
                                        <div class="flex flex-col space-y-1">
                                            <div class="flex items-center justify-between">
                                                <label for="repite" class="text-sm font-semibold text-gray-500">Contrase&ntilde;a</label>              
                                            </div> 

                                            <div class="relative w-full">

                                                <div class="absolute inset-y-0 right-0 flex items-center px-2">
                                                <input class="hidden js-password-toggle" id="toggle" type="checkbox" />
                                                <label class="bg-gray-300 hover:bg-gray-400 rounded px-2 py-1 text-sm text-gray-600 font-mono cursor-pointer js-password-label" for="toggle"><i class='far fa-eye texto-violeta-oscuro text-base xl:text-xl'></i></label>
                            
                                                </div>
                                            
                                                <input 
                                                        type="password"
                                                        id="password"
                                                        name="pass"
                                                        autofocus
                                                        class="appearance-none w-full js-password inputLogin px-4 py-2 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-pink-700" autocomplete="off" />
                                            
                                            </div>

                                        </div>
                                    
                                        <span id="mensajeValidacion" class="font-bold font-bold text-pink-600"></span> 
                                        <div id="contenedorBoton">                     
                                            <button type="submit" class="w-full px-4 py-2 text-lg font-semibold text-white transition-colors duration-300 bg-violeta-oscuro hover:bg-pink-900 rounded-md shadow focus:outline-none focus:ring-blue-200 focus:ring-4">${this.nombre}</button>                     
                                        </div>                    
                                    </form>
                                </div>
                            </div>
                        </div>`;         
                        
        this.verPassword();
    }    

}

window.customElements.define("dlm-loginverincidencia", formLoginVerIncidencia);