<?php


class ModeloProductos{

    private $db;


    public function __construct(){
        $this->db = new Base;
    }

    public function obtenerProductosTablaClassBuscar($filas,$orden,$filaspagina,$tipoOrden,$cond)
    {
        $this->db->query("SELECT pro.numero as 'Nº', pro.nombre AS 'Nombre', 
                        pro.stock AS 'Stock',
                        pro.marca AS 'Marca',                         
                        pro.iva AS 'Iva',
                        pro.pvtadefault AS 'P.Vta'
                        FROM productos pro                        
                        WHERE $cond 
                        order by " . $orden . " " . $tipoOrden . " limit $filaspagina,$filas ");
       
        $resultado = $this->db->registros();

        return $resultado;
    }

    public function totalRegistrosProductosBuscar($cond)
    {
        $this->db->query("SELECT count(*) AS contador 
                        FROM productos pro                       
                        WHERE $cond  ");
       
        $fila = $this->db->registro();
        return $fila;
    }

    public function insertarDatosProductoNuevo($datos){
       
        $nombre = strtoupper($datos['nombre']);        
        $unidad = $datos['unidad'];
        $marca = $datos['marca'];
        $iva = $datos['iva'];
        $estado = $datos['estado'];      
        $numero = $datos['numero'];   
        $stock = $datos['stock'];   
        
        date_default_timezone_set("Europe/Madrid");
        $fecha = date('Y-m-d');
        $modificacion = date('Y-m-d  H:i:s');
        $observaciones = $datos['observaciones'];
        $proveedoresprecios = $datos['proveedoresprecios'];
        $proveedordefault = (isset($datos['proveedordefault']))? $datos['proveedordefault']: 0;
        $pvtadefault = $datos['pvtadefault'];

        $this->db->query("INSERT INTO productos (numero,nombre,unidad,marca,iva,estado,fecha,modificacion,observaciones,provprecios,proveedordefault,pvtadefault,stock) 
                        VALUES ('$numero','$nombre','$unidad','$marca','$iva','$estado','$fecha','$modificacion','$observaciones','$proveedoresprecios','$proveedordefault','$pvtadefault','$stock')");

        if($this->db->execute()){
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    public function obtenerProductoById($id){
        $this->db->query('SELECT * FROM productos WHERE id = :id');
        $this->db->bind(':id', $id);

        $fila = $this->db->registro();

        return $fila;
    }

    
    public function obtenerProductoByNumero($numero){
        $this->db->query('SELECT * FROM productos WHERE numero = :numero');
        $this->db->bind(':numero', $numero);

        $fila = $this->db->registro();

        return $fila;
    }

    public function obtenerNombreProductoById($id){
        $this->db->query('SELECT nombre FROM productos WHERE id = :id');
        $this->db->bind(':id', $id);

        $fila = $this->db->registro();

        return (isset($fila->nombre)? $fila->nombre: '');
    }    

    
    public function obtenerNombreProductoByNumero($numero){
        $this->db->query('SELECT nombre FROM productos WHERE numero = :numero');
        $this->db->bind(':numero', $numero);

        $fila = $this->db->registro();

        return (isset($fila->nombre)? $fila->nombre: '');
    }  

    public function actualizarDatosProducto($datos)
    {   
        $id = strtoupper($datos['id']);           
        $nombre = strtoupper($datos['nombre']);        
        $unidad = $datos['unidad'];
        $marca = $datos['marca'];
        $iva = $datos['iva'];
        $estado = $datos['estado'];                
        $observaciones = $datos['observaciones'];
        $proveedoresprecios = $datos['proveedoresprecios'];
        $proveedordefault = (isset($datos['proveedordefault']))? $datos['proveedordefault']: 0;
        $pvtadefault = $datos['pvtadefault'];
        $stock = $datos['stock'];   

        date_default_timezone_set("Europe/Madrid");        
        $modificacion = date('Y-m-d  H:i:s');

        $this->db->query("UPDATE productos 
                        SET nombre = '$nombre', unidad = '$unidad', 
                        marca = '$marca', iva = '$iva', estado = '$estado',
                        modificacion = '$modificacion',
                        observaciones = '$observaciones', provprecios = '$proveedoresprecios',
                        proveedordefault = '$proveedordefault', pvtadefault = '$pvtadefault',
                        stock = '$stock'
                        WHERE id = '$id'");           

        if($this->db->execute()){
        return true;

        }else {
        return false;
        }
    }

    
    public function borrarProducto($datos){
        $this->db->query('DELETE FROM productos WHERE numero = :id');
        $this->db->bind(':id', $datos['id']);

        if($this->db->execute()){
            return 1;

        }else {
            return 0;
        }
    }


    public function buscarProdutosPorTipoIva($tipoiva){
        $this->db->query("SELECT count(*) as contador FROM productos WHERE iva = '$tipoiva'");        
        $fila = $this->db->registro();
        return $fila->contador;
    }

    public function fechaUltimaModificacion($id)
    {
        $this->db->query("SELECT modificacion FROM productos WHERE id = '$id'");
        $fila = $this->db->registro();
        return (isset($fila->modificacion))? $fila->modificacion: false;
    }
    
    public function buscarProdutosActivos(){
        $this->db->query("SELECT * FROM productos WHERE estado= 'activo' ");        
        $filas = $this->db->registros();
        return $filas;
    }

    public function obtenerUnidadProducto($id){
        $this->db->query('SELECT unidad FROM productos WHERE id = :id');
        $this->db->bind(':id', $id);

        $fila = $this->db->registro();
        $unidad = (isset($fila->unidad) && $fila->unidad != null)?$fila->unidad:'';

        return $unidad;
    }

    public function calcularNumeroCorrelativoProducto()
    {
        $this->db->query('SELECT COALESCE(MAX(numero), 0) + 1 AS maximo 
                        FROM productos');        

        $fila = $this->db->registro();
        $maximo = (isset($fila->maximo) && $fila->maximo != null)?$fila->maximo:1;

        return $maximo;
    }

    public function buscarProductosConLike($like)
    {
        $this->db->query("SELECT 
                        id, numero, nombre, unidad, iva, proveedordefault,
                        JSON_UNQUOTE(JSON_EXTRACT(provprecios, CONCAT('$.', CAST(proveedordefault AS CHAR), '.precioVta'))) AS precio
                        FROM productos
                        WHERE estado='activo'AND nombre LIKE ". $like         
                        );
       
        $resultados = $this->db->registros();
        return $resultados;        
    }


}