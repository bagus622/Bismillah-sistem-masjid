<?php
/**
 * Mosque Controller
 * Manage mosques (superadmin only)
 */

class MosqueController extends Controller {
    
    // List all mosques
    public function index() {
        // Only superadmin can access
        if (!isSuperAdmin()) {
            redirect('dashboard');
            return;
        }
        
        $mosques = $this->db->query("
            SELECT m.*, 
                   (SELECT COUNT(*) FROM users WHERE mosque_id = m.id) as user_count,
                   (SELECT COUNT(*) FROM transactions WHERE mosque_id = m.id) as transaction_count
            FROM mosques m
            ORDER BY m.created_at DESC
        ");
        
        $pageTitle = 'Kelola Masjid';
        $this->view('mosques/index', compact('pageTitle', 'mosques'));
    }
    
    // Show create form
    public function create() {
        if (!isSuperAdmin()) {
            redirect('dashboard');
            return;
        }
        
        $pageTitle = 'Tambah Masjid Baru';
        $this->view('mosques/create', compact('pageTitle'));
    }
    
    // Store new mosque
    public function store() {
        if (!isSuperAdmin()) {
            redirect('dashboard');
            return;
        }
        
        $name = $this->input('name');
        $address = $this->input('address');
        $phone = $this->input('phone');
        $email = $this->input('email');
        
        // Validation
        if (empty($name)) {
            setError('Nama masjid harus diisi');
            redirect('mosques/create');
            return;
        }
        
        // Insert mosque
        $this->db->query("
            INSERT INTO mosques (name, address, phone, email, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ", [$name, $address, $phone, $email]);
        
        setSuccess('Masjid berhasil ditambahkan');
        redirect('mosques');
    }
    
    // Show edit form
    public function edit($id) {
        if (!isSuperAdmin()) {
            redirect('dashboard');
            return;
        }
        
        $mosque = $this->db->first("SELECT * FROM mosques WHERE id = ?", [$id]);
        
        if (!$mosque) {
            setError('Masjid tidak ditemukan');
            redirect('mosques');
            return;
        }
        
        $pageTitle = 'Edit Masjid';
        $this->view('mosques/edit', compact('pageTitle', 'mosque'));
    }
    
    // Update mosque
    public function update($id) {
        if (!isSuperAdmin()) {
            redirect('dashboard');
            return;
        }
        
        $name = $this->input('name');
        $address = $this->input('address');
        $phone = $this->input('phone');
        $email = $this->input('email');
        
        // Validation
        if (empty($name)) {
            setError('Nama masjid harus diisi');
            redirect('mosques/edit/' . $id);
            return;
        }
        
        // Update mosque
        $this->db->query("
            UPDATE mosques 
            SET name = ?, address = ?, phone = ?, email = ?, updated_at = NOW()
            WHERE id = ?
        ", [$name, $address, $phone, $email, $id]);
        
        setSuccess('Masjid berhasil diupdate');
        redirect('mosques');
    }
    
    // Delete mosque
    public function delete($id) {
        if (!isSuperAdmin()) {
            redirect('dashboard');
            return;
        }
        
        // Validate ID - ensure it's a valid integer
        if (!is_numeric($id) || intval($id) <= 0) {
            setError('ID Masjid tidak valid');
            redirect('mosques');
            return;
        }
        
        $id = intval($id);
        
        // Check if mosque exists
        $mosque = $this->db->first("SELECT * FROM mosques WHERE id = ?", [$id]);
        if (!$mosque) {
            setError('Masjid tidak ditemukan');
            redirect('mosques');
            return;
        }
        
        // Check if mosque has users (exclude superadmin)
        $userCount = $this->db->first("SELECT COUNT(*) as count FROM users WHERE mosque_id = ? AND role != 'superadmin'", [$id]);
        
        if ($userCount && $userCount['count'] > 0) {
            setError('Tidak dapat menghapus masjid yang masih memiliki pengguna');
            redirect('mosques');
            return;
        }
        
        // Delete mosque
        $this->db->query("DELETE FROM mosques WHERE id = ?", [$id]);
        
        setSuccess('Masjid berhasil dihapus');
        redirect('mosques');
    }
    
    // View mosque details
    public function detail($id) {
        if (!isSuperAdmin()) {
            redirect('dashboard');
            return;
        }
        
        $mosque = $this->db->first("SELECT * FROM mosques WHERE id = ?", [$id]);
        
        if (!$mosque) {
            setError('Masjid tidak ditemukan');
            redirect('mosques');
            return;
        }
        
        // Get users
        $users = $this->db->query("
            SELECT * FROM users 
            WHERE mosque_id = ? 
            ORDER BY created_at DESC
        ", [$id]);
        
        // Get statistics
        $stats = $this->db->first("
            SELECT 
                (SELECT COUNT(*) FROM users WHERE mosque_id = ?) as user_count,
                (SELECT COUNT(*) FROM transactions WHERE mosque_id = ?) as transaction_count,
                (SELECT COUNT(*) FROM accounts WHERE mosque_id = ?) as account_count,
                (SELECT COALESCE(SUM(amount), 0) FROM transactions WHERE mosque_id = ? AND type = 'income') as total_income,
                (SELECT COALESCE(SUM(amount), 0) FROM transactions WHERE mosque_id = ? AND type = 'expense') as total_expense
        ", [$id, $id, $id, $id, $id]);
        
        $pageTitle = 'Detail Masjid';
        $this->view('mosques/view', compact('pageTitle', 'mosque', 'users', 'stats'));
    }
}
