<?php


include('includes/config.inc.php');
include('includes/database.inc.php');
include('includes/funciones.inc.php');

seguridad();

include('includes/logedHeader.inc.php');




if (isset($_POST['usuario'])) {

    foreach ($_POST as $key => $value) {
        if (is_string($value)) {
            $_POST[$key] = trim($value, );
        }
    }

    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);



    if ($stm = $conectar->prepare('UPDATE usuario SET usuario = ? WHERE idusuario = ?')) {
        $stm->bind_param('si', $_POST['usuario'], $_GET['id']);
        $stm->execute();
        $stm->close();


        set_mensaje("El usuario: " . $_GET['id'] . " ha sido editado correctamente");
        header('Location: usuarios.php');



    } else {

        echo 'No se pudo ejecutar la solicitud de cambio de usuario';

    }

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT email FROM usuario WHERE email = '$email'";
        $resultado = mysqli_query($conectar, $sql);
        if (mysqli_num_rows($resultado) > 0 && $_SESSION['email'] <> $email) {

            
            // Asignar el mensaje de error usando set_mensaje
            set_mensaje("El email " . $email . " ya se encuentra registrado, por favor ingresa otro");
        } else {

            foreach ($_POST as $key => $value) {
                if (is_string($value)) {
                    $_POST[$key] = trim($value, );
                }
            }

            if ($stmEmail = $conectar->prepare('UPDATE usuario SET email = ? WHERE idusuario = ?')) {               
                $stmEmail->bind_param('si', $_POST['email'], $_GET['id']);
                $stmEmail->execute();
                $stmEmail->close();  

                // Asignar el mensaje de éxito usando set_mensaje
                set_mensaje("El email del usuario: " . $_POST['usuario'] . " ha sido modificado correctamente");
                header('Location: usuarios.php');
                die();

            } else {

                echo 'No se pudo cambiar email';

            }

        }




    }



} else {


}














if (isset($_GET['id'])) {

    if ($stm = $conectar->prepare('SELECT usuario.*, rol.*, acceso.* FROM usuario LEFT JOIN acceso ON acceso.usuario_idusuario = usuario.idusuario LEFT JOIN rol_usuario ON rol_usuario.usuario_idusuario = usuario.idusuario LEFT JOIN rol ON rol_usuario.rol_idrol = rol.idrol WHERE idusuario = ?')) {
        $stm->bind_param('i', $_GET['id']);
        $stm->execute();

        $resultado = $stm->get_result();
        $usuario = $resultado->fetch_assoc();

        if ($usuario) {

            ?>


            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-9">

                        <h1 class="display-1 text-center">Editar Mi Usuario</h1>
                        <div class="container mt-5">
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <form method="post" class="row gx-3">
                                        <!-- Email input -->
                                        <div class="form-outline mb-4">
                                            <input type="email" id="email" name="email" class="form-control active"
                                                value="<?php echo $usuario['email'] ?>" readonly />
                                            <label class="form-label" for="email">Email</label>
                                        </div>
                                        <!-- Botón para habilitar el email -->
                                        <div class="form-outline mb-4">
                                            <button type="button" id="habilitar-email" class="btn btn-secondary btn-block"
                                                onclick="habilitarField('habilitar-email', 'email', 'Email')">
                                                Cambiar Email
                                            </button>
                                        </div>
                                        <!-- Usuario input -->
                                        <div class="form-outline mb-4">
                                            <input type="text" id="usuario" name="usuario" class="form-control active"
                                                value="<?php echo $usuario['usuario'] ?> " />
                                            <label class="form-label" for="usuario">Nombre de Usuario</label>
                                        </div>
                                        <!-- Botón para cambiar el de usuario -->
                                        <div class="form-outline mb-4">
                                            <button type="submit" class="btn btn-primary btn-block">Editar Usuario</button>
                                        </div>
                                    </form>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <?php

        }

        $stm->close();
        die();

    } else {
        echo 'No se pudo ejecutar la sentencia';

    }

} else {
    echo 'No se ha seleccionado ningún usuario';
    die();
}




include('includes/footer.inc.php');


?>

<!-- Estilo para el botón -->
<style>
    #habilitar-email {
        color: white;
        /* Cambia el color del texto a blanco */
    }
</style>