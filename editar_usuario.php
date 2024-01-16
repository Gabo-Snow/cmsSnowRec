<?php


include('includes/config.inc.php');
include('includes/database.inc.php');
include('includes/funciones.inc.php');

seguridad();

include('includes/logedHeader.inc.php');


if (isset($_POST['usuario'])) {


    if ($_SESSION['idusuario'] <> 1 && $_POST['rol'] == 1) {

        echo 'Solo dios puede agregar administradores';

    } else {

        $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $activo = filter_input(INPUT_POST, 'activo', FILTER_SANITIZE_NUMBER_INT);
        $rol = filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_NUMBER_INT);
        $fallidos = filter_input(INPUT_POST, 'fallidos', FILTER_SANITIZE_NUMBER_INT);
        $acceso = filter_input(INPUT_POST, 'acceso', FILTER_SANITIZE_STRING);
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        foreach ($_POST as $key => $value) {
            if (is_string($value)) {
                $_POST[$key] = trim($value, );
            }
        }


        if (
            $stm = $conectar->prepare('UPDATE usuario u
        JOIN rol_usuario ru ON u.idusuario = ru.usuario_idusuario
        JOIN acceso a ON u.idusuario = a.usuario_idusuario
        SET u.usuario = ? , u.email = ? , u.activo = ? , ru.rol_idrol = ? , a.fallidos = ? , a.bloqueado = ?
        WHERE u.idusuario = ? ')
        ) {

            $bloqueo = ($_POST['acceso'] == "Vigente") ? 0 : 1;

            $stm->bind_param('sssiiii', $_POST['usuario'], $_POST['email'], $_POST['activo'], $_POST['rol'], $_POST['fallidos'], $bloqueo, $_GET['id']);
            $stm->execute();
            $stm->close();

            if (isset($_POST['pass']) && !empty($_POST['pass'])) {

                if ($stm = $conectar->prepare('UPDATE usuario SET pass = ? WHERE idusuario = ? ')) {
                    $hashed = password_hash($_POST['pass'], PASSWORD_DEFAULT);
                    $stm->bind_param('si', $hashed, $_GET['id']);
                    $stm->execute();
                    $stm->close();

                } else {

                    echo 'No se pudo ejecutar la solicitud de cambio de contraseña';

                }

            }

            set_mensaje("El usuario: " . $_GET['id'] . " ha sido editado correctamente");
            header('Location: usuarios.php');



        } else {

            echo 'No se pudo ejecutar la solicitud de cambio de usuario';

        }


    }





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

                        <h1 class="display-1 text-center">Editar Usuario</h1>



                        <div class="container mt-5">
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <form method="post" class="row gx-3">
                                        <!-- Email input -->
                                        <div class="form-outline mb-4">
                                            <input type="email" id="email" name="email" class="form-control active"
                                                value="<?php echo $usuario['email'] ?>" />
                                            <label class="form-label" for="email">Email</label>
                                        </div>
                                        <!-- Usuario input -->
                                        <div class="form-outline mb-4">
                                            <input type="text" id="usuario" name="usuario" class="form-control active"
                                                value="<?php echo $usuario['usuario'] ?> " />
                                            <label class="form-label" for="usuario">Nombre de Usuario</label>
                                        </div>
                                        <!-- Password input -->
                                        <div class="form-outline mb-4">
                                            <input type="password" id="pass" name="pass" class="form-control" />
                                            <label class="form-label" for="pass">Contraseña</label>
                                        </div>
                                        <!-- Seleccionar Usuairo Activo/Inactivo -->
                                        <div class="form-outline mb-4">
                                            <label class="form-label small" for="activo">Estado Usuario</label>
                                            <select name="activo" class="form-select" id="activo">
                                                <option <?php echo ($usuario['activo']) ? "selected" : ""; ?> value="1">Activo
                                                </option>
                                                <option <?php echo ($usuario['activo']) ? "" : "selected"; ?> value="0">Inactivo
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label small" for="rol">Rol</label>
                                            <select name="rol" class="form-select" id="rol">
                                                <option <?php echo ($usuario['idrol'] == 1) ? "selected" : "";
                                                if ($_SESSION['idusuario'] <> 1) {
                                                    echo 'disabled';
                                                } else {
                                                    echo '';
                                                }
                                                ?> value="1">Administrador</option>
                                                <option <?php echo ($usuario['idrol'] == 2) ? "selected" : ""; ?> value="2">
                                                    Moderador</option>
                                                <option <?php echo ($usuario['idrol'] == 4) ? "selected" : ""; ?> value="4">Editor
                                                </option>
                                                <option <?php echo ($usuario['idrol'] == 3) ? "selected" : ""; ?> value="3">
                                                    Usuario</option>
                                            </select>
                                        </div>
                                        <div class="form-outline mb-4">
                                            <input type="text" id="fallidos" name="fallidos" class="form-control active"
                                                value="<?php echo $usuario['fallidos'] ?>" readonly />
                                            <label class="form-label" for="fallidos">Intentos Fallidos</label>
                                        </div>
                                        <div class="form-outline mb-4">
                                            <input type="text" id="acceso" name="acceso" class="form-control active" value="<?php

                                            if ($usuario['bloqueado'] == 0) {
                                                echo 'Vigente';
                                            } elseif ($usuario['bloqueado'] == 1) {
                                                echo 'Bloqueada';
                                            }

                                            ?>" readonly />
                                            <label class="form-label" for="acceso">Estado contraseña</label>
                                        </div>
                                        <div class="form-outline mb-4">
                                            <button type="button" id="resetear" name="resetear" class="btn btn-secondary btn-block"
                                                onclick="resetearParametros()">Resetear Acceso</button>
                                        </div>
                                        <!-- Submit button -->
                                        <div class="form-outline mb-4">
                                            <button type="submit" class="btn btn-primary btn-block">Editar Usuario</button>
                                        </div>
                                </div>


                                </form>
                            </div>



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