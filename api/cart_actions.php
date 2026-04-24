<?php
session_start();
header('Content-Type: application/json');
require_once 'db_config.php';

$sid = session_id(); // Use PHP session ID to uniquely identify the anonymous user
$action = $_GET['action'] ?? '';
$json = file_get_contents('php://input');
$data = json_decode($json, true);

function getCartCount($pdo, $sid) {
    $stmt = $pdo->prepare("SELECT SUM(quantity) as calc FROM cart_items WHERE session_id = ?");
    $stmt->execute([$sid]);
    $res = $stmt->fetch();
    return (int)($res['calc'] ?? 0);
}

function getHydratedCart($pdo, $sid) {
    $stmt = $pdo->prepare("
        SELECT c.quantity as cart_qty, p.* 
        FROM cart_items c
        JOIN products p ON c.product_id = p.id
        WHERE c.session_id = ?
    ");
    $stmt->execute([$sid]);
    $items = $stmt->fetchAll();
    
    $subtotal = 0;
    foreach($items as &$item) {
        $item['prime'] = (bool)$item['prime']; // format for frontend compatibility
        $subtotal += ($item['price'] * $item['cart_qty']);
    }
    
    return [
        'items' => $items,
        'subtotal' => $subtotal,
        'count' => getCartCount($pdo, $sid)
    ];
}

try {
    switch ($action) {
        case 'get':
            echo json_encode(['status' => 'success', 'data' => getHydratedCart($pdo, $sid)]);
            break;

        case 'add':
            $productId = $data['id'] ?? null;
            if ($productId) {
                // Check if already in cart
                $stmt = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE session_id = ? AND product_id = ?");
                $stmt->execute([$sid, $productId]);
                $existing = $stmt->fetch();

                if ($existing) {
                    $upd = $pdo->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE id = ?");
                    $upd->execute([$existing['id']]);
                } else {
                    $ins = $pdo->prepare("INSERT INTO cart_items (session_id, product_id, quantity) VALUES (?, ?, 1)");
                    $ins->execute([$sid, $productId]);
                }

                echo json_encode(['status' => 'success', 'count' => getCartCount($pdo, $sid)]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No product ID']);
            }
            break;

        case 'update':
            $productId = $data['id'] ?? null;
            $qty = (int)($data['qty'] ?? 1);
            if ($productId) {
                if ($qty > 0) {
                    $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE session_id = ? AND product_id = ?");
                    $stmt->execute([$qty, $sid, $productId]);
                } else {
                    // if trying to set 0, delete it
                    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE session_id = ? AND product_id = ?");
                    $stmt->execute([$sid, $productId]);
                }
                echo json_encode(['status' => 'success', 'data' => getHydratedCart($pdo, $sid)]);
            }
            break;

        case 'remove':
            $productId = $data['id'] ?? null;
            if ($productId) {
                $stmt = $pdo->prepare("DELETE FROM cart_items WHERE session_id = ? AND product_id = ?");
                $stmt->execute([$sid, $productId]);
                echo json_encode(['status' => 'success', 'data' => getHydratedCart($pdo, $sid)]);
            }
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
