<?php 
session_start();
unset($_SESSION['email']);
unset($_SESSION['foto']);     //Borra las variables de sesion
unset($_SESSION['id']);
unset($_SESSION['nombre']);
unset($_SESSION['sid']);
setcookie('sidUsuario', '', 0, '/');
header('location: ../index.php');
?>