<header id="sidebarMobile" class="<?php print BG_SIDEBAR; ?> w-full py-5 px-6 hidden sm:hidden">
      <!--div class="flex items-center justify-between">
          <a href="index.html" class="text-white text-3xl font-semibold uppercase hover:text-gray-300">Admin</a>
          <button @click="isOpen = !isOpen" class="text-white text-3xl focus:outline-none">
              <i x-show="!isOpen" class="fas fa-bars"></i>
              <i x-show="isOpen" class="fas fa-times"></i>
          </button>
      </div-->

      <!-- Dropdown Nav -->

      <nav class="text-white text-base font-semibold" id="menu">
           
           <?php foreach($_SESSION['permisos'] as $menu){  ?>
             <div class="m-2">
             <div class="flex items-center active-nav-link text-white opacity-75 hover:opacity-100 py-1 pl-6 nav-item menu-btn">
             <?php print "<span><i class='" . $menu[1] . " mr-3'></i></span>";
             print($menu[2]); ?>
             <span><i class="fas fa-angle-right ml-3"></i></span>
             <?php  if(isset($menu[3])){ ?>
                 </div> 
                 <div class="bg-violeta-claro hidden flex-col rounded ml-6 mt-1 p-1 text-sm w-32 dropdown">
                   <?php for($i=0;$i<count($menu[3]);$i++){ 
                      if($menu[3][$i][1] != "link"){ 
                        $visible = ($menu[3][$i][0] == '/ModalidadesMantto' && EMPRESA === 'INFOMALAGA')? 'display:none':'';
                          ?>
                     <a style="<?php echo $visible;?>" href="<?php echo RUTA_URL . $menu[3][$i][0]; ?>" class="px-3 py-1 hover:<?php print BG_SUBMENU_HOVER; ?>"><?php print($menu[3][$i][1]); ?></a>
                   <?php } }; //fin del segundo foreach ?>
                 </div> 
               <?php } else {  ?>
                 </div> 
               <?php  }; // fin del if ?>
               

       </div> <!-- fin del div class m-2 -->
       <?php  }; // fin del primer foreach ?>
   </nav>
  </header>