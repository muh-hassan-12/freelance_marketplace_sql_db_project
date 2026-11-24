<?php
// header.php - Modern Premium UI
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' — ' . SITE_NAME : SITE_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bg: #0a0a0f;
            --surface: #13131a;
            --surface2: #1c1c28;
            --border: #2a2a3a;
            --accent: #6c63ff;
            --accent2: #ff6584;
            --accent3: #43e97b;
            --text: #e8e8f0;
            --muted: #7a7a9a;
            --card-bg: #16161f;
            --sidebar-w: 260px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--surface); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }

        /* ── NAVBAR ── */
        .topbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            height: 60px;
            background: rgba(10,10,15,0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center;
            padding: 0 24px;
            gap: 16px;
        }
        .topbar-brand {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.2rem;
            color: var(--text);
            text-decoration: none;
            display: flex; align-items: center; gap: 8px;
            letter-spacing: -0.5px;
        }
        .topbar-brand .logo-icon {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
        }
        .topbar-spacer { flex: 1; }
        .topbar-nav { display: flex; align-items: center; gap: 8px; }
        .topbar-nav a {
            color: var(--muted);
            text-decoration: none;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            display: flex; align-items: center; gap: 6px;
        }
        .topbar-nav a:hover { color: var(--text); background: var(--surface2); }
        .topbar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 6px 12px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
        }
        .topbar-user .avatar {
            width: 28px; height: 28px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: #fff;
        }
        .topbar-user .uname { font-size: 13px; font-weight: 500; color: var(--text); }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed; top: 60px; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            overflow-y: auto;
            padding: 20px 12px;
            z-index: 100;
        }
        .sidebar-section { margin-bottom: 24px; }
        .sidebar-label {
            font-size: 10px; font-weight: 700; letter-spacing: 1.5px;
            text-transform: uppercase; color: var(--muted);
            padding: 0 12px; margin-bottom: 6px;
        }
        .sidebar a {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            color: var(--muted);
            text-decoration: none;
            border-radius: 10px;
            font-size: 14px; font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 2px;
        }
        .sidebar a:hover { color: var(--text); background: var(--surface2); }
        .sidebar a.active { color: var(--accent); background: rgba(108,99,255,0.12); }
        .sidebar a i { font-size: 16px; width: 20px; }
        .sidebar a.danger { color: var(--accent2); }
        .sidebar a.danger:hover { background: rgba(255,101,132,0.1); }

        /* ── MAIN CONTENT ── */
        .main {
            margin-left: var(--sidebar-w);
            margin-top: 60px;
            padding: 32px;
            min-height: calc(100vh - 60px);
        }
        .main-full { margin-left: 0; margin-top: 60px; padding: 32px; }

        /* ── PAGE HEADER ── */
        .page-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 1.6rem;
            color: var(--text);
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }
        .page-subtitle { color: var(--muted); font-size: 14px; margin-bottom: 28px; }

        /* ── CARDS ── */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }
        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            font-weight: 600;
            font-size: 14px;
            color: var(--text);
            display: flex; align-items: center; justify-content: space-between;
            background: transparent;
        }
        .card-body { padding: 24px; }

        /* ── STAT CARDS ── */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 16px; margin-bottom: 28px; }
        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
            position: relative; overflow: hidden;
            transition: transform 0.2s, border-color 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); border-color: var(--accent); }
        .stat-card::before {
            content: '';
            position: absolute; top: -30px; right: -30px;
            width: 80px; height: 80px;
            border-radius: 50%;
            opacity: 0.15;
        }
        .stat-card.blue::before { background: var(--accent); }
        .stat-card.green::before { background: var(--accent3); }
        .stat-card.pink::before { background: var(--accent2); }
        .stat-card.gold::before { background: #f59e0b; }
        .stat-card.cyan::before { background: #06b6d4; }
        .stat-num {
            font-family: 'Syne', sans-serif;
            font-size: 2rem; font-weight: 800;
            line-height: 1;
            margin-bottom: 6px;
        }
        .stat-card.blue .stat-num { color: var(--accent); }
        .stat-card.green .stat-num { color: var(--accent3); }
        .stat-card.pink .stat-num { color: var(--accent2); }
        .stat-card.gold .stat-num { color: #f59e0b; }
        .stat-card.cyan .stat-num { color: #06b6d4; }
        .stat-label { font-size: 12px; color: var(--muted); font-weight: 500; }

        /* ── TABLES ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: 12px 16px;
            text-align: left;
            font-size: 11px; font-weight: 700; letter-spacing: 0.8px;
            text-transform: uppercase; color: var(--muted);
            border-bottom: 1px solid var(--border);
            background: var(--surface);
        }
        tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            font-size: 14px; color: var(--text);
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: rgba(255,255,255,0.02); }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 18px; border-radius: 10px;
            font-size: 14px; font-weight: 600;
            cursor: pointer; border: none;
            text-decoration: none; transition: all 0.2s;
            font-family: 'DM Sans', sans-serif;
        }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: #5a52e0; color: #fff; }
        .btn-danger { background: rgba(255,101,132,0.15); color: var(--accent2); border: 1px solid rgba(255,101,132,0.3); }
        .btn-danger:hover { background: var(--accent2); color: #fff; }
        .btn-outline { background: transparent; color: var(--muted); border: 1px solid var(--border); }
        .btn-outline:hover { color: var(--text); border-color: var(--accent); background: rgba(108,99,255,0.08); }
        .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 8px; }
        .btn-success { background: rgba(67,233,123,0.15); color: var(--accent3); border: 1px solid rgba(67,233,123,0.3); }
        .btn-success:hover { background: var(--accent3); color: #000; }

        /* ── BADGES ── */
        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 600; letter-spacing: 0.3px;
        }
        .badge-blue { background: rgba(108,99,255,0.2); color: var(--accent); }
        .badge-green { background: rgba(67,233,123,0.2); color: var(--accent3); }
        .badge-red { background: rgba(255,101,132,0.2); color: var(--accent2); }
        .badge-yellow { background: rgba(245,158,11,0.2); color: #f59e0b; }
        .badge-gray { background: rgba(122,122,154,0.2); color: var(--muted); }
        .badge-cyan { background: rgba(6,182,212,0.2); color: #06b6d4; }

        /* ── FORMS ── */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--muted); margin-bottom: 7px; letter-spacing: 0.3px; }
        .form-control, .form-select {
            width: 100%; padding: 11px 14px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-size: 14px; font-family: 'DM Sans', sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .form-control:focus, .form-select:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(108,99,255,0.15); }
        .form-control::placeholder { color: var(--muted); }
        textarea.form-control { resize: vertical; min-height: 100px; }
        .form-check { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
        .form-check-input { width: 16px; height: 16px; accent-color: var(--accent); cursor: pointer; }
        .form-check-label { font-size: 14px; color: var(--text); cursor: pointer; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        /* ── ALERTS ── */
        .alert { padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: rgba(67,233,123,0.1); border: 1px solid rgba(67,233,123,0.3); color: var(--accent3); }
        .alert-danger { background: rgba(255,101,132,0.1); border: 1px solid rgba(255,101,132,0.3); color: var(--accent2); }
        .alert-info { background: rgba(108,99,255,0.1); border: 1px solid rgba(108,99,255,0.3); color: var(--accent); }

        /* ── MISC ── */
        hr { border-color: var(--border); margin: 20px 0; }
        a { color: var(--accent); text-decoration: none; }
        a:hover { color: #9d97ff; }
        code { background: var(--surface2); padding: 2px 6px; border-radius: 4px; font-size: 13px; color: var(--accent3); }
        .text-muted { color: var(--muted) !important; }
        .text-center { text-align: center; }
        .mt-auto { margin-top: auto; }
        .fw-bold { font-weight: 700; }
        .d-flex { display: flex; }
        .align-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .mb-1 { margin-bottom: 6px; }
        .mb-2 { margin-bottom: 12px; }
        .mb-3 { margin-bottom: 18px; }
        .mb-4 { margin-bottom: 24px; }
        .mt-2 { margin-top: 12px; }
        .mt-3 { margin-top: 18px; }
        .w-100 { width: 100%; }
        .empty-state { text-align: center; padding: 60px 20px; color: var(--muted); }
        .empty-state i { font-size: 3rem; display: block; margin-bottom: 14px; opacity: 0.4; }
        .empty-state p { font-size: 15px; }

        /* ── ANIMATIONS ── */
        @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp 0.4s ease forwards; }
        .fade-up-2 { animation: fadeUp 0.4s 0.08s ease both; }
        .fade-up-3 { animation: fadeUp 0.4s 0.16s ease both; }

        /* ── AUTH PAGE ── */
        .auth-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; margin-top: 0 !important; }
        .auth-card { width: 100%; max-width: 460px; background: var(--card-bg); border: 1px solid var(--border); border-radius: 20px; padding: 40px; animation: fadeUp 0.5s ease; }
        .auth-logo { text-align: center; margin-bottom: 28px; }
        .auth-logo .icon { width: 52px; height: 52px; background: linear-gradient(135deg, var(--accent), var(--accent2)); border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 12px; }
        .auth-logo h2 { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.4rem; letter-spacing: -0.5px; }
        .auth-logo p { color: var(--muted); font-size: 14px; margin-top: 4px; }
        .auth-divider { text-align: center; color: var(--muted); font-size: 13px; margin: 18px 0; position: relative; }
        .auth-divider::before { content:''; position:absolute; top:50%; left:0; right:0; height:1px; background:var(--border); }
        .auth-divider span { background: var(--card-bg); padding: 0 12px; position: relative; }

        /* ── HOME PAGE ── */
        .hero {
            min-height: calc(100vh - 60px);
            display: flex; align-items: center; justify-content: center;
            text-align: center; padding: 60px 20px;
            position: relative; overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute; top: -200px; left: 50%; transform: translateX(-50%);
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(108,99,255,0.15) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-title {
            font-family: 'Syne', sans-serif;
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 800; letter-spacing: -2px;
            line-height: 1.1; margin-bottom: 20px;
        }
        .hero-title .grad { background: linear-gradient(135deg, var(--accent), var(--accent2)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero-sub { font-size: 1.1rem; color: var(--muted); max-width: 500px; margin: 0 auto 36px; }
        .hero-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn-hero { padding: 13px 28px; font-size: 15px; border-radius: 12px; }
        .feature-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; padding: 60px 32px; }
        .feature-card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 16px; padding: 28px; transition: border-color 0.2s, transform 0.2s; }
        .feature-card:hover { border-color: var(--accent); transform: translateY(-3px); }
        .feature-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 16px; }
        .feature-card h4 { font-family: 'Syne', sans-serif; font-weight: 700; margin-bottom: 8px; }
        .feature-card p { color: var(--muted); font-size: 14px; }
        .jobs-section { padding: 20px 32px 60px; }
        .job-card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 16px; padding: 20px; transition: border-color 0.2s, transform 0.2s; }
        .job-card:hover { border-color: var(--accent); transform: translateY(-2px); }
        .job-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px; }
        .section-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.5rem; margin-bottom: 20px; letter-spacing: -0.5px; }
    </style>
</head>
<body>
<nav class="topbar">
    <a href="<?= BASE_URL ?>/index.php" class="topbar-brand">
        <div class="logo-icon">⚡</div>
        <?= SITE_NAME ?>
    </a>
    <div class="topbar-spacer"></div>
    <div class="topbar-nav">
        <?php if (isLoggedIn()): ?>
            <a href="<?= BASE_URL ?>/index.php?page=dashboard"><i class="bi bi-grid"></i> Dashboard</a>
            <a href="<?= BASE_URL ?>/index.php?page=profile"><i class="bi bi-person"></i> Profile</a>
            <div class="topbar-user">
                <div class="avatar"><?= strtoupper(substr($_SESSION['first_name'],0,1)) ?></div>
                <span class="uname"><?= htmlspecialchars($_SESSION['first_name']) ?></span>
            </div>
            <a href="<?= BASE_URL ?>/index.php?page=logout" style="color:var(--accent2)"><i class="bi bi-box-arrow-right"></i></a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>/index.php?page=login">Login</a>
            <a href="<?= BASE_URL ?>/index.php?page=register" class="btn btn-primary btn-sm" style="border-radius:8px">Get Started</a>
        <?php endif; ?>
    </div>
</nav>
