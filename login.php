<?php


include('includes/config.inc.php');
include('includes/database.inc.php');
include('includes/funciones.inc.php');

include('includes/header.inc.php');

var_dump($_POST);

?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <form method="post">
                <!-- Email input -->
                <div class="form-outline mb-4">
                    <input type="email" id="email" name="email" class="form-control" />
                    <label class="form-label" for="email">Email</label>
                </div>

                <!-- Password input -->
                <div class="form-outline mb-4">
                    <input type="password" id="pass" name="pass" class="form-control" />
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