<?php


include('includes/config.inc.php');
include('includes/database.inc.php');
include('includes/funciones.inc.php');

include('includes/header.inc.php');



if (isset($_POST['email'])) {

  $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
  if ($email) {
    try {
      $stmt_usuario = $conectar->prepare('SELECT usuario.*, acceso.* FROM usuario LEFT JOIN acceso ON  acceso.usuario_idusuario = usuario.idusuario WHERE email = ?');
      $stmt_usuario->bind_param('s', $email);
      $stmt_usuario->execute();
      $resultado = $stmt_usuario->get_result();
      $usuario = $resultado->fetch_object();
      $stmt_usuario->close();

      if ($usuario->activo == 0) {

        echo 'Usuario inactivo por favor valídalo contacta con un administrador o con dios, si eres un usuario nuevo debes activar tu cuenta con el enlace enviado a tu email';

      } elseif ($usuario->bloqueado == 1) {

        echo 'Demasiados intentos fallidos, puedes recuperarla en enlace de abajo';

      } else {
        if (password_verify($_POST['pass'], $usuario->pass)) {
          $_SESSION['idusuario'] = $usuario->idusuario;
          $_SESSION['email'] = $usuario->email;
          $_SESSION['usuario'] = $usuario->usuario;

          set_mensaje("Bienvenido señor " . $_SESSION['usuario']); //agregar función para que cambie de título entre maestro, ingenmiero, doctor, etc. xd

          header('Location: dashboard.php'); 

          $stm_resetIntentos = $conectar->prepare('UPDATE acceso SET fallidos = 0 WHERE usuario_idusuario = ?');
          $stm_resetIntentos->bind_param('i', $_SESSION['idusuario']);
          $stm_resetIntentos->execute();


          die();

        } else {

          $stm_consultaFallido = $conectar->prepare('SELECT fallidos FROM acceso WHERE usuario_idusuario = ?');
          $stm_consultaFallido->bind_param('i', $usuario->idusuario);
          $stm_consultaFallido->execute();

          $resultado = $stm_consultaFallido->get_result();
          $fallidos = $resultado->fetch_assoc();

          if ($fallidos['fallidos'] == 10) {

            $stm_bloquear = $conectar->prepare('UPDATE acceso SET bloqueado = 1 WHERE usuario_idusuario = ?');
            $stm_bloquear->bind_param('i', $usuario->idusuario);
            $stm_bloquear->execute();

          }

          $fallidos['fallidos'] = $fallidos['fallidos'] + 1;


          $stm_intentoFallido = $conectar->prepare('UPDATE acceso SET fallidos = ? WHERE usuario_idusuario = ?');
          $stm_intentoFallido->bind_param('ii', $fallidos['fallidos'], $usuario->idusuario);
          $stm_intentoFallido->execute();

          echo 'Usuario o contraseña incorrecta';


        }

      }



    } catch (Exception $e) {

      echo 'Error: ' . $e->getMessage();
    }
  } else {

    echo 'El email no es válido';
  }
}




?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <form method="post">
        <!-- Email input -->
        <div class="form-outline mb-4">
          <input type="email" id="email" name="email" class="form-control" required />
          <label class="form-label" for="email">Email</label>
        </div>

        <!-- Password input -->
        <div class="form-outline mb-4">
          <input type="password" id="pass" name="pass" class="form-control"  />
          <label class="form-label" for="pass">Contraseña</label>
        </div>

        <!-- 2 column grid layout for inline styling -->
        <div class="row mb-4">
          <div class="col d-flex justify-content-center">
            <!-- Checkbox -->
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="form1Example3" checked />
              <label class="form-check-label" for="form1Example3"> Recuérdame</label>
            </div>
          </div>

          <div class="col">
            <!-- Simple link -->
            <a href="#!">Recupera tu contraseña</a>
          </div>
        </div>

        <!-- Submit button -->
        <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
      </form>
    </div>



  </div>
</div>

<?php


include('includes/footer.inc.php');


?>