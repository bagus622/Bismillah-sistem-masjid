<?php
/**
 * Dashboard Controller
 * Main dashboard view with statistics
 */

class DashboardController extends Controller {
    
    // Main dashboard
    public function index() {
        // Check if user is super admin and hasn't selected a mosque
        if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'superadmin') {
            $selectedMosqueId = $_SESSION['selected_mosque_id'] ?? null;
            
            if (!$selectedMosqueId) {
                // Show mosque selector for super admin
                $this->showMosqueSelector();
                return;
            }
        }
        
        $mosqueId = $this->getMosqueId();
        
        // Get total balance
        $totalBalance = $this->db->first("
            SELECT 
                COALESCE((SELECT SUM(initial_balance) FROM accounts WHERE mosque_id = ?), 0) + 
                COALESCE((SELECT SUM(t.amount) FROM transactions t JOIN accounts a ON t.account_id = a.id WHERE a.mosque_id = ? AND t.type = 'income'), 0) - 
                COALESCE((SELECT SUM(t.amount) FROM transactions t JOIN accounts a ON t.account_id = a.id WHERE a.mosque_id = ? AND t.type = 'expense'), 0) as balance
        ", [$mosqueId, $mosqueId, $mosqueId]);
        
        $balance = $totalBalance ? floatval($totalBalance['balance']) : 0;
        
        // Get total income this month
        $startOfMonth = date('Y-m-01');
        $endOfMonth = date('Y-m-t');
        
        $monthlyIncome = $this->db->first("
            SELECT COALESCE(SUM(amount), 0) as total 
            FROM transactions 
            WHERE mosque_id = ? AND type = 'income' 
            AND transaction_date BETWEEN ? AND ?
        ", [$mosqueId, $startOfMonth, $endOfMonth]);
        
        $totalIncome = $monthlyIncome ? floatval($monthlyIncome['total']) : 0;
        
        // Get all-time total income
        $allTimeIncome = $this->db->first("
            SELECT COALESCE(SUM(amount), 0) as total 
            FROM transactions 
            WHERE mosque_id = ? AND type = 'income'
        ", [$mosqueId]);
        
        $allTimeTotalIncome = $allTimeIncome ? floatval($allTimeIncome['total']) : 0;
        
        // Get total expense this month
        $monthlyExpense = $this->db->first("
            SELECT COALESCE(SUM(amount), 0) as total 
            FROM transactions 
            WHERE mosque_id = ? AND type = 'expense' 
            AND transaction_date BETWEEN ? AND ?
        ", [$mosqueId, $startOfMonth, $endOfMonth]);
        
        $totalExpense = $monthlyExpense ? floatval($monthlyExpense['total']) : 0;
        
        // Get all-time total expense
        $allTimeExpense = $this->db->first("
            SELECT COALESCE(SUM(amount), 0) as total 
            FROM transactions 
            WHERE mosque_id = ? AND type = 'expense'
        ", [$mosqueId]);
        
        $allTimeTotalExpense = $allTimeExpense ? floatval($allTimeExpense['total']) : 0;
        
        // Get active goals count and total target
        $goalsCount = $this->db->count('goals', 'mosque_id = ? AND is_completed = 0', [$mosqueId]);
        
        $goalsTotal = $this->db->first("
            SELECT COALESCE(SUM(target_amount), 0) as total_target, COALESCE(SUM(current_amount), 0) as total_current
            FROM goals WHERE mosque_id = ?
        ", [$mosqueId]);
        
        $totalGoalsTarget = $goalsTotal ? floatval($goalsTotal['total_target']) : 0;
        $totalGoalsCurrent = $goalsTotal ? floatval($goalsTotal['total_current']) : 0;
        
        // Recent transactions
        $recentTransactions = $this->db->query("
            SELECT t.*, a.name as account_name, c.name as category_name, c.type as category_type,
                   u.name as created_by_name
            FROM transactions t
            LEFT JOIN accounts a ON t.account_id = a.id
            LEFT JOIN categories c ON t.category_id = c.id
            LEFT JOIN users u ON t.created_by = u.id
            WHERE t.mosque_id = ?
            ORDER BY t.transaction_date DESC, t.id DESC
            LIMIT 10
        ", [$mosqueId]);
        
        // Get upcoming transactions
        $upcomingTransactions = $this->db->query("
            SELECT t.*, a.name as account_name, c.name as category_name
            FROM transactions t
            LEFT JOIN accounts a ON t.account_id = a.id
            LEFT JOIN categories c ON t.category_id = c.id
            WHERE t.mosque_id = ? AND t.is_upcoming = 1
            ORDER BY t.transaction_date ASC
            LIMIT 5
        ", [$mosqueId]);
        
        // Get accounts
        $accounts = $this->db->query("
            SELECT a.*, 
                   COALESCE(a.initial_balance, 0) + COALESCE(
                       (SELECT SUM(t.amount) FROM transactions t WHERE t.account_id = a.id AND t.type = 'income'), 0
                   ) - COALESCE(
                       (SELECT SUM(t.amount) FROM transactions t WHERE t.account_id = a.id AND t.type = 'expense'), 0
                   ) as balance
            FROM accounts a
            WHERE a.mosque_id = ? AND a.is_active = 1
            ORDER BY a.name
        ", [$mosqueId]);
        
        // Chart data - last 6 months
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $monthName = getMonthName(date('n', strtotime($month)));
            
            $income = $this->db->first("
                SELECT COALESCE(SUM(amount), 0) as total 
                FROM transactions 
                WHERE mosque_id = ? AND type = 'income' 
                AND DATE_FORMAT(transaction_date, '%Y-%m') = ?
            ", [$mosqueId, $month]);
            
            $expense = $this->db->first("
                SELECT COALESCE(SUM(amount), 0) as total 
                FROM transactions 
                WHERE mosque_id = ? AND type = 'expense' 
                AND DATE_FORMAT(transaction_date, '%Y-%m') = ?
            ", [$mosqueId, $month]);
            
            $chartData['labels'][] = $monthName;
            $chartData['income'][] = floatval($income['total']);
            $chartData['expense'][] = floatval($expense['total']);
        }
        
        $pageTitle = trans('dashboard');
        
        // Prepare stats array for view
        $stats = [
            'total_income' => $allTimeTotalIncome,
            'total_expense' => $allTimeTotalExpense,
            'balance' => $balance,
            'goals' => $totalGoalsCurrent
        ];
        
        $this->view('dashboard/index', compact(
            'pageTitle',
            'stats',
            'balance',
            'totalIncome',
            'totalExpense',
            'allTimeTotalIncome',
            'allTimeTotalExpense',
            'totalGoalsTarget',
            'totalGoalsCurrent',
            'goalsCount',
            'recentTransactions',
            'upcomingTransactions',
            'accounts',
            'chartData'
        ));
    }
    
    // Show mosque selector for super admin
    private function showMosqueSelector() {
        // Get all mosques
        $mosques = $this->db->query("
            SELECT id, name, address, phone, email, is_active
            FROM mosques
            WHERE is_active = 1
            ORDER BY name ASC
        ");
        
        $pageTitle = 'Pilih Masjid';
        
        $this->view('dashboard/mosque_selector', compact('pageTitle', 'mosques'));
    }
    
    // Switch mosque (for super admin)
    public function switchMosque() {
        // Only super admin can switch mosques
        if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'superadmin') {
            $this->setError('Anda tidak memiliki izin untuk mengakses fitur ini.');
            $this->redirect('dashboard');
        }
        
        if (!isPost()) {
            $this->redirect('dashboard');
        }
        
        $mosqueId = $this->input('mosque_id');
        
        if (empty($mosqueId)) {
            $this->setError('Silakan pilih masjid terlebih dahulu.');
            $this->redirect('dashboard');
        }
        
        // Verify mosque exists
        $mosque = $this->db->first("SELECT * FROM mosques WHERE id = ? AND is_active = 1", [$mosqueId]);
        
        if (!$mosque) {
            $this->setError('Masjid tidak ditemukan.');
            $this->redirect('dashboard');
        }
        
        // Set selected mosque in session
        $_SESSION['selected_mosque_id'] = $mosqueId;
        $_SESSION['mosque'] = $mosque;
        
        $this->setSuccess('Berhasil beralih ke ' . $mosque['name']);
        $this->redirect('dashboard');
    }
    
    // Clear selected mosque (for super admin)
    public function clearMosque() {
        // Only super admin can clear mosque selection
        if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'superadmin') {
            $this->setError('Anda tidak memiliki izin untuk mengakses fitur ini.');
            $this->redirect('dashboard');
        }
        
        // Clear selected mosque
        unset($_SESSION['selected_mosque_id']);
        unset($_SESSION['mosque']);
        
        $this->setSuccess('Berhasil keluar dari masjid.');
        $this->redirect('dashboard');
    }
}
