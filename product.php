<?php
session_start();
require_once 'api/db_config.php';

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: index.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) { header("Location: index.php"); exit; }

$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['title']) ?> | NexStore 2026</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .product-layout {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: start;
        }
        .product-gallery {
            position: sticky;
            top: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }
        .product-gallery img {
            max-width: 100%;
            height: auto;
            max-height: 600px;
            object-fit: contain;
            filter: drop-shadow(0 20px 30px rgba(0, 240, 255, 0.2));
            transition: transform 0.5s;
        }
        .product-gallery img:hover { transform: scale(1.05); }

        .product-info-panel { padding: 40px 0; }
        .p-title { font-size: 38px; line-height: 1.2; margin-bottom: 20px; }
        .p-rating { display: flex; align-items: center; gap: 8px; color: var(--accent-cyan); margin-bottom: 30px; font-size: 16px; }
        
        .p-price-box { margin-bottom: 30px; }
        .p-price { font-size: 48px; font-weight: 800; font-variant-numeric: tabular-nums; background: linear-gradient(135deg, #fff, #a0aab2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;}
        
        .p-meta { font-size: 15px; color: var(--text-muted); margin-bottom: 40px; line-height: 1.8;}
        
        .buy-box { padding: 40px; background: rgba(255,255,255,0.02); }
        .actions-stack { display: flex; flex-direction: column; gap: 15px; }
    </style>
</head>
<body>
    <div class="bg-orb orb-1"></div>
    <div class="bg-orb orb-2"></div>

    <header class="navbar glass-panel">
        <div class="nav-container">
            <a href="index.php" class="nav-logo">
                <div class="logo-icon-box"><i class="ph-fill ph-planet"></i></div>
                <span>NEXSTORE</span>
            </a>
            <div class="search-form glow-focus">
                <input type="text" id="search-input" placeholder="Buscar tecnología, moda y futuro...">
                <button class="search-btn"><i class="ph ph-magnifying-glass"></i></button>
            </div>
            <div class="nav-actions">
                <div class="nav-item">
                    <span class="nav-label">Nexus ID</span>
                    <?php if($isLoggedIn): ?>
                        <span class="nav-bold"><?= htmlspecialchars($userName) ?> <a href="#" onclick="logoutUser(event)" style="font-size:11px; color:var(--accent-cyan);">(Salir)</a></span>
                    <?php else: ?>
                        <a href="login.php" class="nav-bold">Iniciar Sesión</a>
                    <?php endif; ?>
                </div>
                <a href="cart.php" class="nav-cart">
                    <div class="cart-wrapper">
                        <i class="ph ph-shopping-bag-open"></i>
                        <span class="cart-badge" id="cart-count">0</span>
                    </div>
                </a>
            </div>
        </div>
    </header>

    <main class="product-layout">
        <div class="product-gallery glass-card">
            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['title']) ?>">
        </div>

        <div class="product-info-panel">
            <?php if($product['prime']): ?>
                <div class="prime-badge" style="display:inline-block; margin-bottom:15px; font-size:13px; padding:6px 12px;"><i class="ph-fill ph-check"></i> PRIME CUÁNTICO</div>
            <?php endif; ?>
            <h1 class="p-title"><?= htmlspecialchars($product['title']) ?></h1>
            <div class="p-rating">
                <?php
                for($i=1; $i<=5; $i++) {
                    if($i <= floor($product['rating'])) echo '<i class="ph-fill ph-star"></i>';
                    elseif($i - $product['rating'] < 1) echo '<i class="ph-fill ph-star-half"></i>';
                    else echo '<i class="ph ph-star"></i>';
                }
                ?>
                <span style="color:var(--text-muted); margin-left:10px;"><?= number_format($product['reviews']) ?> valoraciones globales</span>
            </div>

            <div class="p-price-box">
                <div class="p-price">$<?= number_format($product['price'], 2) ?></div>
                <div style="color:var(--text-muted); font-size:14px; margin-top:5px;">Impuestos intergalácticos incluidos.</div>
            </div>

            <div class="p-meta">
                <p><strong style="color:white;">Entrega Proyectada:</strong> <?= htmlspecialchars($product['delivery']) ?></p>
                <p><strong style="color:white;">Distribuidor:</strong> NexStore Core Alpha</p>
                <p><strong style="color:white;">Soporte:</strong> Garantía de reemplazo 3D inmediata por 2 años.</p>
            </div>

            <div class="glass-card buy-box">
                <h3 style="margin-bottom: 20px;">Añadir a tu equipamiento</h3>
                <div class="actions-stack">
                    <button class="btn btn-gradient add-to-cart" data-id="<?= htmlspecialchars($product['id']) ?>" style="font-size: 18px; padding: 20px;">
                        Descargar al Carrito <i class="ph-bold ph-download-simple"></i>
                    </button>
                    <?= !$isLoggedIn ? '<p style="color:#FF6B6B; font-size:13px; text-align:center;">Crea una identidad para guardado permanente en la nube.</p>' : '' ?>
                </div>
            </div>
        </div>
    </main>

    <div id="toast-container" class="toast-container"></div>
    <script src="js/main.js"></script>
    <script>
        function logoutUser(e) {
            e.preventDefault();
            fetch('api/auth.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({action: 'logout'})
            }).then(() => location.reload());
        }
        
        // Product specific listener since JS attaches them to dynamically loaded cards usually
        document.querySelector('.add-to-cart').addEventListener('click', async (e) => {
            const btnEl = e.target.closest('button');
            const originalText = btnEl.innerHTML;
            btnEl.innerHTML = 'Procesando... <i class="ph ph-spinner-gap spin"></i>';
            btnEl.disabled = true;

            const id = btnEl.getAttribute('data-id');
            await addToCart(id);
            
            btnEl.innerHTML = '<i class="ph-fill ph-check-circle"></i> Añadido';
            setTimeout(() => {
                btnEl.innerHTML = originalText;
                btnEl.disabled = false;
            }, 3000);
        });
    </script>
</body>
</html>
