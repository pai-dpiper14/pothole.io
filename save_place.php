<?php
$db = new Mysqli("localhost", "root", "pothole", "pothole"); 
if ($db->connect_errno){
	die('Connect Error: ' . $db->connect_errno);
}
$place = $_POST['place'];
$description = $_POST['description'];
$latitude = $_POST['lat'];
$longitude = $_POST['lng'];
$db->query("INSERT INTO tbl_places SET place='$place', description='$description', lat='$latitude', lng='$longitude'");
$place_id = $db->insert_id;
echo $place_id;
?>