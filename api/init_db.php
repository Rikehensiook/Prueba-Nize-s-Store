<?php
// api/init_db.php
require_once 'db_config.php';
require_once 'products_data.php';

try {
    // 1. Create Products Table
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

    // 2. Create Cart Items Table 
    // We'll track items per session_id so multiple users can have isolated carts
    $pdo->exec("CREATE TABLE IF NOT EXISTS cart_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        session_id TEXT NOT NULL,
        product_id TEXT NOT NULL,
        quantity INTEGER NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id)
    )");

    // 3. Clear existing products to avoid duplicates on re-run
    $pdo->exec("DELETE FROM products");

    // 4. Seed Products from the static array
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

    echo "Database initialized successfully.\n";

} catch (PDOException $e) {
    echo "Error initializing DB: " . $e->getMessage() . "\n";
}
