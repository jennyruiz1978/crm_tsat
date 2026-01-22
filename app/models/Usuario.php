<?php


class Usuario{

    private $db;


    public function __construct(){
        $this->db = new Base;
    }


    public function obtenerUsuarios(){
        $this->db->query('SELECT * FROM usuarios WHERE rol IN (0,1) and activo=1 ');

        $resultado = $this->db->registros();

        return $resultado;
    }

    public function obtenerUsuariosTablaClassBuscar($filas,$orden,$filaspagina,$tipoOrden,$cond)
    {
        $this->db->query("SELECT usu.id as 'idusuario', usu.nombre AS 'Nombre', 
                        usu.apellidos AS 'Apellidos',
                        usu.correo AS 'Email', 
                        IF(usu.telefono >0,usu.telefono,0) AS 'Teléfono', 
                        /*IF(usu.rol=0,'Administrador',IF(usu.rol=1,'Cliente','')) AS 'Rol', */
                        roles.nombre AS 'Rol',
                        IF(cli.nombre <> '',cli.nombre, '') AS 'Cliente',
                        IF(usu.clientetipo <> '',usu.clientetipo, '') AS 'Cliente tipo'
                        FROM usuarios usu
                        LEFT JOIN clientes cli ON usu.idcliente= cli.id
                        LEFT JOIN rolesbase roles ON usu.rol=roles.rol
                        WHERE usu.activo = 1 AND usu.rol<>2  $cond 
                        order by " . $orden . " " . $tipoOrden . " limit $filaspagina,$filas ");

        $resultado = $this->db->registros();

        return $resultado;
    }

    public function totalRegistrosUsuariosBuscar($cond)
    {
        $this->db->query("SELECT count(*) AS contador 
                        FROM usuarios usu
                        LEFT JOIN clientes cli ON usu.idcliente= cli.id
                        LEFT JOIN rolesbase roles ON usu.rol=roles.rol
                        WHERE usu.activo = 1 AND usu.rol<>2  $cond  ");
       
        $fila = $this->db->registro();
        return $fila;
    }

    public function agregarUsuario($datos){

        $this->db->query("INSERT INTO usuarios (nombre,apellidos,rol,correo,contra,estado, cambiar,idcliente,clientetipo, equipos, idsucursal,recibemails,caducidadenlace) 
                        VALUES (:nombre, :apellidos, :rol,:correo,:contra,:estado,:cambiar,:idcliente,:clientetipo,:equipos, :sucursales, :recibemails, :caducidadenlace)");      

        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':apellidos', $datos['apellidos']);
        $this->db->bind(':rol', $datos['rol']);
        $this->db->bind(':correo', $datos['correo']);
        $this->db->bind(':contra', $datos['contra']);
        $this->db->bind(':estado', $datos['estado']);
        $this->db->bind(':cambiar', $datos['cambiar']);
        $this->db->bind(':idcliente', $datos['idcliente']);
        $this->db->bind(':clientetipo', $datos['clientetipo']);        
        $this->db->bind(':equipos', $datos['equipos']);
        $this->db->bind(':sucursales', $datos['sucursales']);
        $this->db->bind(':recibemails', $datos['recibemails']);
        $this->db->bind(':caducidadenlace', $datos['caducidadenlace']);

        if($this->db->execute()){
            return $this->db->lastInsertId();
        } else {
            return false;
        }

    }

    public function obtenerUsuarioId($id){
        $this->db->query('SELECT * FROM usuarios WHERE id = :id');
        $this->db->bind(':id', $id);

        $fila = $this->db->registro();

        return $fila;
    }

    public function actualizarUsuario($datos){
        $this->db->query('UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, 
                        rol = :rol, correo = :correo, contra = :contra, estado = :estado,
                        cambiar = :cambiar, idcliente = :idcliente, clientetipo = :clientetipo, equipos =:equipos,
                        idsucursal = :sucursales, recibemails = :recibemails
                        WHERE id = :id');

        $this->db->bind(':id', $datos['id']);
        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':apellidos', $datos['apellidos']);
        $this->db->bind(':rol', $datos['rol']);
        $this->db->bind(':correo', $datos['correo']);
        $this->db->bind(':contra', $datos['contra']);
        $this->db->bind(':estado', $datos['estado']);
        $this->db->bind(':cambiar', $datos['cambiar']);
        $this->db->bind(':idcliente', $datos['idcliente']);
        $this->db->bind(':clientetipo', $datos['clientetipo']);        
        $this->db->bind(':equipos', $datos['equipos']);
        $this->db->bind(':sucursales', $datos['sucursales']);
        $this->db->bind(':recibemails', $datos['recibemails']);        
        
        if($this->db->execute()){
            return true;

        }else {
            return false;
        }
    }

    public function borrarUsuario($datos){
        $this->db->query('UPDATE usuarios SET activo = -1 WHERE id = :id');
        $this->db->bind(':id', $datos['id']);

        if($this->db->execute()){
            return 1;

        }else {
            return 0;
        }
    }

    /*
    public function obtenerPermisos($id){
        $this->db->query('SELECT menu FROM permisos WHERE idUsuario = :id');
        $this->db->bind(':id', $id);

        $fila = $this->db->registro();

        if($fila){
            return $fila->menu;
        } else {
            return 0;
        }
        
    }
    */

    
    public function obtenerListaClientesActivo(){
        $this->db->query("SELECT id, nombre
                        FROM clientes 
                        WHERE activo =1
                        ORDER BY id DESC");

        $resultado = $this->db->registros();

        return $resultado;
    }   

    public function obtenerSucursalesActivasPorCliente($id)
    {
        $this->db->query("SELECT id, nombre FROM sucursales WHERE activo =1 AND idcliente= '$id' ");
        $resultado = $this->db->registros();
        return $resultado;
    }

    public function listaTodosLosEquiposDelCliente($id)
    {
        $this->db->query("SELECT id FROM equipos WHERE  idcliente= '$id' ");
        $resultado = $this->db->registros();
        return $resultado;
    }

    public function obtenerEquiposPorSucursal($idSucursal)
    {
        $this->db->query("SELECT id, nombre FROM equipos WHERE  idsucursal= '$idSucursal' ");
        $resultado = $this->db->registros();
        return $resultado;
    }

    public function obtenerDetalleEquipos($idEquipo)
    {
        $this->db->query("SELECT equipos.id, equipos.nombre AS nombreequipo, suc.nombre AS nombresucursal 
                        FROM equipos 
                        LEFT JOIN sucursales suc ON equipos.idsucursal=suc.id
                        WHERE equipos.id= '$idEquipo' ");
        $resultado = $this->db->registro();
        return $resultado;
    }

    public function obtenerNombreCliente($id)
    {
        $this->db->query("SELECT nombre 
                        FROM clientes                         
                        WHERE id= '$id' ");
                       
        $resultado = $this->db->registro();
        return $resultado->nombre;
    }

    public function obtenerNombreRol($rol)
    {
        $this->db->query("SELECT nombre 
                        FROM rolesbase                         
                        WHERE rol= '$rol' ");
        $resultado = $this->db->registro();
        return $resultado->nombre;
    }    

    public function listaTodasLasSucursalesDelCliente($idCliente)
    {
        $this->db->query("SELECT id 
                        FROM sucursales                         
                        WHERE idcliente = '$idCliente' ");
        $resultado = $this->db->registros();
        return $resultado;
    }    

    public function obtenerIdDeLaSucursalDelEquipo($idEquipo)
    {
        $this->db->query("SELECT idsucursal 
                        FROM equipos                         
                        WHERE id = '$idEquipo' ");
        $resultado = $this->db->registro();
        return $resultado->idsucursal;
    }
}