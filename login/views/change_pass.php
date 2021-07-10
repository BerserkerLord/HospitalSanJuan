<main class="form-signin">
    <form action="../login/login.php?action=save_pass" method="post">

        <img src="../images/caduceo.png" class="rounded mx-auto d-block" alt="user_login_image" width="100">

        <h1 class="display-6">Establecer contraseña</h1>

        <div class="form-floating mb-2">
            <input type="password" class="form-control" id="correo" name="contrasena">
            <label for="correo">Nueva contraseña</label>
        </div>

        <button class="w-100 btn btn-lg btn-primary" type="submit" name="enviar">Reestablecer contraseña</button>

        <input type="hidden" name="correo" value='<?php echo $correo ?>'>
        <input type="hidden" name="token" value='<?php echo $token ?>'>
    </form>
</main>