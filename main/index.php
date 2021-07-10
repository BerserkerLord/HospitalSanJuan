        <?php include('head.html'); ?>
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel"> <!--Carrusel-->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner img-fluid">
                <div class="carousel-item active">
                    <img src="../images/banner1.jpg" class="d-block w-100" alt="Banner 1">
                </div>
                <div class="carousel-item">
                    <img src="../images/banner2.jpg" class="d-block w-100" alt="Banner 2">
                </div>
                <div class="carousel-item">
                    <img src="../images/banner3.jpg" class="d-block w-100" alt="Banner 3">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <?php include('navbar.html'); ?>
        <section class="container-fluid"> <!--Sección-->
            <div class="bg-light text-dark">
                <div class="row presentation">
                    <div class="col-md-7">
                        <h2>Cuidamos tu Salud</h2>
                        <p>
                            Nuestro staff médico son profesionales agrupados en consejos o asociaciones de su 
                            especialidad, las que los certifican al término de su especialidad y después cada 
                            cinco años sobre la base de su actualización por congresos, cursos, docencia y/o 
                            publicaciones científicas, es decir nunca dejan de estar al día, para estar vigentes 
                            académica y científicamente.
                        </p>
                        <p>
                            En el Hospital San Juan nos comprometemos a brindar servicios médicos de 
                            la más alta calidad y eficiencia para lograr la completa satisfacción de nuestros clientes.
                        </p>
                    </div>
                    <div class="col-md-5">
                        <img src="../images/hospital1.jpg" alt="hospital1" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="row text-light">
                <div id="urgencias" class="col-md-4 presentation">
                    <h2>Urgencias 24 Horas.</h2>
                    <p>
                        El servicio brinda una atención integral y de alta calidad en áreas de diagnóstico, 
                        medicina general, de especialidad, urgencias vitales, pequeñas cirugías, así como servicios 
                        básicos de enfermería.
                    </p>
                </div>
                <div id="ambulancia" class="col-md-4 presentation">
                    <h2>Ambulancia 24 Horas.</h2>
                    <p>
                        Contamos con el servicio de Ambulancia, destinada al servicio programado de pacientes que 
                        por su estado de salud requieren traslados a unidades hospitalarias con los mejores estándares 
                        de seguridad los 365 día del año.
                    </p>
                </div>
                <div id="farmacia" class="col-md-4 presentation">
                    <h2>Farmacia 24 horas.</h2>
                    <p>
                        En el Hospital San Juan estamos comprometidos con la sociedad a proveer productos 
                        y servicios para la salud y cuidado personal de excelente calidad y amplio surtido.
                    </p>
                </div>
            </div>
            <div class="bg-light text-dark">
                <div class="row presentation">
                    <div class="col-md-7">
                        <img src="../images/inbursa.jpg" alt="Aseguradora Inbursa" class="img-fluid">
                        <img src="../images/seguros_atlas.png" alt="Seguros Atlas" class="img-fluid">
                    </div>
                    <div class="col-md-5">
                        <h2>Convenios con aseguradoras</h2>
                        <p>
                            El Hospital San Juan tiene entre sus compromisos buscar bienestar y tranquilidad 
                            para los pacientes por ello se han establecido convenios con las principales Compañías Aseguradoras.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <?php include('footer.html') ?>
