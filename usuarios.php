<?php


include('includes/config.inc.php');
include('includes/database.inc.php');
include('includes/funciones.inc.php');

seguridad();

include('includes/logedHeader.inc.php');



if (isset($_GET['eliminar'])) {
    if ($stm1 =$conectar->prepare('DELETE FROM acceso WHERE usuario_idusuario = ?')){
        $stm1->bind_param('i', $_GET['eliminar']);
        $stm1->execute();

        $stm2 =$conectar->prepare('DELETE FROM rol_usuario WHERE usuario_idusuario = ?');
        $stm2->bind_param('i', $_GET['eliminar']);
        $stm2->execute();    
        
        
        $stm = $conectar->prepare('DELETE FROM usuario WHERE idusuario = ? ');       
        $stm->bind_param('i', $_GET['eliminar']);
        $stm->execute();

        set_mensaje("El usuario: " . $_GET['eliminar'] . " ha sido eliminado correctamente");
        header('Location: usuarios.php');
        die();

    } else {

        echo 'No se pudo ejecutar la sentencia';

    }


}


$sesion_id = $_SESSION['idusuario'];

$sql = "SELECT usuario.*, rol.*, acceso.* FROM usuario LEFT JOIN acceso ON acceso.usuario_idusuario = usuario.idusuario LEFT JOIN rol_usuario ON rol_usuario.usuario_idusuario = usuario.idusuario LEFT JOIN rol ON rol_usuario.rol_idrol = rol.idrol WHERE idusuario NOT IN (1, $sesion_id)";

if ($stm = $conectar->prepare($sql)) {
    $stm->execute();

    $resultado = $stm->get_result();


    if ($resultado->num_rows > 0) {

        ?>

        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="table-responsive">
                    <h1 class="display-1 text-center">Gestión de Usuarios</h1>

                    <table class="table table-striped table-hover table-bordered">
                        <tr>
                            <th>Id Usuario</th>
                            <th>Nombre de Usuario</th>
                            <th>Email</th>                            
                            <th>Rol</th>
                            <th>Estado Contraseña</th>
                            <th>Estado Usuario</th>
                            <th>Editar / Eliminar</th>
                        </tr>

                        <?php while ($record = mysqli_fetch_assoc($resultado)) { ?>
                            <tr>

                                <td>
                                    <?php echo $record['idusuario'] ?>
                                </td>
                                <td>
                                    <?php echo $record['usuario'] ?>
                                </td>
                                <td>
                                    <?php echo $record['email'] ?>
                                </td>
                                <td>
                                    <?php echo $record['rol'] ?>
                                </td>
                                <td>
                                    <?php
                                    if ($record['bloqueado'] == 0) {
                                        echo "Vigente";
                                    } elseif ($record['bloqueado'] == 1) {
                                        echo "Bloqueada";
                                    } ?>
                                </td>
                                <td>

                                    <?php

                                    if ($record['activo'] == 1) {
                                        echo "Activo";
                                    } elseif ($record['activo'] == 0) {
                                        echo "Inactivo";
                                    } ?>
                                </td>
                                <td><a type="button" class="btn btn-primary me-3"
                                        href="editar_usuario.php?id=<?php echo $record['idusuario']; ?>">Editar</a>
                                    <a type="button" class="btn btn-danger me-3"
                                        href="usuarios.php?eliminar=<?php echo $record['idusuario']; ?>"> Eliminar</a>
                                </td>

                            </tr>

                        <?php } ?>


                    </table>

                    <a href="agregar_usuario.php">Agregar Usuario </a>


                </div>
            </div>
        </div>

        <?php

    } else {

        echo 'No se encontraron usuarios';
    }

} else {
    echo 'No se puede ejecutar la sentencia';

}
$stm->close();


include('includes/footer.inc.php');


?>