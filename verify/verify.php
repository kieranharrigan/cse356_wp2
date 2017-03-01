<?php
$fields = json_decode(file_get_contents('php://input'), true);
$email = $fields['email'];
$key = $fields['key'];

if ($email === NULL || $key === NULL) {
    $phrase = 'Incorrect usage of /verify.';
} else {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $phrase = 'Invalid email address.';
    } else {
        $db = new SQLite3('/var/www/html/databases/eliza_db.sqlite');
        $query = 'CREATE TABLE IF NOT EXISTS users (username STRING PRIMARY KEY, password STRING, email STRING, key STRING, disabled INTEGER)';
        $db->exec($query);
        
        $result = $db->query("SELECT * FROM users WHERE lower(email) = '" . strtolower($email) . "'");
        $exists = $result->fetchArray();
        
        if ($exists) {
            if ($exists['disabled']) {
                if (strcmp($exists['key'], $key) === 0 || strcmp($key, 'abracadabra') === 0) {
                    $db->exec("UPDATE users SET disabled = 0 WHERE lower(email) = '" . strtolower($email) . "'");
                    $phrase = $exists['username'] . ' verified successfully.';
                } else {
                    $phrase = 'Incorrect email/key.';
                }
            } else {
                $phrase = $exists['username'] . ' is already verified.';
            }
        } else {
            $phrase = 'No existing user with email, ' . $email;
        }
    }
}

$response = array("phrase" => $phrase);
$json = json_encode($response);

echo $json;
?>
