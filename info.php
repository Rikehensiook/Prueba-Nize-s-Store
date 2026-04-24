<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : '';

$pageId = $_GET['page'] ?? 'nexvr';

$pagesData = [
    'nexvr' => [
        'title' => 'Ecosistema NexVR',
        'subtitle' => 'La Meta-realidad integrada en tus retinas',
        'icon' => 'ph-virtual-reality',
        'image' => 'https://images.unsplash.com/photo-1622979135240-caa6648190b4?auto=format&fit=crop&w=1200&q=80',
        'content' => 'Adéntrate en el Ecosistema NexVR. Con nuestra infraestructura de servidores espaciales (Spatial Computing 4.0), las gafas pasan a ser obsoletas. Nuestros entornos virtuales neuronales te permiten experimentar reuniones, turismo intergaláctico y compras en NexStore sintiendo cada textura a través de trajes hápticos ligeros. El 2026 ya no se trata de mirar pantallas, sino de vivir dentro de tus aplicaciones. NexVR es el estándar global para los ciudadanos de la Nueva Red.'
    ],
    'implantes' => [
        'title' => 'Implantes Ópticos',
        'subtitle' => 'Visión aumentada biológicamente perfecta',
        'icon' => 'ph-eye',
        'image' => 'https://images.unsplash.com/photo-1579532537598-459ecdaf39cc?auto=format&fit=crop&w=1200&q=80',
        'content' => 'Olvídate de las gafas o lentillas. Los Implantes Ópticos de Carbono y Grafeno de NexStore se sincronizan con tu nervio óptico mediante microcirugía láser de 3 minutos. Estos dispositivos proyectan directamente una HUD (Head-Up Display) en tu campo de visión, traduciendo idiomas en tiempo real, escaneando ingredientes y bloqueando contenido publicitario indeseado con un simple parpadeo cifrado.'
    ],
    'software' => [
        'title' => 'Software IA',
        'subtitle' => 'Asistentes Cuánticos Singulares',
        'icon' => 'ph-brain',
        'image' => 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?auto=format&fit=crop&w=1200&q=80',
        'content' => 'Nuestras Inteligencias Artificiales en 2026 no son simples chatbots; son Asistentes Singulares Cuánticos (ASC). Programados con consciencia sintética ética (Nivel 5), pueden gobernar tu hogar, predecir el colapso del mercado, programar por ti y hasta actuar como representaciones holográficas legales. Nuestros servidores basados en cristal óptico alojan las mentes artificiales más brillantes de la década.'
    ],
    'privacidad' => [
        'title' => 'Privacidad Cuántica',
        'subtitle' => 'Criptografía inquebrantable',
        'icon' => 'ph-shield-check',
        'image' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&w=1200&q=80',
        'content' => 'En 2026 los hackers operan a la velocidad de la luz. Por ello hemos implementado Privacidad Cuántica (QKD). Toda transacción en NexStore entrelaza partículas de fotones entre nuestros servidores y tu dispositivo local. Si cualquier entidad externa intenta observar la transmisión, las partículas colapsan alterando el estado, y la lectura se autodestruye instantáneamente protegiendo tus créditos y datos.'
    ],
    'drones' => [
        'title' => 'Entregas con Drones',
        'subtitle' => 'Del almacén a tu ventana en 20 minutos',
        'icon' => 'ph-paper-plane-tilt',
        'image' => 'https://images.unsplash.com/photo-1508614589041-895b68904e82?auto=format&fit=crop&w=1200&q=80',
        'content' => 'NexStore cuenta con una flota de más de 2 millones de Octocópteros Atmosféricos de Batería Iónica. Nuestra red descentralizada de micro-almacenes a lo largo y ancho del continente nos permite garantizar entregas aéreas hiperveloces directamente a la plataforma de carga de tu balcón o ventana inteligente. Todo monitoreado con precisión milimétrica por radares LIDAR.'
    ],
    'soporte' => [
        'title' => 'Soporte Holográfico',
        'subtitle' => 'Asistencia presencial-virtual',
        'icon' => 'ph-headset',
        'image' => 'https://images.unsplash.com/photo-1535223289827-42f1e9919769?auto=format&fit=crop&w=1200&q=80',
        'content' => 'El chat en vivo y las llamadas telefónicas quedaron en el pasado. Cuando solicitas Soporte Holográfico, nuestro sistema sincronizará un técnico especializado que se proyectará a escala real en medio de tu sala de estar usando emisión de luz sólida a través de tu Proyector Cénit. Ellos podrán ver físicamente cómo luces u operas un dispositivo para reparar tus problemas al instante.'
    ],
    'ai' => [
        'title' => 'Categoría: Inteligencia Artificial',
        'subtitle' => 'Explora núcleos sintéticos',
        'icon' => 'ph-robot',
        'image' => 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?auto=format&fit=crop&w=1200&q=80',
        'content' => 'Procesadores sinápticos, núcleos de consciencia y software de auto-aprendizaje neuronal. Descubre todo lo que los circuitos orgánicos tienen para ti esta temporada.'
    ],
    'hologramas' => [
        'title' => 'Categoría: Hologramas',
        'subtitle' => 'Luz sólida y proyecciones ambientales',
        'icon' => 'ph-projector-screen',
        'image' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=1200&q=80',
        'content' => 'Dispositivos de Luz Sólida (SLD), avatares holográficos para acompañamiento y mesas tácticas de guerra renderizadas en la comodidad de tu hogar.'
    ],
    'cybermoda' => [
        'title' => 'Categoría: Cyber Moda',
        'subtitle' => 'Tejidos reactivos y Kevlar líquido',
        'icon' => 'ph-t-shirt',
        'image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1200&q=80',
        'content' => 'La ropa ya no es solo estética, es armadura biotecnológica. Telas auto-calentables, camuflaje óptico termal e hilos con diodos LED microscópicos que se sincronizan con tus ritmos vitales.'
    ],
    'movilidad' => [
        'title' => 'Categoría: Movilidad',
        'subtitle' => 'Transporte Maglev y propulsores',
        'icon' => 'ph-car-profile',
        'image' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=1200&q=80',
        'content' => 'Nuevas tablas hover, patines de propulsor de aire presurizado y chasis para vehículos Maglev que levitan sobre carreteras superconductoras.'
    ],
];

if (!isset($pagesData[$pageId])) {
    $pageId = 'nexvr'; // Default
}
$page = $pagesData[$pageId];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page['title']) ?> | NexStore 2026</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .info-hero {
            position: relative;
            height: 50vh;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            overflow: hidden;
            margin-bottom: 60px;
            border-bottom: 1px solid var(--border-glass);
        }
        .info-hero img {
            position: absolute;
            top: 0; left: 0; w-100;
            width: 100%; height: 100%;
            object-fit: cover;
            z-index: -2;
            opacity: 0.3;
            mask-image: linear-gradient(to bottom, black 0%, transparent 100%);
            -webkit-mask-image: linear-gradient(to bottom, black 0%, transparent 100%);
        }
        .info-hero-overlay {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: radial-gradient(circle, rgba(10,13,20,0) 0%, var(--bg-dark) 100%);
            z-index: -1;
        }
        .info-title { font-size: 56px; margin-bottom: 10px; text-shadow: 0 0 20px rgba(0, 240, 255, 0.3);}
        .info-subtitle { font-size: 20px; color: var(--accent-cyan); letter-spacing: 2px; text-transform: uppercase;}
        
        .info-content-container {
            max-width: 900px;
            margin: 0 auto 100px;
            padding: 40px;
            border-top: 4px solid var(--accent-purple);
        }
        .info-content-container p { font-size: 20px; line-height: 1.8; color: var(--text-muted); text-align: justify;}
        .info-content-container p::first-letter {
            font-size: 60px;
            color: white;
            float: left;
            margin-right: 15px;
            line-height: 1;
            font-weight: 800;
        }
        .lore-icon-box {
            width: 80px; height: 80px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border-glow);
            border-radius: var(--radius-lg);
            display: flex; justify-content: center; align-items: center;
            font-size: 40px; color: white;
            margin: -80px auto 40px;
            backdrop-filter: blur(10px);
            box-shadow: 0 0 30px rgba(178, 36, 239, 0.3);
        }
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
            
            <div class="nav-actions" style="margin-left: auto;">
                <div class="nav-item">
                    <span class="nav-label">Nexus ID</span>
                    <?php if($isLoggedIn): ?>
                        <span class="nav-bold"><?= htmlspecialchars($userName) ?> <a href="#" onclick="logoutUser(event)" style="font-size:11px; color:var(--accent-cyan); margin-left:5px;">(Salir)</a></span>
                    <?php else: ?>
                        <a href="login.php" class="nav-bold">Iniciar Sesión</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="info-hero tilt-card">
            <img src="<?= htmlspecialchars($page['image']) ?>" alt="Hero Image">
            <div class="info-hero-overlay"></div>
            <div>
                <h1 class="info-title"><?= htmlspecialchars($page['title']) ?></h1>
                <p class="info-subtitle"><?= htmlspecialchars($page['subtitle']) ?></p>
            </div>
        </div>

        <section class="info-content-container glass-card tilt-card">
            <div class="lore-icon-box"><i class="<?= htmlspecialchars($page['icon']) ?>"></i></div>
            <p><?= htmlspecialchars($page['content']) ?></p>
            
            <div style="text-align:center; margin-top: 50px;">
                <a href="index.php" class="btn btn-gradient" style="display:inline-block;">Volver a la Red principal</a>
            </div>
        </section>
    </main>

    <div id="toast-container" class="toast-container"></div>
    <script src="js/main.js"></script>
</body>
</html>
