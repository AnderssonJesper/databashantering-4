<?php

$host = "localhost";
$port = 3306;
$database = "test";
$username = "root";
$password = "";


$connection = new mysqli($host, $username, $password, $database, $port);

if ($connection->connect_error) {
   die("Anslutningen misslyckades:" . $connection->connect_error);
}

echo "<h1>Min E-Handel</h1>";

function hash_sensitive_data($data){
   $hashed_data = password_hash($data, PASSWORD_BCRYPT);
   return $hashed_data;
}

function insert_user($username, $password, $ssn){
   global $connection;

   $hashed_password = hash_sensitive_data($password);
   $hashed_ssn = hash_sensitive_data($ssn);

   $stmt = $connection->prepare("INSERT INTO users (username, password, ssn) VALUES(?, ?, ?)");
   $stmt->bind_param("sss", $username, $hashed_password, $hashed_ssn);

   $stmt->execute();
   $stmt->close();
}

function get_user($username, $password){
   global $connection;

   $stmt = $connection->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
   $stmt-> bind_param("ss", $username, $password);

   $stmt->execute();
   $result = $stmt->get_result();
   $user_data = $result->fetch_assoc();

   $stmt->close();

   return $user_data;
}

insert_user('john_doe', 'secure_password', '1980011011234');
$user_data = get_user('john_doe', 'secure_password');
print_r($user_data);


$connection->close();
?>