<?php
/**
 * Report Controller
 * Generate financial reports with charts
 */

require_once BASE_PATH . '/helpers/SimpleExcelWriter.php';

class ReportController extends Controller {
    
    // Main report page
    public function index() {
        $this->requirePermission('reports.view');
        
        $mosqueId = $this->getMosqueId();
        $type = $this->input('type', 'summary');
        $fromDate = $this->input('from_date', date('Y-01-01'));
        $toDate = $this->input('to_date', date('Y-12-31'));
        
        // Summary report
        $summary = $this->db->first("
            SELECT 
                COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as total_income,
                COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as total_expense
            FROM transactions 
            WHERE mosque_id = ? AND transaction_date BETWEEN ? AND ?
        ", [$mosqueId, $fromDate, $toDate]);
        
        $totalIncome = floatval($summary['total_income']);
        $totalExpense = floatval($summary['total_expense']);
        $netBalance = $totalIncome - $totalExpense;
        
        // Calculate savings rate
        $savingsRate = $totalIncome > 0 ? (($totalIncome - $totalExpense) / $totalIncome) * 100 : 0;
        
        // Get previous period for comparison
        $fromDateObj = new DateTime($fromDate);
        $toDateObj = new DateTime($toDate);
        $interval = $fromDateObj->diff($toDateObj);
        $days = $interval->days;
        
        $prevFromDate = date('Y-m-d', strtotime($fromDate . ' -' . ($days + 1) . ' days'));
        $prevToDate = date('Y-m-d', strtotime($fromDate . ' -1 day'));
        
        $prevSummary = $this->db->first("
            SELECT 
                COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as total_income,
                COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as total_expense
            FROM transactions 
            WHERE mosque_id = ? AND transaction_date BETWEEN ? AND ?
        ", [$mosqueId, $prevFromDate, $prevToDate]);
        
        $prevIncome = floatval($prevSummary['total_income']);
        $prevExpense = floatval($prevSummary['total_expense']);
        
        // Calculate percentage changes
        $incomeChange = $prevIncome > 0 ? (($totalIncome - $prevIncome) / $prevIncome) * 100 : 0;
        $expenseChange = $prevExpense > 0 ? (($totalExpense - $prevExpense) / $prevExpense) * 100 : 0;
        
        // Income by category
        $incomeByCategory = $this->db->query("
            SELECT c.name, c.color, SUM(t.amount) as total
            FROM transactions t
            JOIN categories c ON t.category_id = c.id
            WHERE t.mosque_id = ? AND t.type = 'income' AND t.transaction_date BETWEEN ? AND ?
            GROUP BY c.id
            ORDER BY total DESC
        ", [$mosqueId, $fromDate, $toDate]);
        
        // Expense by category
        $expenseByCategory = $this->db->query("
            SELECT c.name, c.color, SUM(t.amount) as total
            FROM transactions t
            JOIN categories c ON t.category_id = c.id
            WHERE t.mosque_id = ? AND t.type = 'expense' AND t.transaction_date BETWEEN ? AND ?
            GROUP BY c.id
            ORDER BY total DESC
        ", [$mosqueId, $fromDate, $toDate]);
        
        // Get budgets data
        $budgets = $this->db->query("
            SELECT b.*, c.name as category_name,
                   COALESCE((SELECT SUM(t.amount) 
                            FROM transactions t 
                            WHERE t.category_id = b.category_id 
                            AND t.mosque_id = b.mosque_id
                            AND t.type = 'expense'
                            AND YEAR(t.transaction_date) = b.year
                            AND (b.month IS NULL OR MONTH(t.transaction_date) = b.month)), 0) as realization
            FROM budgets b
            JOIN categories c ON b.category_id = c.id
            WHERE b.mosque_id = ?
            AND b.year = YEAR(?)
            ORDER BY b.year DESC, b.month DESC
        ", [$mosqueId, $fromDate]);
        
        // Get goals data
        $goals = $this->db->query("
            SELECT g.*,
                   COALESCE((SELECT SUM(gd.amount) 
                            FROM goal_deposits gd 
                            WHERE gd.goal_id = g.id), 0) as total_deposits
            FROM goals g
            WHERE g.mosque_id = ?
            AND (g.target_date BETWEEN ? AND ? OR g.created_at BETWEEN ? AND ?)
            ORDER BY g.target_date ASC
        ", [$mosqueId, $fromDate, $toDate, $fromDate, $toDate]);
        
        // Get accounts with balance
        $accounts = $this->db->query("
            SELECT a.*,
                   COALESCE(a.initial_balance, 0) + 
                   COALESCE((SELECT SUM(t.amount) FROM transactions t WHERE t.account_id = a.id AND t.type = 'income'), 0) -
                   COALESCE((SELECT SUM(t.amount) FROM transactions t WHERE t.account_id = a.id AND t.type = 'expense'), 0) as balance
            FROM accounts a
            WHERE a.mosque_id = ?
            AND a.is_active = 1
            ORDER BY a.name
        ", [$mosqueId]);
        
        // Monthly data
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $month = str_pad($m, 2, '0', STR_PAD_LEFT);
            
            $income = $this->db->first("
                SELECT COALESCE(SUM(amount), 0) as total
                FROM transactions 
                WHERE mosque_id = ? AND type = 'income' 
                AND transaction_date LIKE ?
            ", [$mosqueId, date('Y') . '-' . $month . '%']);
            
            $expense = $this->db->first("
                SELECT COALESCE(SUM(amount), 0) as total
                FROM transactions 
                WHERE mosque_id = ? AND type = 'expense' 
                AND transaction_date LIKE ?
            ", [$mosqueId, date('Y') . '-' . $month . '%']);
            
            $monthlyData['labels'][] = getMonthName($m);
            $monthlyData['income'][] = floatval($income['total']);
            $monthlyData['expense'][] = floatval($expense['total']);
        }
        
        $pageTitle = trans('reports');
        $this->view('reports/index', compact(
            'pageTitle', 
            'type', 
            'fromDate', 
            'toDate',
            'totalIncome',
            'totalExpense',
            'netBalance',
            'savingsRate',
            'incomeChange',
            'expenseChange',
            'incomeByCategory',
            'expenseByCategory',
            'monthlyData',
            'budgets',
            'goals',
            'accounts'
        ));
    }
    
    // Export to PDF
    public function exportPdf() {
        $this->requirePermission('reports.export');
        
        $mosqueId = $this->getMosqueId();
        $fromDate = $this->input('from_date', date('Y-01-01'));
        $toDate = $this->input('to_date', date('Y-12-31'));
        
        // Get mosque info
        $mosque = $this->db->first("SELECT * FROM mosques WHERE id = ?", [$mosqueId]);
        
        // Summary report
        $summary = $this->db->first("
            SELECT 
                COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as total_income,
                COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as total_expense
            FROM transactions 
            WHERE mosque_id = ? AND transaction_date BETWEEN ? AND ?
        ", [$mosqueId, $fromDate, $toDate]);
        
        $totalIncome = floatval($summary['total_income']);
        $totalExpense = floatval($summary['total_expense']);
        $netBalance = $totalIncome - $totalExpense;
        
        // Income by category
        $incomeByCategory = $this->db->query("
            SELECT c.name, c.color, SUM(t.amount) as total
            FROM transactions t
            JOIN categories c ON t.category_id = c.id
            WHERE t.mosque_id = ? AND t.type = 'income' AND t.transaction_date BETWEEN ? AND ?
            GROUP BY c.id
            ORDER BY total DESC
        ", [$mosqueId, $fromDate, $toDate]);
        
        // Expense by category
        $expenseByCategory = $this->db->query("
            SELECT c.name, c.color, SUM(t.amount) as total
            FROM transactions t
            JOIN categories c ON t.category_id = c.id
            WHERE t.mosque_id = ? AND t.type = 'expense' AND t.transaction_date BETWEEN ? AND ?
            GROUP BY c.id
            ORDER BY total DESC
        ", [$mosqueId, $fromDate, $toDate]);
        
        // Get transactions
        $transactions = $this->db->query("
            SELECT t.*, c.name as category_name, c.color as category_color, a.name as account_name
            FROM transactions t
            LEFT JOIN categories c ON t.category_id = c.id
            LEFT JOIN accounts a ON t.account_id = a.id
            WHERE t.mosque_id = ? AND t.transaction_date BETWEEN ? AND ?
            ORDER BY t.transaction_date DESC
        ", [$mosqueId, $fromDate, $toDate]);
        
        // Generate HTML content for PDF
        $html = $this->generatePdfHtml($mosque, $fromDate, $toDate, $totalIncome, $totalExpense, $netBalance, $incomeByCategory, $expenseByCategory, $transactions);
        
        // Output as PDF (using browser's print to PDF)
        header('Content-Type: text/html; charset=utf-8');
        echo $html;
        exit;
    }
    
    // Generate PDF HTML content
    private function generatePdfHtml($mosque, $fromDate, $toDate, $totalIncome, $totalExpense, $netBalance, $incomeByCategory, $expenseByCategory, $transactions) {
        $mosqueName = $mosque['name'] ?? 'Masjid';
        $mosqueAddress = $mosque['address'] ?? '';
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan - ' . htmlspecialchars($mosqueName) . '</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #10B981; padding-bottom: 20px; }
        .header h1 { font-size: 24px; color: #10B981; margin-bottom: 5px; }
        .header p { font-size: 14px; color: #666; }
        .date-range { text-align: center; margin-bottom: 20px; padding: 10px; background: #f5f5f5; border-radius: 5px; }
        .summary { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .summary-box { flex: 1; padding: 15px; margin: 0 5px; border-radius: 8px; text-align: center; }
        .summary-box.income { background: #d1fae5; }
        .summary-box.expense { background: #fee2e2; }
        .summary-box.balance { background: #dbeafe; }
        .summary-box h3 { font-size: 12px; margin-bottom: 5px; }
        .summary-box .amount { font-size: 18px; font-weight: bold; }
        .income .amount { color: #059669; }
        .expense .amount { color: #dc2626; }
        .balance .amount { color: #2563eb; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th, table td { padding: 10px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        table th { background: #f9fafb; font-weight: bold; }
        table tr:hover { background: #f9fafb; }
        .section { margin-bottom: 30px; }
        .section h2 { font-size: 16px; margin-bottom: 15px; color: #10B981; border-left: 4px solid #10B981; padding-left: 10px; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; }
        .print-btn { position: fixed; top: 20px; right: 20px; }
        @media print {
            .print-btn { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ Print / Save as PDF</button>
    
    <div class="header">
        <h1>' . htmlspecialchars($mosqueName) . '</h1>
        <p>' . htmlspecialchars($mosqueAddress) . '</p>
        <h2 style="font-size: 18px; margin-top: 15px;">Laporan Keuangan</h2>
    </div>
    
    <div class="date-range">
        <strong>Periode:</strong> ' . formatDate($fromDate) . ' - ' . formatDate($toDate) . '
    </div>
    
    <div class="summary">
        <div class="summary-box income">
            <h3>Total Pemasukan</h3>
            <div class="amount">' . formatCurrency($totalIncome) . '</div>
        </div>
        <div class="summary-box expense">
            <h3>Total Pengeluaran</h3>
            <div class="amount">' . formatCurrency($totalExpense) . '</div>
        </div>
        <div class="summary-box balance">
            <h3>Saldo Bersih</h3>
            <div class="amount">' . formatCurrency($netBalance) . '</div>
        </div>
    </div>
    
    <div class="section">
        <h2>Detail Pemasukan per Kategori</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th style="text-align: right;">Jumlah</th>
                    <th style="text-align: right;">Persentase</th>
                </tr>
            </thead>
            <tbody>';
        
        $total = 0;
        foreach ($incomeByCategory as $cat) {
            $total += floatval($cat['total']);
        }
        $no = 1;
        foreach ($incomeByCategory as $cat) {
            $percentage = $total > 0 ? number_format(($cat['total'] / $total) * 100, 1) : 0;
            $html .= '<tr>
                <td>' . $no . '</td>
                <td>' . htmlspecialchars($cat['name']) . '</td>
                <td style="text-align: right;">' . formatCurrency($cat['total']) . '</td>
                <td style="text-align: right;">' . $percentage . '%</td>
            </tr>';
            $no++;
        }
        $html .= '<tr style="font-weight: bold; background: #f9fafb;">
            <td colspan="2">Total</td>
            <td style="text-align: right;">' . formatCurrency($total) . '</td>
            <td style="text-align: right;">100%</td>
        </tr>';
        
        $html .= '</tbody>
        </table>
    </div>
    
    <div class="section">
        <h2>Detail Pengeluaran per Kategori</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th style="text-align: right;">Jumlah</th>
                    <th style="text-align: right;">Persentase</th>
                </tr>
            </thead>
            <tbody>';
        
        $total = 0;
        foreach ($expenseByCategory as $cat) {
            $total += floatval($cat['total']);
        }
        $no = 1;
        foreach ($expenseByCategory as $cat) {
            $percentage = $total > 0 ? number_format(($cat['total'] / $total) * 100, 1) : 0;
            $html .= '<tr>
                <td>' . $no . '</td>
                <td>' . htmlspecialchars($cat['name']) . '</td>
                <td style="text-align: right;">' . formatCurrency($cat['total']) . '</td>
                <td style="text-align: right;">' . $percentage . '%</td>
            </tr>';
            $no++;
        }
        $html .= '<tr style="font-weight: bold; background: #f9fafb;">
            <td colspan="2">Total</td>
            <td style="text-align: right;">' . formatCurrency($total) . '</td>
            <td style="text-align: right;">100%</td>
        </tr>';
        
        $html .= '</tbody>
        </table>
    </div>
    
    <div class="section">
        <h2>Daftar Transaksi</h2>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Akun</th>
                    <th>Deskripsi</th>
                    <th style="text-align: right;">Jumlah</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach ($transactions as $trx) {
            $amountClass = $trx['type'] === 'income' ? 'color: #059669;' : 'color: #dc2626;';
            $amountPrefix = $trx['type'] === 'income' ? '+' : '-';
            $html .= '<tr>
                <td>' . formatDate($trx['transaction_date']) . '</td>
                <td>' . htmlspecialchars($trx['category_name'] ?? '-') . '</td>
                <td>' . htmlspecialchars($trx['account_name'] ?? '-') . '</td>
                <td>' . htmlspecialchars($trx['description'] ?? '-') . '</td>
                <td style="text-align: right; ' . $amountClass . ' font-weight: bold;">' . $amountPrefix . formatCurrency($trx['amount']) . '</td>
            </tr>';
        }
        
        if (empty($transactions)) {
            $html .= '<tr><td colspan="5" style="text-align: center;">Tidak ada transaksi</td></tr>';
        }
        
        $html .= '</tbody>
        </table>
    </div>
    
    <div class="footer">
        <p>Dicetak pada: ' . date('d/m/Y H:i:s') . '</p>
        <p>' . htmlspecialchars($mosqueName) . ' - Sistem Informasi Manajemen Masjid</p>
    </div>
</body>
</html>';
        
        return $html;
    }
    
    // Export to Excel
    public function exportExcel() {
        $this->requirePermission('reports.export');
        
        $mosqueId = $this->getMosqueId();
        $fromDate = $this->input('from_date', date('Y-01-01'));
        $toDate = $this->input('to_date', date('Y-12-31'));
        
        // Get mosque info
        $mosque = $this->db->first("SELECT * FROM mosques WHERE id = ?", [$mosqueId]);
        
        // Summary report
        $summary = $this->db->first("
            SELECT 
                COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as total_income,
                COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as total_expense
            FROM transactions 
            WHERE mosque_id = ? AND transaction_date BETWEEN ? AND ?
        ", [$mosqueId, $fromDate, $toDate]);
        
        $totalIncome = floatval($summary['total_income']);
        $totalExpense = floatval($summary['total_expense']);
        $netBalance = $totalIncome - $totalExpense;
        
        // Income by category
        $incomeByCategory = $this->db->query("
            SELECT c.name, c.color, SUM(t.amount) as total
            FROM transactions t
            JOIN categories c ON t.category_id = c.id
            WHERE t.mosque_id = ? AND t.type = 'income' AND t.transaction_date BETWEEN ? AND ?
            GROUP BY c.id
            ORDER BY total DESC
        ", [$mosqueId, $fromDate, $toDate]);
        
        // Expense by category
        $expenseByCategory = $this->db->query("
            SELECT c.name, c.color, SUM(t.amount) as total
            FROM transactions t
            JOIN categories c ON t.category_id = c.id
            WHERE t.mosque_id = ? AND t.type = 'expense' AND t.transaction_date BETWEEN ? AND ?
            GROUP BY c.id
            ORDER BY total DESC
        ", [$mosqueId, $fromDate, $toDate]);
        
        // Get transactions
        $transactions = $this->db->query("
            SELECT t.*, c.name as category_name, c.color as category_color, a.name as account_name
            FROM transactions t
            LEFT JOIN categories c ON t.category_id = c.id
            LEFT JOIN accounts a ON t.account_id = a.id
            WHERE t.mosque_id = ? AND t.transaction_date BETWEEN ? AND ?
            ORDER BY t.transaction_date DESC
        ", [$mosqueId, $fromDate, $toDate]);
        
        // Generate Excel file
        $this->generateExcelFile($mosque, $fromDate, $toDate, $totalIncome, $totalExpense, $netBalance, $incomeByCategory, $expenseByCategory, $transactions);
    }
    
    // Generate Excel file using XML format (XLSX)
    private function generateExcelFile($mosque, $fromDate, $toDate, $totalIncome, $totalExpense, $netBalance, $incomeByCategory, $expenseByCategory, $transactions) {
        $mosqueName = $mosque['name'] ?? 'Masjid';
        $filename = 'Laporan_Keuangan_' . date('Ymd_His') . '.xlsx';
        
        // Clean mosque name for filename
        $filename = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $filename);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        // Create XML for XLSX
        $xlsx = $this->createXlsxContent($mosqueName, $fromDate, $toDate, $totalIncome, $totalExpense, $netBalance, $incomeByCategory, $expenseByCategory, $transactions);
        
        echo $xlsx;
        exit;
    }
    
    // Create XLSX XML content
    private function createXlsxContent($mosqueName, $fromDate, $toDate, $totalIncome, $totalExpense, $netBalance, $incomeByCategory, $expenseByCategory, $transactions) {
        // Use PHPExcel XML format - simplified version
        $excel = new SimpleExcelWriter();
        
        // Sheet 1: Ringkasan
        $excel->setSheetName('Ringkasan');
        $excel->addRow(['']);
        $excel->addRow(['LAPORAN KEUANGAN']);
        $excel->addRow([$mosqueName]);
        $excel->addRow(['Periode: ' . formatDate($fromDate) . ' - ' . formatDate($toDate)]);
        $excel->addRow(['']);
        $excel->addRow(['RINGKASAN']);
        $excel->addRow(['Total Pemasukan', $totalIncome]);
        $excel->addRow(['Total Pengeluaran', $totalExpense]);
        $excel->addRow(['Saldo Bersih', $netBalance]);
        $excel->addRow(['']);
        
        // Sheet 2: Pemasukan per Kategori
        $excel->addNewSheet('Pemasukan');
        $excel->addRow(['Detail Pemasukan per Kategori']);
        $excel->addRow(['Periode: ' . formatDate($fromDate) . ' - ' . formatDate($toDate)]);
        $excel->addRow(['']);
        $excel->addRow(['No', 'Kategori', 'Jumlah', 'Persentase']);
        
        $total = 0;
        foreach ($incomeByCategory as $cat) {
            $total += floatval($cat['total']);
        }
        $no = 1;
        foreach ($incomeByCategory as $cat) {
            $percentage = $total > 0 ? ($cat['total'] / $total) * 100 : 0;
            $excel->addRow([$no, $cat['name'], floatval($cat['total']), $percentage . '%']);
            $no++;
        }
        $excel->addRow(['Total', '', $total, '100%']);
        
        // Sheet 3: Pengeluaran per Kategori
        $excel->addNewSheet('Pengeluaran');
        $excel->addRow(['Detail Pengeluaran per Kategori']);
        $excel->addRow(['Periode: ' . formatDate($fromDate) . ' - ' . formatDate($toDate)]);
        $excel->addRow(['']);
        $excel->addRow(['No', 'Kategori', 'Jumlah', 'Persentase']);
        
        $total = 0;
        foreach ($expenseByCategory as $cat) {
            $total += floatval($cat['total']);
        }
        $no = 1;
        foreach ($expenseByCategory as $cat) {
            $percentage = $total > 0 ? ($cat['total'] / $total) * 100 : 0;
            $excel->addRow([$no, $cat['name'], floatval($cat['total']), $percentage . '%']);
            $no++;
        }
        $excel->addRow(['Total', '', $total, '100%']);
        
        // Sheet 4: Transaksi
        $excel->addNewSheet('Transaksi');
        $excel->addRow(['Daftar Transaksi']);
        $excel->addRow(['Periode: ' . formatDate($fromDate) . ' - ' . formatDate($toDate)]);
        $excel->addRow(['']);
        $excel->addRow(['Tanggal', 'Jenis', 'Kategori', 'Akun', 'Deskripsi', 'Jumlah']);
        
        foreach ($transactions as $trx) {
            $amount = floatval($trx['amount']);
            $type = $trx['type'] === 'income' ? 'Pemasukan' : 'Pengeluaran';
            $amountStr = $trx['type'] === 'income' ? $amount : -$amount;
            $excel->addRow([
                $trx['transaction_date'],
                $type,
                $trx['category_name'] ?? '-',
                $trx['account_name'] ?? '-',
                $trx['description'] ?? '-',
                $amountStr
            ]);
        }
        
        return $excel->generate();
    }
    
    // Export to PDF (placeholder)
    public function export() {
        $this->requirePermission('reports.export');
        
        $format = $this->input('format', 'pdf');
        
        if ($format === 'excel') {
            $this->exportExcel();
        } else {
            $this->exportPdf();
        }
    }
}
