<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinto Pasai - Dashboard Pemasok</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Urbanist:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --navy: #003366;
            --emerald: #008037;
            --gold: #c5a059;
            --bg-sidebar: #ffffff;
            --bg-card: rgba(255, 255, 255, 0.9);
            --bg-input: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: rgba(226, 232, 240, 0.9);
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }

        body {
            background-color: #f8fafc;
            background-image:
                linear-gradient(rgba(0, 51, 102, 0.015) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 128, 55, 0.015) 1px, transparent 1px);
            background-size: 40px 40px;
            color: var(--text-main);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* SIDEBAR */
        .sidebar {
            width: 280px;
            background-color: var(--bg-sidebar);
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 24px 20px;
            z-index: 100;
            flex-shrink: 0;
            box-shadow: 4px 0 15px rgba(0,0,0,0.02);
        }

        .brand {
            display: flex; align-items: center; gap: 12px;
            padding-bottom: 24px; border-bottom: 1px solid #f1f5f9;
        }
        .brand-name { font-family: 'Urbanist', sans-serif; font-size: 22px; font-weight: 800; color: var(--navy); }
        .brand-name span { color: var(--emerald); }

        .menu-list {
            margin-top: 20px; list-style: none;
            display: flex; flex-direction: column; gap: 4px; flex-grow: 1;
        }

        .menu-item {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 14px; color: var(--text-muted);
            text-decoration: none; border-radius: 10px;
            font-weight: 500; cursor: pointer; font-size: 14px;
            transition: all 0.18s;
        }
        .menu-item i { width: 20px; font-size: 15px; text-align: center; }
        .menu-item:hover { background-color: #f1f5f9; color: var(--navy); }
        .menu-item.active { background-color: var(--navy); color: #ffffff; }

        .user-profile {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 14px; margin-top: 8px;
            border-top: 1px solid #f1f5f9; padding-top: 18px;
            cursor: pointer; border-radius: 10px; transition: all 0.18s;
        }
        .user-profile:hover { background-color: #f1f5f9; }
        .user-profile.active { background-color: var(--navy); }
        .user-profile.active #side-username-display { color: #ffffff !important; }
        .user-profile.active p { color: rgba(255,255,255,0.65) !important; }
        .user-profile.active .avatar-mock { background-color: rgba(255,255,255,0.2); }
        
        .avatar-mock {
            width: 40px; height: 40px; background-color: var(--gold);
            border-radius: 12px; display: flex; align-items: center;
            justify-content: center; font-weight: 700; color: white; font-size: 14px;
            flex-shrink: 0;
        }

        /* MAIN CONTENT */
        .main-content { flex-grow: 1; padding: 36px 40px; overflow-y: auto; height: 100vh; }

        .page { display: none; animation: fadeIn 0.2s ease; }
        .page.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }

        .page-title { font-size: 22px; font-weight: 700; color: var(--navy); margin-bottom: 4px; }
        .page-subtitle { color: var(--text-muted); font-size: 13px; margin-bottom: 24px; }

        /* CARDS */
        .content-card {
            background-color: var(--bg-card); border-radius: 16px; padding: 24px;
            border: 1px solid var(--border); margin-bottom: 24px;
            position: relative; overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }
        .content-card::before {
            content: ""; position: absolute; top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(90deg, var(--navy) 0%, var(--gold) 50%, var(--emerald) 100%);
        }

        /* STATS */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 28px; }
        .stat-card {
            background: white; border: 1px solid var(--border); border-radius: 16px;
            padding: 22px; display: flex; align-items: center; gap: 15px;
            position: relative; overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }
        .stat-card::before { content: ""; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, var(--navy) 0%, var(--gold) 50%, var(--emerald) 100%); }
        .stat-icon { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
        .stat-icon.blue { background: rgba(0, 51, 102, 0.08); color: var(--navy); }
        .stat-icon.green { background: rgba(0, 128, 55, 0.08); color: var(--emerald); }
        .stat-icon.gold { background: rgba(197, 160, 89, 0.08); color: var(--gold); }
        .stat-icon.red { background: rgba(239, 68, 68, 0.08); color: var(--danger); }

        /* TABLES */
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
        th { color: var(--navy); padding: 13px 14px; border-bottom: 2px solid #e2e8f0; font-weight: 600; background-color: #f8fafc; font-size: 12px; text-transform: uppercase; letter-spacing: 0.3px; }
        td { padding: 13px 14px; border-bottom: 1px solid #f1f5f9; color: var(--text-main); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background-color: #fafbfc; }

        /* BADGES */
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-block; }
        .badge.success { background: rgba(16, 185, 129, 0.12); color: var(--success); }
        .badge.warning { background: rgba(245, 158, 11, 0.12); color: var(--warning); }
        .badge.danger { background: rgba(239, 68, 68, 0.12); color: var(--danger); }
        .badge.info { background: rgba(0, 51, 102, 0.1); color: var(--navy); }

        /* INPUTS */
        label { font-size: 12px; font-weight: 600; color: var(--navy); display: block; margin-bottom: 5px; }
        input[type="text"], input[type="password"], input[type="number"], input[type="email"], textarea, select {
            background-color: var(--bg-input); border: 1.5px solid #e2e8f0; color: var(--text-main);
            padding: 12px 14px; border-radius: 10px; font-size: 14px; outline: none;
            width: 100%; transition: all 0.2s; margin-top: 0;
        }
        input:focus, textarea:focus, select:focus { border-color: var(--emerald); background-color: #ffffff; box-shadow: 0 0 0 3px rgba(0, 128, 55, 0.08); }

        .btn-submit {
            background-color: var(--emerald); color: white; border: none;
            padding: 13px 24px; font-weight: 600; border-radius: 10px;
            cursor: pointer; transition: all 0.2s;
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            font-size: 14px;
        }
        .btn-submit:hover { background-color: #006b2e; }
        .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }

        .btn-danger {
            background-color: var(--danger); color: white; border: none;
            padding: 7px 14px; font-weight: 600; border-radius: 8px;
            cursor: pointer; font-size: 12px; transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-danger:hover { background-color: #c53030; }

        /* PRODUK LIST */
        .prod-row { display: flex; align-items: center; justify-content: space-between; padding: 16px; border-bottom: 1px solid #f1f5f9; gap: 12px; }
        .prod-row:last-child { border-bottom: none; }
        .prod-meta { display: flex; align-items: center; gap: 14px; flex: 1; min-width: 0; }
        .prod-img-box { width: 48px; height: 48px; background: #f1f5f9; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--gold); font-size: 18px; border: 1px solid #e2e8f0; flex-shrink: 0; }
        .prod-actions { display: flex; gap: 8px; align-items: center; flex-shrink: 0; }

        /* TRACKING TIMELINE */
        .step-timeline { display: flex; flex-direction: column; gap: 18px; position: relative; padding-left: 20px; margin-top: 14px; }
        .step-timeline::before { content: ""; position: absolute; left: 4px; top: 5px; bottom: 5px; width: 2px; background: #e2e8f0; }
        .step-node { position: relative; padding-left: 14px; }
        .step-node::before { content: ""; position: absolute; left: -20px; top: 4px; width: 10px; height: 10px; border-radius: 50%; background: #cbd5e1; border: 2px solid white; box-shadow: 0 0 0 1px #cbd5e1; }
        .step-node.done::before { background: var(--emerald); box-shadow: 0 0 0 1px var(--emerald); }
        .step-node.active::before { background: var(--gold); box-shadow: 0 0 0 3px rgba(197,160,89,0.2); }

        /* CHECKBOX CARD */
        .selector-grid-group { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 6px; }
        .custom-checkbox-card { border: 1.5px solid #e2e8f0; padding: 13px; border-radius: 12px; display: flex; align-items: center; gap: 12px; cursor: pointer; transition: 0.2s; background: var(--bg-input); }
        .custom-checkbox-card .custom-checkbox-style { width: 16px; height: 16px; accent-color: var(--emerald); flex-shrink: 0; }
        .custom-checkbox-card.selected { border-color: var(--emerald); background: rgba(0,128,55,0.04); }

        .error-validation-msg { display: none; color: var(--danger); font-size: 12px; margin-top: 8px; font-weight: 500; align-items: center; gap: 6px; }

        /* WALLET CARD */
        .wallet-card {
            background: linear-gradient(135deg, var(--navy) 0%, #001a33 100%);
            border-radius: 16px; padding: 32px; color: white; margin-bottom: 24px;
            position: relative; overflow: hidden;
        }
        .wallet-card::after {
            content: ""; position: absolute; right: -30px; top: -30px;
            width: 150px; height: 150px; border-radius: 50%;
            background: rgba(197,160,89,0.08);
        }

        /* ALERT / TOAST */
        .toast {
            display: none; position: fixed; bottom: 24px; right: 24px;
            background: #1e293b; color: white; padding: 14px 20px;
            border-radius: 12px; font-size: 13px; font-weight: 500;
            z-index: 9999; align-items: center; gap: 10px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
            animation: slideUp 0.3s ease;
        }
        .toast.show { display: flex; }
        .toast.success { border-left: 4px solid var(--emerald); }
        .toast.error { border-left: 4px solid var(--danger); }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        /* EMPTY STATE */
        .empty-state { text-align: center; padding: 48px 20px; color: var(--text-muted); }
        .empty-state i { font-size: 32px; color: var(--gold); margin-bottom: 12px; display: block; }
        .empty-state p { font-size: 14px; }

        /* MOBILE HEADER & NAV */
        .mobile-header, .bottom-nav { display: none; }

        @media screen and (max-width: 768px) {
            body { overflow: auto; }
            .sidebar { display: none; }
            .main-content { padding: 16px; padding-top: 112px; padding-bottom: 80px; height: auto; overflow: visible; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .selector-grid-group { grid-template-columns: 1fr; }
            .mobile-header {
                display: block; position: fixed; top: 0; left: 0; right: 0; z-index: 999;
                background: rgba(255,255,255,0.97); backdrop-filter: blur(10px);
                border-bottom: 1px solid #e2e8f0; padding: 12px 16px;
            }
            .mobile-header-top { display: flex; justify-content: space-between; align-items: center; }
            .mobile-brand { display: flex; align-items: center; gap: 8px; }
            .mobile-brand .brand-name { font-family: 'Urbanist', sans-serif; font-size: 18px; font-weight: 800; color: var(--navy); }
            .mobile-brand .brand-name span { color: var(--emerald); }
            .bottom-nav {
                display: flex; position: fixed; bottom: 0; left: 0; right: 0; height: 62px;
                background: #ffffff; border-top: 1px solid #e2e8f0; z-index: 999;
                justify-content: space-around; align-items: center;
            }
            .nav-btn { display: flex; flex-direction: column; align-items: center; color: var(--text-muted); background: none; border: none; font-size: 9px; font-weight: 500; gap: 3px; cursor: pointer; padding: 4px 8px; }
            .nav-btn i { font-size: 18px; }
            .nav-btn.active { color: var(--navy); }
            .nav-btn.active i { font-weight: 900; }
            .prod-actions { flex-direction: column; }
        }
    </style>
</head>
<body>

<div class="toast" id="toast-msg">
    <i class="fa-solid fa-circle-check" id="toast-icon"></i>
    <span id="toast-text"></span>
</div>

<div class="mobile-header">
    <div class="mobile-header-top">
        <div class="mobile-brand">
            <div class="brand-name">Pinto<span>Pasai</span></div>
        </div>
        <div style="font-size:12px; color:var(--emerald); font-weight:700;">
            <i class="fa-solid fa-circle" style="font-size:7px;"></i> Online
        </div>
    </div>
    <div style="font-size:14px; font-weight:700; color:var(--navy); margin-top:4px;" id="m-title">Dashboard Utama</div>
</div>

<div class="sidebar">
    <div>
        <div class="brand">
            <div class="brand-name">Pinto<span>Pasai</span></div>
        </div>
        <ul class="menu-list">
            <li class="menu-item active" data-tab="dashboard" onclick="switchTab('dashboard')"><i class="fa-solid fa-chart-pie"></i> Dashboard</li>
            <li class="menu-item" data-tab="jual-barang" onclick="switchTab('jual-barang')"><i class="fa-solid fa-circle-plus"></i> Jual Barang</li>
            <li class="menu-item" data-tab="produk-saya" onclick="switchTab('produk-saya')"><i class="fa-solid fa-boxes-stacked"></i> Produk Saya</li>
            <li class="menu-item" data-tab="tracking-pesanan" onclick="switchTab('tracking-pesanan')"><i class="fa-solid fa-truck-ramp-box"></i> Tracking Pesanan</li>
            <li class="menu-item" data-tab="histori-penjualan" onclick="switchTab('histori-penjualan')"><i class="fa-solid fa-clock-rotate-left"></i> Histori Transaksi</li>
            <li class="menu-item" data-tab="pendapatan" onclick="switchTab('pendapatan')"><i class="fa-solid fa-wallet"></i> Saldo Keuangan</li>
        </ul>
    </div>
    <div class="user-profile" data-tab="profil-supplier" onclick="switchTab('profil-supplier')">
        <div class="avatar-mock" id="side-avatar">PP</div>
        <div style="min-width:0;">
            <p style="font-size:13px; font-weight:700; color:var(--navy); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" id="side-username-display">Memuat...</p>
            <p style="font-size:11px; color:var(--text-muted);">Pemasok Mitra</p>
        </div>
    </div>
</div>

<div class="bottom-nav">
    <button class="nav-btn active" data-tab="dashboard" onclick="switchTab('dashboard')"><i class="fa-solid fa-chart-pie"></i><span>Home</span></button>
    <button class="nav-btn" data-tab="jual-barang" onclick="switchTab('jual-barang')"><i class="fa-solid fa-circle-plus"></i><span>Jual</span></button>
    <button class="nav-btn" data-tab="produk-saya" onclick="switchTab('produk-saya')"><i class="fa-solid fa-boxes-stacked"></i><span>Stok</span></button>
    <button class="nav-btn" data-tab="tracking-pesanan" onclick="switchTab('tracking-pesanan')"><i class="fa-solid fa-truck-ramp-box"></i><span>Lacak</span></button>
    <button class="nav-btn" data-tab="histori-penjualan" onclick="switchTab('histori-penjualan')"><i class="fa-solid fa-clock-rotate-left"></i><span>Histori</span></button>
    <button class="nav-btn" data-tab="pendapatan" onclick="switchTab('pendapatan')"><i class="fa-solid fa-wallet"></i><span>Dana</span></button>
    <button class="nav-btn" data-tab="profil-supplier" onclick="switchTab('profil-supplier')"><i class="fa-solid fa-user-gear"></i><span>Profil</span></button>
</div>

<div class="main-content">

    <div id="dashboard" class="page active">
        <h2 class="page-title" id="dash-welcome-text">Selamat Datang, Mitra</h2>
        <p class="page-subtitle">Berikut ringkasan performa distribusi Anda hari ini.</p>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fa-solid fa-wallet"></i></div>
                <div><p style="font-size:11px;color:var(--text-muted)">Total Saldo Laba</p><h3 style="font-size:17px; color:var(--navy);" id="dash-saldo-val">Rp 0</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="fa-solid fa-wheat-awn"></i></div>
                <div><p style="font-size:11px;color:var(--text-muted)">Komoditas Aktif</p><h3 style="font-size:17px; color:var(--navy);" id="dash-product-count">0 Produk</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon gold"><i class="fa-solid fa-truck-fast"></i></div>
                <div><p style="font-size:11px;color:var(--text-muted)">Pengiriman Aktif</p><h3 style="font-size:17px; color:var(--navy);" id="dash-track-count">0 Rute</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon red"><i class="fa-solid fa-receipt"></i></div>
                <div><p style="font-size:11px;color:var(--text-muted)">Total Transaksi</p><h3 style="font-size:17px; color:var(--navy);" id="dash-trx-count">0 Nota</h3></div>
            </div>
        </div>
    </div>

    <div id="jual-barang" class="page">
        <h2 class="page-title">Form Jual Komoditas Baru</h2>
        <p class="page-subtitle">Luncurkan barang dagangan ke jaringan ekosistem pasar.</p>
        <div class="content-card">
            <form onsubmit="validateProductForm(event)">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <label>Nama Barang</label>
                        <input type="text" id="p_nama" placeholder="Beras Ramos Super" required>
                    </div>
                    <div>
                        <label>Harga per Satuan (Rp)</label>
                        <input type="number" id="p_harga" placeholder="0" min="1" required>
                    </div>
                    <div>
                        <label>Stok Tersedia</label>
                        <input type="number" id="p_stok" placeholder="0" min="0" required>
                    </div>
                    <div>
                        <label>Satuan Ukur</label>
                        <select id="p_satuan">
                            <option value="Kg">Kg (Kilogram)</option>
                            <option value="Ton">Ton</option>
                            <option value="Liter">Liter</option>
                            <option value="Karung">Karung</option>
                            <option value="Pcs">Pcs</option>
                        </select>
                    </div>
                    <div style="grid-column:span 2;">
                        <label>Deskripsi / Catatan Mutu</label>
                        <textarea id="p_deskripsi" rows="3" placeholder="Deskripsi kondisi barang, grade mutu, dll..."></textarea>
                    </div>

                    <div style="grid-column:span 2;">
                        <label style="margin-bottom:8px;">Metode Penyerahan Barang <span style="color:var(--danger)">*</span></label>
                        <div class="selector-grid-group">
                            <label class="custom-checkbox-card selected" id="card-antar">
                                <input type="checkbox" id="chk-antar" class="custom-checkbox-style" checked onchange="handleDeliveryChange('card-antar', this)">
                                <div><h4 style="color:var(--navy); font-size:13px;"><i class="fa-solid fa-truck" style="color:var(--gold);"></i> Bisa Antar</h4><p style="font-size:11px; color:var(--text-muted);">Pemasok kirim ke pembeli</p></div>
                            </label>
                            <label class="custom-checkbox-card" id="card-jemput">
                                <input type="checkbox" id="chk-jemput" class="custom-checkbox-style" onchange="handleDeliveryChange('card-jemput', this)">
                                <div><h4 style="color:var(--navy); font-size:13px;"><i class="fa-solid fa-warehouse" style="color:var(--gold);"></i> Wajib Jemput</h4><p style="font-size:11px; color:var(--text-muted);">Pembeli ambil sendiri</p></div>
                            </label>
                        </div>
                        <div class="error-validation-msg" id="delivery-error-msg">
                            <i class="fa-solid fa-triangle-exclamation"></i> Pilih minimal satu metode penyerahan!
                        </div>
                    </div>

                    <div style="grid-column:span 2;">
                        <label style="margin-bottom:8px;">Metode Pembayaran yang Diterima <span style="color:var(--danger)">*</span></label>
                        <div class="selector-grid-group">
                            <label class="custom-checkbox-card selected" id="card-cash">
                                <input type="checkbox" id="chk-cash" class="custom-checkbox-style" checked onchange="handlePaymentChange('card-cash', this)">
                                <div><h4 style="color:var(--navy); font-size:13px;"><i class="fa-solid fa-money-bill" style="color:var(--emerald);"></i> Cash / COD</h4></div>
                            </label>
                            <label class="custom-checkbox-card selected" id="card-transfer">
                                <input type="checkbox" id="chk-transfer" class="custom-checkbox-style" checked onchange="handlePaymentChange('card-transfer', this)">
                                <div><h4 style="color:var(--navy); font-size:13px;"><i class="fa-solid fa-building-columns" style="color:var(--navy);"></i> Transfer Bank</h4></div>
                            </label>
                        </div>
                        <div class="error-validation-msg" id="pay-error-msg">
                            <i class="fa-solid fa-triangle-exclamation"></i> Pilih minimal satu metode pembayaran!
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn-submit" style="margin-top:20px; width:100%;" id="btn-publish">
                    <i class="fa-solid fa-paper-plane"></i> Publikasikan Produk
                </button>
            </form>
        </div>
    </div>

    <div id="produk-saya" class="page">
        <h2 class="page-title">Manajemen Produk Gudang</h2>
        <p class="page-subtitle">Kontrol stok, harga, dan kelola komoditas yang aktif di pasar.</p>
        <div class="content-card" style="padding:0;" id="product-list-container">
            <div class="empty-state"><i class="fa-solid fa-box-open"></i><p>Memuat data produk...</p></div>
        </div>
    </div>

    <div id="tracking-pesanan" class="page">
        <h2 class="page-title">Pelacakan Distribusi Armada</h2>
        <p class="page-subtitle">Pantau rute pengiriman komoditas secara langsung dan setujui pesanan baru.</p>
        <div id="tracking-list-container">
            <div class="empty-state"><i class="fa-solid fa-truck-ramp-box"></i><p>Memuat data pengiriman...</p></div>
        </div>
    </div>

    <div id="histori-penjualan" class="page">
        <h2 class="page-title">Buku Transaksi Selesai</h2>
        <p class="page-subtitle">Rekaman nota pembayaran penjualan komoditas yang sah.</p>
        <div class="content-card" style="padding:0; overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID Nota</th>
                        <th>Tanggal</th>
                        <th>Komoditas</th>
                        <th>Total</th>
                        <th>Metode</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="transaksi-table-body">
                    <tr><td colspan="6" style="text-align:center; padding:30px; color:var(--text-muted);">Memuat histori transaksi...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="pendapatan" class="page">
        <h2 class="page-title">Keuangan & Pencairan Dana</h2>
        <p class="page-subtitle">Tarik saldo bersih hasil konfirmasi pembayaran penjualan Anda.</p>
        <div class="wallet-card">
            <p style="font-size:12px; color:var(--gold); font-weight:700; letter-spacing:0.5px; text-transform:uppercase;">Saldo Dapat Dicairkan</p>
            <h1 style="font-size:34px; font-weight:800; margin:10px 0;" id="wallet-saldo-val">Rp 0</h1>
            <p style="font-size:12px; color:#94a3b8; margin-bottom:22px;" id="wallet-bank-info">
                <i class="fa-solid fa-circle-info"></i> Memuat info rekening...
            </p>
            <button class="btn-submit" style="background:var(--gold); width:auto; padding:12px 22px;" onclick="requestPencairan()">
                <i class="fa-solid fa-money-bill-transfer"></i> Cairkan Saldo Dana
            </button>
        </div>
    </div>

    <div id="profil-supplier" class="page">
        <h2 class="page-title">Pengaturan Profil Mitra</h2>
        <p class="page-subtitle">Kelola informasi badan usaha dan cakupan wilayah distribusi.</p>

        <div id="view-profile" class="content-card">
            <div style="display:flex; justify-content:space-between; align-items:start; flex-wrap:wrap; gap:16px;">
                <div style="display:flex; gap:16px; align-items:center;">
                    <div class="avatar-mock" style="width:56px; height:56px; font-size:18px;" id="prof-avatar">PP</div>
                    <div>
                        <h3 style="color:var(--navy); font-weight:700; font-size:16px;" id="d-name">Memuat...</h3>
                        <p style="color:var(--emerald); font-weight:600; font-size:12px;">Pemasok Utama Logistik Pasar</p>
                    </div>
                </div>
                <button onclick="toggleEditMode(true)" style="background:none; border:1.5px solid var(--gold); color:var(--gold); padding:8px 16px; border-radius:8px; font-weight:700; font-size:12px; cursor:pointer;">
                    <i class="fa-solid fa-pen"></i> Edit Profil
                </button>
            </div>
            <div style="margin-top:20px; display:grid; gap:10px; font-size:14px;">
                <div><i class="fa-solid fa-location-dot" style="color:var(--gold); width:20px;"></i> <span id="d-addr">-</span></div>
                <div><i class="fa-solid fa-phone" style="color:var(--gold); width:20px;"></i> <span id="d-phone">-</span></div>
            </div>
            <div style="margin-top:20px; padding-top:16px; border-top:1px solid #f1f5f9;">
                <button class="btn-submit" style="background:var(--danger);" onclick="handleLogoutSystem()">
                    <i class="fa-solid fa-power-off"></i> Keluar Aplikasi
                </button>
            </div>
        </div>

        <div id="edit-profile" class="content-card" style="display:none;">
            <h4 style="margin-bottom:16px; color:var(--navy); font-weight:700;">Edit Detail Profil</h4>
            <form onsubmit="saveProfileChanges(event)">
                <div style="display:grid; gap:14px;">
                    <div><label>Nama Usaha / Gudang</label><input type="text" id="i-name" required></div>
                    <div><label>No. Telepon / WhatsApp</label><input type="text" id="i-phone" required></div>
                    <div><label>Alamat Lokasi Utama</label><textarea id="i-addr" rows="2" required></textarea></div>
                    <div style="display:flex; gap:10px; margin-top:4px;">
                        <button type="button" onclick="toggleEditMode(false)" style="flex:1; padding:12px; border-radius:8px; border:1.5px solid #cbd5e1; background:none; cursor:pointer; font-weight:600; color:var(--text-muted);">Batal</button>
                        <button type="submit" class="btn-submit" style="flex:2;">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Profil
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    const API_URL = "api.php";

    // INIT: Cek sesi login saat halaman pertama kali dibuka
    document.addEventListener('DOMContentLoaded', async () => {
        try {
            const res = await fetch(`${API_URL}?action=check_session`);
            const result = await res.json();
            if (result.status !== 'success' || result.role !== 'pemasok') {
                window.location.href = 'login.html';
                return;
            }
            fetchAndSyncAllData();
        } catch (e) {
            window.location.href = 'login.html';
        }
    });

    const TAB_LABELS = {
        'dashboard':         'Dashboard Utama',
        'jual-barang':       'Jual Komoditas',
        'produk-saya':       'Produk Saya',
        'tracking-pesanan':  'Lacak Distribusi',
        'histori-penjualan': 'Buku Transaksi',
        'pendapatan':        'Saldo Keuangan',
        'profil-supplier':   'Profil Toko'
    };

    // NAVIGASI MENU (TAB)
    function switchTab(id) {
        document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
        const target = document.getElementById(id);
        if (target) target.classList.add('active');

        document.querySelectorAll('[data-tab]').forEach(el => {
            el.classList.toggle('active', el.getAttribute('data-tab') === id);
        });

        const titleEl = document.getElementById('m-title');
        if (titleEl) titleEl.textContent = TAB_LABELS[id] || '';

        if (id !== 'profil-supplier') toggleEditMode(false);
    }

    function fetchAndSyncAllData() {
        fetchDashboardMetrics();
        fetchDatabaseProducts();
        fetchDatabaseTracking();
        fetchDatabaseTransactions();
    }

    // 1. MEMUAT METRIK UTAMA DASHBOARD
    async function fetchDashboardMetrics() {
        try {
            const res    = await fetch(`${API_URL}?action=get_dashboard`);
            const result = await res.json();

            if (result.status === 'unauthorized') { redirectToLogin(); return; }
            if (result.status === 'success') {
                const stats = result.stats;
                const profile = result.profile;

                // Render metrik teks statistik ke elemen HTML
                document.getElementById('dash-welcome-text').textContent = `Selamat Datang, ${profile.nama_usaha || 'Mitra'}`;
                document.getElementById('dash-saldo-val').textContent = `Rp ${formatRupiah(stats.saldo)}`;
                document.getElementById('wallet-saldo-val').textContent = `Rp ${formatRupiah(stats.saldo)}`;
                document.getElementById('dash-product-count').textContent = `${stats.produk_aktif} Produk`;
                document.getElementById('dash-track-count').textContent = `${stats.pengiriman_aktif} Rute`;
                document.getElementById('dash-trx-count').textContent = `${stats.total_transaksi} Nota`;

                // Render profil supplier
                document.getElementById('side-username-display').textContent = profile.nama_usaha;
                document.getElementById('d-name').textContent = profile.nama_usaha;
                document.getElementById('d-phone').textContent = profile.telepon || 'Belum diatur';
                document.getElementById('d-addr').textContent = profile.alamat || 'Belum diatur';

                // Set inisial avatar mock huruf depan
                const initials = (profile.nama_usaha || 'PP').substring(0, 2).toUpperCase();
                document.getElementById('side-avatar').textContent = initials;
                document.getElementById('prof-avatar').textContent = initials;

                // Isi form edit profile dengan data eksis
                document.getElementById('i-name').value = profile.nama_usaha || '';
                document.getElementById('i-phone').value = profile.telepon || '';
                document.getElementById('i-addr').value = profile.alamat || '';
                document.getElementById('wallet-bank-info').innerHTML = `<i class=\"fa-solid fa-circle-info\"></i> Rekening terikat otomatis via profil mitra Anda.`;
            }
        } catch (err) {
            showToast('error', 'Gagal memuat metrik utama server.');
        }
    }

    // 2. FORM HANDLER: VALIDASI DAN KIRIM PRODUK BARU
    function handleDeliveryChange(cardId, checkbox) {
        document.getElementById(cardId).classList.toggle('selected', checkbox.checked);
    }

    function handlePaymentChange(cardId, checkbox) {
        document.getElementById(cardId).classList.toggle('selected', checkbox.checked);
    }

    async function validateProductForm(e) {
        e.preventDefault();
        
        const chkAntar = document.getElementById('chk-antar').checked;
        const chkJemput = document.getElementById('chk-jemput').checked;
        const chkCash = document.getElementById('chk-cash').checked;
        const chkTransfer = document.getElementById('chk-transfer').checked;

        let isValid = true;

        if (!chkAntar && !chkJemput) {
            document.getElementById('delivery-error-msg').style.display = 'flex';
            isValid = false;
        } else {
            document.getElementById('delivery-error-msg').style.display = 'none';
        }

        if (!chkCash && !chkTransfer) {
            document.getElementById('pay-error-msg').style.display = 'flex';
            isValid = false;
        } else {
            document.getElementById('pay-error-msg').style.display = 'none';
        }

        if (!isValid) return;

        const btn = document.getElementById('btn-publish');
        btn.disabled = true;
        btn.innerHTML = `<i class=\"fa-solid fa-spinner fa-spin\"></i> Memproses...`;

        let logistikArr = [];
        if (chkAntar) logistikArr.push('Bisa Antar');
        if (chkJemput) logistikArr.push('Wajib Jemput');

        let paymentArr = [];
        if (chkCash) paymentArr.push('COD');
        if (chkTransfer) paymentArr.push('Transfer Bank');

        const payload = {
            nama: document.getElementById('p_nama').value,
            harga: parseInt(document.getElementById('p_harga').value),
            stok: parseFloat(document.getElementById('p_stok').value),
            satuan: document.getElementById('p_satuan').value,
            deskripsi: document.getElementById('p_deskripsi').value,
            logistik: logistikArr.join(', '),
            pembayaran: paymentArr.join(', ')
        };

        try {
            const res = await fetch(`${API_URL}?action=add_produk`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const result = await res.json();

            if (result.status === 'success') {
                showToast('success', 'Komoditas baru berhasil diterbitkan ke bursa!');
                e.target.reset();
                document.getElementById('card-antar').classList.add('selected'); document.getElementById('chk-antar').checked = true;
                document.getElementById('card-jemput').classList.remove('selected'); document.getElementById('chk-jemput').checked = false;
                switchTab('produk-saya');
                fetchAndSyncAllData();
            } else {
                showToast('error', result.message || 'Gagal menyimpan produk.');
            }
        } catch (err) {
            showToast('error', 'Gangguan koneksi internet ke sistem backend.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = `<i class=\"fa-solid fa-paper-plane\"></i> Publikasikan Produk`;
        }
    }

    // 3. MEMUAT DAFTAR PRODUK YANG DIMILIKI GUDANG
    async function fetchDatabaseProducts() {
        const container = document.getElementById('product-list-container');
        try {
            const res = await fetch(`${API_URL}?action=get_produk`);
            const result = await res.json();

            if (result.status === 'success' && result.data.length > 0) {
                let html = '';
                result.data.forEach(p => {
                    html += `
                    <div class="prod-row">
                        <div class="prod-meta">
                            <div class="prod-img-box">${getProductIcon(p.nama)}</div>
                            <div style="min-width:0;">
                                <h4 style="font-size:14px; font-weight:700; color:var(--navy); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${p.nama}</h4>
                                <p style="font-size:12px; color:var(--text-muted); margin-top:2px;">
                                    Harga: <span style="color:var(--emerald); font-weight:600;">Rp ${formatRupiah(p.harga)}</span>/${p.satuan} &bull; Stok: <b>${p.stok}</b>
                                </p>
                            </div>
                        </div>
                        <div class="prod-actions">
                            <button class="btn-danger" onclick="deleteProductById(${p.id})">
                                <i class="fa-solid fa-trash-can"></i> Hapus
                            </button>
                        </div>
                    </div>`;
                });
                container.innerHTML = html;
            } else {
                container.innerHTML = `<div class="empty-state"><i class="fa-solid fa-box-open"></i><p>Belum ada produk komoditas yang dijual.</p></div>`;
            }
        } catch (err) {
            container.innerHTML = `<div class="empty-state"><i class="fa-solid fa-circle-xmark" style="color:var(--danger)"></i><p>Gagal sinkronisasi produk.</p></div>`;
        }
    }

    async function deleteProductById(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus komoditas ini dari bursa pasar?')) return;
        try {
            const res = await fetch(`${API_URL}?action=delete_produk`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            });
            const result = await res.json();
            if (result.status === 'success') {
                showToast('success', 'Produk berhasil ditarik dari pasar.');
                fetchAndSyncAllData();
            } else {
                showToast('error', result.message || 'Gagal menghapus produk.');
            }
        } catch (err) {
            showToast('error', 'Koneksi lokal bermasalah.');
        }
    }

    // 4. MEMUAT DATA TRACKING LOGISTIK (DI SINI SAYA INTEGRASIKAN LOGIKA PINTEK AMBIL / ANTAR BARU)
    async function fetchDatabaseTracking() {
        const container = document.getElementById('tracking-list-container');
        try {
            const res = await fetch(`${API_URL}?action=get_tracking`);
            const result = await res.json();

            if (result.status === 'success' && result.data.length > 0) {
                let html = '';
                result.data.forEach(t => {
                    const isBelumInput = (t.no_resi === 'BELUM DI-INPUT' || t.no_resi === '');
                    
                    // Deteksi jenis logistik dari text tujuan / catatan pembeli
                    const isAmbil = (t.tujuan.toLowerCase().includes('ambil') || t.tujuan.toLowerCase().includes('gudang'));
                    const logistikTextLabel = isAmbil ? '<span class="badge info"><i class="fa-solid fa-warehouse"></i> Opsi: Ambil Sendiri</span>' : '<span class="badge success"><i class="fa-solid fa-truck"></i> Opsi: Kirim Armada</span>';

                    let statusBadge = '';
                    if (t.status_pengiriman === 'Menunggu Konfirmasi') statusBadge = 'warning';
                    else if (t.status_pengiriman === 'Selesai') statusBadge = 'success';
                    else statusBadge = 'info';

                    html += `
                    <div class="content-card" style="margin-bottom: 16px;">
                        <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:12px; gap:8px; flex-wrap:wrap;">
                            <div>
                                <h4 style="color:var(--navy); font-size:15px; font-weight:700;">${t.komoditas}</h4>
                                <p style="font-size:11px; color:var(--text-muted); margin-top:2px;">
                                    Tujuan/Catatan Pembeli: <i class="fa-solid fa-location-arrow" style="color:var(--gold);"></i> <b>${t.tujuan}</b>
                                </p>
                            </div>
                            <div style="display:flex; flex-direction:column; align-items:flex-end; gap:4px;">
                                <span class="badge ${statusBadge}">${t.status_pengiriman}</span>
                                ${logistikTextLabel}
                            </div>
                        </div>
                        
                        <div style="background:#f8fafc; padding:12px; border-radius:8px; border:1px solid #e2e8f0; font-size:13px; color:var(--text-main);">
                            <div style="margin-bottom:4px;"><b>Kode Kontrak / Resi:</b> <code style="background:#e2e8f0; padding:2px 6px; border-radius:4px; color:var(--danger); font-weight:600;">${t.no_resi}</code></div>
                            <div><b>Pelacakan Status:</b> <span style="color:var(--text-muted); font-style:italic;">"${t.catatan_status}"</span></div>
                        </div>

                        <div style="margin-top:14px; display:flex; gap:8px; justify-content:flex-end;">
                            ${isBelumInput ? `
                                <button class="btn-submit" style="padding:6px 14px; font-size:12px; border-radius:6px;" onclick="handleSetujuiPesanan(${t.id}, '${escHtml(t.tujuan)}')">
                                    <i class="fa-solid fa-truck-fast"></i> Setujui & Beri Kode Logistik
                                </button>
                            ` : `
                                <button class="btn-submit" style="padding:6px 14px; font-size:12px; border-radius:6px; background:var(--gold);" onclick="handleSetujuiPesanan(${t.id}, '${escHtml(t.tujuan)}')">
                                    <i class="fa-solid fa-pen-to-square"></i> Perbarui Resi / Waktu
                                </button>
                            `}

                            ${t.status_pengiriman !== 'Selesai' && !isBelumInput ? `
                                <button class="btn-submit" style="padding:6px 14px; font-size:12px; border-radius:6px; background:var(--emerald);" onclick="handleSelesaikanPesanan(${t.id})">
                                    <i class="fa-solid fa-circle-check"></i> Selesaikan Pesanan
                                </button>
                            ` : ''}
                        </div>
                    </div>`;
                });
                container.innerHTML = html;
            } else {
                container.innerHTML = `<div class="empty-state"><i class="fa-solid fa-truck-ramp-box"></i><p>Tidak ada riwayat pengiriman kargo aktif.</p></div>`;
            }
        } catch (err) {
            container.innerHTML = `<div class="empty-state"><i class="fa-solid fa-circle-xmark" style="color:var(--danger)"></i><p>Gagal menyinkronkan jalur logistik.</p></div>`;
        }
    }

    // FUNGSI BARU YANG DITAMBAHKAN: HANDLE INTERAKTIF INPUT "BATAS JAM" ATAU "ESTIMASI HARI"
    async function handleSetujuiPesanan(trackingId, teksTujuan) {
        const isAmbil = teksTujuan.toLowerCase().includes('ambil') || teksTujuan.toLowerCase().includes('gudang');
        
        let promptResiText = isAmbil 
            ? "Masukkan Kode Pengangkutan Gudang / Nomor Nota Ambil:" 
            : "Masukkan Nomor Resi Kargo / Armada Pengiriman Pengantar:";
            
        const noResi = prompt(promptResiText);
        if (!noResi) return;

        let batasJam = 24;
        let estimasi = "1-3 Hari";
        
        if (isAmbil) {
            const jamInput = prompt("Batas maksimal pengambilan barang oleh pembeli (dalam satuan JAM):", "24");
            if (jamInput) batasJam = parseInt(jamInput) || 24;
        } else {
            const hariInput = prompt("Estimasi durasi waktu pengiriman sampai tujuan (Contoh: 1-2 Hari, 3-4 Hari):", "1-3 Hari");
            if (hariInput) estimasi = hariInput;
        }

        try {
            const res = await fetch(`${API_URL}?action=setujui_pesanan`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id: trackingId,
                    no_resi: noResi,
                    batas_jam: batasJam,
                    estimasi: estimasi
                })
            });

            const result = await res.json();
            if (result.status === 'success') {
                showToast('success', result.message || 'Kontrak armada berhasil disetujui!');
                fetchAndSyncAllData();
            } else {
                showToast('error', result.message);
            }
        } catch (err) {
            showToast('error', 'Gagal memproses validasi logistik ke server backend.');
        }
    }

    // FUNGSI BARU YANG DITAMBAHKAN: SUPAYA PEMASOK BISA MENUTUP STATUS PESANAN MENJADI SELESAI
    async function handleSelesaikanPesanan(trackingId) {
        if (!confirm('Apakah penyerahan komoditas telah selesai serah terima secara utuh? Status pesanan akan dikunci dan diselesaikan.')) return;
        try {
            const res = await fetch(`${API_URL}?action=selesaikan_pesanan`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: trackingId })
            });
            const result = await res.json();
            if (result.status === 'success') {
                showToast('success', result.message || 'Status pesanan ditutup sukses!');
                fetchAndSyncAllData();
            } else {
                showToast('error', result.message);
            }
        } catch (err) {
            showToast('error', 'Terjadi masalah pada koneksi penyelesaian pesanan.');
        }
    }

    // 5. MEMUAT HISTORI PENJUALAN SELESAI
    async function fetchDatabaseTransactions() {
        const body = document.getElementById('transaksi-table-body');
        try {
            const res = await fetch(`${API_URL}?action=get_transaksi`);
            const result = await res.json();

            if (result.status === 'success' && result.data.length > 0) {
                let html = '';
                result.data.forEach(tr => {
                    let stBadge = tr.status_transaksi === 'Selesai' ? 'success' : 'warning';
                    html += `
                    <tr>
                        <td style="font-weight:600; color:var(--navy); font-family:monospace;">${tr.no_nota}</td>
                        <td style="font-size:13px; color:var(--text-muted);">${tr.tanggal}</td>
                        <td><b>${tr.komoditas}</b></td>
                        <td style="font-weight:700; color:var(--emerald);">Rp ${formatRupiah(tr.total_pembayaran)}</td>
                        <td><span class="badge info"><i class="fa-solid fa-credit-card"></i> ${tr.metode}</span></td>
                        <td><span class="badge ${stBadge}">${tr.status_transaksi}</span></td>
                    </tr>`;
                });
                body.innerHTML = html;
            } else {
                body.innerHTML = `<tr><td colspan="6" style="text-align:center; padding:40px; color:var(--text-muted);"><i class="fa-solid fa-folder-open" style="display:block; margin-bottom:8px; font-size:20px; color:var(--gold);"></i> Belum ada rekaman nota penjualan masuk.</td></tr>`;
            }
        } catch (err) {
            body.innerHTML = `<tr><td colspan="6" style="text-align:center; padding:30px; color:var(--danger);">Gagal sinkronisasi data transaksi keuangan.</td></tr>`;
        }
    }

    // 6. PENCAIRAN SALDO DAN PROFILE MITRA UTILITIES
    function requestPencairan() {
        const valText = document.getElementById('wallet-saldo-val').textContent;
        if (valText === 'Rp 0') {
            showToast('error', 'Saldo berjalan Anda kosong, tidak ada dana yang bisa dicairkan saat ini.');
            return;
        }
        alert('Permintaan pencairan dana laba usaha Anda telah diteruskan ke sistem kliring bank utama. Mohon tunggu proses validasi 1x24 jam.');
    }

    function toggleEditMode(isEdit) {
        document.getElementById('view-profile').style.display = isEdit ? 'none' : 'block';
        document.getElementById('edit-profile').style.display = isEdit ? 'block' : 'none';
    }

    async function saveProfileChanges(e) {
        e.preventDefault();
        const payload = {
            nama_usaha: document.getElementById('i-name').value,
            telepon: document.getElementById('i-phone').value,
            alamat: document.getElementById('i-addr').value
        };

        try {
            const res = await fetch(`${API_URL}?action=update_profil`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const result = await res.json();
            if (result.status === 'success') {
                showToast('success', 'Informasi profil badan usaha sukses diperbarui!');
                toggleEditMode(false);
                fetchAndSyncAllData();
            } else {
                showToast('error', result.message || 'Gagal memperbarui profil.');
            }
        } catch (err) {
            showToast('error', 'Gangguan server lokal.');
        }
    }

    async function handleLogoutSystem() {
        if (!confirm('Apakah Anda yakin ingin menutup sesi dan keluar dari sistem aplikasi pemasok?')) return;
        await fetch(`${API_URL}?action=logout`);
        window.location.href = 'login.html';
    }

    // UTILITIES HELPER FORMATTING
    function redirectToLogin() {
        window.location.href = 'login.html';
    }

    function formatRupiah(angka) {
        return parseInt(angka || 0).toLocaleString('id-ID');
    }

    function escHtml(str) {
        return (str || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
    }

    function getProductIcon(nama) {
        const n = (nama || '').toLowerCase();
        if (n.includes('beras'))   return '<i class="fa-solid fa-wheat-awn"></i>';
        if (n.includes('kedelai')) return '<i class="fa-solid fa-seedling"></i>';
        if (n.includes('gula'))    return '<i class="fa-solid fa-cubes-stack"></i>';
        if (n.includes('minyak'))  return '<i class="fa-solid fa-droplet"></i>';
        if (n.includes('tepung'))  return '<i class="fa-solid fa-jar"></i>';
        return '<i class="fa-solid fa-box"></i>';
    }

    let toastTimer;
    function showToast(type, message) {
        const toast = document.getElementById('toast-msg');
        const icon  = document.getElementById('toast-icon');
        const text  = document.getElementById('toast-text');

        icon.className = type === 'success' ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-exclamation';
        toast.className = `toast show ${type}`;
        text.textContent = message;

        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => { toast.className = 'toast'; }, 3500);
    }
</script>
</body>
</html>