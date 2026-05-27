<?php
  define("DB_HOST", "db");
  define("DB_USER", "cv_user");
  define("DB_PASS", "password123");
  define("DB_NAME", "cv_platform");
  
  $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  
  if ($connection->connect_error) {
    die(json_encode([
      "success" => false,
      "message" => "Erreur de connexion: " . $connection->connect_error
    ]));
  }
  
  $connection->set_charset("utf8mb4");
  session_start();
?>
