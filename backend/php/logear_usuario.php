<?php
include 'connection.php';
session_start();

$q=$_POST["q"];
$datos = explode("<tag>",$q);

$mail = $datos[0];
$pass = $datos[1];

$pass = md5($pass);

$consulta=pg_exec("SELECT * FROM usuarios WHERE correo = '".$mail."' AND pass = '".$pass."'");
$filas=pg_numrows($consulta);

if ($filas == 1){
        $_SESSION['correo'] = $mail;                
        $_SESSION['usuario'] = 'cliente';                
        echo "usuario";
}        
pg_free_result($consulta);        

?>