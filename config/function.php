<?php
// Include database configuration first
require_once __DIR__ . '/db.php';

// Define global variables
global $app_name, $url;
$app_name = "JangkrikNet Store";
$url = "http://localhost/hfd/";

// Verify database connection
if (!isset($conn) || !$conn) {
    die("Error: Database connection not available. Please check your database configuration.");
}

// ============= FUNGSI DASAR ===============


function escape_string($data) {
    global $conn;
    if (!isset($conn) || !$conn) {
        die("Error: Database connection not available in escape_string function.");
    }
    return $conn->real_escape_string($data);
}


function query_all($query) {
    global $conn;
    if (!isset($conn) || !$conn) {
        die("Error: Database connection not available in query_all function.");
    }
    
    $result = $conn->query($query);
    $data = [];
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        return [
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' => $data
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Query gagal: ' . $conn->error,
        'data' => null
    ];
}


function query_one($query) {
    global $conn;
    if (!isset($conn) || !$conn) {
        die("Error: Database connection not available in query_one function.");
    }
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        return [
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' => $result->fetch_assoc()
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Data tidak ditemukan',
        'data' => null
    ];
}


function execute_query($query) {
    global $conn;
    
    if ($conn->query($query)) {
        $last_id = $conn->insert_id;
        
        return [
            'status' => true,
            'message' => 'Query berhasil dieksekusi',
            'insert_id' => $last_id
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Query gagal: ' . $conn->error,
        'insert_id' => null
    ];
}


function generate_invoice_code() {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $code = '';
    
    for ($i = 0; $i < 6; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    return $code;
}


function upload_file($file, $destination = 'uploads/') {
    // Pastikan direktori ada
    if (!is_dir($destination)) {
        mkdir($destination, 0777, true);
    }
    
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];
    
    // Validasi file
    if ($file_error === 0) {
        // Validasi tipe file
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        if (!in_array($file_ext, $allowed)) {
            return [
                'status' => false,
                'message' => 'Tipe file tidak diizinkan. Hanya file gambar (jpg, jpeg, png, gif) yang diperbolehkan.',
                'file_name' => null
            ];
        }
        
        // Validasi ukuran file (max 5MB)
        if ($file_size > 5000000) {
            return [
                'status' => false,
                'message' => 'Ukuran file terlalu besar. Maksimal 5MB.',
                'file_name' => null
            ];
        }
        
        // Generate unique filename
        $file_name_new = uniqid('', true) . '_' . $file_name;
        $file_destination = $destination . $file_name_new;
        
        if (move_uploaded_file($file_tmp, $file_destination)) {
            return [
                'status' => true,
                'message' => 'File berhasil diupload',
                'file_name' => $file_name_new
            ];
        } else {
            return [
                'status' => false,
                'message' => 'Gagal memindahkan file',
                'file_name' => null
            ];
        }
    }
    
    return [
        'status' => false,
        'message' => 'Terjadi kesalahan saat upload file: ' . $file_error,
        'file_name' => null
    ];
}

// ============= FUNGSI USER ===============


function login($username, $password) {
    $username = escape_string($username);
    
    $query = "SELECT * FROM user WHERE username = '$username'";
    $result = query_one($query);
    
    if ($result['status']) {
        $user = $result['data'];
        
        // Verifikasi password (assuming it's hashed with password_hash)
        if (password_verify($password, $user['password'])) {
            unset($user['password']); // Remove password from response
            
            return [
                'status' => true,
                'message' => 'Login berhasil',
                'data' => $user
            ];
        }
        
        return [
            'status' => false,
            'message' => 'Password salah',
            'data' => null
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Username tidak ditemukan',
        'data' => null
    ];
}


function register_user($data) {
    $nama = escape_string($data['nama']);
    $username = escape_string($data['username']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $email = escape_string($data['email']);
    $alamat = escape_string($data['alamat']);
    $no_telp = escape_string($data['no_telp']);
    $role = isset($data['role']) ? escape_string($data['role']) : 'user';
    
    // Check if username already exists
    $check_username = query_one("SELECT id FROM user WHERE username = '$username'");
    if ($check_username['status']) {
        return [
            'status' => false,
            'message' => 'Username sudah digunakan',
            'data' => null
        ];
    }
    
    // Check if email already exists
    $check_email = query_one("SELECT id FROM user WHERE email = '$email'");
    if ($check_email['status']) {
        return [
            'status' => false,
            'message' => 'Email sudah digunakan',
            'data' => null
        ];
    }
    
    $query = "INSERT INTO user (nama, username, password, email, alamat, no_telp, role) 
              VALUES ('$nama', '$username', '$password', '$email', '$alamat', '$no_telp', '$role')";
              
    $result = execute_query($query);
    
    if ($result['status']) {
        return [
            'status' => true,
            'message' => 'Registrasi berhasil',
            'data' => [
                'id' => $result['insert_id'],
                'nama' => $nama,
                'username' => $username,
                'email' => $email,
                'alamat' => $alamat,
                'no_telp' => $no_telp,
                'role' => $role
            ]
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Registrasi gagal: ' . $result['message'],
        'data' => null
    ];
}


function get_all_users() {
    $query = "SELECT id, nama, username, email, alamat, no_telp, role FROM user";
    return query_all($query);
}


function get_user_by_id($id) {
    $id = (int) $id;
    $query = "SELECT id, nama, username, email, alamat, no_telp, role FROM user WHERE id = $id";
    return query_one($query);
}


function update_user($id, $data) {
    $id = (int) $id;
    
    // Ambil data user yang akan diupdate
    $user = get_user_by_id($id);
    if (!$user['status']) {
        return [
            'status' => false,
            'message' => 'User tidak ditemukan',
            'data' => null
        ];
    }
    
    $nama = isset($data['nama']) ? escape_string($data['nama']) : $user['data']['nama'];
    $email = isset($data['email']) ? escape_string($data['email']) : $user['data']['email'];
    $alamat = isset($data['alamat']) ? escape_string($data['alamat']) : $user['data']['alamat'];
    $no_telp = isset($data['no_telp']) ? escape_string($data['no_telp']) : $user['data']['no_telp'];
    $role = isset($data['role']) ? escape_string($data['role']) : $user['data']['role'];
    
    // Update password jika ada
    $password_sql = "";
    if (isset($data['password']) && !empty($data['password'])) {
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $password_sql = ", password = '$password'";
    }
    
    // Check if email already exists
    if ($email != $user['data']['email']) {
        $check_email = query_one("SELECT id FROM user WHERE email = '$email' AND id != $id");
        if ($check_email['status']) {
            return [
                'status' => false,
                'message' => 'Email sudah digunakan',
                'data' => null
            ];
        }
    }
    
    $query = "UPDATE user SET 
              nama = '$nama', 
              email = '$email', 
              alamat = '$alamat', 
              no_telp = '$no_telp',
              role = '$role'
              $password_sql
              WHERE id = $id";
              
    $result = execute_query($query);
    
    if ($result['status']) {
        return [
            'status' => true,
            'message' => 'User berhasil diupdate',
            'data' => [
                'id' => $id,
                'nama' => $nama,
                'email' => $email,
                'alamat' => $alamat,
                'no_telp' => $no_telp,
                'role' => $role
            ]
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Update user gagal: ' . $result['message'],
        'data' => null
    ];
}


function delete_user($id) {
    $id = (int) $id;
    
    // Cek apakah user ada
    $user = get_user_by_id($id);
    if (!$user['status']) {
        return [
            'status' => false,
            'message' => 'User tidak ditemukan',
            'data' => null
        ];
    }
    
    $query = "DELETE FROM user WHERE id = $id";
    $result = execute_query($query);
    
    if ($result['status']) {
        return [
            'status' => true,
            'message' => 'User berhasil dihapus',
            'data' => null
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Hapus user gagal: ' . $result['message'],
        'data' => null
    ];
}

// ============= FUNGSI KATEGORI ===============


function create_kategori($nama) {
    $nama = escape_string($nama);
    
    $query = "INSERT INTO kategori (nama) VALUES ('$nama')";
    $result = execute_query($query);
    
    if ($result['status']) {
        return [
            'status' => true,
            'message' => 'Kategori berhasil ditambahkan',
            'data' => [
                'id' => $result['insert_id'],
                'nama' => $nama
            ]
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Tambah kategori gagal: ' . $result['message'],
        'data' => null
    ];
}


function get_all_kategori() {
    $query = "SELECT * FROM kategori ORDER BY nama ASC";
    return query_all($query);
}


function get_kategori_by_id($id) {
    $id = (int) $id;
    $query = "SELECT * FROM kategori WHERE id = $id";
    return query_one($query);
}


function update_kategori($id, $nama) {
    $id = (int) $id;
    $nama = escape_string($nama);
    
    // Cek apakah kategori ada
    $kategori = get_kategori_by_id($id);
    if (!$kategori['status']) {
        return [
            'status' => false,
            'message' => 'Kategori tidak ditemukan',
            'data' => null
        ];
    }
    
    $query = "UPDATE kategori SET nama = '$nama' WHERE id = $id";
    $result = execute_query($query);
    
    if ($result['status']) {
        return [
            'status' => true,
            'message' => 'Kategori berhasil diupdate',
            'data' => [
                'id' => $id,
                'nama' => $nama
            ]
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Update kategori gagal: ' . $result['message'],
        'data' => null
    ];
}


function delete_kategori($id) {
    $id = (int) $id;
    
    // Cek apakah kategori ada
    $kategori = get_kategori_by_id($id);
    if (!$kategori['status']) {
        return [
            'status' => false,
            'message' => 'Kategori tidak ditemukan',
            'data' => null
        ];
    }
    
    // Cek apakah kategori digunakan oleh produk
    $check_produk = query_all("SELECT id, nama FROM produk WHERE id_kategori = $id");
    if ($check_produk['status'] && !empty($check_produk['data'])) {
        $produk_list = array_map(function($p) {
            return $p['nama'];
        }, $check_produk['data']);
        
        return [
            'status' => false,
            'message' => 'Kategori tidak dapat dihapus karena digunakan oleh produk: ' . implode(', ', $produk_list),
            'data' => null
        ];
    }
    
    $query = "DELETE FROM kategori WHERE id = $id";
    $result = execute_query($query);
    
    if ($result['status']) {
        return [
            'status' => true,
            'message' => 'Kategori berhasil dihapus',
            'data' => null
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Hapus kategori gagal: ' . $result['message'],
        'data' => null
    ];
}

// ============= FUNGSI PRODUK ===============


function create_produk($data, $file = null) {
    // Validate required fields
    $required_fields = ['nama', 'id_kategori', 'deskripsi', 'harga', 'stock'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            return [
                'status' => false,
                'message' => "Field $field harus diisi",
                'data' => null
            ];
        }
    }

    $nama = escape_string($data['nama']);
    $id_kategori = escape_string($data['id_kategori']);
    $deskripsi = escape_string($data['deskripsi']);
    $harga = escape_string($data['harga']);
    $stock = escape_string($data['stock']);
    
    // Upload gambar jika ada
    $gambar = null;
    if ($file && $file['error'] === 0) {
        $upload_result = upload_file($file, 'uploads/');
        if (!$upload_result['status']) {
            return [
                'status' => false,
                'message' => $upload_result['message'],
                'data' => null
            ];
        }
        $gambar = $upload_result['file_name'];
    } else {
        return [
            'status' => false,
            'message' => 'Gambar produk harus diupload',
            'data' => null
        ];
    }
    
    $query = "INSERT INTO produk (id_kategori, nama, gambar, deskripsi, harga, stock) 
              VALUES ('$id_kategori', '$nama', '$gambar', '$deskripsi', '$harga', '$stock')";
              
    $result = execute_query($query);
    
    if ($result['status']) {
        return [
            'status' => true,
            'message' => 'Produk berhasil ditambahkan',
            'data' => [
                'id' => $result['insert_id'],
                'id_kategori' => $id_kategori,
                'nama' => $nama,
                'gambar' => $gambar,
                'deskripsi' => $deskripsi,
                'harga' => $harga,
                'stock' => $stock
            ]
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Gagal menambahkan produk: ' . $result['message'],
        'data' => null
    ];
}


function get_all_produk($filters = []) {
    $where = "";
    
    // Filter berdasarkan kategori
    if (isset($filters['id_kategori']) && !empty($filters['id_kategori'])) {
        $id_kategori = (int) $filters['id_kategori'];
        $where = "WHERE p.id_kategori = $id_kategori";
    }
    
    // Filter berdasarkan keyword
    if (isset($filters['keyword']) && !empty($filters['keyword'])) {
        $keyword = escape_string($filters['keyword']);
        $where = $where ? $where . " AND p.nama LIKE '%$keyword%'" : "WHERE p.nama LIKE '%$keyword%'";
    }
    
    $query = "SELECT p.*, k.nama as nama_kategori 
              FROM produk p 
              JOIN kategori k ON p.id_kategori = k.id 
              $where 
              ORDER BY p.nama ASC";
              
    return query_all($query);
}


function get_produk_by_id($id) {
    $id = (int) $id;
    $query = "SELECT p.*, k.nama as nama_kategori 
              FROM produk p 
              JOIN kategori k ON p.id_kategori = k.id 
              WHERE p.id = $id";
              
    return query_one($query);
}


function update_produk($id, $data, $file = null) {
    // Validate required fields
    $required_fields = ['nama', 'id_kategori', 'deskripsi', 'harga', 'stock'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            return [
                'status' => false,
                'message' => "Field $field harus diisi",
                'data' => null
            ];
        }
    }

    $id = escape_string($id);
    $nama = escape_string($data['nama']);
    $id_kategori = escape_string($data['id_kategori']);
    $deskripsi = escape_string($data['deskripsi']);
    $harga = escape_string($data['harga']);
    $stock = escape_string($data['stock']);
    
    // Upload gambar jika ada
    $gambar = null;
    if ($file && $file['error'] === 0) {
        $upload_result = upload_file($file, 'uploads/');
        if (!$upload_result['status']) {
            return [
                'status' => false,
                'message' => $upload_result['message'],
                'data' => null
            ];
        }
        $gambar = $upload_result['file_name'];
        
        // Hapus gambar lama
        $old_produk = get_produk_by_id($id);
        if ($old_produk['status'] && $old_produk['data']['gambar']) {
            $old_gambar = 'uploads/' . $old_produk['data']['gambar'];
            if (file_exists($old_gambar)) {
                unlink($old_gambar);
            }
        }
    }
    
    $query = "UPDATE produk SET 
              id_kategori = '$id_kategori',
              nama = '$nama',
              deskripsi = '$deskripsi',
              harga = '$harga',
              stock = '$stock'";
              
    if ($gambar) {
        $query .= ", gambar = '$gambar'";
    }
    
    $query .= " WHERE id = '$id'";
    
    $result = execute_query($query);
    
    if ($result['status']) {
        return [
            'status' => true,
            'message' => 'Produk berhasil diupdate',
            'data' => [
                'id' => $id,
                'id_kategori' => $id_kategori,
                'nama' => $nama,
                'deskripsi' => $deskripsi,
                'harga' => $harga,
                'stock' => $stock,
                'gambar' => $gambar ? $gambar : $old_produk['data']['gambar']
            ]
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Gagal mengupdate produk: ' . $result['message'],
        'data' => null
    ];
}


function delete_produk($id) {
    $id = (int) $id;
    
    // Cek apakah produk ada
    $produk = get_produk_by_id($id);
    if (!$produk['status']) {
        return [
            'status' => false,
            'message' => 'Produk tidak ditemukan',
            'data' => null
        ];
    }
    
    // Cek apakah produk ada di keranjang
    $check_keranjang = query_one("SELECT id FROM keranjang WHERE id_produk = $id LIMIT 1");
    if ($check_keranjang['status']) {
        return [
            'status' => false,
            'message' => 'Produk tidak dapat dihapus karena ada di keranjang',
            'data' => null
        ];
    }
    
    // Cek apakah produk ada di detail pesanan
    $check_detail = query_one("SELECT id FROM detail_pesanan WHERE id_produk = $id LIMIT 1");
    if ($check_detail['status']) {
        return [
            'status' => false,
            'message' => 'Produk tidak dapat dihapus karena sudah ada dalam pesanan',
            'data' => null
        ];
    }
    
    $query = "DELETE FROM produk WHERE id = $id";
    $result = execute_query($query);
    
    if ($result['status']) {
        // Hapus gambar jika ada
        $gambar = $produk['data']['gambar'];
        if ($gambar && file_exists('uploads/' . $gambar)) {
            unlink('uploads/' . $gambar);
        }
        
        return [
            'status' => true,
            'message' => 'Produk berhasil dihapus',
            'data' => null
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Hapus produk gagal: ' . $result['message'],
        'data' => null
    ];
}

// ============= FUNGSI KERANJANG ===============


function add_to_cart($id_user, $id_produk, $qty) {
    $id_user = (int) $id_user;
    $id_produk = (int) $id_produk;
    $qty = (int) $qty;
    
    // Cek apakah produk ada
    $produk = get_produk_by_id($id_produk);
    if (!$produk['status']) {
        return [
            'status' => false,
            'message' => 'Produk tidak ditemukan',
            'data' => null
        ];
    }
    
    // Cek stok produk
    if ($produk['data']['stock'] < $qty) {
        return [
            'status' => false,
            'message' => 'Stok tidak mencukupi',
            'data' => null
        ];
    }
    
    // Cek apakah produk sudah ada di keranjang
    $check = query_one("SELECT * FROM keranjang WHERE id_user = $id_user AND id_produk = $id_produk");
    
    if ($check['status']) {
        // Update qty jika produk sudah ada di keranjang
        $new_qty = $check['data']['qty'] + $qty;
        
        // Cek stok kembali
        if ($produk['data']['stock'] < $new_qty) {
            return [
                'status' => false,
                'message' => 'Stok tidak mencukupi',
                'data' => null
            ];
        }
        
        $query = "UPDATE keranjang SET qty = $new_qty WHERE id = " . $check['data']['id'];
        $result = execute_query($query);
        
        if ($result['status']) {
            return [
                'status' => true,
                'message' => 'Produk berhasil diupdate di keranjang',
                'data' => [
                    'id' => $check['data']['id'],
                    'id_user' => $id_user,
                    'id_produk' => $id_produk,
                    'nama_produk' => $produk['data']['nama'],
                    'qty' => $new_qty,
                    'harga' => $produk['data']['harga']
                ]
            ];
        }
    } else {
        // Tambahkan produk baru ke keranjang
        $harga = $produk['data']['harga'];
        $query = "INSERT INTO keranjang (id_user, id_produk, qty, harga) 
                  VALUES ($id_user, $id_produk, $qty, $harga)";
                  
        $result = execute_query($query);
        
        if ($result['status']) {
            return [
                'status' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'data' => [
                    'id' => $result['insert_id'],
                    'id_user' => $id_user,
                    'id_produk' => $id_produk,
                    'nama_produk' => $produk['data']['nama'],
                    'qty' => $qty,
                    'harga' => $harga
                ]
            ];
        }
    }
    
    return [
        'status' => false,
        'message' => 'Gagal menambahkan produk ke keranjang',
        'data' => null
    ];
}


function get_cart($id_user) {
    $id_user = (int) $id_user;
    
    $query = "SELECT k.*, p.nama as nama_produk, p.gambar, p.stock 
              FROM keranjang k 
              JOIN produk p ON k.id_produk = p.id 
              WHERE k.id_user = $id_user";
              
    return query_all($query);
}


function update_cart_qty($id_keranjang, $qty) {
    $id_keranjang = (int) $id_keranjang;
    $qty = (int) $qty;
    
    // Cek apakah item keranjang ada
    $cart_item = query_one("SELECT * FROM keranjang WHERE id = $id_keranjang");
    if (!$cart_item['status']) {
        return [
            'status' => false,
            'message' => 'Item keranjang tidak ditemukan',
            'data' => null
        ];
    }
    
    // Cek stok produk
    $produk = get_produk_by_id($cart_item['data']['id_produk']);
    if ($produk['data']['stock'] < $qty) {
        return [
            'status' => false,
            'message' => 'Stok tidak mencukupi',
            'data' => null
        ];
    }
    
    $query = "UPDATE keranjang SET qty = $qty WHERE id = $id_keranjang";
    $result = execute_query($query);
    
    if ($result['status']) {
        return [
            'status' => true,
            'message' => 'Jumlah produk berhasil diupdate',
            'data' => [
                'id' => $id_keranjang,
                'id_produk' => $cart_item['data']['id_produk'],
                'qty' => $qty,
                'harga' => $cart_item['data']['harga']
            ]
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Update jumlah produk gagal',
        'data' => null
    ];
}


function remove_from_cart($id_keranjang) {
    $id_keranjang = (int) $id_keranjang;
    
    // Cek apakah item keranjang ada
    $cart_item = query_one("SELECT * FROM keranjang WHERE id = $id_keranjang");
    if (!$cart_item['status']) {
        return [
            'status' => false,
            'message' => 'Item keranjang tidak ditemukan',
            'data' => null
        ];
    }
    
    $query = "DELETE FROM keranjang WHERE id = $id_keranjang";
    $result = execute_query($query);
    
    if ($result['status']) {
        return [
            'status' => true,
            'message' => 'Produk berhasil dihapus dari keranjang',
            'data' => null
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Hapus produk dari keranjang gagal',
        'data' => null
    ];
}


function empty_cart($id_user) {
    $id_user = (int) $id_user;
    
    $query = "DELETE FROM keranjang WHERE id_user = $id_user";
    $result = execute_query($query);
    
    if ($result['status']) {
        return [
            'status' => true,
            'message' => 'Keranjang berhasil dikosongkan',
            'data' => null
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Gagal mengosongkan keranjang',
        'data' => null
    ];
}


function get_cart_total($id_user) {
    $id_user = (int) $id_user;
    
    $query = "SELECT SUM(qty * harga) as total FROM keranjang WHERE id_user = $id_user";
    $result = query_one($query);
    
    if ($result['status']) {
        return [
            'status' => true,
            'message' => 'Total belanja berhasil dihitung',
            'data' => [
                'total' => $result['data']['total'] ? $result['data']['total'] : 0
            ]
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Gagal menghitung total belanja',
        'data' => null
    ];
}

// ============= FUNGSI PESANAN ===============


function create_order($id_user, $metode_pembayaran) {
    global $conn;
    $id_user = (int) $id_user;
    $metode_pembayaran = escape_string($metode_pembayaran);
    
    // Cek apakah keranjang ada dan tidak kosong
    $cart = get_cart($id_user);
    if (!$cart['status'] || empty($cart['data'])) {
        return [
            'status' => false,
            'message' => 'Keranjang kosong',
            'data' => null
        ];
    }
    
    // Hitung total belanja
    $cart_total = get_cart_total($id_user);
    if (!$cart_total['status']) {
        return [
            'status' => false,
            'message' => 'Gagal menghitung total belanja',
            'data' => null
        ];
    }
    
    $total_harga = $cart_total['data']['total'];
    $kode_invoice = generate_invoice_code();
    $tanggal = date('Y-m-d H:i:s');
    
    // Mulai transaksi
    $conn->begin_transaction();
    
    try {
        // Buat pesanan
        $query = "INSERT INTO pesanan (id_user, kode_invoice, total_harga, status, tanggal) 
                  VALUES ($id_user, '$kode_invoice', $total_harga, 'Pending', '$tanggal')";
                  
        $result = execute_query($query);
        
        if (!$result['status']) {
            throw new Exception('Gagal membuat pesanan: ' . $result['message']);
        }
        
        $id_pesanan = $result['insert_id'];
        
        // Tambahkan detail pesanan
        foreach ($cart['data'] as $item) {
            $id_produk = $item['id_produk'];
            $qty = $item['qty'];
            $harga = $item['harga'];
            
            $query = "INSERT INTO detail_pesanan (id_pesanan, id_produk, qty, harga) 
                      VALUES ($id_pesanan, $id_produk, $qty, $harga)";
                      
            $result = execute_query($query);
            
            if (!$result['status']) {
                throw new Exception('Gagal menambahkan detail pesanan: ' . $result['message']);
            }
            
            // Kurangi stok produk
            $query = "UPDATE produk SET stock = stock - $qty WHERE id = $id_produk";
            $result = execute_query($query);
            
            if (!$result['status']) {
                throw new Exception('Gagal mengupdate stok produk: ' . $result['message']);
            }
        }
        
        // Buat entri pembayaran
        $query = "INSERT INTO pembayaran (id_pesanan, nominal_pembayaran, tanggal, status, metode_pembayaran) 
                  VALUES ($id_pesanan, $total_harga, '$tanggal', 'pending', '$metode_pembayaran')";
                  
        $result = execute_query($query);
        
        if (!$result['status']) {
            throw new Exception('Gagal membuat entri pembayaran: ' . $result['message']);
        }
        
        // Kosongkan keranjang
        $result = empty_cart($id_user);
        
        if (!$result['status']) {
            throw new Exception('Gagal mengosongkan keranjang: ' . $result['message']);
        }
        
        // Commit transaksi
        $conn->commit();
        
        return [
            'status' => true,
            'message' => 'Pesanan berhasil dibuat',
            'data' => [
                'id_pesanan' => $id_pesanan,
                'kode_invoice' => $kode_invoice,
                'total_harga' => $total_harga,
                'status' => 'Pending',
                'tanggal' => $tanggal
            ]
        ];
    } catch (Exception $e) {
        // Rollback transaksi jika ada error
        $conn->rollback();
        
        return [
            'status' => false,
            'message' => $e->getMessage(),
            'data' => null
        ];
    }
}


function get_all_orders($filters = []) {
    $where = "";
    
    // Filter berdasarkan user
    if (isset($filters['id_user']) && !empty($filters['id_user'])) {
        $id_user = (int) $filters['id_user'];
        $where = "WHERE p.id_user = $id_user";
    }
    
    // Filter berdasarkan status
    if (isset($filters['status']) && !empty($filters['status'])) {
        $status = escape_string($filters['status']);
        $where = $where ? $where . " AND p.status = '$status'" : "WHERE p.status = '$status'";
    }
    
    $query = "SELECT p.*, u.nama as nama_user, u.no_telp, u.alamat
              FROM pesanan p
              JOIN user u ON p.id_user = u.id
              $where
              ORDER BY p.id DESC";
              
    return query_all($query);
}


function get_order_by_id($id) {
    $id = (int) $id;
    
    $query = "SELECT p.*, u.nama as nama_user, u.no_telp, u.alamat, pm.metode_pembayaran, pm.status as status_pembayaran
              FROM pesanan p
              JOIN user u ON p.id_user = u.id
              LEFT JOIN pembayaran pm ON p.id = pm.id_pesanan
              WHERE p.id = $id";
              
    return query_one($query);
}


function get_order_details($id_pesanan) {
    $id_pesanan = (int) $id_pesanan;
    
    $query = "SELECT dp.*, p.nama as nama_produk, p.gambar 
              FROM detail_pesanan dp
              JOIN produk p ON dp.id_produk = p.id
              WHERE dp.id_pesanan = $id_pesanan";
              
    return query_all($query);
}


function update_order_status($id_pesanan, $status, $resi = null) {
    $id_pesanan = (int) $id_pesanan;
    $status = escape_string($status);
    
    // Cek apakah pesanan ada
    $pesanan = get_order_by_id($id_pesanan);
    if (!$pesanan['status']) {
        return [
            'status' => false,
            'message' => 'Pesanan tidak ditemukan',
            'data' => null
        ];
    }
    
    // Validasi status
    $valid_statuses = ['Pending', 'Sukses', 'Ditolak', 'Proses Pengiriman'];
    if (!in_array($status, $valid_statuses)) {
        return [
            'status' => false,
            'message' => 'Status tidak valid',
            'data' => null
        ];
    }
    
    $resi_sql = "";
    if ($status == 'Proses Pengiriman' && $resi) {
        $resi = escape_string($resi);
        // Add column resi_pengiriman if not exists (Best effort check)
        $check_col = query_all("SHOW COLUMNS FROM pesanan LIKE 'resi_pengiriman'");
        if (empty($check_col['data'])) {
            execute_query("ALTER TABLE pesanan ADD COLUMN resi_pengiriman VARCHAR(100) DEFAULT NULL AFTER status");
        }
        $resi_sql = ", resi_pengiriman = '$resi'";
    }
    
    $query = "UPDATE pesanan SET status = '$status' $resi_sql WHERE id = $id_pesanan";
    $result = execute_query($query);
    
    if ($result['status']) {
        return [
            'status' => true,
            'message' => 'Status pesanan berhasil diupdate',
            'data' => [
                'id_pesanan' => $id_pesanan,
                'status' => $status
            ]
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Update status pesanan gagal',
        'data' => null
    ];
}


function delete_pesanan($id) {
    global $conn;
    $id = (int) $id;
    
    // Cek apakah pesanan ada
    $pesanan = get_order_by_id($id);
    if (!$pesanan['status']) {
        return [
            'status' => false,
            'message' => 'Pesanan tidak ditemukan',
            'data' => null
        ];
    }
    
    // Mulai transaksi
    $conn->begin_transaction();
    
    try {
        // Hapus detail pesanan
        $query = "DELETE FROM detail_pesanan WHERE id_pesanan = $id";
        $result = execute_query($query);
        
        if (!$result['status']) {
            throw new Exception('Gagal menghapus detail pesanan: ' . $result['message']);
        }
        
        // Hapus pembayaran
        $query = "DELETE FROM pembayaran WHERE id_pesanan = $id";
        $result = execute_query($query);
        
        if (!$result['status']) {
            throw new Exception('Gagal menghapus data pembayaran: ' . $result['message']);
        }
        
        // Hapus pesanan
        $query = "DELETE FROM pesanan WHERE id = $id";
        $result = execute_query($query);
        
        if (!$result['status']) {
            throw new Exception('Gagal menghapus pesanan: ' . $result['message']);
        }
        
        // Commit transaksi
        $conn->commit();
        
        return [
            'status' => true,
            'message' => 'Pesanan berhasil dihapus',
            'data' => null
        ];
    } catch (Exception $e) {
        // Rollback transaksi jika ada error
        $conn->rollback();
        
        return [
            'status' => false,
            'message' => $e->getMessage(),
            'data' => null
        ];
    }
}

// ============= FUNGSI PEMBAYARAN ===============


function get_payment_by_order_id($id_pesanan) {
    $id_pesanan = (int) $id_pesanan;
    
    $query = "SELECT * FROM pembayaran WHERE id_pesanan = $id_pesanan";
    return query_one($query);
}


function upload_payment_proof($id_pesanan, $file) {
    $id_pesanan = (int) $id_pesanan;
    
    // Cek apakah pesanan ada
    $pesanan = get_order_by_id($id_pesanan);
    if (!$pesanan['status']) {
        return [
            'status' => false,
            'message' => 'Pesanan tidak ditemukan',
            'data' => null
        ];
    }
    
    // Cek apakah ada data pembayaran
    $pembayaran = get_payment_by_order_id($id_pesanan);
    if (!$pembayaran['status']) {
        return [
            'status' => false,
            'message' => 'Data pembayaran tidak ditemukan',
            'data' => null
        ];
    }
    
    // Upload bukti pembayaran
    if ($file && $file['bukti_pembayaran']['error'] === 0) {
        $upload = upload_file($file['bukti_pembayaran'], 'uploads/pembayaran/');
        if ($upload['status']) {
            $bukti_pembayaran = $upload['file_name'];
            
            $query = "UPDATE pembayaran SET bukti_pembayaran = '$bukti_pembayaran' WHERE id_pesanan = $id_pesanan";
            $result = execute_query($query);
            
            if ($result['status']) {
                return [
                    'status' => true,
                    'message' => 'Bukti pembayaran berhasil diupload',
                    'data' => [
                        'id_pesanan' => $id_pesanan,
                        'bukti_pembayaran' => $bukti_pembayaran
                    ]
                ];
            }
        } else {
            return [
                'status' => false,
                'message' => 'Upload bukti pembayaran gagal: ' . $upload['message'],
                'data' => null
            ];
        }
    }
    
    return [
        'status' => false,
        'message' => 'File bukti pembayaran tidak valid',
        'data' => null
    ];
}


function update_payment_status($id_pesanan, $status, $resi = null) {
    global $conn;
    $id_pesanan = (int) $id_pesanan;
    $status = escape_string($status);
    
    // Cek apakah pesanan ada
    $pesanan = get_order_by_id($id_pesanan);
    if (!$pesanan['status']) {
        return [
            'status' => false,
            'message' => 'Pesanan tidak ditemukan',
            'data' => null
        ];
    }
    
    // Cek apakah ada data pembayaran
    $pembayaran = get_payment_by_order_id($id_pesanan);
    if (!$pembayaran['status']) {
        return [
            'status' => false,
            'message' => 'Data pembayaran tidak ditemukan',
            'data' => null
        ];
    }
    
    // Validasi status
    $valid_statuses = ['valid', 'pending', 'rejected'];
    if (!in_array($status, $valid_statuses)) {
        return [
            'status' => false,
            'message' => 'Status tidak valid',
            'data' => null
        ];
    }
    
    // Mulai transaksi
    $conn->begin_transaction();
    
    try {
        // Update status pembayaran
        $query = "UPDATE pembayaran SET status = '$status' WHERE id_pesanan = $id_pesanan";
        $result = execute_query($query);
        
        if (!$result['status']) {
            throw new Exception('Gagal mengupdate status pembayaran: ' . $result['message']);
        }
        
        // Update status pesanan jika status pembayaran valid
        if ($status == 'valid') {
            $resi_sql = "";
            if ($resi) {
                $resi = escape_string($resi);
                
                // Add column resi_pengiriman if not exists (Best effort check)
                $check_col = query_all("SHOW COLUMNS FROM pesanan LIKE 'resi_pengiriman'");
                if (empty($check_col['data'])) {
                    execute_query("ALTER TABLE pesanan ADD COLUMN resi_pengiriman VARCHAR(100) DEFAULT NULL AFTER status");
                }
                
                $resi_sql = ", resi_pengiriman = '$resi'";
            }
            
            $query = "UPDATE pesanan SET status = 'Proses Pengiriman' $resi_sql WHERE id = $id_pesanan";
            $result = execute_query($query);
            
            if (!$result['status']) {
                throw new Exception('Gagal mengupdate status pesanan: ' . $result['message']);
            }
        }
        
        // Update status pesanan jika status pembayaran rejected
        if ($status == 'rejected') {
            $query = "UPDATE pesanan SET status = 'Ditolak' WHERE id = $id_pesanan";
            $result = execute_query($query);
            
            if (!$result['status']) {
                throw new Exception('Gagal mengupdate status pesanan: ' . $result['message']);
            }
            
            // Kembalikan stok produk
            $details = get_order_details($id_pesanan);
            if ($details['status']) {
                foreach ($details['data'] as $item) {
                    $id_produk = $item['id_produk'];
                    $qty = $item['qty'];
                    
                    $query = "UPDATE produk SET stock = stock + $qty WHERE id = $id_produk";
                    $result = execute_query($query);
                    
                    if (!$result['status']) {
                        throw new Exception('Gagal mengembalikan stok produk: ' . $result['message']);
                    }
                }
            }
        }
        
        // Commit transaksi
        $conn->commit();
        
        return [
            'status' => true,
            'message' => 'Status pembayaran berhasil diupdate',
            'data' => [
                'id_pesanan' => $id_pesanan,
                'status_pembayaran' => $status
            ]
        ];
    } catch (Exception $e) {
        // Rollback transaksi jika ada error
        $conn->rollback();
        
        return [
            'status' => false,
            'message' => $e->getMessage(),
            'data' => null
        ];
    }
}


function get_sales_report($start_date, $end_date) {
    $start_date = escape_string($start_date);
    $end_date = escape_string($end_date);
    
    $query = "SELECT 
                p.id,
                p.kode_invoice,
                u.nama as nama_user,
                p.total_harga,
                p.status,
                pm.tanggal,
                pm.metode_pembayaran,
                pm.status as status_pembayaran
              FROM pesanan p
              JOIN user u ON p.id_user = u.id
              JOIN pembayaran pm ON p.id = pm.id_pesanan
              WHERE (pm.status = 'valid' OR pm.status = 'pending')
              AND pm.tanggal BETWEEN '$start_date' AND '$end_date'
              ORDER BY pm.tanggal DESC";
              
    return query_all($query);
}


function get_total_revenue($start_date, $end_date) {
    $start_date = escape_string($start_date);
    $end_date = escape_string($end_date);
    
    $query = "SELECT 
                COALESCE(SUM(p.total_harga), 0) as total_revenue
              FROM pesanan p
              JOIN pembayaran pm ON p.id = pm.id_pesanan
              WHERE pm.status = 'valid'
              AND p.status = 'Sukses'
              AND DATE(pm.tanggal) BETWEEN '$start_date' AND '$end_date'";
              
    $result = query_one($query);
    
    if ($result['status']) {
        return [
            'status' => true,
            'message' => 'Total pendapatan berhasil dihitung',
            'data' => [
                'total_revenue' => (float)$result['data']['total_revenue']
            ]
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Gagal menghitung total pendapatan',
        'data' => null
    ];
}


function get_best_selling_products($limit = 5) {
    $limit = (int) $limit;
    
    $query = "SELECT 
                p.id,
                p.nama,
                p.gambar,
                p.harga,
                p.stock,
                k.nama as nama_kategori,
                SUM(dp.qty) as total_terjual
              FROM produk p
              JOIN kategori k ON p.id_kategori = k.id
              JOIN detail_pesanan dp ON p.id = dp.id_produk
              JOIN pesanan ps ON dp.id_pesanan = ps.id
              JOIN pembayaran pm ON ps.id = pm.id_pesanan
              WHERE pm.status = 'valid'
              GROUP BY p.id, p.nama, p.gambar, p.harga, p.stock, k.nama
              ORDER BY total_terjual DESC
              LIMIT $limit";
              
    return query_all($query);
}

// ============= FUNGSI BANK ===============


function create_bank($data, $file = null) {
    // Validate required fields
    $required_fields = ['nama_bank', 'no_rekening', 'atas_nama'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            return [
                'status' => false,
                'message' => "Field $field harus diisi",
                'data' => null
            ];
        }
    }

    $nama_bank = escape_string($data['nama_bank']);
    $no_rekening = escape_string($data['no_rekening']);
    $atas_nama = escape_string($data['atas_nama']);
    
    // Upload logo jika ada
    $logo = null;
    if ($file && $file['error'] === 0) {
        $upload_result = upload_file($file, 'uploads/');
        if (!$upload_result['status']) {
            return [
                'status' => false,
                'message' => $upload_result['message'],
                'data' => null
            ];
        }
        $logo = $upload_result['file_name'];
    } else {
        return [
            'status' => false,
            'message' => 'Logo bank harus diupload',
            'data' => null
        ];
    }
    
    $query = "INSERT INTO bank (logo, nama_bank, no_rekening, atas_nama) 
              VALUES ('$logo', '$nama_bank', '$no_rekening', '$atas_nama')";
              
    $result = execute_query($query);
    
    if ($result['status']) {
        return [
            'status' => true,
            'message' => 'Bank berhasil ditambahkan',
            'data' => [
                'id' => $result['insert_id'],
                'logo' => $logo,
                'nama_bank' => $nama_bank,
                'no_rekening' => $no_rekening,
                'atas_nama' => $atas_nama
            ]
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Gagal menambahkan bank: ' . $result['message'],
        'data' => null
    ];
}


function get_all_bank() {
    $query = "SELECT * FROM bank ORDER BY nama_bank ASC";
    return query_all($query);
}


function get_bank_by_id($id) {
    $id = (int) $id;
    $query = "SELECT * FROM bank WHERE id = $id";
    return query_one($query);
}


function update_bank($id, $data, $file = null) {
    // Validate required fields
    $required_fields = ['nama_bank', 'no_rekening', 'atas_nama'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            return [
                'status' => false,
                'message' => "Field $field harus diisi",
                'data' => null
            ];
        }
    }

    $id = escape_string($id);
    $nama_bank = escape_string($data['nama_bank']);
    $no_rekening = escape_string($data['no_rekening']);
    $atas_nama = escape_string($data['atas_nama']);
    
    // Upload logo jika ada
    $logo = null;
    if ($file && $file['error'] === 0) {
        $upload_result = upload_file($file, 'uploads/');
        if (!$upload_result['status']) {
            return [
                'status' => false,
                'message' => $upload_result['message'],
                'data' => null
            ];
        }
        $logo = $upload_result['file_name'];
        
        // Hapus logo lama
        $old_bank = get_bank_by_id($id);
        if ($old_bank['status'] && $old_bank['data']['logo']) {
            $old_logo = 'uploads/' . $old_bank['data']['logo'];
            if (file_exists($old_logo)) {
                unlink($old_logo);
            }
        }
    }
    
    $query = "UPDATE bank SET 
              nama_bank = '$nama_bank',
              no_rekening = '$no_rekening',
              atas_nama = '$atas_nama'";
              
    if ($logo) {
        $query .= ", logo = '$logo'";
    }
    
    $query .= " WHERE id = '$id'";
    
    $result = execute_query($query);
    
    if ($result['status']) {
        return [
            'status' => true,
            'message' => 'Bank berhasil diupdate',
            'data' => [
                'id' => $id,
                'nama_bank' => $nama_bank,
                'no_rekening' => $no_rekening,
                'atas_nama' => $atas_nama,
                'logo' => $logo ? $logo : $old_bank['data']['logo']
            ]
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Gagal mengupdate bank: ' . $result['message'],
        'data' => null
    ];
}


function delete_bank($id) {
    $id = (int) $id;
    
    // Cek apakah bank ada
    $bank = get_bank_by_id($id);
    if (!$bank['status']) {
        return [
            'status' => false,
            'message' => 'Bank tidak ditemukan',
            'data' => null
        ];
    }
    
    $query = "DELETE FROM bank WHERE id = $id";
    $result = execute_query($query);
    
    if ($result['status']) {
        // Hapus logo jika ada
        $logo = $bank['data']['logo'];
        if ($logo && file_exists('uploads/' . $logo)) {
            unlink('uploads/' . $logo);
        }
        
        return [
            'status' => true,
            'message' => 'Bank berhasil dihapus',
            'data' => null
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Hapus bank gagal: ' . $result['message'],
        'data' => null
    ];
}

function get_status_badge_class($status) {
    switch ($status) {
        case 'Pending':
            return 'bg-warning';
        case 'Sukses':
            return 'bg-success';
        case 'Ditolak':
            return 'bg-danger';
        case 'Proses Pengiriman':
            return 'bg-info';
        default:
            return 'bg-secondary';
    }
}

// ============= FUNGSI TENTANG ===============


function get_tentang() {
    $query = "SELECT * FROM tentang WHERE id = 1";
    return query_one($query);
}


function update_tentang($data) {
    $judul = escape_string($data['judul']);
    $deskripsi = escape_string($data['deskripsi']);
    $alamat = escape_string($data['alamat']);
    $telepon = escape_string($data['telepon']);
    $email = escape_string($data['email']);
    $maps_embed = escape_string($data['maps_embed']);
    
    $query = "UPDATE tentang SET 
              judul = '$judul',
              deskripsi = '$deskripsi',
              alamat = '$alamat',
              telepon = '$telepon',
              email = '$email',
              maps_embed = '$maps_embed'
              WHERE id = 1";
              
    $result = execute_query($query);
    
    if ($result['status']) {
        return [
            'status' => true,
            'message' => 'Informasi berhasil diupdate',
            'data' => [
                'judul' => $judul,
                'deskripsi' => $deskripsi,
                'alamat' => $alamat,
                'telepon' => $telepon,
                'email' => $email,
                'maps_embed' => $maps_embed
            ]
        ];
    }
    
    return [
        'status' => false,
        'message' => 'Gagal mengupdate informasi: ' . $result['message'],
        'data' => null
    ];
}

