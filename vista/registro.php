<?php 
session_start();
unset($_SESSION['email']);
unset($_SESSION['foto']);     //Borra las variables de sesion
unset($_SESSION['id']);
unset($_SESSION['nombre']);
setcookie('sidUsuario', '', 0, '/');
require_once '../util/config.php';
require_once '../modelo/Usuario.php';
require_once '../modelo/UsuarioDAO.php';
require_once '../util/ConnexionDB.php';
require_once '../util/funciones.php';

$error = '';
$errorPassword = '';
$errorFoto = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Limpiamos los datos
    $foto = '';
    $email = htmlentities($_POST['email']);
    $password1 = htmlentities($_POST['password1']);
    $password2 = htmlentities($_POST['password2']);
    $nombre = htmlentities($_POST['nombre']);
    $telefono = htmlentities($_POST['telefono']);
    $poblacion = htmlentities($_POST['poblacion']);


    //Creamos la conexion utilizando la clase que hemos creado
    $conexionDB = new ConnexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
    $conn = $conexionDB->getConnexion();

    //Compruebo que no haya un usuario registrado con el mismo email
    $usuariosDAO = new UsuarioDAO($conn);
    
    if(empty($email) || empty($password2) || empty($password1) || empty($nombre) || empty($telefono) || empty($poblacion)){
        $error = 'Hay que rellenar todos los campos';
    }else if ($usuariosDAO->getByEmail($email) != null) {
        $error = 'Ya hay un usuario con ese email';
    }else if(strlen($password1)<4 || strlen($password2)<4){
        $error = 'La contraseña debe tener, al menos, 4 caracteres';
    }else if($password1 != $password2) {
        $errorPassword = 'Las contraseñas no coinciden';
    }else if($_FILES['foto']['type'] != 'image/jpeg' && $_FILES['foto']['type'] != 'image/webp' && 
             $_FILES['foto']['type'] != 'image/png' && $_FILES['foto']['type'] != 'image/jpg'){
        $errorFoto = "La foto no tiene el formato admitido, debe ser jpg, webp, png o jpg";
    }else{
        $foto = generarNombreArchivo(htmlspecialchars($_FILES['foto']['name']));
        
        while(file_exists("../fotosPerfil/$foto")){
            $foto = generarNombreArchivo(htmlspecialchars($_FILES['foto']['name']));
        }
        if(!move_uploaded_file($_FILES['foto']['tmp_name'], '../fotosPerfil/'.$foto)){
            die();
        }
        //Insertamos en la BD 
        $usuario = new Usuario();
        $usuario->setEmail($email);
        //Encriptamos la password
        $passwordCifrado = password_hash($password1, PASSWORD_DEFAULT);
        $usuario->setPassword($passwordCifrado);
        $usuario->setNombre($nombre);
        $usuario->setTelefono($telefono);
        $usuario->setPoblacion($poblacion);
        $usuario->setSid(sha1(rand() + time()));
        $usuario->setFoto($foto);
        if ($usuariosDAO->insert($usuario)) {
            header("Location: login.php");
        }
    }
    if(!empty($error) || !empty($errorPassword) || !empty($errorFoto)){
        $nombreCompletar = htmlentities($_POST['nombre']);
        $emailCompletar = htmlentities($_POST['email']);
        $poblacionCompletar = htmlentities($_POST['poblacion']);
        $telefonoCompletar = htmlentities($_POST['telefono']);
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar</title>
    <link rel="stylesheet" href="../estilos/registro.css">
</head>
<body>
<div class="svg-container">
        <svg width="183" height="48" fill="none" xmlns="http://www.w3.org/2000/svg"><g filter="url(#a)"><path fill-rule="evenodd" clip-rule="evenodd" d="M4 16C4 8.268 10.268 2 18 2h12c7.732 0 14 6.268 14 14v12c0 7.732-6.268 14-14 14H18c-7.732 0-14-6.268-14-14V16Z" fill="#13C1AC"/></g><path fill-rule="evenodd" clip-rule="evenodd" d="M35.365 22.003c-.437-2.2-1.814-3.245-2.162-3.462a.46.46 0 0 1-.232-.444c.122-4.045-2.744-7.536-6.744-7.526-2.556.007-4.152 1.151-5.47 2.722-.15.177-.411.158-.615.05-2.582-1.38-5.657-.45-6.957 2.106-1.128 2.216-.68 4.74 1.151 6.385a.553.553 0 0 1 .094.693c-1.598 2.572-.872 5.994 1.625 7.64a5.23 5.23 0 0 0 4.573.596c.271-.08.467 0 .599.21 1.657 2.515 4.983 3.233 7.467 1.569a5.617 5.617 0 0 0 2.429-4.017c.024-.2.152-.39.342-.44 3.697-.974 4.205-4.55 3.9-6.082Zm-6.172 5.369a3.698 3.698 0 0 1-.57 1.882c-1.063 1.684-3.25 2.161-4.886 1.066-1.635-1.095-2.098-3.348-1.035-5.032a.765.765 0 0 0-.212-1.043.732.732 0 0 0-1.026.214 5.48 5.48 0 0 0-.981 3.79.506.506 0 0 1-.393.555 3.606 3.606 0 0 1-2.56-.421c-1.777-1.039-2.4-3.364-1.392-5.195.113-.202.322-.324.557-.261.455.13.925.192 1.396.187a.683.683 0 0 0 .668-.698.686.686 0 0 0-.631-.686c-.663-.042-1.28-.189-1.823-.585-1.662-1.21-1.938-3.448-.785-5.068 1.1-1.547 3.307-1.866 4.902-.56.007.005.054.054.06.06.314.234.858.105 1.035-.25.345-.697.895-1.164 1.182-1.379 2.266-1.695 5.166-1.446 6.984.747 1.87 2.256 1.566 5.546-.805 7.519-.315.261-.341.752-.076 1.068.252.3.704.353 1.012.113.808-.628 1.558-1.485 2.022-2.413a.59.59 0 0 1 .194-.232c.234-.144.534-.03.673.211.288.5.54 1.246.572 1.825.113 2.006-1.424 3.73-3.37 3.846-.459.019-.71.286-.712.74Z" fill="#fff"/><g clip-path="url(#b)"><path d="M167.33 36.458c.028.914.72 1.604 1.61 1.604.889 0 1.581-.692 1.611-1.604l.183-6.029a.791.791 0 0 1 1.078-.713c.891.354 1.862.55 2.88.55 4.171.005 7.639-3.308 7.797-7.46.166-4.421-3.385-8.06-7.791-8.06-4.303 0-7.793 3.475-7.793 7.76 0 1.288.425 13.952.425 13.952Zm7.368-9.894a4.066 4.066 0 0 1-4.073-4.058 4.065 4.065 0 0 1 4.073-4.056 4.066 4.066 0 0 1 4.075 4.057 4.065 4.065 0 0 1-4.075 4.057ZM79.371 16.348c-.05-1.008-.793-1.58-1.596-1.58-.809 0-1.529.572-1.587 1.58l-.406 6.919c-.127 2.18-1.382 3.298-2.981 3.298-1.55 0-2.784-1.022-2.953-2.855l-.505-5.365c-.09-.96-.779-1.447-1.452-1.447l-.036.002-.035-.002c-.674 0-1.362.488-1.452 1.447l-.505 5.365c-.168 1.833-1.403 2.855-2.953 2.855-1.6 0-2.854-1.118-2.981-3.298l-.405-6.92c-.06-1.007-.78-1.578-1.589-1.578-.803 0-1.545.57-1.595 1.579l-.331 6.747c-.213 4.32 3.502 7.173 6.901 7.173a6.908 6.908 0 0 0 4.435-1.6.796.796 0 0 1 1.02 0 6.908 6.908 0 0 0 4.436 1.6c3.4 0 7.115-2.854 6.901-7.174l-.331-6.746Zm78.735-1.601c-4.305 0-7.793 3.474-7.793 7.76s3.488 7.76 7.793 7.76c4.306 0 7.794-3.474 7.794-7.76s-3.49-7.76-7.794-7.76Zm0 11.817a4.067 4.067 0 0 1-4.075-4.058c0-2.24 1.825-4.056 4.075-4.056a4.066 4.066 0 0 1 4.075 4.057 4.067 4.067 0 0 1-4.075 4.057ZM141.6 14.747c-4.305 0-7.794 3.474-7.794 7.76 0 .317.025 1.326.065 2.667-.029.803-.63 1.39-1.4 1.39-.774 0-1.302-.555-1.395-1.272l-.493-3.761-.005-.034-.003-.03c-.51-3.794-3.774-6.72-7.724-6.72-4.271 0-7.738 3.42-7.793 7.658v-.003s-.038 1.164.111 2.586c.087.816-.796 1.577-1.57 1.577-.899 0-1.556-.698-1.603-2.239l-.482-15.815v-.025c-.022-.722-.696-1.486-1.609-1.486-.926 0-1.582.764-1.605 1.51l-.481 15.816c-.045 1.481-1.004 2.24-2.09 2.24-1.098 0-2.034-.759-2.08-2.24l-.481-15.815c-.024-.788-.733-1.511-1.606-1.511-.794 0-1.58.617-1.608 1.51l-.508 16.655c-.025.809-.627 1.4-1.4 1.4-.776 0-1.303-.555-1.396-1.271l-.502-3.826c-.507-3.774-3.739-6.69-7.661-6.72-4.313-.034-7.857 3.467-7.857 7.76 0 4.286 3.489 7.76 7.793 7.76 1.813 0 3.481-.616 4.805-1.65a.928.928 0 0 1 .566-.191c1.166 0 1.31 1.84 4.251 1.84 1.183 0 2.418-.796 3.061-1.282a.702.702 0 0 1 .865.014c.606.487 1.871 1.269 3.741 1.269 1.628 0 2.701-.679 3.261-1.166a.714.714 0 0 1 .935 0 4.774 4.774 0 0 0 3.163 1.166c2.581 0 3.636-1.84 4.404-1.84.242 0 .402.065.588.202a7.78 7.78 0 0 0 4.789 1.638 7.78 7.78 0 0 0 4.803-1.65.927.927 0 0 1 .567-.191c1.166 0 1.44 1.84 3.811 1.84.729 0 1.25-.276 1.848-.618.057-.034.131.007.132.072.109 3.415.22 6.737.22 6.737.028.914.72 1.604 1.61 1.604.889 0 1.582-.692 1.61-1.604l.184-6.029a.794.794 0 0 1 1.091-.707 7.785 7.785 0 0 0 2.867.545c4.172.004 7.64-3.31 7.796-7.46.164-4.422-3.387-8.06-7.791-8.06ZM88.423 26.564a4.067 4.067 0 0 1-4.075-4.058c0-2.24 1.825-4.056 4.075-4.056s4.074 1.816 4.074 4.057c0 2.24-1.824 4.057-4.075 4.057Zm34.426 0a4.067 4.067 0 0 1-4.074-4.058c0-2.24 1.825-4.056 4.074-4.056a4.066 4.066 0 0 1 4.075 4.057 4.067 4.067 0 0 1-4.075 4.057Zm18.751 0a4.067 4.067 0 0 1-4.075-4.058c0-2.24 1.825-4.056 4.075-4.056s4.075 1.816 4.075 4.057c0 2.24-1.825 4.057-4.075 4.057Z" fill="#3DD2BA"/></g><defs><clipPath id="b"><path fill="#fff" transform="translate(56 6.38)" d="M0 0h126.545v32H0z"/></clipPath><filter id="a" x="0" y="0" width="48" height="48" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB"><feFlood flood-opacity="0" result="BackgroundImageFix"/><feColorMatrix in="SourceAlpha" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/><feOffset dy="2"/><feGaussianBlur stdDeviation="2"/><feColorMatrix values="0 0 0 0 0.145098 0 0 0 0 0.196078 0 0 0 0 0.219608 0 0 0 0.1 0"/><feBlend in2="BackgroundImageFix" result="effect1_dropShadow_7634_6626"/><feBlend in="SourceGraphic" in2="effect1_dropShadow_7634_6626" result="shape"/></filter></defs></svg>
  </div>  
  <div class="wrapper">
<form action="registro.php" method="post" enctype="multipart/form-data">
      <div class="input-box">
        <input type="text" placeholder="Nombre..." name="nombre" <?php if(isset($nombreCompletar) && !empty($nombreCompletar)):?> value="<?= $nombreCompletar ?>" <?php endif;?>required maxlength="100">
      </div>
      <div class="input-box">
        <input type="email" placeholder="Email..." name="email" <?php if(isset($emailCompletar) && !empty($emailCompletar)):?> value="<?= $emailCompletar ?>" <?php endif;?> required maxlength="200">
      </div>
      <div class="input-box">
        <input type="text" placeholder="Poblacion..." name="poblacion" <?php if(isset($poblacionCompletar) && !empty($poblacionCompletar)):?> value="<?= $poblacionCompletar ?>" <?php endif;?> required maxlength="200">
      </div>
      <div class="input-box">
        <input type="tel" placeholder="Telefono..." name="telefono" <?php if(isset($telefonoCompletar) && !empty($telefonoCompletar)):?> value="<?= $telefonoCompletar ?>" <?php endif;?> required maxlength="9">
      </div>
      <div class="input-box">
        <input type="password" placeholder="Contraseña..." name="password1" required maxlength="100">
      </div>
      <div class="input-box">
        <input type="password" placeholder="Confirma contraseña..." name="password2" required maxlength="100">
      </div>

      <?php if(!empty($errorPassword)):?>
        <p style="color: red;">*<?= $errorPassword ?></p>
      <?php endif;?>

      <div class="input-box">
        <p>Selecciona una foto</p>
        <input type="file" name="foto" accept="image/jpeg, image/jpg, image/webp, image/png">
      </div><br>

      <?php if(!empty($errorFoto)):?>
        <p style="color: red;">*<?= $errorFoto ?></p>
      <?php endif;?>

      <?php if(!empty($error)):?>
        <p style="color: red;">*<?= $error ?></p>
      <?php endif;?>

      <div class="input-box button">
        <input type="submit" value="Registrar" name="enviar">
      </div>
      <div class="text">
        <h3>¿Ya tienes cuenta? <a href="registro.php">¡Registrate ya!</a></h3>
        <h3><a href="../index.php">Volver atras</a></h3>
      </div>
    </form>
  </div>
</body>
</html>