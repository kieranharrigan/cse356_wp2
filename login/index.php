<?php
$fields = json_decode(file_get_contents('php://input'), true);
$username = $fields['username'];
$password = $fields['password'];

if ($username !== NULL && $password !== NULL) :
	$db = new SQLite3('/var/www/html/databases/eliza_db.sqlite');
	$query = 'CREATE TABLE IF NOT EXISTS users (username STRING PRIMARY KEY, password STRING, email STRING, key STRING, disabled INTEGER)';
	$db->exec($query);

	$result = $db->query("SELECT * FROM users WHERE lower(username) = '" . strtolower($username) . "'");
	$exists = $result->fetchArray();

	if ($exists) {
		if ($exists['disabled']) {
			$phrase = 'ERROR';
		}
		else {
			if(strcmp($exists['password'], $password) === 0) {
				$phrase = 'OK';
				session_start();
				$_SESSION['username'] = $username;
			}
			else {
				$phrase = 'ERROR';
			}
		}
	}
	else {
		$phrase = 'ERROR';
	}

	$response = array("status" => $phrase);
	$json = json_encode($response);

	echo $json;
else :
?>

<html>
<head>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script type="text/javascript" src="login.js"></script>
</head>

<body>
	<form id="input" onsubmit="event.preventDefault(); passToAdd();" autocomplete="off">
		Username: <input type="text" name="username" autofocus><br>
		Password: <input type="text" name="password"><br>
		<input type="submit" value="submit">
	</form>

	<div id="result"></div>
</body>
</html>

<?php
endif;
?>