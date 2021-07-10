<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Hospital San Juan</title>
        <meta name="author" content="Dario Zarate">
        <meta name="description" content="Hospital San Juan">
        <meta name="keywords" content="hospital san juan doctores especialidad">
        <link href="../css/bootstrap/css/bootstrap.css" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/css/bootstrap.min.css" integrity="sha384-DhY6onE6f3zzKbjUPRc2hOzGAdEf4/Dz+WJwBvEYL/lkkIsI3ihufq9hk9K4lVoK" crossorigin="anonymous">
        <link href="../css/main.css" rel="stylesheet">
        <link href="../css/fontawesome/css/all.css" rel="stylesheet">
        <link href="../css/features.css" rel="stylesheet">
        <script src="../css/bootstrap/js/bootstrap.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Menú</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Inicio</a></li>
                        <?php
                            if($sistema -> validarRoles('Administrador')){
                                include('menu_administrador.php');
                            } 
                            if($sistema -> validarRoles('Doctor')){
                                include('menu_doctor.php');
                            } 
                         ?>
                        <li class="nav-item">
                          <a class="nav-link" href="../login/login.php?action=logout" tabindex="-1">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav> 
        
    
       
        

