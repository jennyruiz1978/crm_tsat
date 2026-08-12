<?php

class ModeloSolicitudesModificacion
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function crearSolicitud($datos)
    {
        $this->db->query("INSERT INTO solicitudesmodificacion
            (idfichajeoriginal, idjornadaoriginal, idempleado, tipomodificacion, nuevotipofichaje, nuevafechahora, nuevaobservaciones, motivo)
            VALUES (:idfichajeoriginal, :idjornadaoriginal, :idempleado, :tipomodificacion, :nuevotipofichaje, :nuevafechahora, :nuevaobservaciones, :motivo)");
        $this->db->bind(':idfichajeoriginal', $datos['idfichajeoriginal']);
        $this->db->bind(':idjornadaoriginal', $datos['idjornadaoriginal']);
        $this->db->bind(':idempleado', $datos['idempleado']);
        $this->db->bind(':tipomodificacion', $datos['tipomodificacion']);
        $this->db->bind(':nuevotipofichaje', $datos['nuevotipofichaje']);
        $this->db->bind(':nuevafechahora', $datos['nuevafechahora']);
        $this->db->bind(':nuevaobservaciones', $datos['nuevaobservaciones']);
        $this->db->bind(':motivo', $datos['motivo']);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function obtenerSolicitudesPorEmpleado($idEmpleado)
    {
        $this->db->query("SELECT sm.*, f.fechahora AS fechahoraoriginal, f.tipofichaje AS tipofichajeoriginal
            FROM solicitudesmodificacion sm
            LEFT JOIN fichajes f ON sm.idfichajeoriginal = f.id
            WHERE sm.idempleado = :idempleado AND sm.eliminado = 0
            ORDER BY sm.creadoen DESC");
        $this->db->bind(':idempleado', $idEmpleado);
        return $this->db->registros();
    }

    public function obtenerSolicitudesPendientes()
    {
        $this->db->query("SELECT sm.*, CONCAT(u.nombre, ' ', u.apellidos) AS nombreempleado,
            f.fechahora AS fechahoraoriginal, f.tipofichaje AS tipofichajeoriginal
            FROM solicitudesmodificacion sm
            LEFT JOIN usuarios u ON sm.idempleado = u.id
            LEFT JOIN fichajes f ON sm.idfichajeoriginal = f.id
            WHERE sm.estado = 'pendiente' AND sm.eliminado = 0
            ORDER BY sm.creadoen ASC");
        return $this->db->registros();
    }

    public function obtenerTodasSolicitudes()
    {
        $this->db->query("SELECT sm.*, CONCAT(u.nombre, ' ', u.apellidos) AS nombreempleado,
            f.fechahora AS fechahoraoriginal, f.tipofichaje AS tipofichajeoriginal,
            CONCAT(a.nombre, ' ', a.apellidos) AS nombreadmin
            FROM solicitudesmodificacion sm
            LEFT JOIN usuarios u ON sm.idempleado = u.id
            LEFT JOIN fichajes f ON sm.idfichajeoriginal = f.id
            LEFT JOIN usuarios a ON sm.idadminresuelve = a.id
            WHERE sm.eliminado = 0
            ORDER BY sm.creadoen DESC");
        return $this->db->registros();
    }

    public function obtenerSolicitudPorId($id)
    {
        $this->db->query("SELECT sm.*, CONCAT(u.nombre, ' ', u.apellidos) AS nombreempleado,
            f.fechahora AS fechahoraoriginal, f.tipofichaje AS tipofichajeoriginal
            FROM solicitudesmodificacion sm
            LEFT JOIN usuarios u ON sm.idempleado = u.id
            LEFT JOIN fichajes f ON sm.idfichajeoriginal = f.id
            WHERE sm.id = :id AND sm.eliminado = 0");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    public function aprobarSolicitud($id, $idAdmin, $respuesta)
    {
        $this->db->query("UPDATE solicitudesmodificacion
            SET estado = 'aprobada', idadminresuelve = :idadmin, fecharesolucion = NOW(), respuestaadmin = :respuesta
            WHERE id = :id");
        $this->db->bind(':idadmin', $idAdmin);
        $this->db->bind(':respuesta', $respuesta);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function rechazarSolicitud($id, $idAdmin, $respuesta)
    {
        $this->db->query("UPDATE solicitudesmodificacion
            SET estado = 'rechazada', idadminresuelve = :idadmin, fecharesolucion = NOW(), respuestaadmin = :respuesta
            WHERE id = :id");
        $this->db->bind(':idadmin', $idAdmin);
        $this->db->bind(':respuesta', $respuesta);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function insertarFichajeModificado($datos)
    {
        $this->db->query("INSERT INTO fichajesmodificados
            (idsolicitud, idfichajeoriginal, idempleado, tipofichaje, fechahora, observaciones, idjornada)
            VALUES (:idsolicitud, :idfichajeoriginal, :idempleado, :tipofichaje, :fechahora, :observaciones, :idjornada)");
        $this->db->bind(':idsolicitud', $datos['idsolicitud']);
        $this->db->bind(':idfichajeoriginal', $datos['idfichajeoriginal']);
        $this->db->bind(':idempleado', $datos['idempleado']);
        $this->db->bind(':tipofichaje', $datos['tipofichaje']);
        $this->db->bind(':fechahora', $datos['fechahora']);
        $this->db->bind(':observaciones', $datos['observaciones']);
        $this->db->bind(':idjornada', $datos['idjornada']);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function obtenerFichajesModificadosPorSolicitud($idSolicitud)
    {
        $this->db->query("SELECT * FROM fichajesmodificados WHERE idsolicitud = :idsolicitud");
        $this->db->bind(':idsolicitud', $idSolicitud);
        return $this->db->registros();
    }

    public function contarSolicitudesPendientes()
    {
        $this->db->query("SELECT COUNT(*) AS total FROM solicitudesmodificacion WHERE estado = 'pendiente' AND eliminado = 0");
        $resultado = $this->db->registro();
        return $resultado->total ?? 0;
    }
}