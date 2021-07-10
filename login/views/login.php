<script>
    window.onload = Toasty;
</script>
<main class="form-signin">
    <form action="../login/login.php?action=validate" method="post">

        <img src="../images/caduceo.png" class="rounded mx-auto d-block" alt="user_login_image" width="100">

        <h1 class="display-5">Ingresar</h1>

        <div class="form-floating">
            <input type="email" class="form-control" id="correo" name="correo">
            <label for="correo">Email</label>
        </div>

        <div class="form-floating">
            <input type="password" name="contrasena" class="form-control" id="contrasena">
            <label for="contrasena">Password</label>
        </div>

        <button class="w-100 btn btn-lg btn-primary" type="submit" name="enviar">Iniciar sesión</button>

        <a href="../login/login.php?action=forget">¿Olvidó su contraseña?</a>

        <div id="message" class="bg-dark position-relative bd-example-toasts">
            <div class="toast-container position-absolute p-3 top-0 start-50 translate-middle-x" id="toastPlacement" data-original-class="toast-container position-absolute p-3">
                <div class="toast fade show">
                    <div class="toast-header">
                        <svg class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
                            <rect width="100%" height="100%" fill="#007aff"></rect>
                        </svg>

                        <strong class="me-auto">Inicio de sesión</strong>
                        <small>Hospital San Juan</small>
                    </div>
                    <div class="toast-body">
                        <?php echo $mensaje; ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>