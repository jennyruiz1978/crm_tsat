<?php


class ModeloTecnicos{

    private $db;


    public function __construct(){
        $this->db = new Base;
    } 
    
    public function obtenerTecnicosTablaClass($filas,$orden,$tipoOrden,$filaspagina){
        $this->db->query("SELECT id as 'idTecnico', codigotecnico AS 'Nº', nombre AS 'Nombre', apellidos AS 'Apellidos',
                        correo AS 'Email', IF(telefono >0,telefono,0) AS 'Teléfono'
                        FROM usuarios WHERE activo = 1 AND rol=2
                        order by " . $orden . " " . $tipoOrden . " limit $filaspagina,$filas ");
                                           
        $resultado = $this->db->registros();

        return $resultado;
    }

    public function obtenerTecnicosTablaClassBuscar($filas,$orden,$filaspagina,$tipoOrden,$cond){
        $this->db->query("SELECT id as 'idTecnico', codigotecnico AS 'Nº', nombre AS 'Nombre', apellidos AS 'Apellidos',
                        correo AS 'Email', IF(telefono >0,telefono,0) AS 'Teléfono'
                        FROM usuarios 
                        WHERE activo = 1 AND rol=2  $cond 
                        order by " . $orden . " " . $tipoOrden . " limit $filaspagina,$filas ");

        $resultado = $this->db->registros();

        return $resultado;
    }

    public function totalRegistrosTecnicos()
    {
        $this->db->query("SELECT count(*) AS contador FROM usuarios 
                         WHERE activo = 1 AND rol=2 ");
                        
        $fila = $this->db->registro();
        return $fila;
    }

    public function totalRegistrosTecnicosBuscar($cond)
    {
        $this->db->query("SELECT count(*) AS contador FROM usuarios 
                        WHERE activo = 1 AND rol=2 $cond ");
        $fila = $this->db->registro();
        return $fila;
    }
    
    public function insertarNuevaTecnico($datos)
    {                            
        $nombres = $datos['nombres'];
        $apellidos = $datos['apellidos'];
        $email = $datos['email'];
        $activo = $datos['activo'];        
        $telefono = $datos['telefono'];
        $rol = 2;
        $contra = $datos['contrasenia'];
        $estado = 1;
        $editarTiempo = $datos['editarTiempo']; 
        $verTodas = $datos['verTodas'];
        $recibemails = $datos['recibemails'];        
        $codigoTecnico = $datos['codigoTecnico'];
        $editarClientes = $datos['editarClientes'];
        $verClientes = $datos['verClientes'];

        $this->db->query("INSERT INTO usuarios (nombre,apellidos,correo,activo,telefono,rol,contra,estado,editartiempo,vertodas,recibemails,codigotecnico,editarclientes,verclientes) 
                        VALUES ('$nombres','$apellidos','$email','$activo','$telefono','$rol','$contra','$estado', '$editarTiempo', '$verTodas', '$recibemails', '$codigoTecnico', '$editarClientes','$verClientes')");
        
        if($this->db->execute()){
            return $this->db->lastInsertId();
        } else {
            return false;
        }      

    }

    public function ultimoCodigoTecnico()
    {
        $this->db->query("SELECT MAX(codigotecnico) AS ultimocodigo FROM usuarios");
        $fila = $this->db->registro();
        return $fila->ultimocodigo;
    }

    public function obtenerDatosDetalleTecnico($id)
    {            
        $this->db->query("SELECT * FROM usuarios 
                        WHERE codigotecnico = '$id' ");
        $fila = $this->db->registro();
        return $fila;
    }

    public function actualizarDatosTecnico($datos)
    {
        $id = $datos['id'];
        $nombres = $datos['nombres'];
        $apellidos = $datos['apellidos'];
        $email = $datos['email'];        
        $telefono = $datos['telefono'];
        $contra = $datos['contrasenia'];
        $editarTiempo = $datos['editarTiempo']; 
        $verTodas = $datos['verTodas'];
        $recibemails = $datos['recibemails'];
        $editarClientes = $datos['editarClientes'];
        $verClientes = $datos['verClientes'];

        $this->db->query("UPDATE usuarios 
                        SET  nombre = '$nombres', apellidos = '$apellidos',
                        correo = '$email', telefono = '$telefono', contra = '$contra',
                        editartiempo = '$editarTiempo', vertodas = '$verTodas', recibemails = '$recibemails',
                        editarclientes = '$editarClientes', verclientes = '$verClientes'
                        WHERE id = '$id' ");
                        
        if($this->db->execute()){
            return 1;
        }else {
            return 0;
        }
    }

    public function eliminarTecnico($codigoTecnico)
    {
        $this->db->query("UPDATE usuarios SET activo = -1 WHERE codigotecnico = $codigoTecnico ");
        if($this->db->execute()){
            return 1;
        }else {
            return 0;
        }
    }

    
    public function eliminarTecnicoByIdTecnico($idTecnico)
    {
        $this->db->query("UPDATE usuarios SET activo = -1, codigotecnico=0 WHERE id = $idTecnico ");
        if($this->db->execute()){
            return 1;
        }else {
            return 0;
        }
    }

    public function obtenerListaClientesActivo(){
        $this->db->query("SELECT id, nombre
                FROM clientes 
                WHERE activo =1
                ORDER BY id DESC");
    
        $resultado = $this->db->registros();
    
        return $resultado;
    }   

    public function updateTecnicoEnCliente($ins,$idCliente)
    {
        $this->db->query('UPDATE clientes cli
                        SET cli.tecnicos = JSON_INSERT(cli.tecnicos,"$[1000]", "'.$ins.'") 
                        WHERE cli.id ='.$idCliente );
        $this->db->execute();        
    }


}