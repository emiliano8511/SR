<?php
include 'connection.php';
session_start();

//Distancia entre items
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

$i = 1;
while ($i < $n_actividades + 1)
{
	$j = 1;
	while ($j < $n_actividades + 1)
	{
		$j2 = 1;
		$distancia = 0;
		while ($j2 < $n_lugares + 1)
		{
			$distancia = $distancia + pow($itemsxlugares[$i][$j2] - $lugaresxitems[$j2][$j],2);
			$j2 = $j2 + 1;
		}
		$distancia_items[$i][$j] = sqrt($distancia);
		$j = $j + 1;
	}	
	$i = $i + 1;
}

$i = 1;
echo 'distancia entre items';
echo '<table border = 1>';
while ($i < $n_actividades + 1)
{
	echo '<tr>';
	$j = 1;
	while ($j < $n_actividades + 1)
	{	echo '<td>';
		echo $distancia_items[$i][$j].' ';
		echo '</td>';
		$j = $j + 1;
	}	
	echo '</tr>';
	$i = $i + 1;
}
echo '</table>';

//Distancia entre usuarios


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
	echo $usuariosxitems[$line['id_usuario']][$line['id_preferencia']];		
}                		
pg_free_result($consulta);                

$i = 1;
while ($i < $n_usuarios + 1)
{
	$j = 1;
	while ($j < $n_usuarios + 1)
	{
		$j2 = 1;
		$distancia = 0;
		while ($j2 < $n_actividades + 1)
		{
			$distancia = $distancia + pow($usuariosxitems[$i][$j2] - $itemsxusuarios[$j2][$j],2);
			$j2 = $j2 + 1;
		}
		$distancia_usuarios[$i][$j] = sqrt($distancia);
		$j = $j + 1;
	}	
	$i = $i + 1;
}

$i = 1;
echo 'distancia entre usuarios';
echo '<table border = 1>';
while ($i < $n_usuarios + 1)
{
	echo '<tr>';
	$j = 1;
	while ($j < $n_usuarios + 1)
	{	echo '<td>';
		echo $distancia_usuarios[$i][$j].' ';
		echo '</td>';
		$j = $j + 1;
	}	
	echo '</tr>';
	$i = $i + 1;
}
echo '</table>';




?>