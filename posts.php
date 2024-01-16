<?php


include('includes/config.inc.php');
include('includes/database.inc.php');
include('includes/funciones.inc.php');

seguridad();

include('includes/logedHeader.inc.php');


var_dump($_SESSION);

?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <h1 class="display-1">Panel</h1>
        <a href="usuario.php">Gestión de Usuarios</a>
        <a href="posts.php">Posts de Usuarios</a>
        
</div>
</div>

<?php


include('includes/footer.inc.php');


?>