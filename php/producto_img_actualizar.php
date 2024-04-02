<?php
    require_once "main.php";
	// Almacenando datos 
    $product_id=limpiar_cadena($_POST['img_up_id']);
    // Verificando producto 
    $check_producto=conexion();
    $check_producto=$check_producto->query("SELECT * FROM productos WHERE producto_id='$product_id'");

    if($check_producto->rowCount()==1){
        $datos=$check_producto->fetch();
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                La imagen del producto que intenta actualizar no existe.
            </div>
        ';
        exit();
    }
    $check_producto=null;
    // Comprobando si se ha seleccionado una imagen 
    if($_FILES['producto_img']['name']=="" || $_FILES['producto_img']['size']==0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                No ha seleccionado ninguna imagen, por favor intente nuevamente.
            </div>
        ';
        exit();
    }
    // Directorios de imagenes 
    $img_dir='../img/producto/';
    // Creando directorio de imagenes 
    if(!file_exists($img_dir)){
        if(!mkdir($img_dir,0777)){
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error!</strong><br>
                    Error al crear el directorio de imagenes.
                </div>
            ';
            exit();
        }
    }
    // Cambiando permisos al directorio 
    chmod($img_dir, 0777);
    // Comprobando formato de las imagenes 
    if(mime_content_type($_FILES['producto_img']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['producto_img']['tmp_name'])!="image/png"){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                La imagen seleccionada es de un formato no permitido, por favor intente nuevamente.
            </div>
        ';
        exit();
    }
    // Comprobando que la imagen no supere el peso permitido 
    if(($_FILES['producto_img']['size']/1024)>3072){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                La imagen eleccionada supera el límite de tamaño permitido, por favor intente nuevamente.
            </div>
        ';
        exit();
    }
    // extension de las imagenes 
    switch(mime_content_type($_FILES['producto_img']['tmp_name'])){
        case 'image/jpeg':
          $img_ext=".jpg";
        break;
        case 'image/png':
          $img_ext=".png";
        break;
    }
    // Nombre de la imagen 
    $img_nombre=renombrar_fotos($datos['producto_nom']);
    // Nombre final de la imagen 
    $foto=$img_nombre.$img_ext;
    // Moviendo imagen al directorio
    if(!move_uploaded_file($_FILES['producto_img']['tmp_name'], $img_dir.$foto)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                No es posible subir la imagen al sistema en este momento, por favor intente nuevamente.
            </div>
        ';
        exit();
    }
    // Eliminando la imagen anterior 
    if(is_file($img_dir.$datos['producto_img']) && $datos['producto_img']!=$foto){
        chmod($img_dir.$datos['producto_img'], 0777);
        unlink($img_dir.$datos['producto_img']);
    }
    // Actualizando datos 
    $actualizar_producto=conexion();
    $actualizar_producto=$actualizar_producto->prepare("UPDATE productos SET producto_img=:foto WHERE producto_id=:id");
    $marcadores=[
        ":foto"=>$foto,
        ":id"=>$product_id
    ];
    if($actualizar_producto->execute($marcadores)){
        echo '
            <div class="notification is-info is-light">
                <strong>¡Imagen Actualizada!</strong><br>
                La imagen del producto se actualizó correctamente, pulse Actualizar para ver los cambios.

                <p class="has-text-centered pt-5 pb-5">
                    <a href="index.php?vista=product_img&product_id_up='.$product_id.'" class="button is-link is-rounded">Actualizar</a>
                </p">
            </div>
        ';
    }else{
        if(is_file($img_dir.$foto)){
            chmod($img_dir.$foto, 0777);
            unlink($img_dir.$foto);
        }
        echo '
            <div class="notification is-warning is-light">
                <strong>¡Ocurrió un error!</strong><br>
                No es posible subir la imagen al sistema en este momento, por favor intente nuevamente.
            </div>
        ';
    }
    $actualizar_producto=null;