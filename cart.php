<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito | NexStore 2026</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>
    <div class="bg-orb orb-1"></div>
    <div class="bg-orb orb-2"></div>

    <header class="navbar glass-panel checkout-nav">
        <div class="nav-container checkout-container">
            <a href="index.php" class="nav-logo">
                <div class="logo-icon-box"><i class="ph-fill ph-planet"></i></div>
                <span>NEXSTORE</span>
            </a>
            
            <div style="flex-grow:1; text-align:center;">
                <?php if($isLoggedIn): ?>
                    <span style="font-size:14px; color:var(--text-muted);">Comprador: <strong style="color:white;"><?= htmlspecialchars($userName) ?></strong> <a href="#" onclick="logoutUser(event)" style="font-size:11px; color:var(--accent-cyan); margin-left:5px;">(Salir)</a></span>
                <?php else: ?>
                    <span style="font-size:14px; color:var(--text-muted);"><a href="login.php" style="color:var(--accent-cyan);">Inicia sesión</a> para guardado permanente</span>
                <?php endif; ?>
            </div>

            <div class="checkout-badge">
                <i class="ph-fill ph-lock-key"></i>
                <span>Pago Cuántico Seguro</span>
            </div>
        </div>
    </header>

    <main class="cart-layout">
        <!-- Cart Items Column -->
        <div class="cart-items-section glass-card">
            <div class="cart-header">
                <h2>Tu Bolsa Espacial</h2>
                <div class="price-header">Créditos</div>
            </div>

            <div id="cart-items-container">
                <div class="cart-loader">
                    <i class="ph ph-spinner-gap spin"></i> Sincronizando datos...
                </div>
            </div>
        </div>

        <!-- Summary Column -->
        <div class="cart-summary-section">
            <div class="summary-card glass-card hover-glow">
                <h3>Resumen de Transacción</h3>
                <div class="summary-row">
                    <span>Artículos (<span id="cart-item-count-side">0</span>)</span>
                    <span id="cart-subtotal-side" style="font-weight:600;">$0.00</span>
                </div>
                <div class="summary-row">
                    <span>Entrega Drone</span>
                    <span style="color:var(--accent-cyan);">GRATIS</span>
                </div>
                <hr class="glass-hr">
                <div class="summary-row total-row">
                    <span>Total a Pagar</span>
                    <span class="gradient-text" id="cart-total">$0.00</span>
                </div>
                
                <button class="btn btn-gradient checkout-btn" id="btn-checkout">Completar Transacción <i class="ph ph-fingerprint"></i></button>
            </div>
            
            <div class="policy-card glass-card text-center text-sm">
                <i class="ph ph-shield-check" style="color:var(--accent-cyan); font-size:24px; margin-bottom:10px;"></i>
                <p>Las transacciones están aseguradas mediante blockchain distribuida y encriptación E2E.</p>
            </div>
        </div>
    </main>

    <div id="toast-container" class="toast-container"></div>

    <script src="js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
             if(typeof loadCartItems === 'function') {
                 loadCartItems();
             }
        });
        
        // Sync cart total visually since it's the same as subtotal in this prototype
        const totalEl = document.getElementById('cart-total');
        const observer = new MutationObserver(() => {
            const sub = document.getElementById('cart-subtotal-side').innerText;
            if(totalEl) totalEl.innerText = sub;
        });
        if(document.getElementById('cart-subtotal-side')) {
            observer.observe(document.getElementById('cart-subtotal-side'), { childList: true, subtree: true });
        }
    </script>
</body>
</html>
