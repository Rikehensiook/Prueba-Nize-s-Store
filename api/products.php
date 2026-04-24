<?php
header('Content-Type: application/json');
require_once 'db_config.php';

// Simulate network delay for UI fluidity (Loader)
usleep(800000);

try {
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll();
    
    // Cast integer 1/0 back to boolean for the frontend
    foreach ($products as &$p) {
        $p['prime'] = (bool)$p['prime'];
    }
    
    echo json_encode(['status' => 'success', 'data' => $products]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
