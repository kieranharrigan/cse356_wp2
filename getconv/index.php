<?php
session_start();
$fields = json_decode(file_get_contents('php://input'), true);
$id = $fields['id'];

$phrase = 'ERROR';
$convos = array();

if($_SESSION['username'] !== NULL) {
	$phrase = 'OK';

	$single = new SQLite3('/var/www/html/databases/conv.sqlite');
	$query = 'CREATE TABLE IF NOT EXISTS conv (id STRING, timestamp STRING, name STRING, text STRING)';
	$single->exec($query);

	$result = $single->query("SELECT * FROM conv WHERE lower(id) = '" . strtolower($id) . "'");

	$exists = $result->fetchAll();

	while($exists = $result->fetchArray()) {
		array_push($convos, array("timestamp" => $exists['timestamp'], "name" => $exists['name'], "text" => $exists['text']));
	}
}

$response = array("status" => $phrase, "conversation" => $convos);
$json = json_encode($response);

echo $json;
?>