<?php
$salida= array(); //recogerá los datos que nos muestre el script de Python
 
    $texto="Hola Mundo";
    exec("python ../lala.py '".$texto."'",$salida);
    echo $salida[0];
?>