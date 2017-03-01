<?php
$data = json_decode(file_get_contents('php://input'), true);
$human = $data['human'];
$first = $data['first'];

if(strcmp($first, '1') === 0) {
 $response = array("eliza" => "Eliza: Please tell me how you are feeling.");
}
else {
  $input = phrase($human);

  $response = array("eliza" => "You: " . $human . "<br>Eliza: " . $input);
}
$json = json_encode($response);

echo $json;

function phrase($human) {
  $human = preg_replace('/\s+/', '', $human);
  $phrase = "Sorry, I didn't catch that.";

  $list = array("Why do you feel that way?", "What makes you feel that way?", "Hmmmm I see, please go on.", "Interesting, can you elaborate?");

  if(stripos($human, '/adduser') === 0) {
    if(stripos($human, ',') !== false) {
      $json_str = convertJSON($human);

      $fields = json_decode($json_str, true);
      $username = $fields['username'];
      $password = $fields['password'];
      $email = $fields['email'];

      if($username === NULL || $password === NULL || $email === NULL) {
        $phrase = 'Incorrect usage of /adduser command.';
      }
      else {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $phrase = 'Invalid email address.';
        }
        else {
          $db = new SQLite3('eliza_db.sqlite');
          $query = 'CREATE TABLE IF NOT EXISTS users (username STRING PRIMARY KEY, password STRING, email STRING, key STRING, disabled INTEGER)';
          $db->exec($query);

          $result = $db->query("SELECT * FROM users WHERE lower(username) = '" . strtolower($username) . "'");
          $exists = $result->fetchArray();

          if(!$exists) {
            $key = md5(uniqid($username, true));
            $query = "INSERT INTO users VALUES ('" . $username . "', '" . $password . "', '" . $email . "', '" . $key . "', 1)";
            $db->exec($query);

            $phrase = 'Added disabled user, ' . $username . '.';

            $body = "Thank you for creating an account with Eliza.\r\n\r\n" . "Username: " . $username . "\r\nKey: " . $key . "\r\n\r\nPlease verify your email using the above key and the /verify command.\r\n" . "Alternatively, enter the following into your Eliza chat: /verify, {email:" . $email . ", key:" . $key . "}";

            exec("php send.php '$email' '$body' > /dev/null 2>&1 &");

          }
          else {
            $phrase = 'The user, ' . $username . ', already exists.';
          }                    
        }
      }
    }
    else {
      $phrase = 'Incorrect usage of /adduser command.';
    }
  }

  else if(stripos($human, '/verify') === 0) {
    if(stripos($human, ',') !== false) {
      $json_str = convertJSON($human);

      $fields = json_decode($json_str, true);
      $email = $fields['email'];
      $key = $fields['key'];

      if($email === NULL || $key === NULL) {
        $phrase = 'Incorrect usage of /verify command.';
      }
      else {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $phrase = 'Invalid email address.';
        }
        else {
         $db = new SQLite3('eliza_db.sqlite');
         $query = 'CREATE TABLE IF NOT EXISTS users (username STRING PRIMARY KEY, password STRING, email STRING, key STRING, disabled INTEGER)';  
         $db->exec($query);

         $result = $db->query("SELECT * FROM users WHERE lower(email) = '" . strtolower($email) . "'");
         $exists = $result->fetchArray();

         if($exists) {
          if($exists['disabled']) {
            if(strcmp($exists['key'], $key) === 0 || strcmp($key, 'abracadabra') === 0) {
              $db->exec("UPDATE users SET disabled = 0 WHERE lower(email) = '" . strtolower($email) . "'");
              $phrase = $exists['username']. ' verified successfully.';
            }
            else {
              $phrase = 'Incorrect email/key.';
            }
          }
          else {
            $phrase = $exists['username'] . ' is already verified.';
          } 
        }
        else {
         $phrase = 'No existing user with email, ' . $email;
       }
     }
   }
 }
 else {
  $phrase = 'Incorrect usage of /verify command.';
}
}

else if(strcmp($human, '') !== 0) {
  $phrase = $list[array_rand($list)];
}

return $phrase;
}

function convertJSON($input) {
  $json_str = substr($input, stripos($input, ',') + 1);
  $json_str = str_replace('{', '{"', $json_str);
  $json_str = str_replace('}', '"}', $json_str);
  $json_str = str_replace(':', '":"', $json_str);
  $json_str = str_replace(',', '","', $json_str);

  return $json_str;
}
?>