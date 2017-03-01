<?php
$fields = json_decode(file_get_contents('php://input'), true);
$username = $fields['username'];
$password = $fields['password'];
$email = $fields['email'];

if($username === NULL || $password === NULL || $email === NULL) {
    echo 'Incorrect usage of /adduser command.';
}
else {
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email address.' . $email;
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
            
            echo 'Added disabled user, ' . $username . '.';
            
            $body = "Thank you for creating an account with Eliza.\r\n\r\n" . "Username: " . $username . "\r\nKey: " . $key . "\r\n\r\nPlease click the following link to verify your email:\r\n130.245.168.97/verify/?email=" . $email . "&key=" . $key;
            
            exec("php send.php '$email' '$body' > /dev/null 2>&1 &");
            
        }
        else {
            echo 'The user, ' . $username . ', already exists.';
        }
    }
}
?>