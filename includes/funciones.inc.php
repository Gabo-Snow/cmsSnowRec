<?php

function seguridad()
{
    if (!isset($_SESSION['idusuario'])) {
        echo "Por favor inicia sesión para entrar aquí";
        header('Location: /cms');
        die();

    }
}

function logeado()
{
    if (!isset($_SESSION['idusuario'])) {
        echo '<a type="button" class="btn btn-primary me-3" href="/cms/login.php">
        Iniciar Sesión
        </a>';
    } else {

        echo '<a type="button" class="btn btn-primary me-3" href="/cms/dashboard.php">
        Panel
        </a>';
    }

}

function set_mensaje($mensaje)
{ {
        $_SESSION['mensaje'] = $mensaje;
    }
}

function get_mensaje()
{ {
        if (isset($_SESSION['mensaje'])) {

            echo '<p>' . $_SESSION['mensaje'] . '</p> <hr>';
            unset($_SESSION['mensaje']);
        }

    }
}



?>

<script>
    function resetearParametros() {
        // Obtener los elementos del formulario
        var fallidos = document.getElementById("fallidos");
        var acceso = document.getElementById("acceso");
        // Cambiar los valores de los elementos
        fallidos.value = 0;
        acceso.value = "Vigente";
    }


</script>
<script>

    // Función para habilitar o deshabilitar la edición de un campo
    function habilitarField(idBoton, idCampo, nombreCampo) {
        // Obtener el botón y el campo por su id
        var boton = document.getElementById(idBoton);
        var campo = document.getElementById(idCampo);

        // Comprobar si el campo está deshabilitado
        if (campo.hasAttribute("readonly")) {
            // Habilitar el campo quitando el atributo readonly
            campo.removeAttribute("readonly");
            // Cambiar el texto y el color del botón
            boton.innerHTML = "Deshabilitar " + nombreCampo;
            boton.classList.remove("btn-secondary");
            boton.classList.add("btn-warning");
        } else {
            // Deshabilitar el campo añadiendo el atributo readonly
            campo.setAttribute("readonly", true);
            // Cambiar el texto y el color del botón
            boton.innerHTML = "Habilitar " + nombreCampo;
            boton.classList.remove("btn-warning");
            boton.classList.add("btn-secondary");
        }
    }

    // Función para validar que las contraseñas coincidan
    function validarContraseñas(idNueva, idConfirmacion) {
        // Obtener los valores de los campos de contraseña
        var nueva = document.getElementById(idNueva);
        var confirmacion = document.getElementById(idConfirmacion);

        // Comparar los valores de las contraseñas
        if (nueva.value !== confirmacion.value) {
            // Si no coinciden, asignar un mensaje de error al campo de confirmación
            confirmacion.setCustomValidity("Las contraseñas no coinciden");
        } else {
            // Si coinciden, borrar el mensaje de error del campo de confirmación
            confirmacion.setCustomValidity("");
        }
        // Comprobar la validez del campo de confirmación y mostrar el mensaje si es necesario
        confirmacion.reportValidity();
    }



</script>