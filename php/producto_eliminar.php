<?php
    // Almacenando datos 
    $product_id_del=limpiar_cadena($_GET['product_id_del']);
    // Verificando producto 
    $check_producto=conexion();
    $check_producto=$check_producto->query("SELECT * FROM productos WHERE producto_id='$product_id_del'");

    if($check_producto->rowCount() == 1){
        $datos=$check_producto->fetch();
        $eliminar_producto=conexion();
	    $eliminar_producto=$eliminar_producto->prepare("DELETE FROM productos WHERE producto_id=:id");

	    $eliminar_producto->execute([":id"=>$product_id_del]);
        
	    if($eliminar_producto->rowCount()==1){
            if(is_file("./img/producto/".$datos['producto_img'])){
                chmod("./img/producto/".$datos['producto_img'], 0777);
                unlink("./img/producto/".$datos['producto_img']);
            }
		    echo '
		        <div class="notification is-info is-light">
		            <strong>¡Producto Eliminado!</strong><br>
		            El producto se eliminó correctamente.
		        </div>
		    ';
		}else{
		    echo '
		        <div class="notification is-danger is-light">
		            <strong>¡Ocurrió un error!</strong><br>
		            No fue posible eliminar el producto, por favor intente nuevamente.
		        </div>
		    ';
		}
		$eliminar_producto=null;

    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                El producto que intenta eliminar no existe.
            </div>
        ';
    }
    $check_producto = null;