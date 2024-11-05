<?php

    require 'config/config.php';
    require 'config/database.php';
    require 'clases/clienteFunciones.php';
    $db = new Database();
    $con = $db->conectar();

    $errors = [];

    if(!empty($_POST)){

        $nombres = trim($_POST['nombres']);
        $apellidos = trim($_POST['apellidos']);
        $email = trim($_POST['email']);
        $telefono = trim($_POST['telefono']);
        $dni = trim($_POST['dni']);
        $usuario = trim($_POST['usuario']);
        $password = trim($_POST['password']);
        $repassword = trim($_POST['repassword']);

        if(esNulo([$nombres, $apellidos, $email, $telefono, $dni, $usuario, $password, $repassword])){
            $errors[] = "Debe de llenar todos los campos";
        }

        if(!esEmail($email)){
            $errors[] = "El correo no es valido";
        }

        if(!validaPassword($password, $repassword)){
            $errors[] = "Contraseñas diferentes";
        }

        if(usuarioExiste($usuario, $con)){
            $errors[] = "$usuario ya existe";
        }

        if(emailExiste($email, $con)){
            $errors[] = "$email ya existe";
        }

        if(count($errors) == 0){

        $id = registraCliente([$nombres, $apellidos, $email, $telefono, $dni], $con);
        if($id>0){
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $token = generarToken();
            if(!registraUsuario([$usuario, $pass_hash, $token, $id], $con)){
                $errors[] = "Error al registrar al usuario";
            }
        } else{
            $errors[] = "Error al registrar al cliente";
        }
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
            <li class="nav-item">
                <a href="#" class="nav-link active">Catalogo</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">Contacto</a>
            </li>
        </ul>

        <a href="checkout.php" class="btn btn-primary">
          Carrito<span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span>
        </a>
      </div>
    </div>
  </div>
</header>

<main>
    <div class="container">
        <h2>Datos del cliente</h2>

        <?php mostrarMensajes($errors); ?>

        <form class="row g-3" action="registro.php" method="post" autocomplete="off">
            <div class="col-md-6">
                <label for="nombres"><span class="text-danger">*</span>Nombres</label>
                <input type="text" name="nombres" id="nombres" class="form-control" requireda>
            </div>
            <div class="col-md-6">
                <label for="apellidos"><span class="text-danger">*</span>Apellidos</label>
                <input type="text" name="apellidos" id="apellidos" class="form-control" requireda>
            </div>
            <div class="col-md-6">
                <label for="email"><span class="text-danger">*</span>Correo electronico</label>
                <input type="email" name="email" id="email" class="form-control" requireda>
                <span id="validaEmail" class="text-danger" ></span>
            </div>
            <div class="col-md-6">
                <label for="telefono"><span class="text-danger">*</span>Telefono</label>
                <input type="tel" name="telefono" id="telefono" class="form-control" requireda>
            </div>
            <div class="col-md-6">
                <label for="dni"><span class="text-danger">*</span>DNI</label>
                <input type="text" name="dni" id="dni" class="form-control" requireda>
            </div>
            <div class="col-md-6">
                <label for="usuario"><span class="text-danger">*</span>Usuario</label>
                <input type="text" name="usuario" id="usuario" class="form-control" requireda>
                <span id="validaUsuario" class="text-danger" ></span>
            </div>
            <div class="col-md-6">
                <label for="password"><span class="text-danger">*</span>Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" requireda>
            </div>
            <div class="col-md-6">
                <label for="repassword"><span class="text-danger">*</span>Repetir contraseña</label>
                <input type="password" name="repassword" id="repassword" class="form-control" requireda>
            </div>

            <i><b>Nota:</b> Los campos con asterisco son obligatorios</i>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

<script>
    let txtUsuario = document.getElementById('usuario')
    txtUsuario.addEventListener("blur", function(){
        existeUsuario(txtUsuario.value)
    }, false)

    let txtEmail = document.getElementById('email')
    txtEmail.addEventListener("blur", function(){
        existeEmail(txtEmail.value)
    }, false)

    function  existeUsuario(usuario){
        let url = "clases/clienteAjax.php"
        let formData = new FormData()
        formData.append("action", "existeUsuario")
        formData.append("usuario", usuario)

        fetch(url, {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {

            if(data.ok){
                document.getElementById('usuario').value = ''
                document.getElementById('validaUsuario').innerHTML = 'Usuario no disponible'
            } else{
                document.getElementById('validaUsuario').innerHTML = ''
            }

        })

    }

    function  existeEmail(email){
        let url = "clases/clienteAjax.php"
        let formData = new FormData()
        formData.append("action", "existeEmail")
        formData.append("email", email)

        fetch(url, {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {

            if(data.ok){
                document.getElementById('email').value = ''
                document.getElementById('validaEmail').innerHTML = 'email no disponible'
            } else{
                document.getElementById('validaEmail').innerHTML = ''
            }

        })

    }
</script>   



</body>
</html>