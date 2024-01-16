<?php
include('includes/config.inc.php');
include('includes/database.inc.php');
include('includes/funciones.inc.php');

seguridad();

include('includes/logedHeader.inc.php');

if (!empty($_POST['passNew'])) {
    foreach ($_POST as $key => $value) {
        if (is_string($value)) {
            $_POST[$key] = trim($value, );
        }
    }
    $stmt_usuario = $conectar->prepare('SELECT pass FROM usuario WHERE idusuario = ?');
    $stmt_usuario->bind_param('i', $_GET['id']);
    $stmt_usuario->execute();
    $resultado = $stmt_usuario->get_result();
    $verificar_usuario = $resultado->fetch_object();
    $stmt_usuario->close();
    if (password_verify($_POST['passOld'], $verificar_usuario->pass)) {

        if (isset($_POST['passNew']) && !empty($_POST['passNew']) && $_POST['passRep'] && !empty($_POST['passRep'])) {
            if ($_POST['passNew'] <> $_POST['passRep']) {
                echo 'La contraseña nueva no coincide';
            } else {

                if ($stm = $conectar->prepare('UPDATE usuario SET pass = ? WHERE idusuario = ? ')) {
                    $hashed = password_hash($_POST['passNew'], PASSWORD_DEFAULT);
                    $stm->bind_param('si', $hashed, $_GET['id']);
                    $stm->execute();
                    $stm->close();
                    set_mensaje("La contraseña ha sido modificado correctamente");

                    header('Location: cambiar_pass.php');
                    
                    die();
                } else {

                    echo 'No se pudo ejecutar la solicitud de cambio de contraseña';

                }
            }
        }
    } else {
        echo 'La contraseña anterior no coincide con la almacenada';
    }
} 
if (isset($_GET['id'])) {

    if ($stm = $conectar->prepare('SELECT pass FROM usuario WHERE idusuario = ?')) {
        $stm->bind_param('i', $_GET['id']);
        $stm->execute();
        $resultado = $stm->get_result();
        $usuario = $resultado->fetch_assoc();
        var_dump($usuario);
        if ($usuario) {
            ?>
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-9">
                        <h1 class="display-1 text-center">Cambiar Contraseña</h1>
                        <div class="container mt-5">
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <form method="post" class="row gx-3">

                                        <!-- Password input -->
                                        <div class="form-outline mb-4">
                                            <input type="password" id="passOld" name="passOld" class="form-control active" />
                                            <label class="form-label" for="passOld"> Tu contraseña anterior</label>
                                        </div>
                                        <!-- Password input -->
                                        <div class="form-outline mb-4">
                                            <input type="password" id="passNew" name="passNew" class="form-control active " />
                                            <label class="form-label" for="passNew">Tu nueva contraseña</label>
                                        </div>
                                        <!-- Password input -->
                                        <div class="form-outline mb-4">
                                            <input type="password" id="passRep" name="passRep" class="form-control active"
                                                oninput="validarContraseñas('passNew', 'passRep', 'contraseña')" />
                                            <label class="form-label" for="passRep">Repite tu nueva contraseña</label>
                                        </div>
                                        <!-- Submit button -->
                                        <div class="form-outline mb-4">
                                            <button type="submit" class="btn btn-primary btn-block">Cambiar Contraseña</button>
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
<script>
    document.getElementById("passNew").oninput = function () {
        validarContraseñas("passNew", "passRep");
    };
    document.getElementById("passRep").oninput = function () {
        validarContraseñas("passNew", "passRep");
    };
</script>