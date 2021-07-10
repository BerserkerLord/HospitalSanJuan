<main class="form-signin">
    <form action="../login/login.php?action=send_pass" method="post">

        <img src="../images/caduceo.png" class="rounded mx-auto d-block" alt="user_login_image" width="100">

        <h1 class="display-6">Recuperar contraseña</h1>

        <div class="form-floating mb-2">
            <input type="email" class="form-control" id="correo" name="correo">
            <label for="correo">Email</label>
        </div>

        <button class="w-100 btn btn-lg btn-primary" type="submit" name="enviar">Recuperar contraseña</button>
    </form>
</main>