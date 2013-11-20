<?php
include 'connection.php';
session_start();

//Id de quien se busca
$buscado = 3;

//Distancia entre items
//Usa para empezar con el select, remplaza tabla actividades por lugar, y los campos respectivos, en la linea 14 remplazar a la derecha del igual por 0
//Crea un arreglo por lugar, donde se guardara numericamente la cantidad k el lugar a sido de agrado por cada uno de los usuarios.
$consulta="SELECT id,nombre FROM actividades";
$result = pg_query($consulta) or die ('Consulta fallida: ' . pg_last_error());
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))
{
	$items[$line["id"]] = $line["nombre"];				
}		
pg_free_result($consulta);	


$consulta="SELECT count(*) FROM actividades";
$result = pg_query($consulta) or die ('Consulta fallida: ' . pg_last_error());
$line = pg_fetch_array($result, null, PGSQL_ASSOC);
$n_actividades = $line['count'];
pg_free_result($consulta);

$consulta="SELECT count(*) FROM lugares";
$result = pg_query($consulta) or die ('Consulta fallida: ' . pg_last_error());
$line = pg_fetch_array($result, null, PGSQL_ASSOC);
$n_lugares = $line['count'];
pg_free_result($consulta);

$i = 1;
while ($i < $n_actividades + 1)
{
	$j = 1;
	while ($j < $n_lugares + 1)
	{
		$itemsxlugares[$i][$j] = 0;
		$lugaresxitems[$j][$i] = 0;
		$j = $j + 1;
	}
	$i = $i + 1;
}

$consulta="SELECT id_lugar,id_actividad FROM detalle_actividad_lugar";
$result = pg_query($consulta) or die ('Consulta fallida: ' . pg_last_error());
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))
{
	$itemsxlugares[$line['id_actividad']][$line['id_lugar']] = 1;
	$lugaresxitems[$line['id_lugar']][$line['id_actividad']] = 1;
	//echo $itemsxlugares[$line['id_actividad']][$line['id_lugar']];
}
pg_free_result($consulta);

//Calcula la distancia entre items
$i = 1;

$distancia_items[1][0][0] = 'items/items';

while ($i < $n_actividades + 1)
{
	$j = 1;
	while ($j < $n_actividades + 1)
	{		
		$distancia_items[1][$i][0] = $items[$i];	
		$j2 = 1;
		$distancia = 0;
		while ($j2 < $n_lugares + 1)
		{
			$distancia_items[1][0][$j] = $j;	
			$distancia = $distancia + pow($itemsxlugares[$i][$j2] - $lugaresxitems[$j2][$j],2);
			$j2 = $j2 + 1;
		}
		$distancia_items[1][$i][$j] = sqrt($distancia);
		$j = $j + 1;
	}
	$i = $i + 1;
}

//Imprime tabla con distancia entre items, considerando los lugares para el calculo de dicha distancia
$i = 0;
echo 'distancia entre items';
echo '<table border = 1>';
while ($i < $n_actividades + 1)
{
	echo '<tr>';
	$j = 0;
	while ($j < $n_actividades + 1)
	{	echo '<td>';
		echo $distancia_items[1][$i][$j].' ';
		echo '</td>';
		$j = $j + 1;
	}
	echo '</tr>';
	$i = $i + 1;
}
echo '</table>';






















//Distancia entre usuarios

$consulta="SELECT id,nombre FROM usuarios";
$idioma[0] = '';	
$result = pg_query($consulta) or die ('Consulta fallida: ' . pg_last_error());
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))
{
	$usuarios_nombre[$line["id"]] = $line["nombre"];				
}		
pg_free_result($consulta);	



$consulta="SELECT count(*) FROM usuarios";
$result = pg_query($consulta) or die ('Consulta fallida: ' . pg_last_error());
$line = pg_fetch_array($result, null, PGSQL_ASSOC);
$n_usuarios = $line['count'];
pg_free_result($consulta);

$i = 1;
while ($i < $n_usuarios + 1)
{
	$j = 1;
	while ($j < $n_actividades + 1)
	{
		$usuariosxitems[$i][$j] = 0;
		$itemsxusuarios[$j][$i] = 0;
		$j = $j + 1;
	}
	$i = $i + 1;
}

$consulta="SELECT id_usuario,id_preferencia FROM detalle_preferencias_usuario where estado='1'";
$result = pg_query($consulta) or die ('Consulta fallida: ' . pg_last_error());
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))
{
	$usuariosxitems[$line['id_usuario']][$line['id_preferencia']] = 1;
	$itemsxusuarios[$line['id_preferencia']][$line['id_usuario']] = 1;
}
pg_free_result($consulta);

//Calcula la distancia entre usuarios
$i = 1;

$distancia_usuarios[1][0][0] = 'usuarios/usuarios';

while ($i < $n_usuarios + 1)
{
	$distancia_usuarios[1][$i][0] = $usuarios_nombre[$i];	
	$j = 1;
	while ($j < $n_usuarios + 1)
	{
		$distancia_usuarios[1][0][$j] = $j;
		$j2 = 1;
		$distancia = 0;
		while ($j2 < $n_actividades + 1)
		{
			$distancia = $distancia + pow($usuariosxitems[$i][$j2] - $itemsxusuarios[$j2][$j],2);
			$j2 = $j2 + 1;
		}
		$distancia_usuarios[1][$i][$j] = sqrt($distancia);
		$j = $j + 1;
	}
	$i = $i + 1;
}

//imprime tabla distancia entre usuarios, considerando las preferencias del usuario para ello
echo '</br></br>';
$i = 0;
echo 'distancia entre usuarios';
echo '<table border = 1>';
while ($i < $n_usuarios + 1)
{
	echo '<tr>';
	$j = 0;
	while ($j < $n_usuarios + 1)
	{	echo '<td>';
		echo $distancia_usuarios[1][$i][$j].' ';
		echo '</td>';
		$j = $j + 1;
	}
	echo '</tr>';
	$i = $i + 1;
}
echo '</table>';


//Emparejador items

$n = 1;
$encontrado = 0;

while ($n < $n_usuarios )
{
	$min = 20;
	$minx=1;
	$miny=1;
	$i = 1;
	while ($i < $n_usuarios + 1)
	{	
		$j = 1;
		while ($j < $n_usuarios + 1)
		{	
			if ($min > $distancia_usuarios[$n][$i][$j] && $distancia_usuarios[$n][$i][$j] != 0)
			{
				$min = $distancia_usuarios[$n][$i][$j];
				$minx=$i;
				$miny=$j;
			}
			$j = $j + 1;
		}	
		$i = $i + 1;
	}
		
	echo 'min '.$min.' '.$minx.' '.$miny.'</br></br>';

	$i = 1;
	while ($i < $n_usuarios + 1)
	{
		if ($distancia_usuarios[$n][$minx][$i] < $distancia_usuarios[$n][$miny][$i])
		{
			$mini[$i] = $distancia_usuarios[$n][$minx][$i];
		}
		else
		{		
			$mini[$i] = $distancia_usuarios[$n][$miny][$i];	
		}	
		echo '</br>'.$mini[$i];
		$i = $i + 1;
	}

	/* trabajar aca */

	$x = 1;
	$i = 1;
	while ($x < $n_usuarios + 1)
	{
		
		$distancia_usuarios[$n+1][0][$x] = $distancia_usuarios[$n][0][$x];			
		$y = 1;
		if ($i == 1)
		{
			if ($distancia_usuarios[$n][0][$x] != $distancia_usuarios[$n][0][$miny])
			{				
				$distancia_usuarios[$n+1][0][$x] = $distancia_usuarios[$n][0][$x].','.$distancia_usuarios[$n][0][$miny];			
				$distancia_usuarios[$n][0][$miny] = '';
				$i = 2;
				$a = $distancia_usuarios[$n+1][0][$x];
			}
			else
				echo '</br>ERROR</br>';
		}
		while ($y < $n_usuarios + 1)
		{					
			if ($x == $minx)
			{							
				$distancia_usuarios[$n+1][$y][$x] = $mini[$y];			
			}
			else
			{
				if ($x == $miny)
				{
					$distancia_usuarios[$n+1][$y][$x] = 'x';			
				}
				else
				{
					$distancia_usuarios[$n+1][$y][$x] = $distancia_usuarios[$n][$y][$x];			
				}			
			}		
			if ($y == $miny)
			{
				$distancia_usuarios[$n+1][$y][$x] = 'x';			
			}
			$y = $y + 1;
		}
		$x = $x + 1;
	}
	
	echo '</br>vuelta = '.($n+1).'</br>';

	$i = 0;
	echo 'distancia entre usuarios';
	echo '<table border = 1>';
	while ($i < $n_usuarios + 1)
	{
		echo '<tr>';
		$j = 0;
		while ($j < $n_usuarios + 1)
		{	echo '<td>';
			echo $distancia_usuarios[$n+1][$i][$j].' ';
			echo '</td>';
			$j = $j + 1;
		}
		echo '</tr>';
		$i = $i + 1;
	}
	echo '</table>';	
	
	if (($buscado == $minx || $buscado == $miny) && $encontrado == 0)
	{
		$agrupamiento = $a;
		$n_encontrado = $n;
		$encontrado = 1;
	}	
	
	$n = $n+1;	
}

echo '</br>Agrupamineto = '.$agrupamiento.'</br>n encontrado = '.$n_encontrado.'</br>';

//Trabajar de aca en adelante
//Se debe trabajar con la variable agrupamiento, quitando posibles valores repetidos, me dio lata corregir ese error, pasara inadvertido,
//se debe eliminar de la variable el valor encontrado, el cual pertenecera a ella


$consulta="SELECT id_lugar,nombre_lugar FROM lugares";
$result = pg_query($consulta) or die ('Consulta fallida: ' . pg_last_error());
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))
{
	$lugar[$line["id_lugar"]] = 0;				
	$nombre_lugar[$line["id_lugar"]] = $line["nombre_lugar"];				
}		
pg_free_result($consulta);	


$datos = explode(",",$agrupamiento);
$n = count($datos);


$i = 0;

while ($i < $n)
{	
	$consulta="SELECT megusta,id_lugar FROM historial where id_usuario ='".$datos[$i]."'";
	$result = pg_query($consulta) or die ('Consulta fallida: ' . pg_last_error());
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))
	{
		$lugar[$line["id_lugar"]] = $lugar[$line["id_lugar"]] + $line["megusta"];				
	}		
	pg_free_result($consulta);			
	$i = $i+1;
}
//Descontar lo del usuario buscado

$consulta="SELECT megusta,id_lugar FROM historial where id_usuario ='".$buscado."'";
$result = pg_query($consulta) or die ('Consulta fallida: ' . pg_last_error());
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))
{
	$lugar[$line["id_lugar"]] = 0;				
}		
pg_free_result($consulta);			

//ordenar lugar de mayor a menor, los primeros valores son las recomendaciones para el usuario

$i = 1;
while ($i < $n_lugares + 1)
{
	echo '</br>'.$nombre_lugar[$i].' = '.$lugar[$i];
	$i = $i+1;
}

?>