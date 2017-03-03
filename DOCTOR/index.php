<?php
session_start();
date_default_timezone_set('America/New_York');

$data = json_decode(file_get_contents('php://input'), true);
$human = $data['human'];
$first = $data['first'];

$convs_db = new SQLite3('/var/www/html/databases/convs.sqlite');
$query = 'CREATE TABLE IF NOT EXISTS convs (username STRING PRIMARY KEY, id STRING, start_date STRING)';
$convs_db->exec($query);

$query = 'CREATE TABLE IF NOT EXISTS single (id STRING, timestamp STRING, name STRING, text STRING)';
$convs_db->exec($query);

if($_SESSION['id'] === NULL) {
 $id = md5(uniqid($_SESSION['username'], true));
 $_SESSION['id'] = $id;
 $date = date('n/j/Y');

 $query = "INSERT INTO convs VALUES ('" . $_SESSION['username'] . "', '" . $id . "', '" . $date . "')";
 $convs_db->exec($query);
}

if(strcmp($first, '1') === 0) {
 $response = array("eliza" => "Eliza: Please tell me how you are feeling.");
 $text = "Eliza: Please tell me how you are feeling.";
}
else {
  $input = phrase($human);

  $response = array("eliza" => "You: " . $human . "<br>Eliza: " . $input);

  $text = "You: " . $human . PHP_EOL . "Eliza: " . $input;
}

$query = "INSERT INTO single VALUES ('" . $_SESSION['id'] . "', '" . date('n/j/Y') . "', '" . $_SESSION['username'] . "', '" . $text . "')";
$convs_db->exec($query);

$json = json_encode($response);

echo $json . PHP_EOL . $query;

function phrase($human) {
  $human = preg_replace('/\s+/', '', $human);
  $phrase = "Sorry, I didn't catch that.";

  $list = array("Why do you feel that way?", "What makes you feel that way?", "Hmmmm I see, please go on.", "Interesting, can you elaborate?");

  if(strcmp($human, '') !== 0) {
    $phrase = $list[array_rand($list)];
  }

  return $phrase;
}
?>