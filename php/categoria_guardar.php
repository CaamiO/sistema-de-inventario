<?php

    require_once "main.php";

    // Almacenando datos 
    $nombre=limpiar_cadena($_POST['categoria_nom']);
    $ubicacion=limpiar_cadena($_POST['categoria_descripcion']);

    // Verificando campos obligatorios 
    if($nombre==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                No has llenado todos los campos obligatorios, por favor intente nuevamente.
            </div>
        ';
        exit();
       }

    // Verificando integridad de los datos 
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

    // Guardando datos 
    $guardar_categoria=conexion();
    $guardar_categoria=$guardar_categoria->prepare("INSERT INTO categoria(categoria_nom,categoria_descripcion) VALUES(:nombre,:ubicacion)");

    $marcadores=[
        ":nombre"=>$nombre,
        ":ubicacion"=>$ubicacion
    ];

    $guardar_categoria->execute($marcadores);
    if($guardar_categoria->rowCount()==1){
        echo '
            <div class="notification is-info is-light">
                <strong>¡Categoría Registrada!</strong><br>
                La categoría se registró correctamente.
            </div>
        ';
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                No fue posible registrar la categoría, por favor intente nuevamente.
            </div>
        ';
    }
    $guardar_categoria=null;
    