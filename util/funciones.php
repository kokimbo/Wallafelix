<?php 
function generarNombreArchivo(String $nombreOriginal):string{
    $nombreArchivo = md5(time()+rand());
            $partesExtension = explode('.',$nombreOriginal);
            $extension = $partesExtension[count($partesExtension)-1];
            return $nombreArchivo.'.'.$extension;
}
function guardarMensaje($mensaje){
    $_SESSION['error'] = $mensaje;
}

function insertarMensaje() {
    if(isset($_SESSION['error'])){
        echo '<span class="error">'.$_SESSION['error'].'</span>';
        unset($_SESSION['error']);
    }
}

?>