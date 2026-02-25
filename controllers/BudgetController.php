<?php
/**
 * Budget Controller
 * Manage budgets per category
 */

class BudgetController extends Controller {
    
    // List budgets
    public function index() {
        $this->requirePermission('budgets.view');
        
        $mosqueId = $this->getMosqueId();
        $year = $this->input('year', date('Y'));
        
        // Get budgets with realized amounts
        $budgets = $this->db->query("
            SELECT b.*, c.name as category_name, c.color as category_color,
            COALESCE(
                (SELECT SUM(t.amount) FROM transactions t 
                 WHERE t.category_id = b.category_id 
                 AND t.mosque_id = b.mosque_id
                 AND t.type = 'expense'
                 AND YEAR(t.transaction_date) = b.year
                 AND (b.month IS NULL OR MONTH(t.transaction_date) = b.month)
                ), 0
            ) as realized
            FROM budgets b
            LEFT JOIN categories c ON b.category_id = c.id
            WHERE b.mosque_id = ? AND b.year = ?
            ORDER BY c.type, c.name
        ", [$mosqueId, $year]);
        
        // Get years
        $years = $this->db->query("SELECT DISTINCT year FROM budgets WHERE mosque_id = ? ORDER BY year DESC", [$mosqueId]);
        
        // Calculate totals
        $totalBudget = array_sum(array_column($budgets, 'amount'));
        $totalRealized = array_sum(array_column($budgets, 'realized'));
        
        $pageTitle = trans('budgets');
        $this->view('budgets/index', compact('pageTitle', 'budgets', 'years', 'year', 'totalBudget', 'totalRealized'));
    }
    
    // Create budget
    public function create() {
        $this->requirePermission('budgets.create');
        
        $mosqueId = $this->getMosqueId();
        
        // Get expense categories
        $categories = $this->db->query("
            SELECT * FROM categories WHERE mosque_id = ? AND type = 'expense' AND is_active = 1 ORDER BY name
        ", [$mosqueId]);
        
        $pageTitle = trans('add_budget');
        $this->view('budgets/create', compact('pageTitle', 'categories'));
    }
    
    // Store budget
    public function store() {
        if (!isPost()) {
            $this->redirect('budgets');
        }
        
        $this->requirePermission('budgets.create');
        
        $mosqueId = $this->getMosqueId();
        $userId = $this->getUserId();
        
        $categoryId = $this->input('category_id');
        $year = $this->input('year');
        $month = $this->input('month', null);
        $amount = $this->input('amount');
        
        // Clean amount
        $amount = str_replace(['.', ','], '', $amount);
        
        // Check if budget exists
        $exists = $this->db->first("
            SELECT id FROM budgets WHERE mosque_id = ? AND category_id = ? AND year = ? AND (month = ? OR (month IS NULL AND ? IS NULL))
        ", [$mosqueId, $categoryId, $year, $month, $month]);
        
        if ($exists) {
            setError('Budget already exists for this category and period');
            $this->redirect('budgets/create');
        }
        
        $data = [
            'mosque_id' => $mosqueId,
            'category_id' => $categoryId,
            'year' => $year,
            'month' => $month ?: null,
            'amount' => $amount,
            'created_by' => $userId,
            'created_at' => now()
        ];
        
        $this->db->insert('budgets', $data);
        
        setSuccess('Budget created successfully');
        $this->redirect('budgets');
    }
    
    // Edit budget
    public function edit($id = null) {
        $this->requirePermission('budgets.edit');
        
        if (!$id) {
            $this->redirect('budgets');
        }
        
        $mosqueId = $this->getMosqueId();
        
        $budget = $this->db->first("
            SELECT b.*, c.name as category_name, c.color as category_color,
            COALESCE(
                (SELECT SUM(t.amount) FROM transactions t 
                 WHERE t.category_id = b.category_id 
                 AND t.mosque_id = b.mosque_id 
                 AND t.type = 'expense'
                 AND MONTH(t.transaction_date) = b.month 
                 AND YEAR(t.transaction_date) = b.year), 0
            ) as realized
            FROM budgets b
            LEFT JOIN categories c ON c.id = b.category_id
            WHERE b.id = ? AND b.mosque_id = ?
        ", [$id, $mosqueId]);
        
        if (!$budget) {
            setError('Budget not found');
            $this->redirect('budgets');
        }
        
        $categories = $this->db->query("
            SELECT * FROM categories WHERE mosque_id = ? AND type = 'expense' AND is_active = 1 ORDER BY name
        ", [$mosqueId]);
        
        $pageTitle = trans('edit') . ' ' . trans('budget');
        $this->view('budgets/edit', compact('pageTitle', 'budget', 'categories'));
    }
    
    // Update budget
    public function update($id = null) {
        if (!isPost() || !$id) {
            $this->redirect('budgets');
        }
        
        $this->requirePermission('budgets.edit');
        
        $mosqueId = $this->getMosqueId();
        
        // Check budget exists
        $budget = $this->db->first("SELECT * FROM budgets WHERE id = ? AND mosque_id = ?", [$id, $mosqueId]);
        
        if (!$budget) {
            setError('Budget not found');
            $this->redirect('budgets');
        }
        
        $amount = $this->input('amount');
        $amount = str_replace(['.', ','], '', $amount);
        
        $this->db->update('budgets', ['amount' => $amount, 'updated_at' => now()], 'id = ?', [$id]);
        
        setSuccess('Budget updated successfully');
        $this->redirect('budgets');
    }
    
    // Delete budget
    public function delete($id = null) {
        $this->requirePermission('budgets.delete');
        
        if (!$id) {
            $this->redirect('budgets');
        }
        
        $mosqueId = $this->getMosqueId();
        
        $this->db->delete('budgets', 'id = ? AND mosque_id = ?', [$id, $mosqueId]);
        
        setSuccess('Budget deleted successfully');
        $this->redirect('budgets');
    }
}
