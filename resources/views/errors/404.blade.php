<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Página no Encontrada | NESISTEMA 2.0</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Figtree', sans-serif; }
        body { background-color: #0f172a; color: #f8fafc; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card { background: #1e293b; border: 1.5px solid #334155; border-radius: 28px; max-width: 520px; width: 100%; padding: 40px; text-align: center; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); position: relative; overflow: hidden; }
        .card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #6366f1, #38bdf8, #10b981); }
        .icon-box { width: 80px; height: 80px; border-radius: 24px; background: rgba(99, 102, 241, 0.15); border: 2px solid rgba(99, 102, 241, 0.4); color: #818cf8; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.2); }
        .badge { display: inline-block; padding: 4px 12px; background: rgba(99, 102, 241, 0.2); color: #c7d2fe; border: 1px solid rgba(99, 102, 241, 0.4); border-radius: 9999px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; }
        h1 { font-size: 24px; font-weight: 900; color: #ffffff; margin-bottom: 10px; }
        p { font-size: 13px; color: #94a3b8; font-weight: 500; line-height: 1.6; margin-bottom: 24px; }
        .btn-group { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn-primary { background: #4f46e5; color: #ffffff; padding: 12px 24px; border-radius: 14px; font-size: 13px; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; border: none; cursor: pointer; }
        .btn-primary:hover { background: #4338ca; transform: translateY(-1px); }
        .btn-secondary { background: #334155; color: #cbd5e1; padding: 12px 20px; border-radius: 14px; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; border: 1px solid #475569; cursor: pointer; }
        .btn-secondary:hover { background: #475569; color: #ffffff; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-box">
            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <span class="badge">HTTP 404 • RECURSO NO ENCONTRADO</span>
        <h1>Página no Encontrada</h1>
        <p>
            {{ $exception->getMessage() ?: 'El recurso o página que intentas consultar no existe o ha sido movido a otra ubicación.' }}
        </p>
        <div class="btn-group">
            <a href="{{ route('dashboard') }}" class="btn-primary">
                <span>Ir al Dashboard</span> &rarr;
            </a>
            <button onclick="window.history.back()" class="btn-secondary">
                <span>← Regresar</span>
            </button>
        </div>
    </div>
</body>
</html>
