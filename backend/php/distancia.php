<?php
include 'connection.php';
session_start();

//Distancia entre items
$consulta="SELECT id,nombre FROM actividades";
$idioma[0] = '';	
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
			$distancia_items[1][0][$j] = $items[$j];	
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

//Emparejador items

$n = 1;
$min = 20;
$minx=1;
$miny=1;
$i = 1;
while ($i < $n_actividades + 1)
{	
	$j = 1;
	while ($j < $n_actividades + 1)
	{	
		if ($min > $distancia_items[$n][$i][$j] && $distancia_items[$n][$i][$j] != 0)
		{
			$min = $distancia_items[$n][$i][$j];
			$minx=$i;
			$miny=$j;
		}
		$j = $j + 1;
	}	
	$i = $i + 1;
}

echo 'min '.$min.' '.$minx.' '.$miny.'</br></br>';

$i = 1;
while ($i < $n_actividades + 1)
{
	if ($distancia_items[$n][$minx][$i] < $distancia_items[$n][$miny][$i])
	{
		$mini[$i] = $distancia_items[$n][$minx][$i];
	}
	else
	{		
		$mini[$i] = $distancia_items[$n][$miny][$i];	
	}	
	echo '</br>'.$mini[$i];
	$i = $i + 1;
}

/* trabajar aca */
$i = 1;
$x = 0;
if ($i == $minx)
	{		
		$x = 1;
	}
while ($i < $n_actividades + 1)
{	
	$y = 0;
	$j = 1;
	if ($j == $miny)
	{		
		$y = 1;
	}
	while ($j < $n_actividades + 1)
	{
		if ($i == $minx && $j == $miny)
		{
			$distancia_items[$n+1][$i][$j] = $mini[$i];			
		}
		else
		{
			$distancia_items[$n+1][$i][$j] = $distancia_items[$n][$i+$x][$j+$y];		
		}
		
		$j = $j + 1;
		if ($j == $miny)
		{			
			$y = 1;
		}
	}		
	$i = $i + 1;
	if ($i == $minx)
	{		
		$x = 1;
	}	
}












echo '</br>'."Segunda vuelta";

$i = 0;
echo 'distancia entre items';
echo '<table border = 1>';
while ($i < $n_actividades + 1)
{
	echo '<tr>';
	$j = 0;
	while ($j < $n_actividades + 1)
	{	echo '<td>';
		echo $distancia_items[2][$i][$j].' ';
		echo '</td>';
		$j = $j + 1;
	}
	echo '</tr>';
	$i = $i + 1;
}
echo '</table>';


while ($n < $n_actividades + 1)
{
	echo $n;
	$n = $n + 1;
}





















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
		$distancia_usuarios[1][0][$j] = $usuarios_nombre[$j];
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


?>