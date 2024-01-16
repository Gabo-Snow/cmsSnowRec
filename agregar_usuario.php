<?php


include('includes/config.inc.php');
include('includes/database.inc.php');
include('includes/funciones.inc.php');

seguridad();

include('includes/logedHeader.inc.php');



if (isset($_POST['usuario'])) {

    $email = $_POST['email'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT email FROM usuario WHERE email = '$email'";
        $resultado = mysqli_query($conectar, $sql);
        if (mysqli_num_rows($resultado) > 0) {
            // Asignar el mensaje de error usando set_mensaje
            set_mensaje("El email " . $email . " ya se encuentra registrado, por favor ingresa otro");
        } else {

            if ($_SESSION['idusuario'] <> 1 && $_POST['rol'] == 1) {

                echo 'Solo Nika puede agregar administradores';

            } else {

                foreach ($_POST as $key => $value) {
                    if (is_string($value)) {
                        $_POST[$key] = trim($value, );
                    }
                }

                if ($stm = $conectar->prepare('INSERT INTO usuario (usuario, email, pass, activo) VALUES(?,?,?,?)')) {
                    $hashed = password_hash($_POST['pass'], PASSWORD_DEFAULT);
                    $stm->bind_param('ssss', $_POST['usuario'], $_POST['email'], $hashed, $_POST['activo']);
                    $stm->execute();

                    // Obtener el último id insertado
                    $last_id = $conectar->insert_id;

                    // Preparar la consulta para insertar en rol_usuario
                    $rol_id = $_POST['rol']; // O el valor que quieras asignar
                    $stm2 = $conectar->prepare('INSERT INTO rol_usuario (usuario_idusuario, rol_idrol) VALUES(?,?)');
                    $stm2->bind_param('ii', $last_id, $rol_id);
                    $stm2->execute();


                    // Preparar la consulta para insertar en el estado de la contraseña
                    $stm3 = $conectar->prepare('INSERT INTO acceso (usuario_idusuario) VALUES(?)');
                    $stm3->bind_param('i', $last_id);
                    $stm3->execute();



                    // Asignar el mensaje de éxito usando set_mensaje
                    set_mensaje("El nuevo usuario: " . $_POST['usuario'] . " ha sido agregado correctamente");
                    header('Location: usuarios.php');
                    die();

                } else {

                    echo 'No se pudo ejecutar la sentencia';

                }

            }




        }



    }

}

// Mostrar el mensaje usando get_mensaje
echo get_mensaje();




?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-9">

            <h1 class="display-1 text-center">Agregar Usuario</h1>

            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <form method="post">
                            <!-- Email input -->
                            <div class="form-outline mb-4">
                                <input type="email" id="email" name="email" class="form-control" required />
                                <label class="form-label" for="email">Email</label>
                            </div>
                            <!-- Usuario input -->
                            <div class="form-outline mb-4">
                                <input type="text" id="usuario" name="usuario" class="form-control" required />
                                <label class="form-label" for="usuario">Usuario</label>
                            </div>

                            <!-- Password input -->
                            <div class="form-outline mb-4">
                                <input type="password" id="pass" name="pass" class="form-control" required />
                                <label class="form-label" for="pass">Contraseña</label>
                            </div>
                            <!-- Seleccionar Usuairo Activo/Inactivo -->
                            <div class="form-outline mb-4">
                                <select name="activo" class="form-select" id="activo">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                            <div class="form-outline mb-4">
                                <select name="rol" class="form-select" id="rol">
                                    <option <?php
                                    if ($_SESSION['idusuario'] <> 1) {
                                        echo 'disabled';
                                    } else {
                                        echo '';
                                    }
                                    ?> value="1">Administrador</option>
                                    <option value="2">Moderador</option>
                                    <option value="4">Editor</option>
                                    <option value="3">Usuario</option>
                                </select>
                            </div>

                            <!-- Submit button -->
                            <button type="submit" class="btn btn-primary btn-block">Agregar Usuario</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php




include('includes/footer.inc.php');


?>