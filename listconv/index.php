<?php
session_start();
$phrase = 'ERROR';

if($_SESSION['username'] !== NULL) {
	$phrase = 'OK';
}

$convs_db = new SQLite3('/var/www/html/databases/convs.sqlite');
$query = 'CREATE TABLE IF NOT EXISTS convs (username STRING PRIMARY KEY, id STRING, start_date STRING)';
$convs_db->exec($query);

$result = $convs_db->query("SELECT * FROM convs WHERE lower(username) = '" . strtolower($_SESSION['username']) . "'");

$convos = array();

while($exists = $result->fetchArray()) {
	array_push($convos, array("id" => $exists['id'], "start_date" => $exists['start_date']));
}

$response = array("status" => $phrase, "conversations" => $convos);
$json = json_encode($response);

echo $json;
?>