<?php
session_start();
$fields = json_decode(file_get_contents('php://input'), true);
$id = $fields['id'];

$phrase = 'ERROR';
$convos = array();

if($_SESSION['username'] !== NULL) {
	$phrase = 'OK';

	$single = new SQLite3('/var/www/html/databases/conv.sqlite');
	$query = 'CREATE TABLE IF NOT EXISTS conv (id STRING PRIMARY KEY, timestamp STRING, name STRING, text STRING)';
	$single->exec($query);

	$result = $single->query("SELECT * FROM conv WHERE lower(id) = '" . strtolower($id) . "'");

	$exists = $result->fetchAll();

	foreach($exists as $row){
        array_push($convos, array("timestamp" => $row['timestamp'], "name" => $row['name'], "text" => $row['text']));
    }
}

$response = array("status" => $phrase, "conversation" => $convos);
$json = json_encode($response);

echo $json;
?>