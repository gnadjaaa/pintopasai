<?php
session_start();
// Baris ini menjaga agar session tetap aman dan terikat dengan server-side PHP
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinto Pasai - Dashboard Pembeli</title>
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
            width: 40px; height: 40px; background-color: var(--emerald);
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

        /* GRID PASAR */
        .pasar-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-top: 10px; }
        .komoditas-card {
            background: #ffffff; border: 1px solid var(--border); border-radius: 16px;
            padding: 20px; display: flex; flex-direction: column; justify-content: space-between;
            position: relative; box-shadow: 0 2px 6px rgba(0,0,0,0.01); transition: transform 0.2s, box-shadow 0.2s;
        }
        .komoditas-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.04); }
        
        .komoditas-icon-box {
            width: 44px; height: 44px; background: rgba(0,128,55,0.06); color: var(--emerald);
            border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px;
        }

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

        /* BUTTONS */
        .btn-action {
            background-color: var(--navy); color: white; border: none; padding: 8px 14px;
            font-weight: 600; border-radius: 8px; cursor: pointer; font-size: 12px;
            display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s;
        }
        .btn-action:hover { background-color: #002244; }
        .btn-submit {
            background-color: var(--emerald); color: white; border: none;
            padding: 13px 24px; font-weight: 600; border-radius: 10px;
            cursor: pointer; transition: all 0.2s;
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            font-size: 14px;
        }
        .btn-submit:hover { background-color: #006b2e; }

        /* FORMS */
        label { font-size: 12px; font-weight: 600; color: var(--navy); display: block; margin-bottom: 5px; }
        input[type="text"], input[type="number"], textarea, select {
            background-color: var(--bg-input); border: 1.5px solid #e2e8f0; color: var(--text-main);
            padding: 12px 14px; border-radius: 10px; font-size: 14px; outline: none; width: 100%; transition: all 0.2s;
        }
        input:focus, textarea:focus, select:focus { border-color: var(--emerald); background-color: #ffffff; box-shadow: 0 0 0 3px rgba(0, 128, 55, 0.08); }

        /* MODAL */
        .modal-overlay {
            display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.3); backdrop-filter: blur(4px);
            z-index: 1000; align-items: center; justify-content: center;
        }
        .modal-overlay.show { display: flex; }
        .modal-box {
            background: white; border-radius: 16px; width: 460px; max-width: 90%;
            padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            animation: modalUp 0.2s ease;
        }
        @keyframes modalUp { from { transform: translateY(12px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        /* TRACKING TIMELINE */
        .step-timeline { display: flex; flex-direction: column; gap: 18px; position: relative; padding-left: 20px; margin-top: 14px; }
        .step-timeline::before { content: ""; position: absolute; left: 4px; top: 5px; bottom: 5px; width: 2px; background: #e2e8f0; }
        .step-node { position: relative; padding-left: 14px; }
        .step-node::before { content: ""; position: absolute; left: -20px; top: 4px; width: 10px; height: 10px; border-radius: 50%; background: #cbd5e1; border: 2px solid white; box-shadow: 0 0 0 1px #cbd5e1; }
        .step-node.done::before { background: var(--emerald); box-shadow: 0 0 0 1px var(--emerald); }
        .step-node.active::before { background: var(--gold); box-shadow: 0 0 0 3px rgba(197,160,89,0.2); }

        /* TOAST */
        .toast {
            display: none; position: fixed; bottom: 24px; right: 24px;
            background: #1e293b; color: white; padding: 14px 20px;
            border-radius: 12px; font-size: 13px; font-weight: 500; z-index: 9999; align-items: center; gap: 10px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15); animation: slideUp 0.3s ease;
        }
        .toast.show { display: flex; }
        .toast.success { border-left: 4px solid var(--emerald); }
        .toast.error { border-left: 4px solid var(--danger); }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        .empty-state { text-align: center; padding: 48px 20px; color: var(--text-muted); width: 100%; }
        .empty-state i { font-size: 32px; color: var(--gold); margin-bottom: 12px; display: block; }

        /* MOBILE STYLING */
        .mobile-header, .bottom-nav { display: none; }

        @media screen and (max-width: 768px) {
            body { overflow: auto; }
            .sidebar { display: none; }
            .main-content { padding: 16px; padding-top: 112px; padding-bottom: 80px; height: auto; overflow: visible; }
            .mobile-header {
                display: block; position: fixed; top: 0; left: 0; right: 0; z-index: 999;
                background: rgba(255,255,255,0.97); backdrop-filter: blur(10px);
                border-bottom: 1px solid #e2e8f0; padding: 12px 16px;
            }
            .mobile-header-top { display: flex; justify-content: space-between; align-items: center; }
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
        <div class="mobile-brand"><div class="brand-name">Pinto<span>Pasai</span></div></div>
        <div style="font-size:12px; color:var(--emerald); font-weight:700;"><i class="fa-solid fa-circle" style="font-size:7px;"></i> Pembeli</div>
    </div>
    <div style="font-size:14px; font-weight:700; color:var(--navy); margin-top:4px;" id="m-title">Pasar Komoditas</div>
</div>

<div class="sidebar">
    <div>
        <div class="brand"><div class="brand-name">Pinto<span>Pasai</span></div></div>
        <ul class="menu-list">
            <li class="menu-item active" data-tab="pasar" onclick="switchTab('pasar')"><i class="fa-solid fa-store"></i> Pasar Komoditas</li>
            <li class="menu-item" data-tab="lacak" onclick="switchTab('lacak')"><i class="fa-solid fa-truck-fast"></i> Lacak Pesanan</li>
            <li class="menu-item" data-tab="riwayat" onclick="switchTab('riwayat')"><i class="fa-solid fa-receipt"></i> Riwayat Belanja</li>
        </ul>
    </div>
    <div class="user-profile" data-tab="profil" onclick="switchTab('profil')">
        <div class="avatar-mock" id="side-avatar">B</div>
        <div style="min-width:0;">
            <p style="font-size:13px; font-weight:700; color:var(--navy); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" id="side-username">Memuat...</p>
            <p style="font-size:11px; color:var(--text-muted);">Mitra Pembeli</p>
        </div>
    </div>
</div>

<div class="bottom-nav">
    <button class="nav-btn active" data-tab="pasar" onclick="switchTab('pasar')"><i class="fa-solid fa-store"></i><span>Pasar</span></button>
    <button class="nav-btn" data-tab="lacak" onclick="switchTab('lacak')"><i class="fa-solid fa-truck-fast"></i><span>Lacak</span></button>
    <button class="nav-btn" data-tab="riwayat" onclick="switchTab('riwayat')"><i class="fa-solid fa-receipt"></i><span>Riwayat</span></button>
    <button class="nav-btn" data-tab="profil" onclick="switchTab('profil')"><i class="fa-solid fa-user"></i><span>Profil</span></button>
</div>

<div class="main-content">

    <div id="pasar" class="page active">
        <h2 class="page-title">Bursa Komoditas Tani Berkelanjutan</h2>
        <p class="page-subtitle">Beli langsung dari kelompok tani utama dengan harga kontrak transparan.</p>
        <div class="pasar-grid" id="pasar-container">
            <div class="empty-state"><i class="fa-solid fa-spinner fa-spin"></i><p>Memuat bursa komoditas...</p></div>
        </div>
    </div>

    <div id="lacak" class="page">
        <h2 class="page-title">Pelacakan Rute Logistik Distribusi</h2>
        <p class="page-subtitle">Pantau status armada kargo dan nomor resi dari pihak pemasok.</p>
        <div id="lacak-container">
            <div class="empty-state"><i class="fa-solid fa-truck-fast"></i><p>Memuat data pelacakan...</p></div>
        </div>
    </div>

    <div id="riwayat" class="page">
        <h2 class="page-title">Arsip Nota Belanja Digital</h2>
        <p class="page-subtitle">Daftar kontrak pembelian komoditas hasil bumi yang telah Anda sepakati.</p>
        <div class="content-card" style="padding:0; overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID Nota</th>
                        <th>Tanggal</th>
                        <th>Komoditas</th>
                        <th>Total Nilai</th>
                        <th>Metode</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="riwayat-table-body">
                    <tr><td colspan="6" style="text-align:center; padding:30px; color:var(--text-muted);">Memuat berkas belanja...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="profil" class="page">
        <h2 class="page-title">Manajemen Akun Pembeli Mitra</h2>
        <p class="page-subtitle">Informasi legalitas usaha dan detail akun operasional Anda.</p>
        <div class="content-card" style="max-width:550px;">
            <div style="display:flex; gap:16px; align-items:center; margin-bottom:20px;">
                <div class="avatar-mock" style="width:52px; height:52px; font-size:16px; background:var(--navy);" id="prof-avatar">B</div>
                <div>
                    <h3 style="color:var(--navy); font-weight:700;" id="p-name-display">Memuat...</h3>
                    <p style="color:var(--text-muted); font-size:12px;">Hak Akses: Corporate Buyer / Pembeli</p>
                </div>
            </div>
            <div style="display:grid; gap:10px; font-size:14px; border-top:1px solid #f1f5f9; padding-top:16px; margin-bottom:20px;">
                <div><i class="fa-solid fa-envelope" style="color:var(--gold); width:20px;"></i> <span id="p-mail">-</span></div>
                <div><i class="fa-solid fa-building" style="color:var(--gold); width:20px;"></i> <span id="p-org">Grup Usaha Pembeli</span></div>
            </div>
            <button class="btn-submit" style="background:var(--danger); width:100%;" onclick="handleLogoutSystem()">
                <i class="fa-solid fa-power-off"></i> Keluar dari Sistem
            </button>
        </div>
    </div>

</div>

<div class="modal-overlay" id="modal-beli">
    <div class="modal-box">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h4 style="color:var(--navy); font-weight:700; font-size:15px;"><i class="fa-solid fa-file-invoice-dollar"></i> Form Kontrak Pembelian</h4>
            <i class="fa-solid fa-xmark" style="cursor:pointer; color:var(--text-muted);" onclick="closeModal()"></i>
        </div>
        <form onsubmit="executePurchase(event)">
            <input type="hidden" id="m-prod-id">
            <div style="margin-bottom:12px; background:#f8fafc; padding:12px; border-radius:10px; border:1px solid #e2e8f0;">
                <p style="font-size:11px; color:var(--text-muted);">Komoditas Dipilih</p>
                <h4 style="color:var(--navy); font-size:14px; font-weight:700; margin-top:2px;" id="m-prod-nama">-</h4>
                <p style="font-size:12px; color:var(--emerald); font-weight:600; margin-top:2px;" id="m-prod-harga">Rp 0</p>
            </div>
            
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
                <div>
                    <label>Jumlah Beli (<span id="m-prod-satuan">Kg</span>)</label>
                    <input type="number" id="m-qty" min="0.01" step="0.01" value="1" oninput="calculateTotal()" required>
                </div>
                <div>
                    <label>Total Pembayaran</label>
                    <div style="font-size:15px; font-weight:700; color:var(--navy); padding-top:10px;" id="m-total-display">Rp 0</div>
                </div>
            </div>

            <div style="margin-bottom:14px;">
                <label>Alamat Bongkar / Tujuan Pengiriman</label>
                <textarea id="m-tujuan" rows="2" placeholder="Masukkan alamat lengkap pengantaran kargo..." required></textarea>
            </div>

            <div style="margin-bottom:16px;">
                <label>Pilih Metode Pembayaran</label>
                <select id="m-metode">
                    <option value="Transfer Bank">Transfer Bank (Verifikasi Kontrak)</option>
                    <option value="Cash / COD">Tunai Langsung / COD</option>
                </select>
            </div>

            <div style="display:flex; gap:10px;">
                <button type="button" onclick="closeModal()" style="flex:1; padding:11px; border-radius:8px; border:1px solid #cbd5e1; background:none; font-weight:600; cursor:pointer; color:var(--text-muted); font-size:13px;">Batal</button>
                <button type="submit" class="btn-submit" style="flex:2; font-size:13px; padding:11px;">Konfirmasi Kontrak Beli</button>
            </div>
        </form>
    </div>
</div>

<script>
    const API_URL = "api.php";
    let selectedProductHarga = 0;

    document.addEventListener('DOMContentLoaded', async () => {
        try {
            // Ditambahkan token acak (_=timestamp) agar browser selalu melakukan verifikasi fresh tanpa cache basi
            const res = await fetch(`${API_URL}?action=check_session&_=` + new Date().getTime());
            const result = await res.json();
            
            // Perbaikan Validasi Utama: Ditambahkan .toLowerCase() agar tidak sensitif huruf kapital 'Pembeli' / 'pembeli'
            if (result.status !== 'success' || !result.role || result.role.toLowerCase() !== 'pembeli') {
                window.location.href = 'login.html';
                return;
            }
            
            // Memasang identitas pembeli ke komponen UI
            document.getElementById('side-username').textContent = result.username || 'Pembeli Mitra';
            document.getElementById('p-name-display').textContent = result.username || 'Pembeli Mitra';
            document.getElementById('p-mail').textContent = (result.username || 'user').toLowerCase() + "@pintopasai.com";
            document.getElementById('side-avatar').textContent = (result.username || 'B').charAt(0).toUpperCase();
            document.getElementById('prof-avatar').textContent = (result.username || 'B').charAt(0).toUpperCase();

            syncBuyerData();
        } catch (e) {
            window.location.href = 'login.html';
        }
    });

    function switchTab(id) {
        document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
        const target = document.getElementById(id);
        if (target) target.classList.add('active');

        document.querySelectorAll('[data-tab]').forEach(el => {
            el.classList.toggle('active', el.getAttribute('data-tab') === id);
        });

        const titleMap = { 'pasar': 'Pasar Komoditas', 'lacak': 'Lacak Pesanan', 'riwayat': 'Riwayat Belanja', 'profil': 'Profil Pembeli' };
        document.getElementById('m-title').textContent = titleMap[id] || '';
    }

    function syncBuyerData() {
        fetchMarketItems();
        fetchTrackingData();
        fetchPurchaseHistory();
    }

    // 1. AMBIL DATA KOMODITAS PASAR DARI SERVER
    async function fetchMarketItems() {
        const grid = document.getElementById('pasar-container');
        try {
            const res = await fetch(`${API_URL}?action=get_produk`);
            const result = await res.json();
            if (result.status === 'success' && result.data && result.data.length > 0) {
                grid.innerHTML = '';
                result.data.forEach(item => {
                    const icon = getProductIcon(item.nama);
                    grid.innerHTML += `
                        <div class="komoditas-card">
                            <div>
                                <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:12px;">
                                    <div class="komoditas-icon-box">${icon}</div>
                                    <span class="badge info"><i class="fa-solid fa-truck"></i> ${item.logistik}</span>
                                </div>
                                <h3 style="font-size:15px; color:var(--navy); font-weight:700;">${item.nama}</h3>
                                <p style="font-size:12px; color:var(--text-muted); margin-top:4px; line-height:1.4;">${item.deskripsi || 'Kualitas mutu hasil bumi terjamin terstandarisasi.'}</p>
                            </div>
                            <div style="margin-top:16px; border-top:1px solid #f1f5f9; padding-top:12px; display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <p style="font-size:10px; color:var(--text-muted);">Harga Kontrak</p>
                                    <h4 style="color:var(--emerald); font-size:14px; font-weight:700;">Rp ${parseInt(item.harga).toLocaleString('id-ID')}<span style="font-size:11px; font-weight:500; color:var(--text-muted);">/${item.satuan}</span></h4>
                                </div>
                                <button class="btn-action" onclick="openOrderModal(${item.id}, '${item.nama.replace(/'/g, "\\'")}', ${item.harga}, '${item.satuan}')">
                                    <i class="fa-solid fa-cart-shopping"></i> Beli
                                </button>
                            </div>
                        </div>`;
                });
            } else {
                grid.innerHTML = '<div class="empty-state"><i class="fa-solid fa-store-slash"></i><p>Belum ada produk hasil tani yang dipublikasikan oleh pemasok.</p></div>';
            }
        } catch (e) {
            grid.innerHTML = '<div class="empty-state" style="color:var(--danger);"><i class="fa-solid fa-circle-exclamation"></i><p>Gagal terhubung ke bursa pasar.</p></div>';
        }
    }

    // 2. AMBIL DATA LACAK STATUS PENGIRIMAN REAL-TIME
    async function fetchTrackingData() {
        const container = document.getElementById('lacak-container');
        try {
            const res = await fetch(`${API_URL}?action=get_tracking`);
            const result = await res.json();
            if (result.status === 'success' && result.data && result.data.length > 0) {
                container.innerHTML = '';
                result.data.forEach(item => {
                    let badgeClass = 'warning';
                    let statusLabelText = item.status_pengiriman;
                    let resiDisplay = '';

                    if (!item.no_resi || item.no_resi === 'BELUM DI-INPUT' || item.status_pengiriman === 'Menunggu Konfirmasi') {
                        badgeClass = 'danger';
                        statusLabelText = 'Menunggu Konfirmasi Pemasok';
                        resiDisplay = `<span style="color:var(--danger); font-weight:600;"><i class="fa-solid fa-hourglass-half"></i> Sedang Diproses Gudang</span>`;
                    } else {
                        if (item.status_pengiriman === 'Selesai') badgeClass = 'success';
                        if (item.status_pengiriman === 'Dalam Perjalanan') badgeClass = 'info';
                        resiDisplay = `<strong style="color:var(--navy); font-family:monospace; font-size:14px;">#${item.no_resi}</strong>`;
                    }

                    const n1 = (item.status_pengiriman === 'Memuat Barang' || item.status_pengiriman === 'Dalam Perjalanan' || item.status_pengiriman === 'Selesai') ? 'done' : 'active';
                    const n2 = item.status_pengiriman === 'Dalam Perjalanan' ? 'active' : (item.status_pengiriman === 'Selesai' ? 'done' : '');
                    const n3 = item.status_pengiriman === 'Selesai' ? 'active' : '';

                    container.innerHTML += `
                        <div class="content-card">
                            <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:10px;">
                                <div>
                                    <p style="font-size:11px; color:var(--text-muted);">No. Resi Armada</p>
                                    <div style="margin-top:2px;">${resiDisplay}</div>
                                </div>
                                <span class="badge ${badgeClass}">${statusLabelText}</span>
                            </div>
                            <p style="font-size:13px; margin-top:8px;">Komoditas: <strong>${item.komoditas}</strong></p>
                            <p style="font-size:13px; color:var(--text-muted); margin-top:2px;"><i class="fa-solid fa-location-dot" style="color:var(--gold);"></i> Bongkar di: ${item.tujuan}</p>
                            
                            <hr style="border:none; border-top:1px solid #f1f5f9; margin:14px 0;">
                            <div class="step-timeline">
                                <div class="step-node ${n1}"><h4 style="font-size:13px; font-weight:600;">Memuat Barang</h4></div>
                                <div class="step-node ${n2}">
                                    <h4 style="font-size:13px; font-weight:600;">Dalam Perjalanan</h4>
                                    ${item.status_pengiriman === 'Dalam Perjalanan' && item.catatan_status ? `<p style="font-size:11px; color:var(--text-muted); margin-top:2px;">${item.catatan_status}</p>` : ''}
                                </div>
                                <div class="step-node ${n3}"><h4 style="font-size:13px; font-weight:600;">Sampai di Penerima</h4></div>
                            </div>
                        </div>`;
                });
            } else {
                container.innerHTML = '<div class="content-card empty-state"><i class="fa-solid fa-route"></i><p>Anda belum memiliki rute pengantaran aktif.</p></div>';
            }
        } catch (e) {
            container.innerHTML = '<div class="content-card empty-state"><i class="fa-solid fa-circle-exclamation" style="color:var(--danger)"></i><p>Gagal memuat status pelacakan logistik.</p></div>';
        }
    }

    // 3. AMBIL DATA HISTORI TRANSAKSI PENJUALAN KONTRAK
    async function fetchPurchaseHistory() {
        const tbody = document.getElementById('riwayat-table-body');
        try {
            const res = await fetch(`${API_URL}?action=get_transaksi`);
            const result = await res.json();
            if (result.status === 'success' && result.data.length > 0) {
                tbody.innerHTML = '';
                result.data.forEach(t => {
                    let cBadge = 'info';
                    if (t.status_transaksi === 'Selesai') cBadge = 'success';
                    if (t.status_transaksi === 'Dibatalkan') cBadge = 'danger';

                    tbody.innerHTML += `
                        <tr>
                            <td style="font-weight:600; color:var(--navy);">#${t.no_nota}</td>
                            <td>${t.tanggal}</td>
                            <td>${t.komoditas}</td>
                            <td><strong>Rp ${parseInt(t.total_pembayaran).toLocaleString('id-ID')}</strong></td>
                            <td><span class="badge info">${t.metode}</span></td>
                            <td><span class="badge ${cBadge}">${t.status_transaksi}</span></td>
                        </tr>`;
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding:30px; color:var(--text-muted);"><i class="fa-solid fa-receipt" style="display:block; font-size:24px; margin-bottom:8px; color:var(--gold);"></i> Belum ada riwayat transaksi belanja.</td></tr>';
            }
        } catch (e) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding:20px; color:var(--danger);">Gagal membaca arsip nota.</td></tr>';
        }
    }

    // OPERASIONAL MODAL & HITUNG TOTAL OTOMATIS
    function openOrderModal(id, nama, harga, satuan) {
        selectedProductHarga = harga;
        document.getElementById('m-prod-id').value = id;
        document.getElementById('m-prod-nama').textContent = nama;
        document.getElementById('m-prod-harga').textContent = 'Rp ' + harga.toLocaleString('id-ID') + '/' + satuan;
        document.getElementById('m-prod-satuan').textContent = satuan;
        document.getElementById('m-qty').value = 1;
        calculateTotal();
        document.getElementById('modal-beli').classList.add('show');
    }

    function closeModal() {
        document.getElementById('modal-beli').classList.remove('show');
    }

    function calculateTotal() {
        const qty = parseFloat(document.getElementById('m-qty').value) || 0;
        const total = qty * selectedProductHarga;
        document.getElementById('m-total-display').textContent = 'Rp ' + Math.round(total).toLocaleString('id-ID');
    }

    // PROSES EKSEKUSI PEMBELIAN KE API BACKEND
    async function executePurchase(e) {
        e.preventDefault();
        const payload = {
            produk_id: document.getElementById('m-prod-id').value,
            qty: parseFloat(document.getElementById('m-qty').value),
            tujuan: document.getElementById('m-tujuan').value,
            metode: document.getElementById('m-metode').value
        };

        try {
            const res = await fetch(`${API_URL}?action=add_transaksi`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const result = await res.json();
            if (result.status === 'success') {
                showToast('success', 'Kontrak pembelian berhasil dibuat! Menunggu konfirmasi resi pemasok.');
                closeModal();
                syncBuyerData();
                switchTab('lacak');
            } else {
                showToast('error', result.message || 'Gagal memproses transaksi.');
            }
        } catch (err) {
            showToast('error', 'Gagal mengirim data pembelian ke server.');
        }
    }

    // UTILITIES & LOGOUT
    function getProductIcon(nama) {
        const n = (nama || '').toLowerCase();
        if (n.includes('beras'))   return '<i class="fa-solid fa-wheat-awn"></i>';
        if (n.includes('kedelai')) return '<i class="fa-solid fa-seedling"></i>';
        if (n.includes('gula'))    return '<i class="fa-solid fa-cubes-stack"></i>';
        if (n.includes('minyak'))  return '<i class="fa-solid fa-droplet"></i>';
        if (n.includes('tepung'))  return '<i class="fa-solid fa-jar"></i>';
        return '<i class="fa-solid fa-box"></i>';
    }

    async function handleLogoutSystem() {
        if (!confirm('Apakah Anda yakin ingin keluar dari sistem dashboard pembeli?')) return;
        await fetch(`${API_URL}?action=logout`);
        window.location.href = 'login.html';
    }

    let toastTimer;
    function showToast(type, msg) {
        const toast = document.getElementById('toast-msg');
        const icon  = document.getElementById('toast-icon');
        const text  = document.getElementById('toast-text');

        icon.className = type === 'success' ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-exclamation';
        text.textContent = msg;
        toast.className = `toast show ${type}`;

        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => { toast.className = 'toast'; }, 3500);
    }
</script>
</body>
</html>