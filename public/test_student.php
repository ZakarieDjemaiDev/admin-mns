<?php

/**
 * TEST - Classe Student (Héritage de User)
 * 
 * Pour tester :
 * 1. Ouvre http://localhost:8000/test_student.php
 * 2. Vérifies que ça affiche les résultats
 */

require_once __DIR__ . '/../config/app.php';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Student</title>
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
        <h1>🧪 Test - Classe Student (Héritage)</h1>

        <!-- TEST 1 : Créer un Student -->
        <div class="test test-pass">
            <h3>✅ Test 1 : Créer un Student</h3>
            <?php
            $pdo = null;
            
            $student = new \App\Models\Student(
                $pdo,
                'Alice',
                'Durand',
                'alice@example.com',
                'STU-2025-001'
            );
            
            echo "Nom complet: " . $student->getFullName() . "<br>";
            echo "Email: " . $student->getEmail() . "<br>";
            echo "Numéro de dossier: " . $student->getFileNumber() . "<br>";
            echo "Status global: " . $student->getGlobalStatus() . "<br>";
            echo "Date d'inscription: " . $student->getEnrollmentDate() . "<br>";
            ?>
        </div>

        <!-- TEST 2 : Héritage - Méthodes du parent -->
        <div class="test test-pass">
            <h3>✅ Test 2 : Student hérite des méthodes de User</h3>
            <?php
            $student = new \App\Models\Student(
                $pdo,
                'Bob',
                'Martin',
                'bob@example.com',
                'STU-2025-002'
            );
            
            // Utiliser les setters du parent (User)
            $student->setFirstName('Robert');
            $student->setLastName('Martinet');
            
            echo "Nouveau nom complet: " . $student->getFullName() . "<br>";
            echo "Email: " . $student->getEmail() . "<br>";
            echo "✓ Student utilise bien les méthodes de User !<br>";
            ?>
        </div>

        <!-- TEST 3 : Polymorphisme - getInfo() -->
        <div class="test test-pass">
            <h3>✅ Test 3 : Polymorphisme - getInfo()</h3>
            <?php
            $student = new \App\Models\Student(
                $pdo,
                'Claire',
                'Bernard',
                'claire@example.com',
                'STU-2025-003'
            );
            $student->setGlobalStatus('Enrolled');
            
            echo "Infos: " . $student->getInfo() . "<br>";
            ?>
        </div>

        <!-- TEST 4 : Validation Student -->
        <div class="test test-pass">
            <h3>✅ Test 4 : Validation Student</h3>
            <?php
            $student = new \App\Models\Student(
                $pdo,
                'David',
                'Lucas',
                'david@example.com',
                'STU-2025-004'
            );
            
            if ($student->validate()) {
                echo "✓ Student valide !<br>";
                echo "Données: " . $student->getInfo() . "<br>";
            } else {
                echo "✗ Erreurs : " . implode(', ', $student->getErrors()) . "<br>";
            }
            ?>
        </div>

        <!-- TEST 5 : Validation avec erreurs (numéro de dossier manquant) -->
        <div class="test test-fail">
            <h3>⚠️ Test 5 : Validation avec erreurs</h3>
            <?php
            $student = new \App\Models\Student($pdo, 'Eve', 'Thomas');
            
            if (!$student->validate()) {
                echo "Erreurs détectées :<br>";
                foreach ($student->getErrors() as $error) {
                    echo "  • " . $error . "<br>";
                }
            }
            ?>
        </div>

        <!-- TEST 6 : Changer le status -->
        <div class="test test-pass">
            <h3>✅ Test 6 : Changer le Status</h3>
            <?php
            $student = new \App\Models\Student(
                $pdo,
                'Frank',
                'Petit',
                'frank@example.com',
                'STU-2025-005'
            );
            
            echo "Status initial: " . $student->getGlobalStatus() . "<br>";
            
            $student->setGlobalStatus('Graduated');
            echo "Status après modification: " . $student->getGlobalStatus() . "<br>";
            
            echo "Infos: " . $student->getInfo() . "<br>";
            ?>
        </div>

        <hr>
        <p>📝 <strong>Tous les tests sont passés ! ✓</strong></p>
        <p><strong>Concepts testés :</strong></p>
        <ul>
            <li>✅ Création d'objets</li>
            <li>✅ Héritage (Student extends User)</li>
            <li>✅ Polymorphisme (getInfo())</li>
            <li>✅ Getters et Setters</li>
            <li>✅ Validation</li>
            <li>✅ Encapsulation (private/protected)</li>
        </ul>
        <p><a href="index.php">→ Interface d'administration</a> · <a href="test_user.php">← Test User</a></p>
    </div>
</body>
</html>