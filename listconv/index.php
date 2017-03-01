<?php
session_start();
$phrase = 'ERROR';
$none = 'none';

if($_SESSION['username'] !== NULL) {
	$phrase = 'OK';
}

$response = array("status" => $phrase, "conversations" => $none);
$json = json_encode($response);

echo $json;
?>