<?php
class UsuarioDAO
{    
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getByEmail($email):Usuario|null {
        if(!$stmt = $this->conn->prepare("SELECT * FROM usuario WHERE email = ?"))
        {
            echo "Error en la SQL: " . $this->conn->error;
        }
        //Asociar las variables a las interrogaciones(parámetros)
        $stmt->bind_param('s',$email);
        //Ejecutamos la SQL
        $stmt->execute();
        //Obtener el objeto mysql_result
        $result = $stmt->get_result();

        //Si ha encontrado algún resultado devolvemos un objeto de la clase Mensaje, sino null
        if($result->num_rows >= 1){
            $usuario = $result->fetch_object(Usuario::class);
            return $usuario;
        }
        else{
            return null;
        }
    }
    
    public function getBySid($sid): Usuario|null{
        if(!$stmt = $this->conn->prepare("SELECT * FROM usuario WHERE sid = ?")){
            echo "Error en la SQL: " . $this->conn->error;
        }
        $stmt->bind_param('s', $sid);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows >= 1){
            $usuario = $result->fetch_object(Usuario::class);
            return $usuario;
        }else{
            return null;
        }
    }

    public function getById($id): Usuario|null{
        if(!$stmt = $this->conn->prepare("SELECT * FROM usuario WHERE id=?")){
            echo "Error en la SQL: " . $this->conn->error;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result(); 
        if($result->num_rows >= 1){
            $usuario = $result->fetch_object(Usuario::class);
            return $usuario;
        }else{
            return null;
        }
    }

    function delete($id):bool{
        if(!$stmt = $this->conn->prepare("DELETE FROM usuario WHERE id = ?"))
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

    function insert(Usuario $usuario):int|bool{
        if(!$stmt = $this->conn->prepare("INSERT INTO usuario (nombre, email, password, poblacion, telefono, foto, sid) VALUES (?,?,?,?,?,?,?)")){
            die("Error al preparar la consulta insert: ".$this->conn->error);
        }

        $nombre = $usuario->getNombre();
        $email = $usuario->getEmail();
        $password = $usuario->getPassword();
        $poblacion = $usuario->getPoblacion();
        $telefono = $usuario->getTelefono();
        $foto = $usuario->getFoto();
        $sid = $usuario->getSid();
        $stmt->bind_param("sssssss",$nombre,$email,$password,$poblacion,$telefono,$foto,$sid);

        if($stmt->execute()){
            return $stmt->insert_id;
        }else{
            return false;
        }
    }
}

?>