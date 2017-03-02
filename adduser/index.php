<?php
$fields = json_decode(file_get_contents('php://input'), true);
$username = $fields['username'];
$password = $fields['password'];
$email = $fields['email'];

if($username !== NULL || $password !== NULL || $email !== NULL) :
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $phrase = 'ERROR';
    }
    else {
        $db = new SQLite3('/var/www/html/databases/eliza_db.sqlite');
        $query = 'CREATE TABLE IF NOT EXISTS users (username STRING PRIMARY KEY, password STRING, email STRING, key STRING, disabled INTEGER)';
        $db->exec($query);
        
        $result = $db->query("SELECT * FROM users WHERE lower(username) = '" . strtolower($username) . "'");
        $exists = $result->fetchArray();
        
        if (!$exists) {
            $key = md5(uniqid($username, true));
            $query = "INSERT INTO users VALUES ('" . $username . "', '" . $password . "', '" . $email . "', '" . $key . "', 1)";
            $db->exec($query);
            
            $phrase = 'OK';
            
            $body = "Thank you for creating an account with Eliza.\r\n\r\n" . "Username: " . $username . "\r\nKey: " . $key . "\r\n\r\nPlease click the following link to verify your email:\r\nhttp://kiharrigan.cse356.compas.cs.stonybrook.edu/verify/?email=" . $email . "&key=" . $key;
            
            exec("php send.php '$email' '$body' > /dev/null 2>&1 &");
        }
        else {
            $phrase = 'ERROR';
        }
    }

    $response = array("status" => $phrase);
	$json = json_encode($response);

	echo $json;
else :
?>
<html>
<head>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="adduser.js"></script>
</head>

<body>
    <form id="input" onsubmit="event.preventDefault(); passToAdd();" autocomplete="off">
        Username: <input type="text" name="username" autofocus><br>
        Password: <input type="text" name="password"><br>
        Email: <input type="text" name="email">
        <input type="submit" value="submit">
    </form>

    <div id="result"></div>
</body>
</html>
<?php
endif;
?>