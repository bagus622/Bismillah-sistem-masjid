<?php
/**
 * Goal Controller
 * Manage fundraising goals/targets
 */

class GoalController extends Controller {
    
    // List goals
    public function index() {
        $this->requirePermission('goals.view');
        
        $mosqueId = $this->getMosqueId();
        
        // Get goals with progress
        $goals = $this->db->query("
            SELECT g.*,
                   (g.current_amount / g.target_amount * 100) as progress_percent
            FROM goals g
            WHERE g.mosque_id = ?
            ORDER BY g.is_completed, g.target_date
        ", [$mosqueId]);
        
        // Calculate totals
        $totalTarget = array_sum(array_column($goals, 'target_amount'));
        $totalCurrent = array_sum(array_column($goals, 'current_amount'));
        $totalPercent = $totalTarget > 0 ? ($totalCurrent / $totalTarget) * 100 : 0;
        
        // Process goals to add additional data
        foreach ($goals as &$goal) {
            $goal['progress_percent'] = floatval($goal['progress_percent']);
            $goal['is_overdue'] = !$goal['is_completed'] && $goal['target_date'] && strtotime($goal['target_date']) < time();
        }
        
        $pageTitle = trans('goals');
        $this->view('goals/index', compact('pageTitle', 'goals', 'totalTarget', 'totalCurrent', 'totalPercent'));
    }
    
    // Create goal
    public function create() {
        $this->requirePermission('goals.create');
        
        $pageTitle = trans('add_goal');
        $this->view('goals/create', compact('pageTitle'));
    }
    
    // Store goal
    public function store() {
        if (!isPost()) {
            $this->redirect('goals');
        }
        
        $this->requirePermission('goals.create');
        
        $mosqueId = $this->getMosqueId();
        $userId = $this->getUserId();
        
        $name = $this->input('name');
        $targetAmount = $this->input('target_amount');
        $targetDate = $this->input('target_date');
        $description = $this->input('description');
        
        // Clean amount
        $targetAmount = str_replace(['.', ','], '', $targetAmount);
        
        $data = [
            'mosque_id' => $mosqueId,
            'name' => $name,
            'target_amount' => $targetAmount,
            'current_amount' => 0,
            'target_date' => $targetDate,
            'description' => $description,
            'is_completed' => 0,
            'created_by' => $userId,
            'created_at' => now()
        ];
        
        $this->db->insert('goals', $data);
        
        setSuccess('Goal created successfully');
        $this->redirect('goals');
    }
    
    // Deposit to goal
    public function deposit($id = null) {
        $this->requirePermission('goals.deposit');
        
        if (!$id) {
            $this->redirect('goals');
        }
        
        $mosqueId = $this->getMosqueId();
        
        $goal = $this->db->first("SELECT * FROM goals WHERE id = ? AND mosque_id = ?", [$id, $mosqueId]);
        
        if (!$goal) {
            setError('Goal not found');
            $this->redirect('goals');
        }
        
        // Calculate is_overdue
        $goal['is_overdue'] = !$goal['is_completed'] && $goal['target_date'] && strtotime($goal['target_date']) < time();
        
        // Get accounts with balance (initial_balance + income - expense)
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
        
        $pageTitle = trans('add_deposit');
        $this->view('goals/deposit', compact('pageTitle', 'goal', 'accounts'));
    }
    
    // Store deposit
    public function storeDeposit() {
        if (!isPost()) {
            $this->redirect('goals');
        }
        
        $this->requirePermission('goals.deposit');
        
        $mosqueId = $this->getMosqueId();
        $userId = $this->getUserId();
        
        $goalId = $this->input('goal_id');
        $accountId = $this->input('account_id');
        $amount = $this->input('amount');
        $notes = $this->input('notes');
        
        // Clean amount
        $amount = str_replace(['.', ','], '', $amount);
        
        // Get goal
        $goal = $this->db->first("SELECT * FROM goals WHERE id = ? AND mosque_id = ?", [$goalId, $mosqueId]);
        
        if (!$goal) {
            setError('Goal not found');
            $this->redirect('goals');
        }
        
        // Insert deposit
        $depositData = [
            'goal_id' => $goalId,
            'account_id' => $accountId,
            'amount' => $amount,
            'notes' => $notes,
            'deposited_by' => $userId,
            'created_at' => now()
        ];
        
        $this->db->insert('goal_deposits', $depositData);
        
        // Update goal current amount
        $newAmount = floatval($goal['current_amount']) + floatval($amount);
        $isCompleted = $newAmount >= floatval($goal['target_amount']) ? 1 : 0;
        
        $this->db->update('goals', [
            'current_amount' => $newAmount,
            'is_completed' => $isCompleted,
            'completed_at' => $isCompleted ? now() : null,
            'updated_at' => now()
        ], 'id = ?', [$goalId]);
        
        // Create transaction
        $category = $this->db->first("SELECT id FROM categories WHERE mosque_id = ? AND name = 'Pembangunan' AND type = 'income'", [$mosqueId]);
        
        if ($category) {
            $transactionData = [
                'mosque_id' => $mosqueId,
                'account_id' => $accountId,
                'category_id' => $category['id'],
                'type' => 'income',
                'amount' => $amount,
                'description' => 'Deposit: ' . $goal['name'],
                'transaction_date' => today(),
                'created_by' => $userId,
                'created_at' => now()
            ];
            
            $transactionId = $this->db->insert('transactions', $transactionData);
            
            // Update deposit with transaction
            $this->db->execute("UPDATE goal_deposits SET transaction_id = ? WHERE goal_id = ? AND created_at = NOW()", [$transactionId, $goalId]);
        }
        
        setSuccess('Deposit added successfully');
        $this->redirect('goals');
    }
    
    // Delete goal
    public function delete($id = null) {
        $this->requirePermission('goals.delete');
        
        if (!$id) {
            $this->redirect('goals');
        }
        
        $mosqueId = $this->getMosqueId();
        
        // Delete deposits first
        $this->db->delete('goal_deposits', 'goal_id = ?', [$id]);
        
        // Delete goal
        $this->db->delete('goals', 'id = ? AND mosque_id = ?', [$id, $mosqueId]);
        
        setSuccess('Goal deleted successfully');
        $this->redirect('goals');
    }
    
    // Edit goal
    public function edit($id = null) {
        $this->requirePermission('goals.edit');
        
        if (!$id) {
            $this->redirect('goals');
        }
        
        $mosqueId = $this->getMosqueId();
        
        $goal = $this->db->first("SELECT * FROM goals WHERE id = ? AND mosque_id = ?", [$id, $mosqueId]);
        
        if (!$goal) {
            setError('Goal not found');
            $this->redirect('goals');
        }
        
        $pageTitle = trans('edit') . ' ' . trans('goal');
        $this->view('goals/edit', compact('pageTitle', 'goal'));
    }
    
    // Update goal
    public function update($id = null) {
        if (!isPost() || !$id) {
            $this->redirect('goals');
        }
        
        $this->requirePermission('goals.edit');
        
        $mosqueId = $this->getMosqueId();
        
        $goal = $this->db->first("SELECT * FROM goals WHERE id = ? AND mosque_id = ?", [$id, $mosqueId]);
        
        if (!$goal) {
            setError('Goal not found');
            $this->redirect('goals');
        }
        
        $name = $this->input('name');
        $targetAmount = $this->input('target_amount');
        $targetDate = $this->input('target_date');
        $description = $this->input('description');
        
        // Clean amount
        $targetAmount = str_replace(['.', ','], '', $targetAmount);
        
        $data = [
            'name' => $name,
            'target_amount' => $targetAmount,
            'target_date' => $targetDate ?: null,
            'description' => $description,
            'updated_at' => now()
        ];
        
        // If target amount decreased below current amount, mark as not completed
        if (floatval($targetAmount) < floatval($goal['current_amount'])) {
            $data['is_completed'] = 0;
            $data['completed_at'] = null;
        }
        
        $this->db->update('goals', $data, 'id = ?', [$id]);
        
        setSuccess('Goal updated successfully');
        $this->redirect('goals');
    }
}
