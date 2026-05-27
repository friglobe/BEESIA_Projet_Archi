<?php
  require "../config.php";
  
  header("Content-Type: application/json");
  
  if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
  }
  
  // Récupérer les données
  $data = json_decode(file_get_contents("php://input"), true);
  
  $email = $data['email'] ?? "";
  $password = $data['password'] ?? "";
  $user_type = $data['user_type'] ?? "";  // "student" ou "company"
  
  // Validation
  if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["error" => "Email et mot de passe requis"]);
    exit;
  }
  
  // Déterminer la table
  $table = ($user_type == "company") ? "entreprises" : "etudiants";
  $email_field = ($user_type == "company") ? "email_contact" : "email";
  
  // Chercher l'utilisateur
  $stmt = $connection->prepare(
    "SELECT id, nom, password_hash FROM $table WHERE $email_field = ?"
  );
  $stmt->bind_param("s", $email);
  $stmt->execute();
  
  $result = $stmt->get_result();
  
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Vérifier le password
    if (password_verify($password, $user['password_hash'])) {
      // Succès
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_type'] = $user_type;
      $_SESSION['nom'] = $user['nom'];
      
      http_response_code(200);
      echo json_encode([
        "success" => true,
        "message" => "Connecté!",
        "user" => [
          "id" => $user['id'],
          "nom" => $user['nom']
        ]
      ]);
    } else {
      // Mot de passe incorrect
      http_response_code(401);
      echo json_encode(["error" => "Mot de passe incorrect"]);
    }
  } else {
    // Email non trouvé
    http_response_code(401);
    echo json_encode(["error" => "Email non trouvé"]);
  }
  
  $stmt->close();
?>
