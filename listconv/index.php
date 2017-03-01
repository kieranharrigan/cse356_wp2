<?php
session_start();
$key = 'status';
$phrase = 'ERROR';

if($_SESSION['username'] !== NULL) {
	$phrase = 'OK';
	$key = 'conversations';
}

$response = array($key => $phrase);
$json = json_encode($response);

echo $json;
?>