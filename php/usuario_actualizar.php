<?php
	require_once "../inc/session_start.php";
	require_once "main.php";
    // Almacenando id 
    $id=limpiar_cadena($_POST['usuario_id']);
    // Verificando usuario 
	$check_usuario=conexion();
	$check_usuario=$check_usuario->query("SELECT * FROM usuario WHERE usuario_id='$id'");

    if($check_usuario->rowCount()<=0){
    	echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El usuario no existe en el sistema
            </div>
        ';
        exit();
    }else{
    	$datos=$check_usuario->fetch();
    }
    $check_usuario=null;
    // Almacenando datos del administrador 
    $admin_usuario=limpiar_cadena($_POST['administrador_usuario']);
    $admin_clave=limpiar_cadena($_POST['administrador_clave']);
    // Verificando campos obligatorios del administrador 
    if($admin_usuario=="" || $admin_clave==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No ha llenado los campos que corresponden a su USUARIO o CLAVE
            </div>
        ';
        exit();
    }
    // Verificando integridad de los datos (admin)
    if(verificar_datos("[a-zA-Z0-9]{4,20}",$admin_usuario)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Su USUARIO no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if(verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$admin_clave)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Su CLAVE no coincide con el formato solicitado
            </div>
        ';
        exit();
    }
    // Verificando el administrador en la base de datos
    $check_admin=conexion();
    $check_admin=$check_admin->query("SELECT usuario_usuario,usuario_pass FROM usuario WHERE usuario_usuario='$admin_usuario' AND usuario_id='".$_SESSION['id']."'");
    if($check_admin->rowCount()==1){

    	$check_admin=$check_admin->fetch();

    	if($check_admin['usuario_usuario']!=$admin_usuario || !password_verify($admin_clave, $check_admin['usuario_pass'])){
    		echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                USUARIO o CLAVE de administrador incorrectos
	            </div>
	        ';
	        exit();
    	}
    }else{
    	echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                USUARIO o CLAVE de administrador incorrectos
            </div>
        ';
        exit();
    }
    $check_admin=null;
    //Almacenamiento de datos
    $nombre = limpiar_cadena($_POST['usuario_nom']);
    $apellido = limpiar_cadena($_POST['usuario_ape']);
    $usuario = limpiar_cadena($_POST['usuario_usuario']);
    $email = limpiar_cadena($_POST['usuario_email']);
    $clave1 = limpiar_cadena($_POST['usuario_clave_1']);
    $clave2 = limpiar_cadena($_POST['usuario_clave_2']);
    //Verficar campos obligatorios
    if($nombre == "" || $apellido == "" || $usuario == "" ){
        echo '<div class = "notification is-danger is-ligth">
            <strong>¡Ocurrió un error!</strong></br>
            No has completado los campos obligatorios.
        </div>';
        exit();
    }
    //Verficar integridad de los datos
    if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $nombre)){
        echo '<div class = "notification is-danger is-ligth">
                <strong>¡Ocurrió un error!</strong></br>
                El nombre no coincide con el formato solicitado.
            </div>';
        exit();
    }
    if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $apellido)){
        echo '<div class = "notification is-danger is-ligth">
                <strong>¡Ocurrió un error!</strong></br>
                El apellido no coincide con el formato solicitado.
            </div>';
        exit();
    }
    if (verificar_datos("[a-zA-Z0-9]{4,20}", $usuario)){
        echo '<div class = "notification is-danger is-ligth">
                <strong>¡Ocurrió un error!</strong></br>
                El usuario no coincide con el formato solicitado.
            </div>';
        exit();
    }
     //Verficar email (que no se repita)
     if($email != "" && $email != $datos['usuario_email']){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $check_email = conexion();
            $check_email = $check_email ->query("SELECT usuario_email FROM usuario WHERE usuario_email = '$email'");
            if($check_email->rowCount()>0){
                echo '<div class = "notification is-danger is-ligth">
                        <strong>¡Ocurrió un error!</strong></br>
                        El email ingresado ya se encuentra regristrado, por favor ingrese otro.
                    </div>';
                exit();
            }
            $check_email = null;
        } else {
            echo '<div class = "notification is-danger is-ligth">
                    <strong>¡Ocurrió un error!</strong></br>
                    El email ingresado no es válido, por favor vuelva a intentarlo.
                </div>';
            exit();
        }
    }
    //Verificar usuario (que no se repita)
    if ($usuario != $datos['usuario_usuario']) {
        $check_usuario = conexion();
        $check_usuario = $check_usuario ->query("SELECT usuario_usuario FROM usuario WHERE usuario_usuario = '$usuario'");
        if($check_usuario->rowCount()>0){
            echo '<div class = "notification is-danger is-ligth">
                    <strong>¡Ocurrió un error!</strong></br>
                    El usuario ingresado ya se encuentra en uso, por favor ingrese otro.
                </div>';
                exit();
            }
        //cerramos conexion a la base de datos para ahorra memoria
        $check_usuario = null;
    }
    //Verificar que las claves ingresadas sean iguales
    if ($clave1 !="" || $clave2 !=""){
        if (verificar_datos("[a-zA-Z0-9$@.-]{8,100}", $clave1) || verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave2)){
            echo '<div class = "notification is-danger is-ligth">
                    <strong>¡Ocurrió un error!</strong></br>
                    Las claves no coinciden con el formato solicitado. </br>
                    Debe tener un mínimo de 8 caracteres. 
                </div>';
            exit();
        } else{ 
            if($clave1 != $clave2){
                echo '<div class = "notification is-danger is-ligth">
                    <strong>¡Ocurrió un error!</strong></br>
                    Las claves ingresadas no coinciden, por favor vuelva a intentarlo.
                </div>';
                exit();
            } else {
                $clave=password_hash($clave1, PASSWORD_BCRYPT,["cost"=>10]);
            }
        }       
    } else {
        $clave = $datos['usuario_pass'];
    }
    //Actualizando datos
    $actualizar_usuario = conexion();
    $actualizar_usuario = $actualizar_usuario->prepare("UPDATE usuario SET usuario_nom = :nombre, usuario_ape = :apellido, 
                                            usuario_usuario = :usuario, usuario_pass = :clave, usuario_email = :email 
                                            WHERE usuario_id = :id");
    $marcadores = [
        ":nombre"=> $nombre,
        ":apellido"=> $apellido,
        ":usuario"=> $usuario,
        ":clave"=> $clave,
        ":email" => $email,
        "id" => $id
    ];

    if ($actualizar_usuario->execute($marcadores)){
        echo '<div class = "notification is-info is-ligth">
                <strong>¡Usuario Actualizado!</strong></br>
                El usuario se actualizó correctamente.
            </div>';
    } else {
        echo '<div class = "notification is-danger is-ligth">
            <strong>¡Ocurrió un error!</strong></br>
            No fue posible actualizar el usuario, por favor intente nuevamente.
        </div>';
    }
    $actualizar_usuario = null;