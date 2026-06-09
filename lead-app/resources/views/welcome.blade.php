<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sohova') }} — Lead Management System</title>
    <meta name="description" content="Sohova Lead Management System — Capture, enrich, and manage your leads from a single dashboard.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-primary: #0a0a0f;
            --bg-secondary: #12121a;
            --bg-card: rgba(255, 255, 255, 0.04);
            --bg-card-hover: rgba(255, 255, 255, 0.07);
            --border-subtle: rgba(255, 255, 255, 0.08);
            --border-hover: rgba(255, 255, 255, 0.15);
            --text-primary: #f0f0f5;
            --text-secondary: #8b8b9e;
            --text-muted: #55556a;
            --accent: #f59e0b;
            --accent-glow: rgba(245, 158, 11, 0.25);
            --accent-secondary: #fb923c;
            --gradient-start: #f59e0b;
            --gradient-end: #ef4444;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Ambient glow effects */
        .glow-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
            pointer-events: none;
            z-index: 0;
        }
        .glow-orb--amber {
            width: 600px; height: 600px;
            background: var(--accent);
            top: -200px; right: -100px;
            animation: float-slow 20s ease-in-out infinite;
        }
        .glow-orb--rose {
            width: 500px; height: 500px;
            background: #e11d48;
            bottom: -150px; left: -100px;
            animation: float-slow 25s ease-in-out infinite reverse;
        }

        @keyframes float-slow {
            0%, 100% { transform: translate(0, 0); }
            33% { transform: translate(30px, -40px); }
            66% { transform: translate(-20px, 20px); }
        }

        /* Grid background pattern */
        .grid-bg {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 64px 64px;
            z-index: 0;
        }

        /* Layout */
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
            position: relative;
            z-index: 1;
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px 0;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text-primary);
        }
        .logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 16px;
            color: #fff;
            box-shadow: 0 0 20px var(--accent-glow);
        }
        .logo-text {
            font-weight: 700;
            font-size: 18px;
            letter-spacing: -0.02em;
        }
        .header-nav {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .header-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            color: var(--text-secondary);
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }
        .header-link:hover {
            color: var(--text-primary);
            background: var(--bg-card);
            border-color: var(--border-subtle);
        }
        .header-link--primary {
            background: linear-gradient(135deg, var(--gradient-start), var(--accent-secondary));
            color: #000;
            font-weight: 600;
        }
        .header-link--primary:hover {
            opacity: 0.9;
            color: #000;
            border-color: transparent;
            box-shadow: 0 0 24px var(--accent-glow);
        }

        /* Hero */
        .hero {
            text-align: center;
            padding: 80px 0 60px;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 100px;
            border: 1px solid var(--border-subtle);
            background: var(--bg-card);
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 28px;
            backdrop-filter: blur(10px);
        }
        .hero-badge-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 8px rgba(34, 197, 94, 0.5);
            animation: pulse-dot 2s ease-in-out infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        .hero h1 {
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.03em;
            margin-bottom: 20px;
        }
        .hero h1 .gradient-text {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-subtitle {
            font-size: 18px;
            line-height: 1.6;
            color: var(--text-secondary);
            max-width: 540px;
            margin: 0 auto 40px;
        }
        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.25s ease;
        }
        .btn--primary {
            background: linear-gradient(135deg, var(--gradient-start), var(--accent-secondary));
            color: #000;
            box-shadow: 0 0 30px var(--accent-glow), 0 4px 12px rgba(0,0,0,0.3);
        }
        .btn--primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 40px var(--accent-glow), 0 8px 20px rgba(0,0,0,0.4);
        }
        .btn--ghost {
            background: var(--bg-card);
            color: var(--text-primary);
            border: 1px solid var(--border-subtle);
            backdrop-filter: blur(10px);
        }
        .btn--ghost:hover {
            background: var(--bg-card-hover);
            border-color: var(--border-hover);
            transform: translateY(-2px);
        }
        .btn-arrow {
            transition: transform 0.2s ease;
        }
        .btn:hover .btn-arrow {
            transform: translateX(3px);
        }

        /* Features grid */
        .features {
            padding: 40px 0 80px;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        .feature-card {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            padding: 28px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .feature-card:hover {
            border-color: var(--border-hover);
            background: var(--bg-card-hover);
            transform: translateY(-4px);
        }
        .feature-card:hover::before { opacity: 1; }

        .feature-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 16px;
        }
        .feature-icon--amber {
            background: rgba(245, 158, 11, 0.12);
            color: var(--accent);
        }
        .feature-icon--blue {
            background: rgba(59, 130, 246, 0.12);
            color: #3b82f6;
        }
        .feature-icon--emerald {
            background: rgba(16, 185, 129, 0.12);
            color: #10b981;
        }
        .feature-icon--rose {
            background: rgba(244, 63, 94, 0.12);
            color: #f43f5e;
        }
        .feature-icon--violet {
            background: rgba(139, 92, 246, 0.12);
            color: #8b5cf6;
        }
        .feature-icon--cyan {
            background: rgba(6, 182, 212, 0.12);
            color: #06b6d4;
        }

        .feature-card h3 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-primary);
        }
        .feature-card p {
            font-size: 13px;
            line-height: 1.5;
            color: var(--text-secondary);
        }

        /* API Info section */
        .api-section {
            padding: 0 0 80px;
        }
        .api-card {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            padding: 36px;
            backdrop-filter: blur(10px);
        }
        .api-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .api-card-header h2 {
            font-size: 20px;
            font-weight: 700;
        }
        .api-method-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            font-family: 'Inter', monospace;
            letter-spacing: 0.05em;
            background: rgba(34, 197, 94, 0.12);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }
        .api-endpoint {
            background: var(--bg-secondary);
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            padding: 14px 18px;
            font-family: 'SF Mono', 'Fira Code', 'Cascadia Code', monospace;
            font-size: 14px;
            color: var(--accent);
            margin-bottom: 16px;
            overflow-x: auto;
        }
        .api-desc {
            font-size: 14px;
            line-height: 1.6;
            color: var(--text-secondary);
        }
        .api-desc code {
            background: rgba(245, 158, 11, 0.1);
            color: var(--accent);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 13px;
        }

        /* Footer */
        .footer {
            border-top: 1px solid var(--border-subtle);
            padding: 24px 0;
            text-align: center;
        }
        .footer p {
            font-size: 13px;
            color: var(--text-muted);
        }
        .footer a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.2s;
        }
        .footer a:hover {
            color: var(--accent);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .features-grid { grid-template-columns: 1fr; }
            .hero { padding: 50px 0 40px; }
            .header { flex-direction: column; gap: 16px; }
            .api-card { padding: 24px; }
        }
        @media (min-width: 769px) and (max-width: 1024px) {
            .features-grid { grid-template-columns: repeat(2, 1fr); }
        }

        /* Entrance animations */
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fade-up 0.7s ease-out forwards;
            opacity: 0;
        }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
    </style>
</head>
<body>
    <div class="glow-orb glow-orb--amber"></div>
    <div class="glow-orb glow-orb--rose"></div>
    <div class="grid-bg"></div>

    <div class="container">
        <!-- Header -->
        <header class="header animate-in">
            <a href="/" class="logo">
                <div class="logo-icon">S</div>
                <span class="logo-text">Sohova</span>
            </a>
            <nav class="header-nav">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/mumernisar') }}" class="header-link">Dashboard</a>
                        <a href="{{ url('/mumernisar/leads') }}" class="header-link header-link--primary">
                            Manage Leads →
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="header-link">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="header-link header-link--primary">Get Started</a>
                        @endif
                    @endauth
                @endif
            </nav>
        </header>

        <!-- Hero -->
        <section class="hero">
            <div class="animate-in delay-1">
                <div class="hero-badge">
                    <span class="hero-badge-dot"></span>
                    Lead Management System
                </div>
            </div>
            <h1 class="animate-in delay-2">
                Capture &amp; manage<br><span class="gradient-text">every lead</span> effortlessly
            </h1>
            <p class="hero-subtitle animate-in delay-3">
                Ingest leads via API, auto-enrich company data, and manage your entire pipeline from a single, powerful admin dashboard.
            </p>
            <div class="hero-actions animate-in delay-4">
                <a href="{{ url('/mumernisar/leads') }}" class="btn btn--primary" id="cta-manage-leads">
                    Open Lead Dashboard
                    <span class="btn-arrow">→</span>
                </a>
                <a href="{{ url('/mumernisar') }}" class="btn btn--ghost" id="cta-admin-panel">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Admin Panel
                </a>
            </div>
        </section>

        <!-- Features -->
        <section class="features">
            <div class="features-grid">
                <div class="feature-card animate-in delay-1">
                    <div class="feature-icon feature-icon--amber">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </div>
                    <h3>API Lead Ingestion</h3>
                    <p>Accept leads from any source via a secure REST API endpoint with token-based authentication.</p>
                </div>

                <div class="feature-card animate-in delay-2">
                    <div class="feature-icon feature-icon--blue">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    </div>
                    <h3>Company Enrichment</h3>
                    <p>Automatically enrich leads with company domain, industry, and size data to qualify prospects faster.</p>
                </div>

                <div class="feature-card animate-in delay-3">
                    <div class="feature-icon feature-icon--emerald">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <h3>Pipeline Management</h3>
                    <p>Track leads through every stage — new, contacted, qualified, or disqualified — with status filters.</p>
                </div>

                <div class="feature-card animate-in delay-1">
                    <div class="feature-icon feature-icon--rose">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <h3>Welcome Emails</h3>
                    <p>Automatically send welcome emails to new leads and track delivery status at a glance.</p>
                </div>

                <div class="feature-card animate-in delay-2">
                    <div class="feature-icon feature-icon--violet">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                    </div>
                    <h3>Filament Admin</h3>
                    <p>Full-featured admin panel built with Filament — search, sort, filter, and bulk manage leads.</p>
                </div>

                <div class="feature-card animate-in delay-3">
                    <div class="feature-icon feature-icon--cyan">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <h3>Token Authentication</h3>
                    <p>Secure your API endpoints with token-based middleware to prevent unauthorized lead submissions.</p>
                </div>
            </div>
        </section>

        <!-- API Info -->
        <section class="api-section">
            <div class="api-card animate-in delay-2">
                <div class="api-card-header">
                    <h2>Quick API Reference</h2>
                    <span class="api-method-badge">POST</span>
                </div>
                <div class="api-endpoint">
                    {{ config('app.url') }}/api/leads
                </div>
                <p class="api-desc">
                    Submit new leads by sending a <code>POST</code> request with an <code>Authorization</code> header.
                    Required fields: <code>name</code>, <code>email</code>.
                    Optional: <code>phone</code>, <code>company</code>, <code>message</code>, <code>source</code>.
                </p>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <p>
                &copy; {{ date('Y') }} Sohova Lead System &middot;
                Built with <a href="https://laravel.com" target="_blank" rel="noopener">Laravel</a> &amp;
                <a href="https://filamentphp.com" target="_blank" rel="noopener">Filament</a>
            </p>
        </footer>
    </div>
</body>
</html>
