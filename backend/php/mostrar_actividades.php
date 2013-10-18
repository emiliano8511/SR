<?php
include 'connection.php';
session_start();

        $consulta="SELECT id_tipo,nombre_tipo FROM tipo_actividad";
        $tipo_actividad[0] = '';        		
        $result = pg_query($consulta) or die ('Consulta fallida: ' . pg_last_error());
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))
        {
                $tipo_actividad[$line["id_tipo"]] = $line["nombre_tipo"];                                								
        }                
        pg_free_result($consulta);        
        
        $consulta="SELECT nombre,id FROM actividades ORDER BY actividades.id_tipo_actividad ASC";						        
        $result = pg_query($consulta) or die ('Consulta fallida: ' . pg_last_error());
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))
        {
				echo '<input type="checkbox" value="'.$line['id'].'">'.$line['nombre'].'</br>';				
        }                		
        pg_free_result($consulta);                
?>