<?php

    require 'config/config.php';
    require 'config/database.php';
    require 'clases/clienteFunciones.php';
    $db = new Database();
    $con = $db->conectar();

    $errors = [];

    if(!empty($_POST)){

        $usuario = trim($_POST['usuario']);
        $password = trim($_POST['password']);

        if(esNulo([$usuario, $password])){
            $errors[] = "Debe de llenar todos los campos";
        }
        if (count($errors) == 0){
        $errors[] = login($usuario, $password, $con);
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda online</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    
<header>
  <div class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand">
        <strong>Inicio</strong>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarHeader">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        </ul>

        <a href="checkout.php" class="btn btn-primary">
          Carrito<span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span>
        </a>
      </div>
    </div>
  </div>
</header>

<main class="form-login m-auto pt-4">

        <h2>Iniciar sesión</h2>

         <?php mostrarMensajes($errors); ?>

         <form class="row g-3" action="login.php" method="post" autocomplete="off">

         <div class="form-floating">
            <input class="form-control" type="text" name="usuario" id="usuario" placeholder="Usuario" requireda>
            <label for="usuario">Usuario</label>
         </div>

         <div class="form-floating">
            <input class="form-control" type="text" name="password" id="password" placeholder="Contraseña" requireda>
            <label for="password">Contraseña</label>
         </div>

         <div class="d-grid gap-3 col-12">
            <button type="submit" class="btn btn-outline-primary">Ingresar</button>
         </div>

         <hr>

         <div class="col-12">
            ¿No tiene cuenta? <a href="registro.php">Registrate</a>
         </div>

         </form>
        

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>





</body>
</html>