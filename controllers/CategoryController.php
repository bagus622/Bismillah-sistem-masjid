<?php
/**
 * Category Controller
 * Manage income and expense categories
 */

class CategoryController extends Controller {
    
    // List categories
    public function index() {
        $this->requirePermission('categories.view');
        
        $mosqueId = $this->getMosqueId();
        
        // Get income categories
        $incomeCategories = $this->db->query("
            SELECT c.*, 
                   (SELECT COUNT(*) FROM transactions WHERE category_id = c.id) as transaction_count
            FROM categories c 
            WHERE c.mosque_id = ? AND c.type = 'income' AND c.is_active = 1
            ORDER BY c.name
        ", [$mosqueId]);
        
        // Get expense categories
        $expenseCategories = $this->db->query("
            SELECT c.*, 
                   (SELECT COUNT(*) FROM transactions WHERE category_id = c.id) as transaction_count
            FROM categories c 
            WHERE c.mosque_id = ? AND c.type = 'expense' AND c.is_active = 1
            ORDER BY c.name
        ", [$mosqueId]);
        
        // Get totals
        $totalIncomeCategories = count($incomeCategories);
        $totalExpenseCategories = count($expenseCategories);
        
        $pageTitle = trans('categories');
        $this->view('categories/index', compact('pageTitle', 'incomeCategories', 'expenseCategories', 'totalIncomeCategories', 'totalExpenseCategories'));
    }
    
    // Create category
    public function create() {
        $this->requirePermission('categories.create');
        
        $type = $this->input('type', 'income');
        $pageTitle = trans('add_category');
        
        $this->view('categories/create', compact('pageTitle', 'type'));
    }
    
    // Store category
    public function store() {
        if (!isPost()) {
            $this->redirect('categories');
        }
        
        $this->requirePermission('categories.create');
        
        $mosqueId = $this->getMosqueId();
        
        $name = $this->input('name');
        $type = $this->input('type');
        $icon = $this->input('icon', 'fa-tag');
        $color = $this->input('color', '#1a73e8');
        $description = $this->input('description');
        
        $data = [
            'mosque_id' => $mosqueId,
            'name' => $name,
            'type' => $type,
            'icon' => $icon,
            'color' => $color,
            'description' => $description,
            'is_active' => 1,
            'created_at' => now()
        ];
        
        $this->db->insert('categories', $data);
        
        setSuccess('Category created successfully');
        $this->redirect('categories');
    }
    
    // Edit category
    public function edit($id = null) {
        $this->requirePermission('categories.edit');
        
        if (!$id) {
            $this->redirect('categories');
        }
        
        $mosqueId = $this->getMosqueId();
        
        $category = $this->db->first("SELECT * FROM categories WHERE id = ? AND mosque_id = ?", [$id, $mosqueId]);
        
        if (!$category) {
            setError('Category not found');
            $this->redirect('categories');
        }
        
        $pageTitle = trans('edit') . ' ' . trans('category');
        $this->view('categories/edit', compact('pageTitle', 'category'));
    }
    
    // Update category
    public function update($id = null) {
        if (!isPost() || !$id) {
            $this->redirect('categories');
        }
        
        $this->requirePermission('categories.edit');
        
        $mosqueId = $this->getMosqueId();
        
        $category = $this->db->first("SELECT * FROM categories WHERE id = ? AND mosque_id = ?", [$id, $mosqueId]);
        
        if (!$category) {
            setError('Category not found');
            $this->redirect('categories');
        }
        
        $name = $this->input('name');
        $icon = $this->input('icon', 'fa-tag');
        $color = $this->input('color', '#1a73e8');
        $description = $this->input('description');
        
        $data = [
            'name' => $name,
            'icon' => $icon,
            'color' => $color,
            'description' => $description,
            'updated_at' => now()
        ];
        
        $this->db->update('categories', $data, 'id = ?', [$id]);
        
        setSuccess('Category updated successfully');
        $this->redirect('categories');
    }
    
    // Delete category
    public function delete($id = null) {
        $this->requirePermission('categories.delete');
        
        if (!$id) {
            $this->redirect('categories');
        }
        
        $mosqueId = $this->getMosqueId();
        
        // Check if category has transactions
        $count = $this->db->count('transactions', 'category_id = ?', [$id]);
        
        if ($count > 0) {
            setError('Cannot delete category with existing transactions');
            $this->redirect('categories');
        }
        
        $this->db->delete('categories', 'id = ? AND mosque_id = ?', [$id, $mosqueId]);
        
        setSuccess('Category deleted successfully');
        $this->redirect('categories');
    }
}
