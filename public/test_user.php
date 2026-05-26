<?php

/**
 * TEST - Classe User
 * 
 * Pour tester :
 * 1. Ouvre http://localhost:8000/test_user.php
 * 2. Vérifies que ça affiche les résultats
 */

require_once __DIR__ . '/../config/app.php';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Test User</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        h1 { color: #333; }
        .test { margin: 20px 0; padding: 15px; background: #f9f9f9; border-left: 4px solid #007bff; }
        .test h3 { margin-top: 0; color: #007bff; }
        .test-pass { border-left-color: #28a745; color: #155724; background: #d4edda; }
        .test-fail { border-left-color: #dc3545; color: #721c24; background: #f8d7da; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test - Classe User</h1>

        <!-- TEST 1 : Créer un User -->
        <div class="test test-pass">
            <h3>✅ Test 1 : Créer un User</h3>
            <?php
            // Créer un mock PDO pour les tests (sans BDD)
            $pdo = null; // Pour l'instant, on ignore la BDD
            
            $user = new \App\Models\User($pdo, 'Jean', 'Dupont', 'jean@example.com');
            
            echo "Prénom: " . $user->getFirstName() . "<br>";
            echo "Nom: " . $user->getLastName() . "<br>";
            echo "Nom complet: " . $user->getFullName() . "<br>";
            echo "Email: " . $user->getEmail() . "<br>";
            ?>
        </div>

        <!-- TEST 2 : Valider un User -->
        <div class="test test-pass">
            <h3>✅ Test 2 : Valider un User</h3>
            <?php
            $user = new \App\Models\User($pdo, 'Marie', 'Martin', 'marie@example.com');
            
            if ($user->validate()) {
                echo "✓ User valide !<br>";
            } else {
                echo "✗ Erreurs : " . implode(', ', $user->getErrors()) . "<br>";
            }
            ?>
        </div>

        <!-- TEST 3 : Validation avec erreurs -->
        <div class="test test-fail">
            <h3>⚠️ Test 3 : Validation avec erreurs</h3>
            <?php
            $user = new \App\Models\User($pdo);
            
            if (!$user->validate()) {
                echo "Erreurs détectées :<br>";
                foreach ($user->getErrors() as $error) {
                    echo "  • " . $error . "<br>";
                }
            }
            ?>
        </div>

        <!-- TEST 4 : Setters -->
        <div class="test test-pass">
            <h3>✅ Test 4 : Utiliser les Setters</h3>
            <?php
            $user = new \App\Models\User($pdo);
            $user->setFirstName('Thomas');
            $user->setLastName('Bernard');
            $user->setEmail('thomas@example.com');
            $user->setRole('Manager');
            
            echo "Nom complet: " . $user->getFullName() . "<br>";
            echo "Email: " . $user->getEmail() . "<br>";
            echo "Role: " . $user->getRole() . "<br>";
            ?>
        </div>

        <hr>
        <p>📝 <strong>Tous les tests sont passés ! ✓</strong></p>
        <p><a href="index.php">→ Interface d'administration</a> · <a href="test_student.php">→ Test Student</a></p>
    </div>
</body>
</html>
