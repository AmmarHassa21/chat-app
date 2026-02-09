<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Chat</title>
    <style>
        :root {
            --bg: #0f172a;
            --card: #111827;
            --accent: #22d3ee;
            --text: #e5e7eb;
            --muted: #94a3b8;
            --border: #1f2937;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Inter", system-ui, -apple-system, Segoe UI, sans-serif;
            background: radial-gradient(circle at 20% 20%, rgba(34,211,238,0.1), transparent 35%),
                        radial-gradient(circle at 80% 0%, rgba(34,211,238,0.1), transparent 25%),
                        var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
        }
        .card {
            background: linear-gradient(135deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01));
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 32px 40px;
            width: min(960px, 100%);
            box-shadow: 0 30px 80px rgba(0,0,0,0.25);
        }
        .pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(34,211,238,0.08);
            color: var(--accent);
            font-weight: 600;
            border: 1px solid rgba(34,211,238,0.25);
        }
        h1 {
            font-size: clamp(28px, 5vw, 42px);
            margin: 18px 0 10px;
            letter-spacing: -0.02em;
        }
        p { margin: 0; color: var(--muted); font-size: 16px; line-height: 1.6; }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-top: 28px;
        }
        .item {
            padding: 16px;
            border-radius: 14px;
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.02);
        }
        .label { color: var(--muted); font-size: 13px; text-transform: uppercase; letter-spacing: 0.08em; }
        .value { font-weight: 700; margin-top: 6px; font-size: 16px; }
    </style>
</head>
<body>
    <main class="card">
        <div class="pill">Laravel Chat · API + UI Shell</div>
        <h1>Welcome to the Laravel Chat backend</h1>
        <p>This backend ships JSON-first endpoints plus auth scaffolding. Use the Mailpit tab for local email testing. The root route now shows this default view.</p>
        <div class="grid">
            <div class="item">
                <div class="label">API Base</div>
                <div class="value">http://localhost:8000</div>
            </div>
            <div class="item">
                <div class="label">Mailpit</div>
                <div class="value">http://localhost:8025</div>
            </div>
            <div class="item">
                <div class="label">SMTP</div>
                <div class="value">host: mail · port: 1025</div>
            </div>
            <div class="item">
                <div class="label">Seed Admin</div>
                <div class="value">admin@example.com / password</div>
            </div>
        </div>
    </main>
</body>
</html>
