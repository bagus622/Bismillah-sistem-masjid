<?php
/**
 * Account Controller
 * Manage cash/bank accounts
 */

class AccountController extends Controller {
    
    // List accounts
    public function index() {
        $this->requirePermission('accounts.view');
        
        $mosqueId = $this->getMosqueId();
        
        // Get accounts with balance
        $accounts = $this->db->query("
            SELECT a.*, 
                   COALESCE(a.initial_balance, 0) + COALESCE(
                       (SELECT SUM(t.amount) FROM transactions t WHERE t.account_id = a.id AND t.type = 'income'), 0
                   ) - COALESCE(
                       (SELECT SUM(t.amount) FROM transactions t WHERE t.account_id = a.id AND t.type = 'expense'), 0
                   ) as balance
            FROM accounts a
            WHERE a.mosque_id = ?
            ORDER BY a.name
        ", [$mosqueId]);
        
        // Calculate total balance
        $totalBalance = array_sum(array_column($accounts, 'balance'));
        
        $pageTitle = trans('accounts');
        $this->view('accounts/index', compact('pageTitle', 'accounts', 'totalBalance'));
    }
    
    // Create account form
    public function create() {
        $this->requirePermission('accounts.create');
        
        $pageTitle = trans('add_account');
        $this->view('accounts/create', compact('pageTitle'));
    }
    
    // Store account
    public function store() {
        if (!isPost()) {
            $this->redirect('accounts');
        }
        
        $this->requirePermission('accounts.create');
        
        $mosqueId = $this->getMosqueId();
        
        $name = $this->input('name');
        $type = $this->input('type');
        $accountNumber = $this->input('account_number');
        $initialBalance = $this->input('initial_balance', 0);
        $description = $this->input('description');
        
        // Clean amount
        $initialBalance = str_replace(['.', ','], '', $initialBalance);
        
        $data = [
            'mosque_id' => $mosqueId,
            'name' => $name,
            'type' => $type,
            'account_number' => $accountNumber,
            'initial_balance' => $initialBalance,
            'description' => $description,
            'is_active' => 1,
            'created_at' => now()
        ];
        
        $this->db->insert('accounts', $data);
        
        setSuccess('Account created successfully');
        $this->redirect('accounts');
    }
    
    // Edit account
    public function edit($id = null) {
        $this->requirePermission('accounts.edit');
        
        if (!$id) {
            $this->redirect('accounts');
        }
        
        $mosqueId = $this->getMosqueId();
        
        $account = $this->db->first("SELECT * FROM accounts WHERE id = ? AND mosque_id = ?", [$id, $mosqueId]);
        
        if (!$account) {
            setError('Account not found');
            $this->redirect('accounts');
        }
        
        $pageTitle = trans('edit') . ' ' . trans('account');
        $this->view('accounts/edit', compact('pageTitle', 'account'));
    }
    
    // Update account
    public function update($id = null) {
        if (!isPost() || !$id) {
            $this->redirect('accounts');
        }
        
        $this->requirePermission('accounts.edit');
        
        $mosqueId = $this->getMosqueId();
        
        $account = $this->db->first("SELECT * FROM accounts WHERE id = ? AND mosque_id = ?", [$id, $mosqueId]);
        
        if (!$account) {
            setError('Account not found');
            $this->redirect('accounts');
        }
        
        $name = $this->input('name');
        $type = $this->input('type');
        $accountNumber = $this->input('account_number');
        $description = $this->input('description');
        
        $data = [
            'name' => $name,
            'type' => $type,
            'account_number' => $accountNumber,
            'description' => $description,
            'updated_at' => now()
        ];
        
        $this->db->update('accounts', $data, 'id = ?', [$id]);
        
        setSuccess('Account updated successfully');
        $this->redirect('accounts');
    }
    
    // Delete account
    public function delete($id = null) {
        $this->requirePermission('accounts.delete');
        
        if (!$id) {
            $this->redirect('accounts');
        }
        
        $mosqueId = $this->getMosqueId();
        
        // Check if account has transactions
        $count = $this->db->count('transactions', 'account_id = ?', [$id]);
        
        if ($count > 0) {
            setError('Cannot delete account with existing transactions');
            $this->redirect('accounts');
        }
        
        $this->db->delete('accounts', 'id = ? AND mosque_id = ?', [$id, $mosqueId]);
        
        setSuccess('Account deleted successfully');
        $this->redirect('accounts');
    }
}
