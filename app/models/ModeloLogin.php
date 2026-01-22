<?php

class ModeloLogin {

    private $db;

    public function __construct() {
        $this->db = new Base;
    }

    public function comprobarLogin($mail, $pass) {   
       
            $this->db->query("SELECT * FROM usuarios WHERE correo = '$mail' AND contra = '$pass' and estado = 1 and activo = 1 ");
           
          
    
            $fila = $this->db->registro();

            if($fila){
                return $fila->nombre . " " . $fila->apellidos;
            } else {
                return 0;
            }
    }

    public function identificadorUsuario($mail, $pass) {
        
        $this->db->query('SELECT id, rol FROM usuarios WHERE correo = :mail AND contra = :pass');
        $this->db->bind(':mail', $mail);
        $this->db->bind(':pass', $pass);

        $fila = $this->db->registro();

        if($fila){
            return $fila;
        } else {
            return 0;
        }

    }

    public function verificarSiExigeCambioPassword($idUsuario)
    {
                
        $this->db->query("SELECT usuarios.*, rol.nombre AS nombrerol
                        FROM usuarios 
                        LEFT JOIN rolesbase rol ON usuarios.rol=rol.rol
                        WHERE  usuarios.id  = $idUsuario");        

        $fila = $this->db->registro();

        if($fila){
            return $fila;
        } else {
            return 0;
        }
    }

    public function consultaPermisos($rol){
        $this->db->query('SELECT permisos FROM rolesbase WHERE rol = :rol');
            $this->db->bind(':rol', $rol);

            $fila = $this->db->registro();

            if($fila){
                return $fila->permisos;
            } else {
                return 0;
            }
    }

    public function datosUsuarioPorEmail($email)
    {               
        $this->db->query("SELECT * FROM usuarios WHERE correo = '$email' and activo=1 ");

        $fila = $this->db->registro();

        if($fila){
            return $fila;
        } else {            
            return 0;
        }
    }

    public function datosUsuarioPorId($id)
    {
        $this->db->query("SELECT * FROM usuarios WHERE id = '$id' ");

        $fila = $this->db->registro();

        if($fila){
            return $fila;
        } else {
            return 0;
        }
    }

    public function actualizarContraseniaUsuario($post)
    {        
        $id = $post["id"];
        $password = $post["password"];

        $this->db->query("UPDATE usuarios
                        SET contra = '$password', cambiar=0
                        WHERE id = $id ");

        if ($this->db->execute()) {
            return 1;
        } else {
            return 0;
        }        
    }

    public function verificarEmailYPassword($mail, $pass) {   
       
        $this->db->query("SELECT * FROM usuarios WHERE correo = '$mail' AND contra = '$pass' and estado = 1 and activo = 1 ");
       

        $fila = $this->db->registro();

        if($fila){
            return 1;
        } else {
            return 0;
        }
    }

    public function obtenerCorreoPorIdusuario($idUsuario)
    {
        $this->db->query("SELECT correo FROM usuarios WHERE id= $idUsuario ");  
		
        $fila = $this->db->registro();
        if(isset($fila->correo)){
            return $fila->correo;
        } else {
            return '';
        }
    }

    public function modificarCampoCambiarYCaducidadEnlace($idUsuario,$caducidadEnlace)
    {
         $this->db->query("UPDATE usuarios
                        SET cambiar=1, caducidadenlace = '$caducidadEnlace'
                        WHERE id = $idUsuario ");

        $this->db->execute();      
    }

}
