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
    <title>NexStore 2026 | Future E-commerce</title>
    <!-- Google Fonts: Plus Jakarta Sans for a very modern, 2026 tech feel -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>
    <!-- Futuristic Background Orbs -->
    <div class="bg-orb orb-1"></div>
    <div class="bg-orb orb-2"></div>

    <!-- Header Glassmorphism -->
    <header class="navbar glass-panel">
        <div class="nav-container">
            <a href="index.php" class="nav-logo">
                <div class="logo-icon-box"><i class="ph-fill ph-planet"></i></div>
                <span>NEXSTORE</span>
            </a>

            <!-- Seamless Search -->
            <div class="search-form glow-focus">
                <input type="text" id="search-input" placeholder="Buscar tecnología, moda y futuro...">
                <button class="search-btn">
                    <i class="ph ph-magnifying-glass"></i>
                </button>
            </div>

            <!-- Actions -->
            <div class="nav-actions">
                <div class="nav-item">
                    <span class="nav-label">Nexus ID</span>
                    <?php if($isLoggedIn): ?>
                        <span class="nav-bold"><?= htmlspecialchars($userName) ?> <a href="#" onclick="logoutUser(event)" style="font-size:11px; color:var(--accent-cyan); margin-left:5px;">(Salir)</a></span>
                    <?php else: ?>
                        <a href="login.php" class="nav-bold">Iniciar Sesión</a>
                    <?php endif; ?>
                </div>
                
                <a href="cart.php" class="nav-cart">
                    <div class="cart-wrapper">
                        <i class="ph ph-shopping-bag-open"></i>
                        <div class="cart-pulse"></div>
                        <span class="cart-badge" id="cart-count">0</span>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- Category Pills -->
        <div class="category-pills">
            <a href="index.php" class="pill active">Descubrir</a>
            <a href="info.php?page=ai" class="pill">Inteligencia Artificial</a>
            <a href="info.php?page=hologramas" class="pill">Hologramas</a>
            <a href="info.php?page=cybermoda" class="pill">Cyber Moda</a>
            <a href="info.php?page=movilidad" class="pill">Movilidad</a>
        </div>
    </header>

    <main>
        <!-- Futuristic Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <div class="badge-new">NUEVA COLECCIÓN 2026</div>
                <h2>Transciende la<br><span class="gradient-text">Realidad.</span></h2>
                <p>Equipos de neurociencia, trajes hápticos y procesadores cuánticos ya disponibles en NexStore Prime.</p>
                <div class="hero-actions">
                    <button class="btn btn-gradient">Explorar Catálogo <i class="ph ph-arrow-right"></i></button>
                    <button class="btn btn-glass">Ver Video</button>
                </div>
            </div>
            <div class="hero-visual">
                <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=800&q=80" alt="Tech Abstract 2026" class="floating-img">
            </div>
            
            <div class="scroll-indicator" id="scrollIndicator">
                <div class="mouse-icon"></div>
                <span>Descubre Más</span>
            </div>
        </section>

        <!-- Dynamic Grid -->
        <section class="products-section">
            <div class="section-title scroll-anim">
                <h3>Tendencias Singulares</h3>
                <a href="#" class="view-all">Ver todo <i class="ph ph-caret-right"></i></a>
            </div>
            
            <div class="products-grid" id="products-container">
                <!-- Skeletons -->
                <div class="loading-skeleton glass-card">
                    <div class="skeleton-img"></div>
                    <div class="skeleton-text"></div>
                    <div class="skeleton-text short"></div>
                </div>
                <div class="loading-skeleton glass-card">
                    <div class="skeleton-img"></div>
                    <div class="skeleton-text"></div>
                    <div class="skeleton-text short"></div>
                </div>
                <div class="loading-skeleton glass-card">
                    <div class="skeleton-img"></div>
                    <div class="skeleton-text"></div>
                    <div class="skeleton-text short"></div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer Glass -->
    <footer class="glass-footer">
        <div class="footer-grid">
            <div class="f-col">
                <div class="nav-logo" style="margin-bottom:20px;">
                    <div class="logo-icon-box"><i class="ph-fill ph-planet"></i></div>
                    <span>NEXSTORE</span>
                </div>
                <p style="color:var(--text-muted); font-size:14px; max-width:250px;">Potenciando el futuro del comercio desde 2026. Conexión neuronal cifrada 256-bit.</p>
            </div>
            <div class="f-col">
                <h4>Ecosistema</h4>
                <a href="info.php?page=nexvr">NexVR</a>
                <a href="info.php?page=implantes">Implantes Ópticos</a>
                <a href="info.php?page=software">Software IA</a>
            </div>
            <div class="f-col">
                <h4>Información</h4>
                <a href="info.php?page=privacidad">Privacidad Cuántica</a>
                <a href="info.php?page=drones">Entregas con Drones</a>
                <a href="info.php?page=soporte">Soporte Holográfico</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 NexStore Corporation. Diseño Antigravity Advanced.</p>
        </div>
    </footer>

    <!-- Toasts -->
    <div id="toast-container" class="toast-container"></div>

    <script src="js/main.js"></script>
</body>
</html>
