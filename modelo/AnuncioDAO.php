<?php 
class AnuncioDAO{
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getById($id): Anuncio|bool{
        if(!$stmt = $this->conn->prepare("SELECT * FROM anuncio WHERE id = ?")){
            echo "Error de sql: " . $this->conn->error;
        }
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows >= 0){
            $anuncio = $result->fetch_object(Anuncio::class);
            return $anuncio;
        }else{
            return false;
        }
    }

    public function insert(Anuncio $anuncio, int $idUsuario): int|bool{
        if (!$stmt =  $this->conn->prepare("INSERT INTO anuncio SET precio=?, titulo=?, descripcion=?, foto=?, idUsuario=?")) echo "Error de sql: " . $this->conn->error;

        $precio = $anuncio->getPrecio();
        $titulo = $anuncio->getTitulo();
        $descripcion = $anuncio->getDescripcion();
        $foto = $anuncio->getFoto();

        $stmt->bind_param('dsssi', $precio, $titulo, $descripcion, $foto, $idUsuario);

        return $stmt->execute() ? $stmt->insert_id : false;
    }

    public function getAll():array {
        if(!$stmt = $this->conn->prepare("SELECT * FROM anuncio ORDER BY fecha DESC"))
        {
            echo "Error en la SQL: " . $this->conn->error;
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $array_anuncios = array();
        
        while($anuncio = $result->fetch_object(Anuncio::class)){
            $array_anuncios[] = $anuncio;
        }
        return $array_anuncios;
    }

    function delete($id):bool{

        if(!$stmt = $this->conn->prepare("DELETE FROM anuncio WHERE id = ?"))
        {
            echo "Error en la SQL: " . $this->conn->error;
        }
        //Asociar las variables a las interrogaciones(parámetros)
        $stmt->bind_param('i',$id);
        //Ejecutamos la SQL
        $stmt->execute();
        //Comprobamos si ha borrado algún registro o no
        if($stmt->affected_rows==1){
            return true;
        }
        else{
            return false;
        }
    }

    
    function update($anuncio){
        if(!$stmt = $this->conn->prepare("UPDATE anuncio SET precio=?, titulo=?, descripcion=?, foto=? WHERE id=?")){
            die("Error al preparar la consulta update: " . $this->conn->error );
        }
        $precio = $anuncio->getPrecio();
        $titulo = $anuncio->getTitulo();
        $descripcion = $anuncio->getDescripcion();
        $foto = $anuncio->getFoto();
        $id = $anuncio->getId();
        $stmt->bind_param('dsssi',$precio, $titulo, $descripcion, $foto, $id);
        return $stmt->execute();
    }


    function filtrarAnuncio($titulo):array|bool{
        if(!$stmt = $this->conn->prepare("SELECT * FROM anuncio WHERE titulo LIKE ?")){
            die ("Error al preparar la consulta insert: " . $this->conn->error);
        }
        $searchPattern = '%' . $titulo . '%';
        $stmt->bind_param('s',$searchPattern);
        $stmt->execute();
        $array_anuncio = array();
        $result = $stmt->get_result();
        while($anuncio = $result->fetch_object(Anuncio::class)){
            $array_anuncio[] = $anuncio;
        }
        if (empty($array_anuncio)) {
            return false;
        }else{
            return $array_anuncio;
        }
    }

    
}

?>