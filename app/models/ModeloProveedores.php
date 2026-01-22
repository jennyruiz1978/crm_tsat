<?php


class ModeloProveedores{

    private $db;


    public function __construct(){
        $this->db = new Base;
    }

    public function obtenerProveedoresActivos(){
        $this->db->query("SELECT id, nombre
                        FROM proveedores 
                        WHERE activo =1
                        ORDER BY nombre ASC");


        $resultado = $this->db->registros();

        return $resultado;
    } 

    public function obtenerClientes(){
        $this->db->query("SELECT id, nombre as 'Razón Social', cif as 'CIF', IF(activo=1,'Activo','') as Estado
                        FROM proveedores 
                        WHERE activo =1
                        ORDER BY id DESC");

        $resultado = $this->db->registros();

        return $resultado;
    } 

    
    public function obtenerProveedoresTablaClass($filas,$orden,$tipoOrden,$filaspagina){
        $this->db->query("SELECT id as 'Nº', nombre as 'Razón Social', cif as 'CIF',
                        poblacion as 'Población', provincia as 'Provincia'
                        FROM proveedores 
                        WHERE activo =1
                        order by " . $orden . " " . $tipoOrden . " limit $filaspagina,$filas ");

        $resultado = $this->db->registros();

        return $resultado;
    }

    public function obtenerProveedoresTablaClassBuscar($filas,$orden,$filaspagina,$tipoOrden,$cond){
        $this->db->query("SELECT id as 'Nº', nombre as 'Razón Social', cif as 'CIF',
                        poblacion as 'Población', provincia as 'Provincia'
                        FROM proveedores 
                        WHERE activo =1  $cond
                        order by " . $orden . " " . $tipoOrden . " limit $filaspagina,$filas ");

        $resultado = $this->db->registros();

        return $resultado;
    }

    public function totalRegistrosProveedores()
    {
        $this->db->query("SELECT count(id) AS contador 
                        FROM proveedores 
                        WHERE activo =1 ");
        $fila = $this->db->registro();
        return $fila;
    }

    public function totalRegistrosProveedoresBuscar($cond)
    {
        $this->db->query("SELECT count(id) AS contador
                        FROM proveedores 
                        WHERE activo =1  $cond ");
        $fila = $this->db->registro();
        return $fila;
    }
    

    public function insertarDatosProveedorNuevo($datos)
    {
        $nombre = strtoupper($datos['nombre']);
        $cif = $datos['cif'];
        $direccion = $datos['direccion'];
        $poblacion = $datos['poblacion'];
        $provincia = $datos['provincia'];
        $codigopostal = $datos['codigopostal'];
        $activo = 1;
        $creacion = date('Y-m-d');
        //$tecnicos = json_encode($datos['idstecnicos']);
        $contactos = json_encode($datos['contactos']);
        $observaciones = $datos['observaciones'];

        $this->db->query("INSERT INTO proveedores (nombre,cif,direccion,poblacion,provincia,codigopostal,activo,creacion, contactos,observaciones) 
                        VALUES ('$nombre','$cif','$direccion','$poblacion','$provincia','$codigopostal','$activo','$creacion','$contactos', '$observaciones')");


        if($this->db->execute()){
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    public function detalleProveedorPorId($id){

        $this->db->query("SELECT * FROM proveedores WHERE id = $id ");

        $resultado = $this->db->registro();

        return $resultado;
    }

    public function eliminarProveedor($id)
    {
        $this->db->query("UPDATE proveedores SET activo = -1 WHERE id = $id ");
        if($this->db->execute()){
            return 1;
        }else {
            return 0;
        }
    }

    public function actualizarDatosProveedorNuevo($datos)
    {         
        $nombre = strtoupper($datos['nombre']);
        $cif = $datos['cif'];
        $direccion = $datos['direccion'];
        $poblacion = $datos['poblacion'];
        $provincia = $datos['provincia'];
        $codigopostal = $datos['codigopostal'];        
        $id = $datos['id'];
        //$tecnicos = json_encode($datos['idstecnicos']);
        $contactos = json_encode($datos['contactos']);
        $observaciones = $datos['observaciones'];

        $this->db->query("UPDATE proveedores 
                        SET nombre = '$nombre', cif = '$cif',direccion = '$direccion',poblacion = '$poblacion',
                        provincia = '$provincia',codigopostal = '$codigopostal', contactos = '$contactos',  observaciones = '$observaciones'
                        WHERE id = $id ");
        
        if($this->db->execute()){
            return 1;
        }else {
            return 0;
        }
    }

    public function nombreTecnicoPorId($idTecnico)
    {        
        $this->db->query("SELECT * FROM usuarios WHERE id = $idTecnico ");        
        $resultado = $this->db->registro();
        return $resultado;
    }

    public function obtenerListaTecnicos()
    {        
        $this->db->query("SELECT * FROM usuarios WHERE rol=2 AND activo = 1 ");
        $resultado = $this->db->registros();
        return $resultado;
    }

    public function obtenerAlmacenesPorProveedor($id)
    {
        $this->db->query("SELECT * FROM almacenes WHERE activo =1 AND idproveedor= '$id' ");
        $resultado = $this->db->registros();
        return $resultado;
    }
    public function obtenerSucursalesActivasPorCliente($id)
    {
        $this->db->query("SELECT * FROM almacenes WHERE activo =1 AND idproveedor= '$id' ");
        $resultado = $this->db->registros();
        return $resultado;
    }

    
    public function detalleAlmacenPorId($id){

        $this->db->query("SELECT * FROM almacenes WHERE id = $id ");
        $resultado = $this->db->registro();
        return $resultado;
    }
    
    public function insertarDatosAlmacenNuevo($datos)
    {      
        
            
        $nombre = $datos['nombreSucursal'];        
        $direccion = $datos['direccionSucursal'];
        $poblacion = $datos['poblacionSucursal'];
        $provincia = $datos['provinciaSucursal'];
        $codigopostal = $datos['codigopostalSucursal'];
        $activo = 1;
        $creacion = date('Y-m-d');        
        $contactos = json_encode($datos['contactos']);
        $idProveedor = $datos['idProveedor'];

        $this->db->query("INSERT INTO almacenes (idproveedor,nombre,direccion,poblacion,provincia,codigopostal,activo,creacion, contactos) 
                        VALUES ('$idProveedor','$nombre','$direccion','$poblacion','$provincia','$codigopostal','$activo','$creacion', '$contactos')");
          
        if($this->db->execute()){
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    public function actualizarDatosSucursalNueva($datos)
    {        
        $nombre = $datos['nombreSucursal'];  
        $direccion = $datos['direccionSucursal'];
        $poblacion = $datos['poblacionSucursal'];
        $provincia = $datos['provinciaSucursal'];
        $codigopostal = $datos['codigopostalSucursal'];              
        $idAlmacen = $datos['idAlmacen'];    
        $contactos = json_encode($datos['contactos']);

        $this->db->query("UPDATE almacenes 
                        SET nombre = '$nombre', direccion = '$direccion',poblacion = '$poblacion',
                        provincia = '$provincia',codigopostal = '$codigopostal', contactos = '$contactos' 
                        WHERE id = $idAlmacen ");
        
        if($this->db->execute()){
            return 1;
        }else {
            return 0;
        }
    }

    public function eliminarAlmacen($idAlmacen)
    {
        $this->db->query("UPDATE almacenes SET activo = -1 WHERE id = $idAlmacen ");
        if($this->db->execute()){
            return 1;
        }else {
            return 0;
        }
    }


    public function aniosConIncidencias()
    {
        $this->db->query("SELECT DISTINCT(YEAR(creacion)) as anio FROM incidencias ");
        $filas = $this->db->registros();
        return $filas;
    }


}