<?php
	require_once "main.php";

	// Almacenando datos 
    $product_id=limpiar_cadena($_POST['img_del_id']);

    // Verificando producto 
    $check_producto=conexion();
    $check_producto=$check_producto->query("SELECT * FROM productos WHERE producto_id='$product_id'");

    if($check_producto->rowCount()==1){
    	$datos=$check_producto->fetch();
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                La imagen del producto que intenta eliminar no existe.
            </div>
        ';
        exit();
    }
    $check_producto=null;
    // Directorios de imagenes 
	$img_dir='../img/producto/';
	// Cambiando permisos al directorio 
	chmod($img_dir, 0777);
	// Eliminando la imagen
	if(is_file($img_dir.$datos['producto_img'])){
		chmod($img_dir.$datos['producto_img'], 0777);
		if(!unlink($img_dir.$datos['producto_img'])){
			echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrió un error!</strong><br>
	                No es posible eliminar la imagen del producto, por favor intente nuevamente.
	            </div>
	        ';
	        exit();
		}
    }
	// Actualizando datos 
    $actualizar_producto=conexion();
    $actualizar_producto=$actualizar_producto->prepare("UPDATE productos SET producto_img=:foto WHERE producto_id=:id");

    $marcadores=[
        ":foto"=>"",
        ":id"=>$product_id
        ];
    if($actualizar_producto->execute($marcadores)){
        echo '
            <div class="notification is-info is-light">
                <strong>¡Imagen Eliminada!</strong><br>
                La imagen del producto se eliminó correctamente, pulse Actualizar para ver los cambios.

                <p class="has-text-centered pt-5 pb-5">
                    <a href="index.php?vista=product_img&product_id_up='.$product_id.'" class="button is-link is-rounded">Actualizar</a>
                </p">
            </div>
        ';
    }else{
        echo '
            <div class="notification is-warning is-light">
                <strong>¡Imagen Eliminada!</strong><br>
                Ocurrieron algunos inconvenientes, sin embargo la imagen del producto se eliminó correctamente, pulse Actualizar para ver los cambios.

                <p class="has-text-centered pt-5 pb-5">
                    <a href="index.php?vista=product_img&product_id_up='.$product_id.'" class="button is-link is-rounded">Actualizar</a>
                </p">
            </div>
        ';
    }
    $actualizar_producto=null;