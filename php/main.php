<?php
    //Conexión a la base de datos
    function conexion(){
        $pdo = new PDO ('mysql:host=localhost;dbname=inventario', 'root', '');
        return $pdo;
    }
    
    //Validación de datos del formulario
    function verificar_datos($filtro, $cadena){
        if (preg_match("/^". $filtro. "$/",$cadena)){
            return false;
        } else{
            return true;
        }
    }

    //Limpiar cadena de texto/evitar inyeccion SQL
    function limpiar_cadena ($cadena) {
        //Elimina espacios vacios
        $cadena = trim($cadena);
        //Elimina barras /
        $cadena = stripslashes($cadena);
        //Reemplaza todas las apariciones de la cadena de búsqueda con la cadena de reemplazo
        //Evita la inyeccion de codigo JS
        $cadena = str_ireplace("<script>","",$cadena);
        $cadena = str_ireplace("</script>","",$cadena);
        $cadena=str_ireplace("<script src", "", $cadena);
		$cadena=str_ireplace("<script type=", "", $cadena);
		$cadena=str_ireplace("SELECT * FROM", "", $cadena);
		$cadena=str_ireplace("DELETE FROM", "", $cadena);
		$cadena=str_ireplace("INSERT INTO", "", $cadena);
		$cadena=str_ireplace("DROP TABLE", "", $cadena);
		$cadena=str_ireplace("DROP DATABASE", "", $cadena);
		$cadena=str_ireplace("TRUNCATE TABLE", "", $cadena);
		$cadena=str_ireplace("SHOW TABLES;", "", $cadena);
		$cadena=str_ireplace("SHOW DATABASES;", "", $cadena);
		$cadena=str_ireplace("<?php", "", $cadena);
		$cadena=str_ireplace("?>", "", $cadena);
		$cadena=str_ireplace("--", "", $cadena);
		$cadena=str_ireplace("^", "", $cadena);
		$cadena=str_ireplace("<", "", $cadena);
		$cadena=str_ireplace("[", "", $cadena);
		$cadena=str_ireplace("]", "", $cadena);
		$cadena=str_ireplace("==", "", $cadena);
		$cadena=str_ireplace(";", "", $cadena);
		$cadena=str_ireplace("::", "", $cadena);
		$cadena=trim($cadena);
		$cadena=stripslashes($cadena);
		return $cadena;
    }

    //Renombrar fotos
    function renombrar_fotos ($nombre) {
        $nombre = str_ireplace(" ","_", $nombre);
        $nombre = str_ireplace("/","_", $nombre);
        $nombre = str_ireplace("#","_", $nombre);
        $nombre = str_ireplace("-","_", $nombre);
        $nombre = str_ireplace("$","_", $nombre);
        $nombre = str_ireplace(".","_", $nombre);
        $nombre = str_ireplace(",","_", $nombre);
        $nombre = $nombre ."_".rand(0,100);
        return $nombre;
    }

    //Funcion paginador de tablas
    function paginador_tablas($pagina, $Npaginas, $url, $botones) {
        $tabla = '<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';
        //Boton anterior se Deshabilita
        if ($pagina <= 1) {
            $tabla.= '
            <a class="pagination-previous is-disabled" disabled>Anterior</a> 
            <ul class="pagination-list">
            ';
        } else {
            $tabla.= '
            <a class="pagination-previous" href="'. $url . ($pagina - 1) .'">Anterior</a>
            <ul class="pagination-list">
                <li><a class="pagination-link" href="'. $url .' 1">1</a></li>
                <li><span class="pagination-ellipsis">&hellip;</span></li>
            ';
        }
        //Botones de paginas
        $contador=0;
        for ($i = $pagina; $i <= $Npaginas; $i++){
            if ($contador>=$botones){
                break;
            } 
            if ($pagina == $i){
                $tabla.='
                <li><a class="pagination-link is-current" href="'.$url.$i.'">'.$i.'</a></li>
                '; 
            } else {
                $tabla.='
                <li><a class="pagination-link" href="'.$url.$i.'">'.$i.'</a></li>
                '; 
            }
            $contador++;
        }
        //Boton siguiente se Deshabilita
        if ($pagina == $Npaginas) {
            $tabla.= '
            </ul>
            <a class="pagination-next is-disabled" disabled >Siguiente</a>
            ';
        } else {
            $tabla.= '
                <li><span class="pagination-ellipsis">&hellip;</span></li>
		        <li><a class="pagination-link" href="'. $url . $Npaginas .'">'.$Npaginas.'</a></li>
            </ul>
            <a class="pagination-next" href="'. $url . ($pagina + 1) .'">Siguiente</a>
            ';
        }
        $tabla.= '</nav>';
        return $tabla;
    }
    