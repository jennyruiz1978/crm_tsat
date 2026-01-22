<?php

class TemplateHelperProveedor {
        

    public static function buildNavBarSuppliers($rol)
    {        
        $html ='<div class="flex flex-wrap" id="tabs-id">
                    <div class="w-full">
                        <ul class="flex mb-0 list-none flex-wrap pt-3 pb-4 flex-row">';

                        if ($rol == 'admin') { 
                            
                            $html .='
                                <li class="my-1 mr-2 last:mr-0 flex-auto text-center">
                                    <a class="tab-proveedores text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal text-white bg-violeta-oscuro" data-tab="tab-profile">
                                    <i class="fas fa-space-shuttle text-base mr-1"></i>  Datos del proveedor
                                    </a>
                                </li>
                                <li class="my-1 mr-2 last:mr-0 flex-auto text-center">
                                    <a class="tab-proveedores text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal texto-violeta-oscuro bg-white" data-tab="tab-settings" data-metodo="verSucursalesProveedor">
                                    <i class="fas fa-cog text-base mr-1"></i>  Almacenes
                                    </a>
                                </li>                                
                                ';
                        }

                        $html .='
                        </ul>
                        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6">
                            <div class="px-4 py-1 flex-auto">
                                <div class="tab-content tab-space">
                    ';
    
        return $html;
    
    }

    public static function buildContactsSuppliers($contactos)
    {      
        $contactos_html = '';
        foreach ($contactos as $contacto) {
            $contactos_html .= '<tr>                                    
                            <td width="40%"><input name="nombreContacto" value="'.$contacto->nombre.'" class="border-2 border-coolGray-300 rounded-lg border-opacity-50" style="width: 100%;"></td>
                            <td width="20%"><input name="mailContacto" value="'.$contacto->email.'" class="border-2 border-coolGray-300 rounded-lg border-opacity-50" style="width: 100%;"></td>
                            <td width="20%"><input type="text" regexp="[0-9]{0,9}" name="telefonoContacto" value="'.$contacto->telefono.'" class="border-2 border-coolGray-300 rounded-lg border-opacity-50" style="width: 100%;"></td>
                            <td width="10%"><a href="" class="eliminarContactoProv"><i class="fas fa-user-minus" style="color:red;"></i></a></td>
                        </tr>';
        }              
        return $contactos_html;
    
    }

    public static function buildFormCreateEditSupplier($nombre, $cif, $direccion, $poblacion, $provincia, $codigopostal, $tituloModal, $observaciones, $contactos, $idBtnSubmit, $btnSubmit, $idBtnSubmit2, $btnSubmit2, $ver)
    {
       
        $html = '
            <div class="block" id="tab-profile">                    
                <form id="formAltaProveedores">

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-2 md:gap-4 m-2">
                            <div class="grid grid-cols-1 md:col-span-2">
                                <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Nombre fiscal</label>
                                <input name="nombre" id="nombre" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent"  placeholder="Nombre fiscal" value="'.$nombre.'" required/>
                            </div>
                            <div class="grid grid-cols-1">
                                <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">CIF/NIF</label>
                                <input type="text" regexp="[a-zA-Z0-9]{0,9}" name="cif" id="cif" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" placeholder="CIF" value="'.$cif.'" />
                            </div>
                                <div class="grid grid-cols-1 lg:col-span-2">
                                <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Dirección</label>
                                <input name="direccion" id="direccion" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" type="text" placeholder="Dirección" value="'.$direccion.'" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2 md:gap-4 m-2">
                        
                            <div class="grid grid-cols-1">
                                <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Población</label>
                                <input type="text" name="poblacion" id="poblacion" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" type="text" placeholder="Población" value="'.$poblacion.'" />
                            </div>
                        
                            <div class="grid grid-cols-1">
                                <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Provincia</label>
                                <input name="provincia" id="provincia" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" type="text" placeholder="Provincia" value="'.$provincia.'" />
                            </div>
                            <div class="grid grid-cols-1">
                                <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Código postal</label>
                                <input type="text" regexp="[0-9]{0,5}" name="codigopostal" id="codigopostal" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" placeholder="Código postal" value="'.$codigopostal.'" />
                            </div>';

                            if ($tituloModal == 'Alta proveedor') {
                                $html .= '<div class="grid grid-cols-1">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" class="form-checkbox h-4 w-4" id="sucursalDefault" name="sucursalDefault" value="1">
                                                <span class="ml-3 uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Almacén por defecto</span>
                                            </label>
                                        </div>';
                            }

                    $html .= '</div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-2 md:gap-4 m-2">                            
                            <div class="grid grid-cols-1">
                                <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Observaciones</label>
                                <textarea name="observaciones" id="observaciones" class="w-full py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">'.$observaciones.'</textarea>
                            </div>
                        </div>

                        <div class="inline-flex m-2">                       
                                <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold mr-4 ">Contactos</label>
                                <a id="addContacto" title="Agregar contactos" class="rounded-full h-6 w-6 flex items-center justify-center  bg-violeta-claro text-white flex-1"><i class="fas fa-plus-circle"></i></a>                        
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-2 md:gap-4 m-2">
                            <div class="grid grid-cols-1">
                                <table id="tablaContactosProveedor">
                                '.$contactos.'
                                </table>
                            </div>
                            <div class="grid grid-cols-1"></div>
                        </div>';
                              
                    $html .= '
                            <div class="grid grid-cols-1">                            
                            </div>
                        </div>';
                    $html .='                                                                      
                        <div class="flex items-center justify-center px-6 pt-3 border-t border-solid border-blueGray-200 rounded-b">
                            <a class="cancelarCerrar w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl font-medium text-xs md:text-sm lg:text-base text-white px-4 py-1 mr-3" >Cerrar</a>
                            <button id="'.$idBtnSubmit.'" class="w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl font-medium text-xs md:text-sm lg:text-base text-white px-4 py-1 mr-3">'.$btnSubmit.'</button>
                            <button id="'.$idBtnSubmit2.'" style="display:'.$ver.'" class="w-auto bg-violeta-claro hover:bg-pink-500 rounded-lg shadow-xl font-medium text-xs md:text-sm lg:text-base text-white px-4 py-1">'.$btnSubmit2.'</button>
                        </div>
                </form>
            </div>';  

        return $html;
    }

    public static function buildHeaderModalSupplierForm($id, $tituloModal, $idProveedor)
    {
        $html = '
        <input type="hidden" value="'.$id.'" id="idProvEdit">

        <div class="flex items-start justify-between p-3 border-b border-solid border-blueGray-200 rounded-t">
            <h1 class="text-center text-sm lg:text-base uppercase texto-violeta-oscuro font-semibold pt-1">'.$tituloModal.' ' . $idProveedor . '</h1>
            <button class="cancelarCerrar p-1 ml-auto bg-transparent border-0 text-gray opacity-50 float-right leading-none font-semibold outline-none focus:outline-none" >
                <span class="bg-transparent text-black opacity-1 text-xs md:text-sm block outline-none focus:outline-none">
                    Cerrar
                </span>
            </button>
        </div>';  
        return $html;   
    }

}