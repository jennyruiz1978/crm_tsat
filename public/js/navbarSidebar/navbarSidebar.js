    // Inicio del script de la pagina


    // Buttons functions
    let profileBtn = document.querySelector('#profileBtn');
    
    

    if (document.querySelector('#notificationsBtn')) {
      let notificationsBtn = document.querySelector('#notificationsBtn');
      notificationsBtn.addEventListener("click", function(){
        let notificationsDiv = document.querySelector('#notificationsDiv');
        showHide(notificationsDiv);
      }) 
    }

    if (document.querySelector('#comentariosBtn')) {
      let comentariosBtn = document.querySelector('#comentariosBtn');
      comentariosBtn.addEventListener("click", function(){
        let comentariosDiv = document.querySelector('#comentariosDiv');
        showHide(comentariosDiv);
      })  
    }
    

    
    if (document.querySelector('#verFacturarBtn')) {
      let verFacturarBtn = document.querySelector('#verFacturarBtn');
      verFacturarBtn.addEventListener("click", function(){
        let verFacturarDiv = document.querySelector('#verFacturarDiv');
        showHide(verFacturarDiv);
      }) 
    }
    if (document.querySelector('#verPresupuestarBtn')) {
      let verPresupuestarBtn = document.querySelector('#verPresupuestarBtn');
      verPresupuestarBtn.addEventListener("click", function(){
        let verPresupuestarDiv = document.querySelector('#verPresupuestarDiv');
        showHide(verPresupuestarDiv);
      }) 
    }   
    
    if (document.querySelector('#verAceptadosBtn')) {
      let verAceptadosBtn = document.querySelector('#verAceptadosBtn');
      verAceptadosBtn.addEventListener("click", function(){
        let verAceptadosDiv = document.querySelector('#verAceptadosDiv');
        showHide(verAceptadosDiv);
      }) 
    }

    profileBtn.addEventListener("click", function(){
      let profileDiv = document.querySelector('#profileDiv');
      showHide(profileDiv);
    })

 
 

    function showHide(element) {
      if (element.classList.contains('hidden')) {
        element.classList.remove('hidden');

      } else {
        element.classList.add('hidden');
      }
    }




    // Show Hide sidebar
    let sidebarBtn = document.querySelector('#sidebarBtn');
   

      sidebarBtn.addEventListener("click", function(){

        let sidebar = document.querySelector('aside');
        let sidebarMobile = document.querySelector('#sidebarMobile');
  
        if (sidebar.classList.contains('sm:block')) {
          sidebar.classList.replace('sm:block', 'sm:hidden');
  
        } else {
          sidebar.classList.replace('sm:hidden', 'sm:block');
        }
  
        if (sidebarMobile.classList.contains('hidden')) {
          sidebarMobile.classList.remove('hidden');
  
        } else {
          sidebarMobile.classList.add('hidden');
        }

      })

     
   // }

    // Fin el script de la pagina














// -----------
