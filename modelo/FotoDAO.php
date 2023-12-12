<?php 
require_once 'Foto.php';
class FotoDAO{
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insert($fotos, int $idAnuncio): int|bool{
        if (!$stmt =  $this->conn->prepare("INSERT INTO foto SET foto=?, idAnuncio=?")) echo "Error de sql: " . $this->conn->error;

        $stmt->bind_param('si', $fotos, $idAnuncio);

        $stmt->execute();

        return $this->conn->affected_rows==1 ? $stmt->insert_id : false;
    }

    public function getAllByIdAnuncio($idAnuncio):array {
        if(!$stmt = $this->conn->prepare("SELECT * FROM foto WHERE idAnuncio=?"))
        {
            echo "Error en la SQL: " . $this->conn->error;
        }
        $stmt->bind_param('i', $idAnuncio);
        $stmt->execute();
        $result = $stmt->get_result();
        $arrFotos = array();
        
        while($foto = $result->fetch_object(Foto::class)){
            $arrFotos[] = $foto;
        }
        return $arrFotos;
    }

    function delete($foto):bool{

        if(!$stmt = $this->conn->prepare("DELETE FROM foto WHERE foto = ?"))
        {
            echo "Error en la SQL: " . $this->conn->error;
        }

        //Asociar las variables a las interrogaciones(parámetros)
        $stmt->bind_param('s',$foto);
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

    
}

?>