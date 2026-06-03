<?php
ob_start();
session_start();
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set('Asia/Jakarta');

// Sembunyikan error PHP mentah ke browser, tapi tetap aman dalam JSON jika koneksi mati
error_reporting(0);
ini_set('display_errors', 0);

$conn = new mysqli("localhost", "root", "", "pinto_pasai");
if ($conn->connect_error) {
    ob_end_clean();
    echo json_encode(["status" => "error", "message" => "Database error: " . $conn->connect_error]); 
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$data = json_decode(file_get_contents("php://input"), true) ?? [];

if ($action == 'login') {
    $username = $conn->real_escape_string($data['username'] ?? '');
    $password = $data['password'] ?? '';
    $role     = $conn->real_escape_string($data['role'] ?? 'pemasok');

    // Tambahkan filter 'role' saat query login agar tidak salah kamar
    $res = $conn->query("SELECT * FROM users WHERE username = '$username' AND role = '$role'");
    if ($res && $res->num_rows > 0) {
        $user = $res->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username']; 
            ob_end_clean();
            echo json_encode(["status" => "success", "role" => $user['role']]);
        } else {
            ob_end_clean();
            echo json_encode(["status" => "error", "message" => "Password salah!"]);
        }
    } else {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "Username tidak ditemukan atau salah memilih tipe akun!"]);
    }
    exit;
}

if ($action == 'register') {
    $username = $conn->real_escape_string($data['username'] ?? '');
    $email = $conn->real_escape_string($data['email'] ?? '');
    $password = password_hash($data['password'] ?? '', PASSWORD_BCRYPT);
    $role = $conn->real_escape_string($data['role'] ?? 'pemasok');

    $check = $conn->query("SELECT id FROM users WHERE username = '$username' OR email = '$email'");
    if ($check && $check->num_rows > 0) {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "Username atau Email sudah terdaftar!"]);
        exit;
    }

    // Memisahkan penamaan variabel input agar sesuai data kiriman JavaScript pembeli/pemasok
    $telepon = $conn->real_escape_string($data['telepon'] ?? '');
    $alamat = $conn->real_escape_string($data['alamat'] ?? '');
    
    if ($role === 'pemasok') {
        $nama_usaha = $conn->real_escape_string($data['nama_usaha'] ?? '');
    } else {
        $nama_usaha = $conn->real_escape_string($data['nama_lengkap'] ?? '');
    }

    $q = "INSERT INTO users (username, email, password, role, nama_usaha, telepon, alamat, saldo) VALUES ('$username', '$email', '$password', '$role', '$nama_usaha', '$telepon', '$alamat', 0.00)";
    
    ob_end_clean();
    if ($conn->query($q)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal mendaftarkan akun ke database: " . $conn->error]);
    }
    exit;
}

if ($action == 'check_session') {
    ob_end_clean();
    if (isset($_SESSION['user_id'])) {
        echo json_encode([
            "status" => "success", 
            "role" => $_SESSION['role'] ?? 'pemasok',
            "username" => $_SESSION['username'] ?? 'User Mitra'
        ]);
    } else {
        echo json_encode(["status" => "unauth"]);
    }
    exit;
}

// Proteksi Session Global (Hanya berlaku untuk aksi di bawah baris ini)
if (!isset($_SESSION['user_id'])) {
    ob_end_clean();
    echo json_encode(["status" => "unauth", "message" => "Silakan login"]); 
    exit;
}

$uid = (int)$_SESSION['user_id'];
$urole = $_SESSION['role'] ?? 'pemasok';

if ($action == 'get_dashboard') {
    $u = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();
    $p = $conn->query("SELECT COUNT(*) as c FROM produk WHERE user_id=$uid")->fetch_assoc()['c'] ?? 0;
    $t = $conn->query("SELECT COUNT(*) as c FROM tracking WHERE user_id=$uid AND status_pengiriman != 'Selesai'")->fetch_assoc()['c'] ?? 0;
    $trx = $conn->query("SELECT COUNT(*) as c FROM transaksi WHERE user_id=$uid")->fetch_assoc()['c'] ?? 0;
    
    ob_end_clean();
    echo json_encode([
        "status" => "success",
        "profile" => [
            "nama_usaha" => $u['nama_usaha'] ?? $u['username'],
            "telepon" => $u['telepon'] ?? '',
            "alamat" => $u['alamat'] ?? ''
        ],
        "stats" => [
            "saldo" => (double)($u['saldo'] ?? 0),
            "produk_aktif" => (int)$p,
            "pengiriman_aktif" => (int)$t,
            "total_transaksi" => (int)$trx
        ]
    ]);
    exit;
} 

if ($action == 'get_produk') {
    if ($urole === 'pembeli') {
        $res = $conn->query("SELECT * FROM produk ORDER BY id DESC");
    } else {
        $res = $conn->query("SELECT * FROM produk WHERE user_id=$uid ORDER BY id DESC");
    }
    $out = []; 
    if ($res) {
        while($r = $res->fetch_assoc()) $out[] = $r;
    }
    ob_end_clean();
    echo json_encode(["status" => "success", "data" => $out]);
    exit;
}

if ($action == 'add_produk') {
    if (!isset($data['nama']) || !isset($data['harga']) || !isset($data['stok'])) {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "Data dalam form kurang!"]); exit;
    }
    $n = $conn->real_escape_string($data['nama']); 
    $h = (int)$data['harga'];
    $s = (double)$data['stok']; 
    $sat = $conn->real_escape_string($data['satuan'] ?? 'Kg');
    $d = $conn->real_escape_string($data['deskripsi'] ?? ''); 
    $l = $conn->real_escape_string($data['logistik'] ?? '');
    $p = $conn->real_escape_string($data['pembayaran'] ?? '');
    
    $q = "INSERT INTO produk (user_id, nama, harga, stok, satuan, deskripsi, logistik, pembayaran) VALUES ($uid, '$n', $h, $s, '$sat', '$d', '$l', '$p')";
    ob_end_clean();
    if ($conn->query($q)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal input data SQL: " . $conn->error]);
    }
    exit;
}

if ($action == 'delete_produk') {
    $pid = (int)($data['id'] ?? 0);
    ob_end_clean();
    if($conn->query("DELETE FROM produk WHERE id=$pid AND user_id=$uid")) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menghapus produk"]);
    }
    exit;
}

if ($action == 'get_tracking') {
    if ($urole === 'pembeli') {
        $res = $conn->query("SELECT * FROM tracking WHERE pembeli_id=$uid ORDER BY id DESC");
    } else {
        $res = $conn->query("SELECT * FROM tracking WHERE user_id=$uid ORDER BY id DESC");
    }
    $out = []; 
    if ($res) {
        while($r = $res->fetch_assoc()) $out[] = $r;
    }
    ob_end_clean();
    echo json_encode(["status" => "success", "data" => $out]);
    exit;
}

if ($action == 'get_transaksi') {
    if ($urole === 'pembeli') {
        $res = $conn->query("SELECT * FROM transaksi WHERE pembeli_id=$uid ORDER BY id DESC");
    } else {
        $res = $conn->query("SELECT * FROM transaksi WHERE user_id=$uid ORDER BY id DESC");
    }
    $out = []; 
    if ($res) {
        while($r = $res->fetch_assoc()) $out[] = $r;
    }
    ob_end_clean();
    echo json_encode(["status" => "success", "data" => $out]);
    exit;
}

if ($action == 'update_profil') {
    $nu = $conn->real_escape_string($data['nama_usaha'] ?? ''); 
    $t = $conn->real_escape_string($data['telepon'] ?? ''); 
    $a = $conn->real_escape_string($data['alamat'] ?? '');
    ob_end_clean();
    if($conn->query("UPDATE users SET nama_usaha='$nu', telepon='$t', alamat='$a' WHERE id=$uid")) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal update profil"]);
    }
    exit;
}

if ($action == 'add_transaksi') {
    $produk_id = (int)($data['produk_id'] ?? 0);
    $qty = (double)($data['qty'] ?? 0);
    $tujuan = $conn->real_escape_string($data['tujuan'] ?? '');
    $metode = $conn->real_escape_string($data['metode'] ?? 'Transfer Bank');

    $p_res = $conn->query("SELECT * FROM produk WHERE id=$produk_id");
    if (!$p_res || $p_res->num_rows == 0) {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "Komoditas tidak ditemukan."]);
        exit;
    }
    $produk = $p_res->fetch_assoc();
    
    if ((double)$produk['stok'] < $qty) {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "Stok bursa tidak mencukupi! Sisa stok saat ini: " . $produk['stok'] . " " . $produk['satuan']]);
        exit;
    }

    $pemasok_id = (int)$produk['user_id'];
    $total_bayar = (double)$qty * (double)$produk['harga'];
    $no_nota = "NTA-" . strtoupper(substr(md5(time() . rand()), 0, 8));
    $tanggal_sekarang = date("Y-m-d H:i:s");

    $q_trx = "INSERT INTO transaksi (user_id, pembeli_id, no_nota, tanggal, komoditas, total_pembayaran, metode, status_transaksi) 
              VALUES ($pemasok_id, $uid, '$no_nota', '$tanggal_sekarang', '{$produk['nama']}', $total_bayar, '$metode', 'Diproses')";
    
    if ($conn->query($q_trx)) {
        $conn->query("UPDATE produk SET stok = stok - $qty WHERE id=$produk_id");

        $p_nama = $conn->real_escape_string($produk['nama']);
        $q_track = "INSERT INTO tracking (user_id, pembeli_id, no_resi, komoditas, tujuan, status_pengiriman, catatan_status) 
                    VALUES ($pemasok_id, $uid, 'BELUM DI-INPUT', '$p_nama', '$tujuan', 'Menunggu Konfirmasi', 'Menunggu konfirmasi penerimaan kontrak.')";
        $conn->query($q_track);

        ob_end_clean();
        echo json_encode(["status" => "success", "message" => "Kontrak pembelian berhasil disimpan."]);
    } else {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "Gagal Query Transaksi: " . $conn->error]);
    }
    exit;
}

if ($action == 'setujui_pesanan' || $action == 'update_resi') {
    // Membaca input data secara fleksibel (Mendukung Payload JSON maupun POST biasa)
    $transaksi_id = isset($data['transaksi_id']) ? (int)$data['transaksi_id'] : (isset($_POST['transaksi_id']) ? (int)$_POST['transaksi_id'] : 0);
    $tracking_id  = isset($data['id']) ? (int)$data['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : 0);
    $no_resi      = isset($data['no_resi']) ? $data['no_resi'] : (isset($_POST['no_resi']) ? $_POST['no_resi'] : '');
    
    // Ambil parameter opsi tambahan dinamis dari pemasok (jam batas ambil atau hari estimasi antar)
    $batas_jam    = isset($data['batas_jam']) ? (int)$data['batas_jam'] : (isset($_POST['batas_jam']) ? (int)$_POST['batas_jam'] : 24);
    $estimasi     = isset($data['estimasi']) ? $data['estimasi'] : (isset($_POST['estimasi']) ? $_POST['estimasi'] : '1-3 Hari');

    $no_resi = $conn->real_escape_string($no_resi);

    if (empty($no_resi)) {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "Nomor resi armada tidak boleh kosong!"]);
        exit;
    }

    // 1. Dapatkan informasi 'tujuan' logistik terlebih dahulu untuk membedakan Ambil vs Antar
    $where_check = ($transaksi_id > 0) ? "transaksi_id = $transaksi_id" : "id = $tracking_id";
    if ($transaksi_id == 0 && $tracking_id == 0) {
        $where_check = "(no_resi = 'BELUM DI-INPUT' OR no_resi = '')";
    }
    
    $check_track = $conn->query("SELECT tujuan FROM tracking WHERE $where_check AND user_id = $uid LIMIT 1");
    $logistik_type = 'antar'; // fallback default
    
    if ($check_track && $check_track->num_rows > 0) {
        $track_data = $check_track->fetch_assoc();
        // Jika kolom tujuan berisi instruksi ambil sendiri, ubah modenya
        if (stripos($track_data['tujuan'], 'ambil') !== false || stripos($track_data['tujuan'], 'gudang') !== false) {
            $logistik_type = 'ambil';
        }
    }

    // 2. Berikan status pengiriman & catatan teks informatif sesuai tipe logistiknya
    if ($logistik_type === 'ambil') {
        $status_pengiriman = "Siap Diambil";
        $catatan_status    = "Barang sudah siap di gudang pemasok. Silakan ambil maksimal dalam " . $batas_jam . " jam dengan membawa bukti kode pengangkutan: " . $no_resi;
    } else {
        $status_pengiriman = "Dalam Pengiriman";
        $catatan_status    = "Armada logistik kargo telah berangkat menuju lokasi. Estimasi sampai tujuan: " . $conn->real_escape_string($estimasi) . ".";
    }

    // 3. Jalankan query update data berdasarkan filter ID yang tersedia
    if ($transaksi_id > 0) {
        $q_resi = "UPDATE tracking SET 
                   no_resi = '$no_resi', 
                   status_pengiriman = '$status_pengiriman', 
                   catatan_status = '$catatan_status' 
                   WHERE transaksi_id = $transaksi_id AND user_id = $uid";
    } elseif ($tracking_id > 0) {
        $q_resi = "UPDATE tracking SET 
                   no_resi = '$no_resi', 
                   status_pengiriman = '$status_pengiriman', 
                   catatan_status = '$catatan_status' 
                   WHERE id = $tracking_id AND user_id = $uid";
    } else {
        $q_resi = "UPDATE tracking SET 
                   no_resi = '$no_resi', 
                   status_pengiriman = '$status_pengiriman', 
                   catatan_status = '$catatan_status' 
                   WHERE (no_resi = 'BELUM DI-INPUT' OR no_resi = '') AND user_id = $uid LIMIT 1";
    }

    ob_end_clean();
    if ($conn->query($q_resi)) {
        echo json_encode(["status" => "success", "message" => "Nomor resi kargo berhasil diperbarui!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal memperbarui resi database: " . $conn->error]);
    }
    exit;
}

if ($action == 'selesaikan_pesanan') {
    $tracking_id  = isset($data['id']) ? (int)$data['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : 0);
    $transaksi_id = isset($data['transaksi_id']) ? (int)$data['transaksi_id'] : (isset($_POST['transaksi_id']) ? (int)$_POST['transaksi_id'] : 0);

    $where_finish = ($transaksi_id > 0) ? "transaksi_id = $transaksi_id" : "id = $tracking_id";

    if ($transaksi_id == 0 && $tracking_id == 0) {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "ID pesanan tracking tidak valid."]);
        exit;
    }

    // Update status logistik pengiriman akhir menjadi Selesai secara sepihak oleh pemasok
    $q_finish_track = "UPDATE tracking SET status_pengiriman = 'Selesai', catatan_status = 'Pesanan telah selesai diserahterimakan dan ditutup oleh pihak pemasok.' WHERE $where_finish AND user_id = $uid";
    
    if ($transaksi_id > 0) {
        $conn->query("UPDATE transaksi SET status_transaksi = 'Selesai' WHERE id = $transaksi_id AND user_id = $uid");
    }

    ob_end_clean();
    if ($conn->query($q_finish_track)) {
        echo json_encode(["status" => "success", "message" => "Status pesanan bursa berhasil diselesaikan!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal memproses penyelesaian pesanan: " . $conn->error]);
    }
    exit;
}

if ($action == 'logout') {
    session_destroy();
    ob_end_clean();
    echo json_encode(["status" => "success"]);
    exit;
}

// Catch-all jika action tidak terdaftar / salah panggil nama action
ob_end_clean();
echo json_encode(["status" => "error", "message" => "Aksi tidak dikenal"]);

$conn->close();
?>