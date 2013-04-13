<?php
     
$server     = 'localhost';
$username   = 'root';
$password   = 'pothole';
$database   = 'pothole';
 
$dsn        = "mysql:host=$server;dbname=$database";

     
    try {
     
        $db = new PDO($dsn, $username, $password);
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
         
        $sth = $db->query("SELECT * FROM tbl_places");
        $locations = $sth->fetchAll();
         
        echo json_encode( $locations );
         
    } catch (Exception $e) {
        echo $e->getMessage();
    }
