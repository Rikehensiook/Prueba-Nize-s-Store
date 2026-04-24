<?php
session_start();
header('Content-Type: application/json');
require_once 'db_config.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$action = $data['action'] ?? '';

usleep(600000);

try {
    if ($action === 'register') {
        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $pass = $data['password'] ?? '';

        if (!$name || !$email || !$pass) {
            echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
            exit;
        }

        // Check if exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            echo json_encode(['status' => 'error', 'message' => 'El email ya está registrado.']);
            exit;
        }

        // Insert
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hash]);
        $userId = $pdo->lastInsertId();

        // Auto login
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $name;

        echo json_encode(['status' => 'success', 'name' => $name]);
    } 
    elseif ($action === 'login') {
        $email = trim($data['email'] ?? '');
        $pass = $data['password'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            echo json_encode(['status' => 'success', 'name' => $user['name']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Credenciales incorrectas.']);
        }
    } 
    elseif ($action === 'logout') {
        session_destroy();
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Acción inválida.']);
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error de Base de Datos: ' . $e->getMessage()]);
}
