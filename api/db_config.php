<?php
// api/db_config.php
$dbPath = __DIR__ . '/database.sqlite';
$needsInit = !file_exists($dbPath);
$dsn = "sqlite:$dbPath";

try {
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Ensure users table exists for authentication system
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL
    )");

    // Auto-initialize DB on first connect
    if ($needsInit) {
        require_once 'products_data.php';

        $pdo->exec("CREATE TABLE IF NOT EXISTS products (
            id TEXT PRIMARY KEY,
            title TEXT NOT NULL,
            price REAL NOT NULL,
            image TEXT,
            rating REAL,
            reviews INTEGER,
            prime INTEGER,
            delivery TEXT
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS cart_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            session_id TEXT NOT NULL,
            product_id TEXT NOT NULL,
            quantity INTEGER NOT NULL,
            FOREIGN KEY (product_id) REFERENCES products(id)
        )");

        $stmt = $pdo->prepare("INSERT INTO products 
            (id, title, price, image, rating, reviews, prime, delivery) 
            VALUES (:id, :title, :price, :image, :rating, :reviews, :prime, :delivery)");

        foreach ($products as $p) {
            $stmt->execute([
                ':id' => $p['id'],
                ':title' => $p['title'],
                ':price' => $p['price'],
                ':image' => $p['image'],
                ':rating' => $p['rating'],
                ':reviews' => $p['reviews'],
                ':prime' => $p['prime'] ? 1 : 0,
                ':delivery' => $p['delivery']
            ]);
        }
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'DB Connection failed: ' . $e->getMessage()]);
    exit;
}
