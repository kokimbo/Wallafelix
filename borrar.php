<?php 
session_start();
require_once 'modelo/Anuncio.php';
require_once 'modelo/AnuncioDAO.php';
require_once 'modelo/Foto.php';
require_once 'modelo/FotoDAO.php';
require_once 'modelo/Usuario.php';
require_once 'modelo/UsuarioDAO.php';
require_once 'util/config.php';
require_once 'util/ConnexionDB.php';

$idAnuncio = '';
    
$connexionDB = new ConnexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
$conn = $connexionDB->getConnexion();

if($_SERVER['REQUEST_METHOD']=='GET'){
    $idAnuncio = $_GET['id'];
    $idUsuarioSesion = $_SESSION['id'];
    $usuarioDAO = new UsuarioDAO($conn);
    $usuario = new Usuario();

    $anuncioDAO = new AnuncioDAO($conn);
    $fotoDAO = new FotoDAO($conn);
    if($anuncioSeleccionado = $anuncioDAO->getById($idAnuncio)){

        $idUsuario = $anuncioSeleccionado->getIdUsuario();
        $usuario = $usuarioDAO->getById($idUsuario);

        if($anuncioSeleccionado->getIdUsuario()==$idUsuarioSesion){
            $arrFotos = $fotoDAO->getAllByIdAnuncio($idAnuncio);
            $fotoPrincipal = $anuncioSeleccionado->getFoto();
            $carpeta = 'fotosTablaFotos/';
            
            foreach ($arrFotos as $foto) {
                unlink($carpeta . $foto->getFotoAnuncio());
            }
            $carpeta = 'fotosAnuncio/';
            unlink($carpeta . $fotoPrincipal);
            
            $anuncioDAO->delete($idAnuncio);
            header("location: index.php");
        }else{
            die("No puedes borrar un anuncio que no es tuyo");
        }
    }else{
        die("Error, el id de anuncio no se corresponde con ningun anuncio en la base de datos");
    } 


}

?>