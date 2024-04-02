<?php
	$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;
	$tabla="";

	$campos="productos.producto_id,productos.producto_codigo,productos.producto_nom,productos.producto_precio,productos.producto_stock,productos.producto_img,productos.categoria_id,productos.usuario_id,categoria.categoria_id,categoria.categoria_nom,usuario.usuario_id,usuario.usuario_nom,usuario.usuario_ape";

	if(isset($busqueda) && $busqueda!=""){

		$consulta_datos="SELECT $campos 
						FROM productos 
						INNER JOIN categoria ON productos.categoria_id=categoria.categoria_id 
						INNER JOIN usuario ON productos.usuario_id=usuario.usuario_id 
						WHERE productos.producto_codigo LIKE '%$busqueda%' 
						OR productos.producto_nom LIKE '%$busqueda%' 
						ORDER BY productos.producto_nom ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(producto_id) 
						FROM productos 
						WHERE producto_codigo LIKE '%$busqueda%' 
						OR producto_nom LIKE '%$busqueda%'";

	}elseif($categoria_id>0){

		$consulta_datos="SELECT $campos 
						FROM productos 
						INNER JOIN categoria ON productos.categoria_id=categoria.categoria_id 
						INNER JOIN usuario ON productos.usuario_id=usuario.usuario_id 
						WHERE productos.categoria_id='$categoria_id' 
						ORDER BY productos.producto_nom ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(producto_id) 
						FROM productos 
						WHERE categoria_id='$categoria_id'";

	}else{

		$consulta_datos="SELECT $campos 
						FROM productos 
						INNER JOIN categoria ON productos.categoria_id=categoria.categoria_id 
						INNER JOIN usuario ON productos.usuario_id=usuario.usuario_id 
						ORDER BY productos.producto_nom ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(producto_id) FROM productos";
	}

	$conexion=conexion();

	$datos = $conexion->query($consulta_datos);
	$datos = $datos->fetchAll();

	$total = $conexion->query($consulta_total);
	$total = (int) $total->fetchColumn();

	$Npaginas =ceil($total/$registros);

	if($total>=1 && $pagina<=$Npaginas){
		$contador=$inicio+1;
		$pag_inicio=$inicio+1;
		foreach($datos as $rows){
			$tabla.='
				<article class="media">
			        <figure class="media-left">
			            <p class="image is-64x64">';
			            if(is_file("./img/producto/".$rows['producto_img'])){
			            	$tabla.='<img src="./img/producto/'.$rows['producto_img'].'">';
			            }else{
			            	$tabla.='<img src="./img/productos.jpeg">';
			            }
			   $tabla.='</p>
			        </figure>
			        <div class="media-content">
			            <div class="content">
			              <p>
			                <strong>'.$contador.' - '.$rows['producto_nom'].'</strong><br>
			                <strong>CODIGO:</strong> '.$rows['producto_codigo'].', <strong>PRECIO:</strong> $'.$rows['producto_precio'].', <strong>STOCK:</strong> '.$rows['producto_stock'].',</br><strong>CATEGORIA:</strong> '.$rows['categoria_nom'].', <strong>REGISTRADO POR:</strong> '.$rows['usuario_nom'].' '.$rows['usuario_ape'].'
			              </p>
			            </div>
			            <div class="has-text-right">
			                <a href="index.php?vista=product_img&product_id_up='.$rows['producto_id'].'" class="button is-link is-rounded is-small">Imagen</a>
			                <a href="index.php?vista=product_update&product_id_up='.$rows['producto_id'].'" class="button is-success is-rounded is-small">Actualizar</a>
			                <a href="'.$url.$pagina.'&product_id_del='.$rows['producto_id'].'" class="button is-danger is-rounded is-small">Eliminar</a>
			            </div>
			        </div>
			    </article>

			    <hr>
            ';
            $contador++;
		}
		$pag_final=$contador-1;
	}else{
		if($total>=1){
			$tabla.='
				<p class="has-text-centered" >
					<a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
						Haga clic acá para recargar el listado
					</a>
				</p>
			';
		}else{
			$tabla.='
				<p class="has-text-centered" >No hay registros en el sistema</p>
			';
		}
	}

	if($total>0 && $pagina<=$Npaginas){
		$tabla.='<p class="has-text-right">Mostrando productos <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
	}

	$conexion=null;
	echo $tabla;

	if($total>=1 && $pagina<=$Npaginas){
		echo paginador_tablas($pagina,$Npaginas,$url,7);
	}