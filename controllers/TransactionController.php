<?php
/**
 * Transaction Controller
 * Manage income and expense transactions
 */

class TransactionController extends Controller {
    
    // List transactions
    public function index() {
        $this->requirePermission('transactions.view');
        
        $mosqueId = $this->getMosqueId();
        $page = $this->input('page', 1);
        $type = $this->input('type', '');
        $categoryId = $this->input('category', '');
        $accountId = $this->input('account', '');
        $fromDate = $this->input('from_date', date('Y-m-01'));
        $toDate = $this->input('to_date', date('Y-m-t'));
        
        // Build query
        $sql = "SELECT t.*, a.name as account_name, c.name as category_name, c.type as category_type,
                       u.name as created_by_name
                FROM transactions t
                LEFT JOIN accounts a ON t.account_id = a.id
                LEFT JOIN categories c ON t.category_id = c.id
                LEFT JOIN users u ON t.created_by = u.id
                WHERE t.mosque_id = ?";
        $params = [$mosqueId];
        
        if ($type) {
            $sql .= " AND t.type = ?";
            $params[] = $type;
        }
        
        if ($categoryId) {
            $sql .= " AND t.category_id = ?";
            $params[] = $categoryId;
        }
        
        if ($accountId) {
            $sql .= " AND t.account_id = ?";
            $params[] = $accountId;
        }
        
        if ($fromDate) {
            $sql .= " AND t.transaction_date >= ?";
            $params[] = $fromDate;
        }
        
        if ($toDate) {
            $sql .= " AND t.transaction_date <= ?";
            $params[] = $toDate;
        }
        
        $sql .= " ORDER BY t.transaction_date DESC, t.id DESC";
        
        // Get paginated data
        $result = $this->paginate($sql, $params, $page);
        
        // Get categories and accounts for filter
        $categories = $this->db->query("
            SELECT * FROM categories WHERE mosque_id = ? AND is_active = 1 ORDER BY type, name
        ", [$mosqueId]);
        
        $accounts = $this->db->query("
            SELECT * FROM accounts WHERE mosque_id = ? AND is_active = 1 ORDER BY name
        ", [$mosqueId]);
        
        // Get summary totals for the current filter
        $summarySql = "SELECT 
            COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as total_income,
            COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as total_expense
        FROM transactions 
        WHERE mosque_id = ?";
        $summaryParams = [$mosqueId];
        
        if ($type) {
            $summarySql .= " AND type = ?";
            $summaryParams[] = $type;
        }
        
        if ($categoryId) {
            $summarySql .= " AND category_id = ?";
            $summaryParams[] = $categoryId;
        }
        
        if ($accountId) {
            $summarySql .= " AND account_id = ?";
            $summaryParams[] = $accountId;
        }
        
        if ($fromDate) {
            $summarySql .= " AND transaction_date >= ?";
            $summaryParams[] = $fromDate;
        }
        
        if ($toDate) {
            $summarySql .= " AND transaction_date <= ?";
            $summaryParams[] = $toDate;
        }
        
        $summary = $this->db->first($summarySql, $summaryParams);
        $totalIncome = floatval($summary['total_income']);
        $totalExpense = floatval($summary['total_expense']);
        $balance = $totalIncome - $totalExpense;
        
        $pageTitle = trans('transactions');
        $this->view('transactions/index', compact(
            'pageTitle',
            'result',
            'categories',
            'accounts',
            'type',
            'categoryId',
            'accountId',
            'fromDate',
            'toDate',
            'totalIncome',
            'totalExpense',
            'balance'
        ));
    }
    
    // Create transaction form
    public function create() {
        $this->requirePermission('transactions.create');
        
        $mosqueId = $this->getMosqueId();
        $type = $this->input('type', 'income');
        
        // Get categories based on type
        $categories = $this->db->query("
            SELECT * FROM categories WHERE mosque_id = ? AND type = ? AND is_active = 1 ORDER BY name
        ", [$mosqueId, $type]);
        
        // Get accounts
        $accounts = $this->db->query("
            SELECT * FROM accounts WHERE mosque_id = ? AND is_active = 1 ORDER BY name
        ", [$mosqueId]);
        
        $pageTitle = $type === 'income' ? trans('add_income') : trans('add_expense');
        $this->view('transactions/create', compact('pageTitle', 'categories', 'accounts', 'type'));
    }
    
    // Store transaction
    public function store() {
        if (!isPost()) {
            $this->redirect('transactions');
        }
        
        $this->requirePermission('transactions.create');
        
        $mosqueId = $this->getMosqueId();
        $userId = $this->getUserId();
        
        $type = $this->input('type');
        $accountId = $this->input('account_id');
        $categoryId = $this->input('category_id');
        $amount = $this->input('amount');
        $description = $this->input('description');
        $transactionDate = $this->input('transaction_date');
        $referenceNumber = $this->input('reference_number');
        $isUpcoming = $this->input('is_upcoming', 0);
        
        // Validate
        if (empty($accountId) || empty($categoryId) || empty($amount) || empty($transactionDate)) {
            setError('Mohon isi semua field yang wajib');
            $this->redirect('transactions/create?type=' . $type);
        }
        
        // Validate photo attachment (REQUIRED)
        if (!isset($_FILES['attachment']) || $_FILES['attachment']['error'] === UPLOAD_ERR_NO_FILE) {
            setError('Foto bukti transaksi wajib diupload');
            $this->redirect('transactions/create?type=' . $type);
        }
        
        // Clean amount
        $amount = str_replace(['.', ','], '', $amount);
        
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Insert transaction
            $data = [
                'mosque_id' => $mosqueId,
                'account_id' => $accountId,
                'category_id' => $categoryId,
                'type' => $type,
                'amount' => $amount,
                'description' => $description,
                'transaction_date' => $transactionDate,
                'reference_number' => $referenceNumber,
                'is_upcoming' => $isUpcoming,
                'created_by' => $userId,
                'created_at' => now()
            ];
            
            $transactionId = $this->db->insert('transactions', $data);
            
            if (!$transactionId) {
                throw new Exception('Gagal menyimpan transaksi');
            }
            
            // Upload attachment
            $uploadDir = UPLOAD_PATH . '/transactions';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $result = $this->upload($_FILES['attachment'], $uploadDir, ['jpg', 'jpeg', 'png', 'pdf']);
            
            if (!$result['success']) {
                throw new Exception($result['message']);
            }
            
            // Save attachment info
            $attachmentData = [
                'transaction_id' => $transactionId,
                'file_name' => $_FILES['attachment']['name'],
                'file_path' => 'uploads/transactions/' . $result['filename'],
                'file_type' => $_FILES['attachment']['type'],
                'file_size' => $_FILES['attachment']['size'],
                'uploaded_by' => $userId,
                'created_at' => now()
            ];
            
            $this->db->insert('transaction_attachments', $attachmentData);
            
            // Commit transaction
            $this->db->commit();
            
            setSuccess(($type === 'income' ? 'Pemasukan' : 'Pengeluaran') . ' berhasil ditambahkan');
            $this->redirect('transactions');
            
        } catch (Exception $e) {
            $this->db->rollback();
            setError('Gagal menyimpan transaksi: ' . $e->getMessage());
            $this->redirect('transactions/create?type=' . $type);
        }
    }
    
    // Edit transaction
    public function edit($id = null) {
        $this->requirePermission('transactions.edit');
        
        if (!$id) {
            $this->redirect('transactions');
        }
        
        $mosqueId = $this->getMosqueId();
        
        // Get transaction
        $transaction = $this->db->first("
            SELECT * FROM transactions WHERE id = ? AND mosque_id = ?
        ", [$id, $mosqueId]);
        
        if (!$transaction) {
            setError('Transaction not found');
            $this->redirect('transactions');
        }
        
        // Get attachments
        $attachments = $this->db->query("
            SELECT * FROM transaction_attachments WHERE transaction_id = ?
        ", [$id]);
        
        // Get categories and accounts
        $categories = $this->db->query("
            SELECT * FROM categories WHERE mosque_id = ? AND is_active = 1 ORDER BY type, name
        ", [$mosqueId]);
        
        $accounts = $this->db->query("
            SELECT * FROM accounts WHERE mosque_id = ? AND is_active = 1 ORDER BY name
        ", [$mosqueId]);
        
        $pageTitle = trans('edit') . ' ' . trans('transaction');
        $this->view('transactions/edit', compact('pageTitle', 'transaction', 'categories', 'accounts', 'attachments'));
    }
    
    // Update transaction
    public function update($id = null) {
        if (!isPost() || !$id) {
            $this->redirect('transactions');
        }
        
        $this->requirePermission('transactions.edit');
        
        $mosqueId = $this->getMosqueId();
        $userId = $this->getUserId();
        
        // Check transaction exists
        $transaction = $this->db->first("SELECT * FROM transactions WHERE id = ? AND mosque_id = ?", [$id, $mosqueId]);
        
        if (!$transaction) {
            setError('Transaction not found');
            $this->redirect('transactions');
        }
        
        $accountId = $this->input('account_id');
        $categoryId = $this->input('category_id');
        $amount = $this->input('amount');
        $description = $this->input('description');
        $transactionDate = $this->input('transaction_date');
        $referenceNumber = $this->input('reference_number');
        
        // Clean amount
        $amount = str_replace(['.', ','], '', $amount);
        
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            $data = [
                'account_id' => $accountId,
                'category_id' => $categoryId,
                'amount' => $amount,
                'description' => $description,
                'transaction_date' => $transactionDate,
                'reference_number' => $referenceNumber,
                'updated_at' => now()
            ];
            
            $this->db->update('transactions', $data, 'id = ?', [$id]);
            
            // Handle new attachment if uploaded
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] !== UPLOAD_ERR_NO_FILE) {
                // Upload new attachment
                $uploadDir = UPLOAD_PATH . '/transactions';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $result = $this->upload($_FILES['attachment'], $uploadDir, ['jpg', 'jpeg', 'png', 'pdf']);
                
                if (!$result['success']) {
                    throw new Exception($result['message']);
                }
                
                // Delete old attachments
                $oldAttachments = $this->db->query("SELECT * FROM transaction_attachments WHERE transaction_id = ?", [$id]);
                foreach ($oldAttachments as $att) {
                    $oldFile = PUBLIC_PATH . '/' . $att['file_path'];
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
                $this->db->delete('transaction_attachments', 'transaction_id = ?', [$id]);
                
                // Save new attachment info
                $attachmentData = [
                    'transaction_id' => $id,
                    'file_name' => $_FILES['attachment']['name'],
                    'file_path' => 'uploads/transactions/' . $result['filename'],
                    'file_type' => $_FILES['attachment']['type'],
                    'file_size' => $_FILES['attachment']['size'],
                    'uploaded_by' => $userId,
                    'created_at' => now()
                ];
                
                $this->db->insert('transaction_attachments', $attachmentData);
            }
            
            // Commit transaction
            $this->db->commit();
            
            setSuccess('Transaction updated successfully');
            $this->redirect('transactions');
            
        } catch (Exception $e) {
            $this->db->rollback();
            setError('Gagal update transaksi: ' . $e->getMessage());
            $this->redirect('transactions/edit/' . $id);
        }
    }
    
    // Delete transaction
    public function delete($id = null) {
        $this->requirePermission('transactions.delete');
        
        if (!$id) {
            $this->redirect('transactions');
        }
        
        $mosqueId = $this->getMosqueId();
        
        // Get attachments to delete files
        $attachments = $this->db->query("SELECT * FROM transaction_attachments WHERE transaction_id = ?", [$id]);
        
        // Delete attachment files
        foreach ($attachments as $att) {
            $filePath = PUBLIC_PATH . '/' . $att['file_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // Delete attachments from database
        $this->db->delete('transaction_attachments', 'transaction_id = ?', [$id]);
        
        // Delete transaction
        $this->db->delete('transactions', 'id = ? AND mosque_id = ?', [$id, $mosqueId]);
        
        setSuccess('Transaction deleted successfully');
        $this->redirect('transactions');
    }
    
    // View transaction detail
    public function detail($id = null) {
        $this->requirePermission('transactions.view');
        
        if (!$id) {
            $this->redirect('transactions');
        }
        
        $mosqueId = $this->getMosqueId();
        
        // Get transaction with related data
        $transaction = $this->db->first("
            SELECT t.*, 
                   a.name as account_name, a.type as account_type,
                   c.name as category_name, c.type as category_type, c.icon as category_icon, c.color as category_color,
                   u.name as created_by_name, u.email as created_by_email
            FROM transactions t
            LEFT JOIN accounts a ON t.account_id = a.id
            LEFT JOIN categories c ON t.category_id = c.id
            LEFT JOIN users u ON t.created_by = u.id
            WHERE t.id = ? AND t.mosque_id = ?
        ", [$id, $mosqueId]);
        
        if (!$transaction) {
            setError('Transaction not found');
            $this->redirect('transactions');
        }
        
        // Get attachments
        $attachments = $this->db->query("
            SELECT ta.*, u.name as uploaded_by_name
            FROM transaction_attachments ta
            LEFT JOIN users u ON ta.uploaded_by = u.id
            WHERE ta.transaction_id = ?
        ", [$id]);
        
        $pageTitle = 'Detail Transaksi';
        $this->view('transactions/view', compact('pageTitle', 'transaction', 'attachments'));
    }
}
