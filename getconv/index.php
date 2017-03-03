<?php
session_start();
$fields = json_decode(file_get_contents('php://input'), true);
$id = $fields['id'];

$phrase = 'ERROR';

if($_SESSION['username'] !== NULL) {
	$phrase = 'OK';

}

$convs_db = new SQLite3('/var/www/html/databases/convs.sqlite');
$query = 'CREATE TABLE IF NOT EXISTS single (id STRING, timestamp STRING, name STRING, text STRING)';
$convs_db->exec($query);

$result = $convs_db->query("SELECT * FROM single WHERE lower(id) = '" . strtolower($id) . "'");
$exists = $result->fetchArray();

$convos = array();
array_push($convos, array("timestamp" => $exists['timestamp'], "name" => $exists['name'], "text" => $exists['text']));

$exists = $result->fetchArray();

array_push($convos, array("timestamp" => $exists['timestamp'], "name" => $exists['name'], "text" => $exists['text']));

$response = array("status" => $phrase, "conversation" => $convos);
$json = json_encode($response);

echo $json;
?>
