window.addEventListener('DOMContentLoaded', () => {
   
    var divs = document.getElementsByClassName("menu-btn");
   
    for (var i = 0; i < divs.length; i++) {
        
            //Añades un evento a cada elemento
            divs[i].addEventListener("click", function () {
                
                if(this.nextElementSibling.classList.contains('hidden')){
                    // cambiamos el icono a down y cambianmos las clases del submenu para que se muestre
                    this.childNodes[3].firstChild.classList.value = "svg-inline--fa fa-angle-down fa-w-8 ml-3";
                    this.nextElementSibling.classList.remove('hidden');
                    this.nextElementSibling.classList.add('flex');
                } else {
                    // cambiamos el icono a right y cambiamos las clases del submenu para que se oculte
                    this.childNodes[3].firstChild.classList.value = "svg-inline--fa fa-angle-right fa-w-8 ml-3";
                    this.nextElementSibling.classList.remove('flex');
                    this.nextElementSibling.classList.add('hidden');
                }

            }); // fin del addEventListener
       
    }; // fin del for i
    
}) // fin del DomContentLoaded