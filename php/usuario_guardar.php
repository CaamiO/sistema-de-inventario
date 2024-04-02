<?php
    require_once "main.php";
    // Almacenando datos 
    $nombre=limpiar_cadena($_POST['usuario_nom']);
    $apellido=limpiar_cadena($_POST['usuario_ape']);
    $usuario=limpiar_cadena($_POST['usuario_usuario']);
    $email=limpiar_cadena($_POST['usuario_email']);
    $clave_1=limpiar_cadena($_POST['usuario_clave_1']);
    $clave_2=limpiar_cadena($_POST['usuario_clave_2']);
    // Verificando campos obligatorios
    if($nombre=="" || $apellido=="" || $usuario=="" || $clave_1=="" || $clave_2==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                No has llenado todos los campos obligatorios, por favor intente nuevamente.
            </div>
        ';
        exit();
    }
    // Verificando integridad de los datos 
    if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                El nombre no coincide con el formato solicitado.
            </div>
        ';
        exit();
    }
    if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                El APELLIDO no coincide con el formato solicitado.
            </div>
        ';
        exit();
    }
    if(verificar_datos("[a-zA-Z0-9]{4,20}",$usuario)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                El usuario no coincide con el formato solicitado.
            </div>
        ';
        exit();
    }
    if(verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_1) || verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_2)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                Las claves o una de ellas no coincide con el formato solicitado.
            </div>
        ';
        exit();
    }
    // Verificando email 
    if($email!=""){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $check_email=conexion();
            $check_email=$check_email->query("SELECT usuario_email FROM usuario WHERE usuario_email='$email'");
            if($check_email->rowCount()>0){
                echo '
                    <div class="notification is-danger is-light">
                        <strong>¡Ocurrió un error!</strong><br>
                        El correo electrónico ya se encuentra registrado, por favor ingrese otro.
                    </div>
                ';
                exit();
            }
            $check_email=null;
        }else{
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error!</strong><br>
                    El correo electrónico ingresado no es válido, por favor intente nuevamente. 
                </div>
            ';
            exit();
        } 
    }
    // Verificando usuario 
    $check_usuario=conexion();
    $check_usuario=$check_usuario->query("SELECT usuario_usuario FROM usuario WHERE usuario_usuario='$usuario'");
    if($check_usuario->rowCount()>0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                El usuario ya se encuentra registrado, por favor ingrese otro.
            </div>
        ';
        exit();
    }
    $check_usuario=null;
    // Verificando claves 
    if($clave_1!=$clave_2){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                Las claves ingresadas no coinciden, por favor intente nuevamente.
            </div>
        ';
        exit();
    }else{
        $clave=password_hash($clave_1,PASSWORD_BCRYPT,["cost"=>10]);
    }
    // Guardando datos
    $guardar_usuario=conexion();
    $guardar_usuario=$guardar_usuario->prepare("INSERT INTO usuario(usuario_nom,usuario_ape,usuario_usuario,usuario_pass,usuario_email) VALUES(:nombre,:apellido,:usuario,:clave,:email)");

    $marcadores=[
        ":nombre"=>$nombre,
        ":apellido"=>$apellido,
        ":usuario"=>$usuario,
        ":clave"=>$clave,
        ":email"=>$email
    ];

    $guardar_usuario->execute($marcadores);
    if($guardar_usuario->rowCount()==1){
        echo '
            <div class="notification is-info is-light">
                <strong>¡Usuario Registrado!</strong><br>
                El usuario se registró correctamente.
            </div>
        ';
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                No fue posible registrar el usuario, por favor intente nuevamente.
            </div>
        ';
    }
    $guardar_usuario=null;