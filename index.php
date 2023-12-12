<?php
session_start();
require_once 'modelo/Usuario.php';
require_once 'modelo/UsuarioDAO.php';
require_once 'modelo/Anuncio.php';
require_once 'modelo/AnuncioDAO.php';
require_once 'util/config.php';
require_once 'util/ConnexionDB.php';
//renovar la cookie al entrar
if(isset($_COOKIE['sidUsuario'])) {
    setcookie('sidUsuario', $_SESSION['sid'], time()+24*60*60, '/');
}    
$connexionDB = new ConnexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
$conn = $connexionDB->getConnexion();

$anuncioDAO = new AnuncioDAO($conn);
$anuncios = $anuncioDAO->getAll();

if( !isset($_SESSION['email']) && isset($_COOKIE['sidUsuario'])){
    //Nos conectamos para obtener el id y la foto del usuario
    $usuariosDAO = new UsuarioDAO($conn);
    //$usuario = $usuariosDAO->getByEmail($_COOKIE['email']);
    if($usuario = $usuariosDAO->getBySid($_COOKIE['sidUsuario'])){
        //Inicio sesión
        $_SESSION['email']=$usuario->getEmail();
        $_SESSION['id']=$usuario->getId();
        $_SESSION['foto']=$usuario->getFoto();
        $_SESSION['nombre'] = $usuario->getNombre();
        $_SESSION['sid'] = $usuario->getSid();
    }
}

if($_SERVER['REQUEST_METHOD']=='GET'){
        if(isset($_GET['filtro']) && !empty($_GET['filtro'])){
            $filtro = $_GET['filtro'];
            if(!$anuncios = $anuncioDAO->filtrarAnuncio($filtro)){
                $anuncios = array();
            }
        }
  }

if($_SERVER['REQUEST_METHOD']=='POST'){

$usuarioDAO = new UsuarioDAO($conn);
  $usuario = new Usuario();

  $email = htmlspecialchars($_POST['email']);
  $password1 = htmlspecialchars($_POST['password1']);
    
  if($usuario = $usuarioDAO->getByEmail($email)){
    if(password_verify($password1, $usuario->getPassword())){
        
      $_SESSION['email'] = $usuario->getEmail();
      $_SESSION['id'] = $usuario->getId();
      $_SESSION['nombre'] = $usuario->getNombre();
      $_SESSION['foto'] = $usuario->getFoto();
      $_SESSION['sid'] = $usuario->getSid();
      setcookie('sidUsuario', $usuario->getSid(), time()+24*60*60, '/');
      header('location: index.php');
      die();
    }
  }


  
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos/index.css">
    <title>Wallafelix</title>
</head>

<body>
    <main>
        <header>
            <svg width="183" height="48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g filter="url(#a)">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4 16C4 8.268 10.268 2 18 2h12c7.732 0 14 6.268 14 14v12c0 7.732-6.268 14-14 14H18c-7.732 0-14-6.268-14-14V16Z" fill="#13C1AC" />
                </g>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M35.365 22.003c-.437-2.2-1.814-3.245-2.162-3.462a.46.46 0 0 1-.232-.444c.122-4.045-2.744-7.536-6.744-7.526-2.556.007-4.152 1.151-5.47 2.722-.15.177-.411.158-.615.05-2.582-1.38-5.657-.45-6.957 2.106-1.128 2.216-.68 4.74 1.151 6.385a.553.553 0 0 1 .094.693c-1.598 2.572-.872 5.994 1.625 7.64a5.23 5.23 0 0 0 4.573.596c.271-.08.467 0 .599.21 1.657 2.515 4.983 3.233 7.467 1.569a5.617 5.617 0 0 0 2.429-4.017c.024-.2.152-.39.342-.44 3.697-.974 4.205-4.55 3.9-6.082Zm-6.172 5.369a3.698 3.698 0 0 1-.57 1.882c-1.063 1.684-3.25 2.161-4.886 1.066-1.635-1.095-2.098-3.348-1.035-5.032a.765.765 0 0 0-.212-1.043.732.732 0 0 0-1.026.214 5.48 5.48 0 0 0-.981 3.79.506.506 0 0 1-.393.555 3.606 3.606 0 0 1-2.56-.421c-1.777-1.039-2.4-3.364-1.392-5.195.113-.202.322-.324.557-.261.455.13.925.192 1.396.187a.683.683 0 0 0 .668-.698.686.686 0 0 0-.631-.686c-.663-.042-1.28-.189-1.823-.585-1.662-1.21-1.938-3.448-.785-5.068 1.1-1.547 3.307-1.866 4.902-.56.007.005.054.054.06.06.314.234.858.105 1.035-.25.345-.697.895-1.164 1.182-1.379 2.266-1.695 5.166-1.446 6.984.747 1.87 2.256 1.566 5.546-.805 7.519-.315.261-.341.752-.076 1.068.252.3.704.353 1.012.113.808-.628 1.558-1.485 2.022-2.413a.59.59 0 0 1 .194-.232c.234-.144.534-.03.673.211.288.5.54 1.246.572 1.825.113 2.006-1.424 3.73-3.37 3.846-.459.019-.71.286-.712.74Z" fill="#fff" />
                <g clip-path="url(#b)">
                    <path d="M167.33 36.458c.028.914.72 1.604 1.61 1.604.889 0 1.581-.692 1.611-1.604l.183-6.029a.791.791 0 0 1 1.078-.713c.891.354 1.862.55 2.88.55 4.171.005 7.639-3.308 7.797-7.46.166-4.421-3.385-8.06-7.791-8.06-4.303 0-7.793 3.475-7.793 7.76 0 1.288.425 13.952.425 13.952Zm7.368-9.894a4.066 4.066 0 0 1-4.073-4.058 4.065 4.065 0 0 1 4.073-4.056 4.066 4.066 0 0 1 4.075 4.057 4.065 4.065 0 0 1-4.075 4.057ZM79.371 16.348c-.05-1.008-.793-1.58-1.596-1.58-.809 0-1.529.572-1.587 1.58l-.406 6.919c-.127 2.18-1.382 3.298-2.981 3.298-1.55 0-2.784-1.022-2.953-2.855l-.505-5.365c-.09-.96-.779-1.447-1.452-1.447l-.036.002-.035-.002c-.674 0-1.362.488-1.452 1.447l-.505 5.365c-.168 1.833-1.403 2.855-2.953 2.855-1.6 0-2.854-1.118-2.981-3.298l-.405-6.92c-.06-1.007-.78-1.578-1.589-1.578-.803 0-1.545.57-1.595 1.579l-.331 6.747c-.213 4.32 3.502 7.173 6.901 7.173a6.908 6.908 0 0 0 4.435-1.6.796.796 0 0 1 1.02 0 6.908 6.908 0 0 0 4.436 1.6c3.4 0 7.115-2.854 6.901-7.174l-.331-6.746Zm78.735-1.601c-4.305 0-7.793 3.474-7.793 7.76s3.488 7.76 7.793 7.76c4.306 0 7.794-3.474 7.794-7.76s-3.49-7.76-7.794-7.76Zm0 11.817a4.067 4.067 0 0 1-4.075-4.058c0-2.24 1.825-4.056 4.075-4.056a4.066 4.066 0 0 1 4.075 4.057 4.067 4.067 0 0 1-4.075 4.057ZM141.6 14.747c-4.305 0-7.794 3.474-7.794 7.76 0 .317.025 1.326.065 2.667-.029.803-.63 1.39-1.4 1.39-.774 0-1.302-.555-1.395-1.272l-.493-3.761-.005-.034-.003-.03c-.51-3.794-3.774-6.72-7.724-6.72-4.271 0-7.738 3.42-7.793 7.658v-.003s-.038 1.164.111 2.586c.087.816-.796 1.577-1.57 1.577-.899 0-1.556-.698-1.603-2.239l-.482-15.815v-.025c-.022-.722-.696-1.486-1.609-1.486-.926 0-1.582.764-1.605 1.51l-.481 15.816c-.045 1.481-1.004 2.24-2.09 2.24-1.098 0-2.034-.759-2.08-2.24l-.481-15.815c-.024-.788-.733-1.511-1.606-1.511-.794 0-1.58.617-1.608 1.51l-.508 16.655c-.025.809-.627 1.4-1.4 1.4-.776 0-1.303-.555-1.396-1.271l-.502-3.826c-.507-3.774-3.739-6.69-7.661-6.72-4.313-.034-7.857 3.467-7.857 7.76 0 4.286 3.489 7.76 7.793 7.76 1.813 0 3.481-.616 4.805-1.65a.928.928 0 0 1 .566-.191c1.166 0 1.31 1.84 4.251 1.84 1.183 0 2.418-.796 3.061-1.282a.702.702 0 0 1 .865.014c.606.487 1.871 1.269 3.741 1.269 1.628 0 2.701-.679 3.261-1.166a.714.714 0 0 1 .935 0 4.774 4.774 0 0 0 3.163 1.166c2.581 0 3.636-1.84 4.404-1.84.242 0 .402.065.588.202a7.78 7.78 0 0 0 4.789 1.638 7.78 7.78 0 0 0 4.803-1.65.927.927 0 0 1 .567-.191c1.166 0 1.44 1.84 3.811 1.84.729 0 1.25-.276 1.848-.618.057-.034.131.007.132.072.109 3.415.22 6.737.22 6.737.028.914.72 1.604 1.61 1.604.889 0 1.582-.692 1.61-1.604l.184-6.029a.794.794 0 0 1 1.091-.707 7.785 7.785 0 0 0 2.867.545c4.172.004 7.64-3.31 7.796-7.46.164-4.422-3.387-8.06-7.791-8.06ZM88.423 26.564a4.067 4.067 0 0 1-4.075-4.058c0-2.24 1.825-4.056 4.075-4.056s4.074 1.816 4.074 4.057c0 2.24-1.824 4.057-4.075 4.057Zm34.426 0a4.067 4.067 0 0 1-4.074-4.058c0-2.24 1.825-4.056 4.074-4.056a4.066 4.066 0 0 1 4.075 4.057 4.067 4.067 0 0 1-4.075 4.057Zm18.751 0a4.067 4.067 0 0 1-4.075-4.058c0-2.24 1.825-4.056 4.075-4.056s4.075 1.816 4.075 4.057c0 2.24-1.825 4.057-4.075 4.057Z" fill="#3DD2BA" />
                </g>
                <defs>
                    <clipPath id="b">
                        <path fill="#fff" transform="translate(56 6.38)" d="M0 0h126.545v32H0z" />
                    </clipPath>
                    <filter id="a" x="0" y="0" width="48" height="48" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                        <feFlood flood-opacity="0" result="BackgroundImageFix" />
                        <feColorMatrix in="SourceAlpha" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                        <feOffset dy="2" />
                        <feGaussianBlur stdDeviation="2" />
                        <feColorMatrix values="0 0 0 0 0.145098 0 0 0 0 0.196078 0 0 0 0 0.219608 0 0 0 0.1 0" />
                        <feBlend in2="BackgroundImageFix" result="effect1_dropShadow_7634_6626" />
                        <feBlend in="SourceGraphic" in2="effect1_dropShadow_7634_6626" result="shape" />
                    </filter>
                </defs>
            </svg>


            
                <form class="form" method="get" action="index.php">
                    <button>
                        <svg width="17" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="search">
                            <path d="M7.667 12.667A5.333 5.333 0 107.667 2a5.333 5.333 0 000 10.667zM14.334 14l-2.9-2.9" stroke="currentColor" stroke-width="1.333" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                    <input class="input" placeholder="Buscar anuncio..." name="filtro" required="" type="text">
                    <button class="reset" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </form>
            

            <?php if(isset($_SESSION['email'])):?>
                <div class="info_usuario">
                    <img src="fotosPerfil/<?= $_SESSION['foto'] ?>" alt="Error">
                    <h3 style="color: rgba(92,122,137,0.7);"><?= $_SESSION['nombre'] ?></h3>
                    <a href="util/logout.php">Cerrar sesion</a>
                    <a href="vista/insertarAnuncio.php"><svg width="26" height="27" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#a)" fill="#fff"><path opacity=".5" d="M13 1.5c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12Zm0-1c7.18 0 13 5.82 13 13s-5.82 13-13 13-13-5.82-13-13S5.82.5 13 .5Z"/><path d="M14 8.5a1 1 0 1 0-2 0v10a1 1 0 1 0 2 0v-10Z"/><path d="M18 12.5H8a1 1 0 1 0 0 2h10a1 1 0 1 0 0-2Z"/></g><defs><clipPath id="a"><path fill="#fff" transform="translate(0 .5)" d="M0 0h26v26H0z"/></clipPath></defs></svg>
                    Subir anuncio</a>
                </div>
            <?php else :?>
                <form action="index.php" method="post">
                    <h4>Inicia sesion</h4>
                    <input placeholder="Username" type="email" class="input_login" required="" name="email">
                    <input placeholder="Password" type="password" class="input_login" required="" name="password1">
                    <div class="submit_login">
                        <h6><a href="vista/registro.php">¿Aún no esta registrado?</a></h6>
                        <h6><button type="submit">Login</button></h6>
                    </div>
                </form>
            <?php endif;?>
        </header>
        <nav>
            <img src="img/logo.png" alt="">
            <ul>
                <li><a href="index.php">Anuncios</a></li>
                <?php if(!isset($_SESSION['email'])):?>
                    <li><a href="vista/login.php">Mis Anuncios</a></li>
                <?php endif;?>

                <?php if(isset($_SESSION['email'])):?>
                    <li><a href="vista/misAnuncios.php">Mis Anuncios</a></li>
                <?php endif;?>
                
                <?php if(!isset($_SESSION['email'])):?>
                    <li><a href="vista/login.php">Inicio sesion</a></li>
                <?php endif;?>

                <li><a href="vista/registro.php">Registro</a></li>
            </ul>
        </nav>
        <section class="leyenda">
            <div>
                <span><strong>Compra y vende artículos de segunda mano en Wallapop</strong></span>
                <span>una comunidad que lleva años generando impacto del bueno</span>
            </div>
        </section>

        <article>
            <section class="contenido">
                    <?php foreach ($anuncios as $anuncio):?>
                        
                            <div style="cursor:pointer;" onclick="window.location.href='vista/anuncio.php?id=<?= $anuncio->getId() ?>'">
                                <div class="anuncio"> 
                                    <img src="fotosAnuncio/<?= $anuncio->getFoto() ?>" alt="Hola">
                                    <div class="content_anuncio">
                                        <div class="info_anuncio">
                                            <h4><strong><?= $anuncio->getPrecio() ?>€</strong></h4>
                                            <h5><?= $anuncio->getTitulo() ?></h5>
                                        </div>
                                        <?php if(isset($_SESSION['id']) && $_SESSION['id']==$anuncio->getIdUsuario()):?>
                                            <div class="edit_delete">
                                                <a href="vista/modificarAnuncio.php?id=<?= $anuncio->getId() ?>"><svg width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 10L4 16V20H8L14 14M10 10L13 7L17 11L14 14M10 10L14 14M14 20H20V4H4V10" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg> </a>
                                                <a href="borrar.php?id=<?= $anuncio->getId() ?> "><svg width="25px" height="25px" viewBox="0 -0.5 21 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs></defs><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="Dribbble-Light-Preview" transform="translate(-179.000000, -360.000000)" fill="#000000"><g id="icons" transform="translate(56.000000, 160.000000)"><path d="M130.35,216 L132.45,216 L132.45,208 L130.35,208 L130.35,216 Z M134.55,216 L136.65,216 L136.65,208 L134.55,208 L134.55,216 Z M128.25,218 L138.75,218 L138.75,206 L128.25,206 L128.25,218 Z M130.35,204 L136.65,204 L136.65,202 L130.35,202 L130.35,204 Z M138.75,204 L138.75,200 L128.25,200 L128.25,204 L123,204 L123,206 L126.15,206 L126.15,220 L140.85,220 L140.85,206 L144,206 L144,204 L138.75,204 Z" id="delete-[#1487]"></path></g></g></g></svg></a>
                                            </div>
                                        <?php endif;?>
                                    </div>
                                </div>
                            </div>
                        
                    <?php endforeach;?>
            </section>
        </article> 
        
        <footer class="footer">
            <div class="waves">
                <div class="wave" id="wave1"></div>
                <div class="wave" id="wave2"></div>
                <div class="wave" id="wave3"></div>
                <div class="wave" id="wave4"></div>
            </div>
            <ul class="social-icon">
                <li class="social-icon__item"><a class="social-icon__link" href="#">
                        <ion-icon name="logo-facebook"></ion-icon>
                    </a></li>
                <li class="social-icon__item"><a class="social-icon__link" href="#">
                        <ion-icon name="logo-twitter"></ion-icon>
                    </a></li>
                <li class="social-icon__item"><a class="social-icon__link" href="#">
                        <ion-icon name="logo-linkedin"></ion-icon>
                    </a></li>
                <li class="social-icon__item"><a class="social-icon__link" href="#">
                        <ion-icon name="logo-instagram"></ion-icon>
                    </a></li>
            </ul>
            <ul class="menu">
                <li class="menu__item"><a class="menu__link" href="#">Home</a></li>
                <li class="menu__item"><a class="menu__link" href="#">Sobre nosotros</a></li>
                <li class="menu__item"><a class="menu__link" href="#">Servicios</a></li>
                <li class="menu__item"><a class="menu__link" href="#">Equipo</a></li>
                <li class="menu__item"><a class="menu__link" href="#">Contacto</a></li>

            </ul>
            <p>&copy;2023 Wallafelix | Todos los derechos reservados</p>
        </footer>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    </main>
</body>

</html>