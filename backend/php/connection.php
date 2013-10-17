<?php
$connection_string="host=localhost port=5432 user=postgres password=123 dbname=sr";
$conn = pg_connect($connection_string) or die("Fall la conexin.");
?>