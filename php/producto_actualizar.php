<?php
	require_once "main.php";
	// Almacenando id 
    $id=limpiar_cadena($_POST['producto_id']);
    // Verificando producto 
	$check_producto=conexion();
	$check_producto=$check_producto->query("SELECT * FROM productos WHERE producto_id='$id'");

    if($check_producto->rowCount()<=0){
    	echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                El producto no existe en el sistema.
            </div>
        ';
        exit();
    }else{
    	$datos=$check_producto->fetch();
    }
    $check_producto=null;
    // Almacenando datos 
    $codigo=limpiar_cadena($_POST['producto_codigo']);
	$nombre=limpiar_cadena($_POST['producto_nom']);

	$precio=limpiar_cadena($_POST['producto_precio']);
	$stock=limpiar_cadena($_POST['producto_stock']);
	$categoria=limpiar_cadena($_POST['producto_categoria']);
	// Verificando campos obligatorios 
    if($codigo=="" || $nombre=="" || $precio=="" || $stock=="" || $categoria==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                No has llenado todos los campos obligatorios, por favor intente nuevamente.
            </div>
        ';
        exit();
    }
    // Verificando integridad de los datos 
    if(verificar_datos("[a-zA-Z0-9- ]{1,70}",$codigo)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                El código no coincide con el formato solicitado.
            </div>
        ';
        exit();
    }
    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}",$nombre)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                El nombre no coincide con el formato solicitado.
            </div>
        ';
        exit();
    }
    if(verificar_datos("[0-9.]{1,25}",$precio)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                El precio no coincide con el formato solicitado.
            </div>
        ';
        exit();
    }
    if(verificar_datos("[0-9]{1,25}",$stock)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error!</strong><br>
                El stock no coincide con el formato solicitado.
            </div>
        ';
        exit();
    }
    // Verificando codigo 
    if($codigo!=$datos['producto_codigo']){
	    $check_codigo=conexion();
	    $check_codigo=$check_codigo->query("SELECT producto_codigo FROM productos WHERE producto_codigo='$codigo'");
	    if($check_codigo->rowCount()>0){
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrió un error!</strong><br>
	                El código ingresado ya se encuentra registrado, por favor ingrese otro.
	            </div>
	        ';
	        exit();
	    }
	    $check_codigo=null;
    }
    // Verificando nombre 
    if($nombre!=$datos['producto_nom']){
	    $check_nombre=conexion();
	    $check_nombre=$check_nombre->query("SELECT producto_nom FROM productos WHERE producto_nom='$nombre'");
	    if($check_nombre->rowCount()>0){
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrió un error!</strong><br>
	                El nombre ya se encuentra registrado, por favor ingrese otro.
	            </div>
	        ';
	        exit();
	    }
	    $check_nombre=null;
    }
    // Verificando categoria 
    if($categoria!=$datos['categoria_id']){
	    $check_categoria=conexion();
	    $check_categoria=$check_categoria->query("SELECT categoria_id FROM categoria WHERE categoria_id='$categoria'");
	    if($check_categoria->rowCount()<=0){
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrió un error!</strong><br>
	                La categoría seleccionada no existe.
	            </div>
	        ';
	        exit();
	    }
	    $check_categoria=null;
    }
    // Actualizando datos 
    $actualizar_producto=conexion();
    $actualizar_producto=$actualizar_producto->prepare("UPDATE productos SET producto_codigo=:codigo,producto_nom=:nombre,producto_precio=:precio,producto_stock=:stock,categoria_id=:categoria WHERE producto_id=:id");

    $marcadores=[
        ":codigo"=>$codigo,
        ":nombre"=>$nombre,
        ":precio"=>$precio,
        ":stock"=>$stock,
        ":categoria"=>$categoria,
        ":id"=>$id
    ];
    if($actualizar_producto->execute($marcadores)){
        echo '
            <div class="notification is-info is-light">
                <strong>¡Producto Actualizado!</strong><br>
                El producto se actualizó correctamente.
            </div>
        ';
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error!</strong><br>
                No fue posible actualizar el producto, por favor intente nuevamente.
            </div>
        ';
    }
    $actualizar_producto=null;