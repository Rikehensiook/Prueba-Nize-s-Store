<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conexión Neural | NexStore 2026</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .auth-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
        }
        .auth-card {
            width: 100%;
            max-width: 450px;
            padding: 40px;
        }
        .auth-header { text-align: center; margin-bottom: 30px; }
        .auth-header h2 { font-size: 28px; margin-bottom: 10px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; color: var(--text-muted); margin-bottom: 5px; text-transform: uppercase; letter-spacing: 1px;}
        .form-control {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-md);
            padding: 15px;
            color: white;
            font-family: var(--font-main);
            font-size: 16px;
            outline: none;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: var(--accent-cyan);
            box-shadow: 0 0 15px rgba(0,240,255,0.2);
            background: rgba(255,255,255,0.08);
        }
        .auth-footer { text-align: center; margin-top: 20px; font-size: 14px; color: var(--text-muted); }
        .error-msg { color: #FF6B6B; font-size: 13px; text-align: center; margin-bottom: 15px; display: none; }
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
        </div>
    </header>

    <main class="auth-container">
        <div class="glass-card auth-card">
            <div class="auth-header">
                <h2>Iniciar Sesión</h2>
                <p style="color:var(--text-muted);">Bienvenido de vuelta a Nexus</p>
            </div>
            
            <div class="error-msg" id="login-error"></div>

            <form id="login-form">
                <div class="form-group">
                    <label>Identidad Digital (Email)</label>
                    <input type="email" id="email" class="form-control" placeholder="usuario@nexus.id" required>
                </div>
                <div class="form-group">
                    <label>Llave Criptográfica (Password)</label>
                    <input type="password" id="password" class="form-control" placeholder="••••••••" required>
                </div>
                
                <button type="submit" class="btn btn-gradient" style="width: 100%; margin-top:10px;">Autenticar <i class="ph ph-fingerprint"></i></button>
            </form>

            <div class="auth-footer">
                ¿No tienes tu Nexus ID? <br><br>
                <a href="register.php" class="btn btn-glass" style="width: 100%;">Registrar Nueva Identidad</a>
            </div>
        </div>
    </main>
    <div id="toast-container" class="toast-container"></div>
    <script src="js/main.js"></script>
    <script>
        document.getElementById('login-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const errEl = document.getElementById('login-error');

            const btn = e.target.querySelector('button');
            btn.innerHTML = 'Procesando... <i class="ph ph-spinner-gap spin"></i>';
            btn.disabled = true;

            try {
                const res = await fetch('api/auth.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({action: 'login', email, password})
                });
                const json = await res.json();

                if (json.status === 'success') {
                    window.location.href = 'index.php';
                } else {
                    errEl.innerText = json.message;
                    errEl.style.display = 'block';
                    btn.innerHTML = 'Autenticar <i class="ph ph-fingerprint"></i>';
                    btn.disabled = false;
                }
            } catch(e) {
                errEl.innerText = 'Error de conexión cuántica.';
                errEl.style.display = 'block';
                btn.innerHTML = 'Autenticar <i class="ph ph-fingerprint"></i>';
                btn.disabled = false;
            }
        });
    </script>
</body>
</html>
