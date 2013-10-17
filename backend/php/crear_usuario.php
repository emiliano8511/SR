<?php
include 'connection.php';
session_start();

$q=$_POST["q"];
$datos = explode("<tag>",$q);

$nombre = $datos[0];
$apellido = $datos[1];
$mail = $datos[2];
$pass = $datos[3];

$pass = md5($pass);

$consulta=pg_exec("SELECT * FROM usuarios WHERE correo = '".$mail."'");
$filas=pg_numrows($consulta);
pg_free_result($consulta);                                        
if ($filas == 0)
{
        pg_exec("INSERT INTO usuarios(nombre,apellido,correo,esadmin,pass) VALUES('".$nombre."','".$apellido."','".$mail."','f','".$pass."')");        
        echo "ok";
        $_SESSION['mail'] = $mail;        
        $_SESSION['nombre'] = $nombre;        
        $_SESSION['usuario'] = 'cliente';                        
}
else
{
        echo "error";
}
pg_free_result($consulta);        
?>