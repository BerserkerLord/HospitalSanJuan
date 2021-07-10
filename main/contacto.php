        <?php include('head.html'); ?>
        <div> <!--Logo-->
            <h1 id="texto_bannerAseguradora" class="pb-1">Contacto</h1>
            <img src="../images/ubicacionBanner.jpg" alt="ubicacionBanner" class="img-fluid">
        </div>
        <?php include('navbar.html'); ?>
        <section class="container-fluid bg-light">
            <div class="row border-bottom">
                <div class="col-md-8">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d954337.6711688078!2d-100.8841748278412!3d20.876824925220735!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85d30b0edf37a705%3A0xb1feb84b181efe3!2sHospital%20General%20San%20Juan%20del%20R%C3%ADo!5e0!3m2!1ses!2smx!4v1617320343514!5m2!1ses!2smx"
                            style="width:100%; border:0;" height="450" allowfullscreen="" loading="lazy"></iframe>
                </div>
                <div class="col-md-4">
                    <div class="mx-auto">
                        <div class="d-flex flex-row">
                            <i class="icons fas fa-location-arrow p-1"></i>
                            <p class="nav">Luis Donaldo Colosio 422, México, 76804 San Juan del Río, Qro.</p>
                        </div>
                        <div class="d-flex flex-row">
                            <i class="icons fas fa-phone p-1"></i>
                            <p class="nav">Teléfono: 461 123 4567</p>
                        </div>
                        <div class="d-flex flex-row">
                            <i class="icons fas fa-phone p-1"></i>
                            <p class="nav">461 123 4567</p>
                        </div>
                        <div class="d-flex flex-row">
                            <i class="icons fas fa-link p-1"></i>
                            <p class="nav">hospitalsnjuan.com</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pt-4 pb-4 ps-4">
                <div class="col-md-7">
                    <h4 class="mb-3">Contactanos</h4>
                    <form action="../admin/comentario.php" method="POST" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="nombre" class="form-label">Nombre <span class="text-muted">(Requerido)</span></label>
                                <input type="text" name="nombre" class="form-control" id="nombre" placeholder="" value="" required>
                                <div class="invalid-feedback">
                                    El nombre es requerido
                                </div>
                            </div>
                            <div class="col-6">
                                <label for="email" class="form-label">Email <span class="text-muted">(Requerido)</span></label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="tu@ejemplo.com" required>
                                <div class="invalid-feedback">
                                    El email es requerido
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="asunto" class="form-label">Asunto</label>
                                <input type="text" name="asunto" class="form-control" id="asunto" required>
                            </div>
                            <div class="col-12">
                                <label for="mensaje" class="form-label">Mensaje <span class="text-muted">(Requerido)</span></label>
                                <textarea class="form-control" name="mensaje" id="mensaje" required rows="5"></textarea>
                                <div class="invalid-feedback">
                                    Escribe un Mensaje.
                                </div>
                            </div>
                            <button class="w-100 btn btn-primary btn-lg" type="submit">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <?php include('footer.html'); ?>