<?php
/**
 * User Controller
 * Manage users and roles
 */

class UserController extends Controller {
    
    // List users
    public function index() {
        $this->requirePermission('users.view');
        
        $mosqueId = $this->getMosqueId();
        
        // Hide superadmin from regular admin view
        $users = $this->db->query("
            SELECT * FROM users 
            WHERE mosque_id = ? AND role != 'superadmin'
            ORDER BY name
        ", [$mosqueId]);
        
        // Calculate summary
        $totalUsers = count($users);
        $activeUsers = count(array_filter($users, function($u) { return $u['is_active']; }));
        $adminUsers = count(array_filter($users, function($u) { return in_array($u['role'], ['admin']); }));
        
        $pageTitle = trans('users');
        $this->view('users/index', compact('pageTitle', 'users', 'totalUsers', 'activeUsers', 'adminUsers'));
    }
    
    // Create user
    public function create() {
        $this->requirePermission('users.create');
        
        global $ROLES;
        
        // Remove superadmin from available roles for regular admins
        $availableRoles = $ROLES;
        unset($availableRoles['superadmin']);
        
        $pageTitle = trans('add_user');
        $this->view('users/create', compact('pageTitle', 'availableRoles'));
    }
    
    // Store user
    public function store() {
        if (!isPost()) {
            $this->redirect('users');
        }
        
        $this->requirePermission('users.create');
        
        $mosqueId = $this->getMosqueId();
        
        $name = $this->input('name');
        $email = $this->input('email');
        $password = $this->input('password');
        $role = $this->input('role');
        $phone = $this->input('phone');
        
        // Check email exists
        $exists = $this->db->first("SELECT id FROM users WHERE email = ?", [$email]);
        
        if ($exists) {
            setError('Email already exists');
            $this->redirect('users/create');
        }
        
        $data = [
            'mosque_id' => $mosqueId,
            'name' => $name,
            'email' => $email,
            'password' => hashPassword($password),
            'role' => $role,
            'phone' => $phone,
            'is_active' => 1,
            'created_at' => now()
        ];
        
        $this->db->insert('users', $data);
        
        setSuccess('User created successfully');
        $this->redirect('users');
    }
    
    // Edit user
    public function edit($id = null) {
        $this->requirePermission('users.edit');
        
        if (!$id) {
            $this->redirect('users');
        }
        
        $mosqueId = $this->getMosqueId();
        
        $user = $this->db->first("SELECT * FROM users WHERE id = ? AND mosque_id = ?", [$id, $mosqueId]);
        
        if (!$user) {
            setError('User not found');
            $this->redirect('users');
        }
        
        global $ROLES;
        
        // Remove superadmin from available roles for regular admins
        $availableRoles = $ROLES;
        unset($availableRoles['superadmin']);
        
        $pageTitle = trans('edit_user');
        $this->view('users/edit', compact('pageTitle', 'user', 'availableRoles'));
    }
    
    // Update user
    public function update($id = null) {
        if (!isPost() || !$id) {
            $this->redirect('users');
        }
        
        $this->requirePermission('users.edit');
        
        $mosqueId = $this->getMosqueId();
        
        $user = $this->db->first("SELECT * FROM users WHERE id = ? AND mosque_id = ?", [$id, $mosqueId]);
        
        if (!$user) {
            setError('User not found');
            $this->redirect('users');
        }
        
        $name = $this->input('name');
        $role = $this->input('role');
        $phone = $this->input('phone');
        
        $data = [
            'name' => $name,
            'role' => $role,
            'phone' => $phone,
            'updated_at' => now()
        ];
        
        $this->db->update('users', $data, 'id = ?', [$id]);
        
        setSuccess('User updated successfully');
        $this->redirect('users');
    }
    
    // Delete user
    public function delete($id = null) {
        $this->requirePermission('users.delete');
        
        if (!$id) {
            $this->redirect('users');
        }
        
        // Cannot delete yourself
        if ($id == $this->getUserId()) {
            setError('You cannot delete your own account');
            $this->redirect('users');
        }
        
        $mosqueId = $this->getMosqueId();
        
        $this->db->delete('users', 'id = ? AND mosque_id = ?', [$id, $mosqueId]);
        
        setSuccess('User deleted successfully');
        $this->redirect('users');
    }
    
    // Change password
    public function changePassword($id = null) {
        $this->requirePermission('users.edit');
        
        if (!$id) {
            $this->redirect('users');
        }
        
        $mosqueId = $this->getMosqueId();
        
        $user = $this->db->first("SELECT * FROM users WHERE id = ? AND mosque_id = ?", [$id, $mosqueId]);
        
        if (!$user) {
            setError('User not found');
            $this->redirect('users');
        }
        
        $pageTitle = trans('change_password');
        $this->view('users/change-password', compact('pageTitle', 'user'));
    }
    
    // Update password
    public function updatePassword($id = null) {
        if (!isPost() || !$id) {
            $this->redirect('users');
        }
        
        $this->requirePermission('users.edit');
        
        $password = $this->input('password');
        
        if (empty($password)) {
            setError('Password cannot be empty');
            $this->redirect('users/change-password/' . $id);
        }
        
        $this->db->update('users', [
            'password' => hashPassword($password),
            'updated_at' => now()
        ], 'id = ?', [$id]);
        
        setSuccess('Password changed successfully');
        $this->redirect('users');
    }
}
