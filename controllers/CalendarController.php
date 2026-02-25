<?php
/**
 * Calendar Controller
 * Financial Calendar - Bismillah Mosque Management System
 * Follows exact database schema and role-based access control
 */

class CalendarController extends Controller {
    
    private $mosqueId;
    private $userId;
    private $userRole;
    
    // Initialize user context
    private function initContext() {
        $this->mosqueId = $this->getMosqueId();
        $this->userId = $this->getUserId();
        $this->userRole = $_SESSION['user']['role'] ?? 'member';
    }
    
    // Check if user has access to calendar
    private function checkAccess($requireEdit = false) {
        $this->requirePermission('reports.view');
        
        // Role-based access check
        $canEdit = in_array($this->userRole, ['super_admin', 'admin', 'accountant', 'treasurer']);
        
        if ($requireEdit && !$canEdit) {
            jsonError('Anda tidak memiliki akses untuk tindakan ini');
        }
        
        return $canEdit;
    }
    
    // Main calendar view
    public function index() {
        $this->initContext();
        $this->checkAccess();
        
        $month = $this->input('month', date('n'));
        $year = $this->input('year', date('Y'));
        
        // Query A: Transactions for the month (Arus Kas)
        $transactions = $this->db->query("
            SELECT 
                t.id,
                t.type,
                t.amount,
                t.description,
                t.transaction_date,
                t.is_upcoming,
                COALESCE(t.is_recurring, 0) as is_recurring,
                t.recurring_interval,
                t.reference_number,
                c.name AS category_name,
                c.icon AS category_icon,
                c.color AS category_color,
                a.name AS account_name,
                a.type AS account_type,
                u.name AS created_by_name
            FROM transactions t
            LEFT JOIN categories c ON t.category_id = c.id
            LEFT JOIN accounts a ON t.account_id = a.id
            LEFT JOIN users u ON t.created_by = u.id
            WHERE t.mosque_id = ? 
              AND YEAR(t.transaction_date) = ? 
              AND MONTH(t.transaction_date) = ?
            ORDER BY t.transaction_date ASC, t.id ASC
        ", [$this->mosqueId, $year, $month]);
        
        // Query B: Goals with target dates this month (Target)
        $goals = $this->db->query("
            SELECT 
                id,
                name,
                target_amount,
                current_amount,
                target_date,
                is_completed,
                created_at
            FROM goals
            WHERE mosque_id = ? 
              AND (
                  (YEAR(target_date) = ? AND MONTH(target_date) = ?)
                  OR (YEAR(created_at) = ? AND MONTH(created_at) = ?)
              )
        ", [$this->mosqueId, $year, $month, $year, $month]);
        
        // Query C: Goal deposits this month (Setoran Target)
        $goalDeposits = $this->db->query("
            SELECT 
                gd.id,
                gd.goal_id,
                gd.amount,
                gd.notes,
                DATE(gd.created_at) AS deposit_date,
                g.name AS goal_name,
                a.name AS account_name
            FROM goal_deposits gd
            JOIN goals g ON gd.goal_id = g.id
            JOIN accounts a ON gd.account_id = a.id
            WHERE g.mosque_id = ? 
              AND YEAR(gd.created_at) = ? 
              AND MONTH(gd.created_at) = ?
        ", [$this->mosqueId, $year, $month]);
        
        // Query D: Budgets for this month (Anggaran)
        $budgets = $this->db->query("
            SELECT 
                b.id,
                b.category_id,
                b.year,
                b.month,
                b.amount as budget_amount,
                b.description,
                b.created_at,
                c.name as category_name,
                c.type as category_type,
                c.color as category_color,
                COALESCE((
                    SELECT SUM(t.amount) 
                    FROM transactions t 
                    WHERE t.category_id = b.category_id 
                      AND t.mosque_id = b.mosque_id
                      AND YEAR(t.transaction_date) = b.year
                      AND MONTH(t.transaction_date) = b.month
                      AND t.is_upcoming = 0
                ), 0) as spent_amount
            FROM budgets b
            LEFT JOIN categories c ON b.category_id = c.id
            WHERE b.mosque_id = ? 
              AND b.year = ?
              AND b.month = ?
        ", [$this->mosqueId, $year, $month]);
        
        // Calculate monthly summary
        $monthIncome = $this->db->first("
            SELECT COALESCE(SUM(amount), 0) as total
            FROM transactions
            WHERE mosque_id = ? 
              AND type = 'income' 
              AND is_upcoming = 0
              AND YEAR(transaction_date) = ?
              AND MONTH(transaction_date) = ?
        ", [$this->mosqueId, $year, $month]);
        
        $monthExpense = $this->db->first("
            SELECT COALESCE(SUM(amount), 0) as total
            FROM transactions
            WHERE mosque_id = ? 
              AND type = 'expense' 
              AND is_upcoming = 0
              AND YEAR(transaction_date) = ?
              AND MONTH(transaction_date) = ?
        ", [$this->mosqueId, $year, $month]);
        
        $monthIncome = floatval($monthIncome['total']);
        $monthExpense = floatval($monthExpense['total']);
        $monthBalance = $monthIncome - $monthExpense;
        
        // Group transactions by date for dots
        $transactionsByDate = [];
        foreach ($transactions as $t) {
            $date = $t['transaction_date'];
            if (!isset($transactionsByDate[$date])) {
                $transactionsByDate[$date] = [];
            }
            $transactionsByDate[$date][] = $t;
        }
        
        // Group goals by date (both target_date and created_at)
        $goalsByDate = [];
        foreach ($goals as $g) {
            // Add to target date
            if ($g['target_date']) {
                $date = $g['target_date'];
                if (!isset($goalsByDate[$date])) {
                    $goalsByDate[$date] = [];
                }
                $goalsByDate[$date][] = array_merge($g, ['event_type' => 'target']);
            }
            // Add to created date
            $createdDate = date('Y-m-d', strtotime($g['created_at']));
            if (date('Y-m', strtotime($createdDate)) == sprintf('%04d-%02d', $year, $month)) {
                if (!isset($goalsByDate[$createdDate])) {
                    $goalsByDate[$createdDate] = [];
                }
                $goalsByDate[$createdDate][] = array_merge($g, ['event_type' => 'created']);
            }
        }
        
        // Group goal deposits by date
        $depositsByDate = [];
        foreach ($goalDeposits as $gd) {
            $date = $gd['deposit_date'];
            if (!isset($depositsByDate[$date])) {
                $depositsByDate[$date] = [];
            }
            $depositsByDate[$date][] = $gd;
        }
        
        // Group budgets by date (created_at only, since budgets are monthly)
        $budgetsByDate = [];
        foreach ($budgets as $b) {
            // Add to created date
            $createdDate = date('Y-m-d', strtotime($b['created_at']));
            if (!isset($budgetsByDate[$createdDate])) {
                $budgetsByDate[$createdDate] = [];
            }
            $budgetsByDate[$createdDate][] = $b;
        }
        
        // Generate calendar days
        $firstDay = mktime(0, 0, 0, $month, 1, $year);
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dayOfWeek = date('w', $firstDay);
        
        // Navigation
        $prevMonth = $month - 1;
        $prevYear = $year;
        if ($prevMonth < 1) {
            $prevMonth = 12;
            $prevYear--;
        }
        
        $nextMonth = $month + 1;
        $nextYear = $year;
        if ($nextMonth > 12) {
            $nextMonth = 1;
            $nextYear++;
        }
        
        // Check if today
        $today = date('j');
        $currentMonth = date('n');
        $currentYear = date('Y');
        $isCurrentMonth = ($month == $currentMonth && $year == $currentYear);
        $todayDate = $isCurrentMonth ? date('Y-m-d') : '';
        
        // Get form data (categories, accounts, goals)
        $incomeCategories = $this->db->query("
            SELECT * FROM categories 
            WHERE mosque_id = ? AND type = 'income' AND is_active = 1
            ORDER BY name
        ", [$this->mosqueId]);
        
        $expenseCategories = $this->db->query("
            SELECT * FROM categories 
            WHERE mosque_id = ? AND type = 'expense' AND is_active = 1
            ORDER BY name
        ", [$this->mosqueId]);
        
        $accounts = $this->db->query("
            SELECT * FROM accounts 
            WHERE mosque_id = ? AND is_active = 1
            ORDER BY name
        ", [$this->mosqueId]);
        
        $allGoals = $this->db->query("
            SELECT g.*, 
                   COALESCE((SELECT SUM(amount) FROM goal_deposits WHERE goal_id = g.id), 0) as deposited_amount
            FROM goals g
            WHERE g.mosque_id = ? AND g.is_completed = 0
            ORDER BY g.name
        ", [$this->mosqueId]);
        
        // Check user can edit
        $canEdit = in_array($this->userRole, ['super_admin', 'admin', 'accountant', 'treasurer']);
        
        $pageTitle = trans('calendar');
        $this->view('calendar/index', compact(
            'pageTitle', 'month', 'year',
            'transactions', 'transactionsByDate',
            'goals', 'goalsByDate',
            'goalDeposits', 'depositsByDate',
            'budgets', 'budgetsByDate',
            'daysInMonth', 'dayOfWeek',
            'prevMonth', 'prevYear', 'nextMonth', 'nextYear',
            'monthIncome', 'monthExpense', 'monthBalance', 'todayDate',
            'incomeCategories', 'expenseCategories', 'accounts', 'allGoals',
            'canEdit'
        ));
    }
    
    // Get date details (AJAX)
    public function getDateDetails() {
        $this->initContext();
        $this->checkAccess();
        
        $date = $this->input('date');
        
        if (empty($date)) {
            jsonError('Tanggal diperlukan');
        }
        
        // Query detail transactions
        $transactions = $this->db->query("
            SELECT 
                t.id,
                t.type,
                t.amount,
                t.description,
                t.transaction_date,
                t.reference_number,
                t.is_upcoming,
                t.is_recurring,
                t.recurring_interval,
                c.name AS category_name,
                c.icon AS category_icon,
                c.color AS category_color,
                a.name AS account_name,
                a.type AS account_type,
                u.name AS created_by_name,
                u.role AS created_by_role
            FROM transactions t
            JOIN categories c ON t.category_id = c.id
            JOIN accounts a ON t.account_id = a.id
            JOIN users u ON t.created_by = u.id
            WHERE t.mosque_id = ? 
              AND t.transaction_date = ?
            ORDER BY t.id ASC
        ", [$this->mosqueId, $date]);
        
        // Get goals for this date
        $goals = $this->db->query("
            SELECT 
                id,
                name,
                target_amount,
                current_amount,
                target_date,
                is_completed,
                created_at
            FROM goals
            WHERE mosque_id = ? 
              AND (
                  target_date = ?
                  OR DATE(created_at) = ?
              )
        ", [$this->mosqueId, $date, $date]);
        
        // Get goal deposits for this date
        $goalDeposits = $this->db->query("
            SELECT 
                gd.id,
                gd.goal_id,
                gd.amount,
                gd.notes,
                DATE(gd.created_at) AS deposit_date,
                g.name AS goal_name,
                a.name AS account_name
            FROM goal_deposits gd
            JOIN goals g ON gd.goal_id = g.id
            JOIN accounts a ON gd.account_id = a.id
            WHERE g.mosque_id = ? 
              AND DATE(gd.created_at) = ?
        ", [$this->mosqueId, $date]);
        
        // Get budgets for this date (created on this date)
        $budgets = $this->db->query("
            SELECT 
                b.id,
                b.category_id,
                b.year,
                b.month,
                b.amount as budget_amount,
                b.description,
                b.created_at,
                c.name as category_name,
                c.type as category_type,
                c.color as category_color,
                COALESCE((
                    SELECT SUM(t.amount) 
                    FROM transactions t 
                    WHERE t.category_id = b.category_id 
                      AND t.mosque_id = b.mosque_id
                      AND YEAR(t.transaction_date) = b.year
                      AND MONTH(t.transaction_date) = b.month
                      AND t.is_upcoming = 0
                ), 0) as spent_amount
            FROM budgets b
            LEFT JOIN categories c ON b.category_id = c.id
            WHERE b.mosque_id = ? 
              AND DATE(b.created_at) = ?
        ", [$this->mosqueId, $date]);
        
        // Calculate daily totals
        $dayIncome = 0;
        $dayExpense = 0;
        
        foreach ($transactions as $t) {
            if ($t['is_upcoming'] == 0) {
                if ($t['type'] === 'income') {
                    $dayIncome += floatval($t['amount']);
                } else {
                    $dayExpense += floatval($t['amount']);
                }
            }
        }
        
        $dayBalance = $dayIncome - $dayExpense;
        
        // Check if user can edit
        $canEdit = in_array($this->userRole, ['super_admin', 'admin', 'accountant', 'treasurer']);
        
        jsonSuccess('Berhasil', [
            'transactions' => $transactions,
            'goals' => $goals,
            'goal_deposits' => $goalDeposits,
            'budgets' => $budgets,
            'date' => $date,
            'summary' => [
                'pemasukan' => $dayIncome,
                'pengeluaran' => $dayExpense,
                'saldo' => $dayBalance
            ],
            'can_edit' => $canEdit
        ]);
    }
    
    // Get form data (AJAX)
    public function getFormData() {
        $this->initContext();
        
        $incomeCategories = $this->db->query("
            SELECT * FROM categories 
            WHERE mosque_id = ? AND type = 'income' AND is_active = 1
            ORDER BY name
        ", [$this->mosqueId]);
        
        $expenseCategories = $this->db->query("
            SELECT * FROM categories 
            WHERE mosque_id = ? AND type = 'expense' AND is_active = 1
            ORDER BY name
        ", [$this->mosqueId]);
        
        $accounts = $this->db->query("
            SELECT * FROM accounts 
            WHERE mosque_id = ? AND is_active = 1
            ORDER BY name
        ", [$this->mosqueId]);
        
        $goals = $this->db->query("
            SELECT g.*, 
                   COALESCE((SELECT SUM(amount) FROM goal_deposits WHERE goal_id = g.id), 0) as deposited_amount
            FROM goals g
            WHERE g.mosque_id = ? AND g.is_completed = 0
            ORDER BY g.name
        ", [$this->mosqueId]);
        
        jsonSuccess('Berhasil', [
            'income_categories' => $incomeCategories,
            'expense_categories' => $expenseCategories,
            'accounts' => $accounts,
            'goals' => $goals
        ]);
    }
    
    // Add transaction (AJAX)
    public function addTransaction() {
        $this->initContext();
        $canEdit = $this->checkAccess(true);
        
        if (!isPost()) {
            jsonError('Metode tidak valid');
        }
        
        $type = $this->input('type');
        $amount = $this->input('amount');
        $accountId = $this->input('account_id');
        $categoryId = $this->input('category_id');
        $description = $this->input('description');
        $transactionDate = $this->input('transaction_date');
        $referenceNumber = $this->input('reference_number', '');
        $isUpcoming = $this->input('is_upcoming', 0);
        $isRecurring = $this->input('is_recurring', 0);
        $recurringInterval = $this->input('recurring_interval', '');
        
        // Validation
        if (!in_array($type, ['income', 'expense'])) {
            jsonError('Jenis transaksi tidak valid');
        }
        
        if (empty($amount) || floatval($amount) <= 0) {
            jsonError('Nominal harus lebih dari Rp 0');
        }
        
        if (empty($accountId)) {
            jsonError('Akun wajib dipilih');
        }
        
        if (empty($categoryId)) {
            jsonError('Kategori wajib dipilih');
        }
        
        if (empty($description)) {
            jsonError('Deskripsi wajib diisi');
        }
        
        if (empty($transactionDate)) {
            jsonError('Tanggal wajib diisi');
        }
        
        // Check if category matches type
        $category = $this->db->first("SELECT type FROM categories WHERE id = ?", [$categoryId]);
        if ($category && $category['type'] !== $type && $isUpcoming == 0) {
            jsonError('Kategori tidak sesuai jenis transaksi');
        }
        
        // Insert transaction
        $data = [
            'mosque_id' => $this->mosqueId,
            'account_id' => $accountId,
            'category_id' => $categoryId,
            'type' => $type,
            'amount' => floatval($amount),
            'description' => $description,
            'transaction_date' => $transactionDate,
            'reference_number' => $referenceNumber,
            'is_upcoming' => $isUpcoming,
            'is_recurring' => $isRecurring,
            'recurring_interval' => $isRecurring ? $recurringInterval : null,
            'created_by' => $this->userId
        ];
        
        $result = $this->db->insert('transactions', $data);
        $newId = $this->db->lastInsertId();
        
        if (!$result) {
            jsonError('Gagal menyimpan transaksi');
        }
        
        // Log activity
        $this->logActivity('create_transaction', 'transactions', $newId, null, $data);
        
        jsonSuccess('Transaksi berhasil ditambahkan pada ' . formatDate($transactionDate), [
            'transaction_id' => $newId,
            'affected_date' => $transactionDate
        ]);
    }
    
    // Edit transaction (AJAX)
    public function editTransaction($id = null) {
        $this->initContext();
        $canEdit = $this->checkAccess(true);
        
        if (!$id) {
            jsonError('ID transaksi tidak valid');
        }
        
        // Get existing transaction
        $transaction = $this->db->first("
            SELECT * FROM transactions WHERE id = ? AND mosque_id = ?
        ", [$id, $this->mosqueId]);
        
        if (!$transaction) {
            jsonError('Transaksi tidak ditemukan');
        }
        
        if (!isPost()) {
            // Return transaction data for editing
            jsonSuccess('Berhasil', ['transaction' => $transaction]);
        }
        
        // Update transaction
        $type = $this->input('type');
        $amount = $this->input('amount');
        $accountId = $this->input('account_id');
        $categoryId = $this->input('category_id');
        $description = $this->input('description');
        $transactionDate = $this->input('transaction_date');
        $referenceNumber = $this->input('reference_number', '');
        $isUpcoming = $this->input('is_upcoming', 0);
        $isRecurring = $this->input('is_recurring', 0);
        $recurringInterval = $this->input('recurring_interval', '');
        
        // Validation
        if (empty($amount) || floatval($amount) <= 0) {
            jsonError('Nominal harus lebih dari Rp 0');
        }
        
        if (empty($accountId) || empty($categoryId) || empty($description) || empty($transactionDate)) {
            jsonError('Semua field wajib diisi');
        }
        
        $oldDate = $transaction['transaction_date'];
        
        $data = [
            'account_id' => $accountId,
            'category_id' => $categoryId,
            'type' => $type,
            'amount' => floatval($amount),
            'description' => $description,
            'transaction_date' => $transactionDate,
            'reference_number' => $referenceNumber,
            'is_upcoming' => $isUpcoming,
            'is_recurring' => $isRecurring,
            'recurring_interval' => $isRecurring ? $recurringInterval : null,
            'updated_at' => now()
        ];
        
        $result = $this->db->update('transactions', $data, 'id = ? AND mosque_id = ?', [$id, $this->mosqueId]);
        
        if (!$result) {
            jsonError('Gagal mengupdate transaksi');
        }
        
        // Log activity
        $this->logActivity('edit_transaction', 'transactions', $id, $transaction, $data);
        
        $affectedDate = ($oldDate !== $transactionDate) ? $oldDate . ',' . $transactionDate : $transactionDate;
        
        jsonSuccess('Transaksi berhasil diupdate', [
            'transaction_id' => $id,
            'affected_date' => $affectedDate
        ]);
    }
    
    // Delete transaction (AJAX)
    public function deleteTransaction($id = null) {
        $this->initContext();
        $canEdit = $this->checkAccess(true);
        
        if (!$id) {
            jsonError('ID transaksi tidak valid');
        }
        
        // Get existing transaction
        $transaction = $this->db->first("
            SELECT * FROM transactions WHERE id = ? AND mosque_id = ?
        ", [$id, $this->mosqueId]);
        
        if (!$transaction) {
            jsonError('Transaksi tidak ditemukan');
        }
        
        $result = $this->db->delete('transactions', 'id = ? AND mosque_id = ?', [$id, $this->mosqueId]);
        
        if (!$result) {
            jsonError('Gagal menghapus transaksi');
        }
        
        // Log activity
        $this->logActivity('delete_transaction', 'transactions', $id, $transaction, null);
        
        jsonSuccess('Transaksi berhasil dihapus', [
            'transaction_id' => $id,
            'affected_date' => $transaction['transaction_date']
        ]);
    }
    
    // Add goal deposit (AJAX)
    public function addGoalDeposit() {
        $this->initContext();
        $canEdit = $this->checkAccess(true);
        
        if (!isPost()) {
            jsonError('Metode tidak valid');
        }
        
        $goalId = $this->input('goal_id');
        $accountId = $this->input('account_id');
        $amount = $this->input('amount');
        $notes = $this->input('notes', '');
        
        // Validation
        if (empty($goalId)) {
            jsonError('Goal wajib dipilih');
        }
        
        if (empty($accountId)) {
            jsonError('Akun wajib dipilih');
        }
        
        if (empty($amount) || floatval($amount) <= 0) {
            jsonError('Nominal harus lebih dari Rp 0');
        }
        
        // Get goal
        $goal = $this->db->first("SELECT * FROM goals WHERE id = ? AND mosque_id = ?", [$goalId, $this->mosqueId]);
        
        if (!$goal) {
            jsonError('Goal tidak ditemukan');
        }
        
        // Insert deposit
        $data = [
            'goal_id' => $goalId,
            'account_id' => $accountId,
            'amount' => floatval($amount),
            'notes' => $notes,
            'deposited_by' => $this->userId
        ];
        
        $result = $this->db->insert('goal_deposits', $data);
        
        if (!$result) {
            jsonError('Gagal menyimpan setoran');
        }
        
        // Update goal current_amount
        $newAmount = floatval($goal['current_amount']) + floatval($amount);
        $isCompleted = $newAmount >= floatval($goal['target_amount']) ? 1 : 0;
        $completedAt = $isCompleted ? now() : null;
        
        $this->db->update('goals', [
            'current_amount' => $newAmount,
            'is_completed' => $isCompleted,
            'completed_at' => $completedAt
        ], 'id = ?', [$goalId]);
        
        // Log activity
        $this->logActivity('create_goal_deposit', 'goal_deposits', $this->db->lastInsertId(), null, $data);
        
        $message = $isCompleted 
            ? '🎉 Setoran berhasil! Goal "' . $goal['name'] . '" telah tercapai!' 
            : 'Setoran berhasil ditambahkan';
        
        jsonSuccess($message, [
            'goal_id' => $goalId,
            'is_completed' => $isCompleted
        ]);
    }
    
    // Log activity
    private function logActivity($action, $entityType, $entityId, $oldValues = null, $newValues = null) {
        $data = [
            'mosque_id' => $this->mosqueId,
            'user_id' => $this->userId,
            'action' => $action,
            'description' => ucfirst(str_replace('_', ' ', $action)),
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? ''
        ];
        
        $this->db->insert('activity_logs', $data);
    }
}
