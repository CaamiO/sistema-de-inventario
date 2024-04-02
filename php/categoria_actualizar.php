<?php
    require_once "main.php";
    // Almacenando id 
    $id=limpiar_cadena($_POST['categoria_id']);
    // Verificando categoria
	$check_categoria=conexion();
	$check_categoria=$check_categoria->query("SELECT * FROM categoria WHERE categoria_id='$id'");

    if($check_categoria->rowCount()<=0){
    	echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                La categoría no existe en el sistema.
            </div>
        ';
        exit();
    }else{
    	$datos=$check_categoria->fetch();
    }
    $check_categoria=null;
    // Almacenando datos 
    $nombre=limpiar_cadena($_POST['categoria_nom']);
    $ubicacion=limpiar_cadena($_POST['categoria_descripcion']);
    /*== Verificando campos obligatorios ==*/
    if($nombre==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                No has llenado todos los campos obligatorios, por favor intente nuevamente.
            </div>
        ';
        exit();
    }
    //Verificando integridad de los datos 
    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}",$nombre)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                El nombre no coincide con el formato solicitado.
            </div>
        ';
        exit();
    }

    if($ubicacion!=""){
        if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}",$ubicacion)){
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error!</strong><br>
                    La descripción no coincide con el formato solicitado.
                </div>
            ';
            exit();
        }
    }
    // Verificando nombre
    if($nombre != $datos['categoria_nom']){
        $check_nombre=conexion();
        $check_nombre=$check_nombre->query("SELECT categoria_nom FROM categoria WHERE categoria_nom='$nombre'");
        if($check_nombre->rowCount()>0){
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error!</strong><br>
                    El nombre ingresado ya se encuentra registrado, por favor ingrese otro.
                </div>
            ';
            exit();
        }
        $check_nombre=null;
    }
    //Actualizar datos
    $actualizar_categoria = conexion();
    $actualizar_categoria = $actualizar_categoria->prepare("UPDATE categoria SET categoria_nom = :nombre, categoria_descripcion = :ubicacion 
                                                WHERE categoria_id = :id");
    $marcadores = [
      ":nombre"=> $nombre,
      ":ubicacion"=> $ubicacion,
      ":id" => $id
    ];
    if ($actualizar_categoria->execute($marcadores)){
        echo '<div class = "notification is-info is-ligth">
                <strong>¡Categoría Actualizada!</strong></br>
                La categoría se actualizó correctamente.
            </div>';
    } else {
        echo '<div class = "notification is-danger is-ligth">
            <strong>¡Ocurrió un error!</strong></br>
            No fue posible actualizar la categoría, por favor intente nuevamente.
        </div>';
    }
    $actualizar_categoria = null;