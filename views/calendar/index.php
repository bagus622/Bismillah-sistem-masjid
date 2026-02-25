<?php 
// Helper functions
function getMonthNameIndo($month) {
    $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    return $months[$month] ?? '';
}

function getDayNameIndo($dayNum) {
    $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    return $days[$dayNum] ?? '';
}

function formatCurrencyIndo($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

// Day names
$dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
?>

<style>
@keyframes slideIn {
    from { transform: translateX(100%); }
    to { transform: translateX(0); }
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes scaleIn {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
.slide-in { animation: slideIn 0.3s ease-out; }
.fade-in { animation: fadeIn 0.3s ease-out; }
.scale-in { animation: scaleIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
</style>

<!-- Calendar Page -->
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 flex items-center gap-2">
                <span class="text-4xl">📅</span>
                Kalender Keuangan
            </h1>
            <p class="text-slate-600 mt-1">Lacak aktivitas pemasukan & pengeluaran Anda</p>
        </div>
        
        <div class="flex items-center gap-3 flex-wrap">
            <!-- Month Navigator -->
            <div class="flex items-center gap-2 bg-white rounded-xl shadow-sm border border-slate-200 p-1">
                <a href="<?= base_url('calendar?month=' . $prevMonth . '&year=' . $prevYear) ?>" 
                   class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                    <i data-lucide="chevron-left" class="w-5 h-5 text-slate-600"></i>
                </a>
                <span class="px-4 font-semibold text-slate-800 min-w-[140px] text-center">
                    <?= getMonthNameIndo($month) ?> <?= $year ?>
                </span>
                <a href="<?= base_url('calendar?month=' . $nextMonth . '&year=' . $nextYear) ?>" 
                   class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                    <i data-lucide="chevron-right" class="w-5 h-5 text-slate-600"></i>
                </a>
            </div>
            
            <a href="<?= base_url('calendar?month=' . date('n') . '&year=' . date('Y')) ?>" 
               class="px-4 py-2 bg-white border-2 border-indigo-600 text-indigo-600 rounded-xl font-semibold hover:bg-indigo-50 transition-all flex items-center gap-2">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                Hari Ini
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Income Card -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-6 hover:shadow-lg transition-all card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-700 font-medium text-sm mb-1">Pemasukan</p>
                    <p class="text-2xl font-bold text-green-800"><?= formatCurrencyIndo($monthIncome) ?></p>
                </div>
                <div class="w-14 h-14 bg-green-500 rounded-2xl flex items-center justify-center shadow-lg">
                    <i data-lucide="trending-up" class="w-7 h-7 text-white"></i>
                </div>
            </div>
        </div>
        
        <!-- Expense Card -->
        <div class="bg-gradient-to-br from-red-50 to-rose-50 border border-red-200 rounded-2xl p-6 hover:shadow-lg transition-all card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-700 font-medium text-sm mb-1">Pengeluaran</p>
                    <p class="text-2xl font-bold text-red-800"><?= formatCurrencyIndo($monthExpense) ?></p>
                </div>
                <div class="w-14 h-14 bg-red-500 rounded-2xl flex items-center justify-center shadow-lg">
                    <i data-lucide="trending-down" class="w-7 h-7 text-white"></i>
                </div>
            </div>
        </div>
        
        <!-- Balance Card -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-6 hover:shadow-lg transition-all card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-700 font-medium text-sm mb-1">Saldo</p>
                    <p class="text-2xl font-bold <?= $monthBalance >= 0 ? 'text-green-800' : 'text-red-800' ?>">
                        <?= formatCurrencyIndo($monthBalance) ?>
                    </p>
                </div>
                <div class="w-14 h-14 bg-blue-500 rounded-2xl flex items-center justify-center shadow-lg">
                    <i data-lucide="wallet" class="w-7 h-7 text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
        <!-- Day Headers -->
        <div class="grid grid-cols-7 bg-gradient-to-r from-indigo-600 to-purple-600">
            <?php foreach ($dayNames as $idx => $dayName): ?>
            <div class="text-center py-4 text-white font-bold text-sm <?= in_array($idx, [0, 6]) ? 'bg-white/10' : '' ?>">
                <?= $dayName ?>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Calendar Grid -->
        <div class="grid grid-cols-7">
            <?php 
            $day = 1;
            $firstDayFilled = false;
            $totalCells = $dayOfWeek + $daysInMonth;
            $rows = ceil($totalCells / 7);
            
            // Render empty cells for days before month starts
            for ($cell = 0; $cell < $dayOfWeek; $cell++): 
            ?>
            <div class="min-h-[120px] bg-slate-50 border border-slate-100 p-3"></div>
            <?php endfor; ?>
            
            <!-- Day cells -->
            <?php 
            for ($cell = $dayOfWeek; $cell < $dayOfWeek + $daysInMonth; $cell++): 
                $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                $isToday = ($todayDate === $dateStr);
                $isWeekend = ($cell % 7 == 0 || $cell % 7 == 6);
                
                // Get dots
                $dots = [];
                $hasIncome = false;
                $hasExpense = false;
                $hasUpcoming = false;
                $hasGoal = false;
                $hasDeposit = false;
                $hasBudget = false;
                
                // Check transactions
                if (isset($transactionsByDate[$dateStr])) {
                    foreach ($transactionsByDate[$dateStr] as $t) {
                        if ($t['type'] === 'income' && $t['is_upcoming'] == 0) {
                            $hasIncome = true;
                        }
                        if ($t['type'] === 'expense' && $t['is_upcoming'] == 0) {
                            $hasExpense = true;
                        }
                        if ($t['is_upcoming'] == 1) {
                            $hasUpcoming = true;
                        }
                    }
                }
                
                // Check goals
                if (isset($goalsByDate[$dateStr])) {
                    $hasGoal = true;
                }
                
                // Check deposits
                if (isset($depositsByDate[$dateStr])) {
                    $hasDeposit = true;
                }
                
                // Check budgets
                if (isset($budgetsByDate[$dateStr])) {
                    $hasBudget = true;
                }
                
                if ($hasIncome) $dots[] = 'income';
                if ($hasExpense) $dots[] = 'expense';
                if ($hasUpcoming) $dots[] = 'upcoming';
                if ($hasGoal) $dots[] = 'goal';
                if ($hasDeposit) $dots[] = 'deposit';
                if ($hasBudget) $dots[] = 'budget';
            ?>
            <div onclick="showDateDetails('<?= $dateStr ?>')" 
                 class="min-h-[120px] border border-slate-100 p-3 cursor-pointer transition-all hover:bg-indigo-50 hover:shadow-lg hover:scale-105 hover:z-10 <?= $isWeekend ? 'bg-red-50/30' : 'bg-white' ?> <?= $isToday ? 'ring-2 ring-indigo-500 bg-indigo-50' : '' ?>">
                <div class="flex items-center justify-between mb-2">
                    <span class="<?= $isToday ? 'w-8 h-8 bg-gradient-to-br from-indigo-600 to-purple-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-lg' : 'font-semibold text-slate-700' ?> <?= $isWeekend ? 'text-red-600' : '' ?>">
                        <?= $day ?>
                    </span>
                    <?php if ($isToday): ?>
                    <span class="text-[9px] bg-indigo-600 text-white px-2 py-0.5 rounded-full font-bold">HARI INI</span>
                    <?php endif; ?>
                </div>
                
                <?php 
                // Count all events for this date
                $transCount = isset($transactionsByDate[$dateStr]) ? count($transactionsByDate[$dateStr]) : 0;
                $goalCount = isset($goalsByDate[$dateStr]) ? count($goalsByDate[$dateStr]) : 0;
                $depositCount = isset($depositsByDate[$dateStr]) ? count($depositsByDate[$dateStr]) : 0;
                $budgetCount = isset($budgetsByDate[$dateStr]) ? count($budgetsByDate[$dateStr]) : 0;
                $totalEvents = $transCount + $goalCount + $depositCount + $budgetCount;
                
                if ($totalEvents > 0): 
                ?>
                <div class="text-xs text-slate-600 mb-2 font-medium">
                    <?= $totalEvents ?> event<?= $totalEvents > 1 ? 's' : '' ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($dots)): ?>
                <div class="flex flex-wrap gap-1.5 mt-2">
                    <?php foreach (array_slice($dots, 0, 6) as $dot): 
                        $dotColors = [
                            'income' => 'bg-green-500',
                            'expense' => 'bg-red-500',
                            'upcoming' => 'bg-orange-500',
                            'goal' => 'bg-purple-500',
                            'deposit' => 'bg-blue-500',
                            'budget' => 'bg-yellow-500'
                        ];
                    ?>
                    <span class="w-2 h-2 rounded-full <?= $dotColors[$dot] ?? 'bg-gray-500' ?> shadow-sm"></span>
                    <?php endforeach; ?>
                    <?php if (count($dots) > 6): ?>
                    <span class="text-[9px] text-slate-500 font-bold">+<?= count($dots) - 6 ?></span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php $day++; endfor; ?>
            
            <?php 
            // Fill remaining cells to complete the grid
            $remainingCells = 7 - (($dayOfWeek + $daysInMonth) % 7);
            if ($remainingCells < 7) {
                for ($i = 0; $i < $remainingCells; $i++): 
            ?>
            <div class="min-h-[120px] bg-slate-50 border border-slate-100 p-3"></div>
            <?php 
                endfor;
            }
            ?>
        </div>
    </div>

    <!-- Legend -->
    <div class="flex items-center justify-center gap-6 flex-wrap bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-green-500 shadow-sm"></span>
            <span class="text-sm text-slate-700 font-medium">Pemasukan</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-red-500 shadow-sm"></span>
            <span class="text-sm text-slate-700 font-medium">Pengeluaran</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-orange-500 shadow-sm"></span>
            <span class="text-sm text-slate-700 font-medium">Akan Datang</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-purple-500 shadow-sm"></span>
            <span class="text-sm text-slate-700 font-medium">Target/Goal</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-blue-500 shadow-sm"></span>
            <span class="text-sm text-slate-700 font-medium">Setoran</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-yellow-500 shadow-sm"></span>
            <span class="text-sm text-slate-700 font-medium">Anggaran</span>
        </div>
    </div>
</div>

<!-- Detail Slide Panel -->
<div id="detailPanel" class="fixed right-0 top-0 h-full w-full sm:w-[400px] bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 overflow-y-auto">
    <div class="sticky top-0 bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6 z-10">
        <div class="flex items-center justify-between mb-2">
            <h3 id="panelDate" class="text-2xl font-bold">-</h3>
            <button onclick="closePanel()" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-xl transition-colors flex items-center justify-center">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <p id="panelDayName" class="text-indigo-100">-</p>
    </div>
    
    <!-- Summary -->
    <div class="grid grid-cols-3 gap-3 p-6 bg-slate-50">
        <div class="bg-white rounded-xl p-3 text-center shadow-sm">
            <p class="text-xs text-slate-600 mb-1">Pemasukan</p>
            <p id="panelIncome" class="text-sm font-bold text-green-600">-</p>
        </div>
        <div class="bg-white rounded-xl p-3 text-center shadow-sm">
            <p class="text-xs text-slate-600 mb-1">Pengeluaran</p>
            <p id="panelExpense" class="text-sm font-bold text-red-600">-</p>
        </div>
        <div class="bg-white rounded-xl p-3 text-center shadow-sm">
            <p class="text-xs text-slate-600 mb-1">Saldo</p>
            <p id="panelBalance" class="text-sm font-bold text-blue-600">-</p>
        </div>
    </div>
    
    <!-- Transactions -->
    <div id="panelTransactions" class="p-6">
        <!-- Content loaded via JS -->
    </div>
    
    <!-- Footer - Removed Add Transaction Button -->
</div>

<!-- Panel Overlay -->
<div id="panelOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 opacity-0 pointer-events-none transition-opacity duration-300" onclick="closePanel()"></div>

<!-- Transaction Modal -->
<div id="transactionModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto scale-in">
        <div class="sticky top-0 bg-white border-b border-slate-200 p-6 flex items-center justify-between z-10">
            <h3 id="modalTitle" class="text-xl font-bold text-slate-800">Tambah Transaksi</h3>
            <button onclick="closeModal()" class="w-10 h-10 hover:bg-slate-100 rounded-xl transition-colors flex items-center justify-center">
                <i data-lucide="x" class="w-5 h-5 text-slate-600"></i>
            </button>
        </div>
        
        <form id="transactionForm" class="p-6 space-y-4">
            <input type="hidden" id="transactionId">
            
            <!-- Type -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Jenis Transaksi</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="income" checked class="hidden peer" onchange="updateCategories()">
                        <div class="p-3 text-center rounded-xl border-2 border-green-500 bg-green-500 text-white font-semibold peer-[:not(:checked)]:border-slate-300 peer-[:not(:checked)]:bg-white peer-[:not(:checked)]:text-slate-700 transition-all">
                            Pemasukan
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="expense" class="hidden peer" onchange="updateCategories()">
                        <div class="p-3 text-center rounded-xl border-2 border-red-500 text-red-500 font-semibold peer-checked:bg-red-500 peer-checked:text-white peer-[:not(:checked)]:border-slate-300 peer-[:not(:checked)]:text-slate-700 transition-all">
                            Pengeluaran
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- Amount -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Nominal (Rp)</label>
                <input type="text" id="amountInput" required class="w-full px-4 py-3 border-2 border-slate-300 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all" placeholder="0">
            </div>
            
            <!-- Account -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Akun</label>
                <select id="accountSelect" class="w-full px-4 py-3 border-2 border-slate-300 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all" required>
                    <option value="">Pilih Akun</option>
                    <?php foreach ($accounts as $acc): ?>
                    <option value="<?= $acc['id'] ?>"><?= $acc['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Category -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                <select id="categorySelect" class="w-full px-4 py-3 border-2 border-slate-300 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all" required>
                    <option value="">Pilih Kategori</option>
                </select>
            </div>
            
            <!-- Description -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi</label>
                <input type="text" id="descInput" class="w-full px-4 py-3 border-2 border-slate-300 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all" placeholder="Deskripsi transaksi" required>
            </div>
            
            <!-- Date -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal</label>
                <input type="date" id="dateInput" class="w-full px-4 py-3 border-2 border-slate-300 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all" required>
            </div>
            
            <!-- Upcoming -->
            <div class="flex items-center gap-3">
                <input type="checkbox" id="isUpcoming" value="1" class="w-5 h-5 text-indigo-600 rounded focus:ring-2 focus:ring-indigo-500">
                <label for="isUpcoming" class="text-sm text-slate-700">Akan Datang (jadwalkan di masa depan)</label>
            </div>
            
            <!-- Submit -->
            <button type="submit" class="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all">
                Simpan Transaksi
            </button>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center scale-in">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="alert-triangle" class="w-8 h-8 text-red-600"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-800 mb-2">Hapus Transaksi?</h3>
        <p class="text-slate-600 mb-6">Apakah Anda yakin ingin menghapus transaksi ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 py-2.5 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-colors">
                Batal
            </button>
            <button onclick="confirmDelete()" class="flex-1 py-2.5 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-colors">
                Hapus
            </button>
        </div>
    </div>
</div>

<script>
    const transactionsByDate = <?= json_encode($transactionsByDate) ?>;
    const currentYear = <?= $year ?>;
    const currentMonth = <?= $month ?>;
    const canEdit = <?= $canEdit ? 'true' : 'false' ?>;
    const incomeCategories = <?= json_encode($incomeCategories) ?>;
    const expenseCategories = <?= json_encode($expenseCategories) ?>;
    
    const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const monthNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    let selectedDate = null;
    let deleteId = null;
    
    document.getElementById('dateInput').value = new Date().toISOString().split('T')[0];
    updateCategories();
    
    function updateCategories() {
        const type = document.querySelector('input[name="type"]:checked').value;
        const cats = type === 'income' ? incomeCategories : expenseCategories;
        const sel = document.getElementById('categorySelect');
        sel.innerHTML = '<option value="">Pilih Kategori</option>';
        cats.forEach(c => sel.innerHTML += `<option value="${c.id}">${c.name}</option>`);
    }
    
    function showDateDetails(dateStr) {
        selectedDate = dateStr;
        const date = new Date(dateStr + 'T00:00:00');
        document.getElementById('panelDate').textContent = date.getDate() + ' ' + monthNames[currentMonth] + ' ' + currentYear;
        document.getElementById('panelDayName').textContent = dayNames[date.getDay()];
        document.getElementById('panelTransactions').innerHTML = `
            <div class="text-center py-12">
                <div class="w-12 h-12 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-slate-600">Memuat data...</p>
            </div>
        `;
        
        document.getElementById('detailPanel').classList.remove('translate-x-full');
        document.getElementById('panelOverlay').classList.remove('opacity-0', 'pointer-events-none');
        document.body.style.overflow = 'hidden';
        
        fetch('<?= base_url('calendar/getDateDetails') ?>?date=' + dateStr)
            .then(r => r.json())
            .then(result => {
                if (result.success) renderPanelContent(result.data);
                else document.getElementById('panelTransactions').innerHTML = '<p class="text-center text-red-600 py-12">Gagal memuat data</p>';
            })
            .catch(err => {
                document.getElementById('panelTransactions').innerHTML = '<p class="text-center text-red-600 py-12">Terjadi kesalahan</p>';
            });
    }
    
    function renderPanelContent(data) {
        const { transactions, goals, goal_deposits, budgets, summary } = data;
        
        document.getElementById('panelIncome').textContent = formatCurrency(summary.pemasukan);
        document.getElementById('panelExpense').textContent = formatCurrency(summary.pengeluaran);
        document.getElementById('panelBalance').textContent = formatCurrency(summary.saldo);
        document.getElementById('panelBalance').className = summary.saldo >= 0 ? 'text-sm font-bold text-green-600' : 'text-sm font-bold text-red-600';
        
        const container = document.getElementById('panelTransactions');
        
        const hasData = transactions.length > 0 || goals.length > 0 || goal_deposits.length > 0 || budgets.length > 0;
        
        if (!hasData) {
            container.innerHTML = `
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="inbox" class="w-8 h-8 text-slate-400"></i>
                    </div>
                    <p class="text-slate-600 font-medium mb-1">Tidak ada aktivitas</p>
                    <p class="text-sm text-slate-500">Belum ada data di tanggal ini</p>
                </div>
            `;
            lucide.createIcons();
            return;
        }
        
        let html = '<div class="space-y-4">';
        
        // Render Transactions
        if (transactions.length > 0) {
            html += '<div><h3 class="text-sm font-bold text-slate-700 mb-2 flex items-center gap-2"><i data-lucide="arrow-right-left" class="w-4 h-4"></i> Transaksi</h3><div class="space-y-2">';
            transactions.forEach(t => {
                const isIncome = t.type === 'income';
                const isUpcoming = t.is_upcoming == 1;
                let bgClass, iconBg, amountColor;
                
                if (isUpcoming) {
                    bgClass = 'bg-orange-50 border-orange-200';
                    iconBg = 'bg-orange-500';
                    amountColor = 'text-orange-600';
                } else if (isIncome) {
                    bgClass = 'bg-green-50 border-green-200';
                    iconBg = 'bg-green-500';
                    amountColor = 'text-green-600';
                } else {
                    bgClass = 'bg-red-50 border-red-200';
                    iconBg = 'bg-red-500';
                    amountColor = 'text-red-600';
                }
                
                const icon = isIncome ? 'trending-up' : 'trending-down';
                
                html += `
                    <div class="flex items-start gap-3 p-3 ${bgClass} border rounded-xl">
                        <div class="w-10 h-10 ${iconBg} rounded-xl flex items-center justify-center flex-shrink-0">
                            <i data-lucide="${icon}" class="w-5 h-5 text-white"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-slate-800 text-sm">${t.category_name}</p>
                            <p class="text-xs text-slate-600 truncate">${t.description || '-'}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-sm ${amountColor}">${isIncome ? '+' : '-'}${formatCurrency(t.amount)}</p>
                        </div>
                    </div>
                `;
            });
            html += '</div></div>';
        }
        
        // Render Goals
        if (goals.length > 0) {
            html += '<div><h3 class="text-sm font-bold text-slate-700 mb-2 flex items-center gap-2"><i data-lucide="target" class="w-4 h-4"></i> Target/Goal</h3><div class="space-y-2">';
            goals.forEach(g => {
                const progress = (g.current_amount / g.target_amount * 100).toFixed(1);
                html += `
                    <div class="p-3 bg-purple-50 border border-purple-200 rounded-xl">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                    <i data-lucide="target" class="w-4 h-4 text-white"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800 text-sm">${g.name}</p>
                                    <p class="text-xs text-slate-600">${formatCurrency(g.current_amount)} / ${formatCurrency(g.target_amount)}</p>
                                </div>
                            </div>
                            ${g.is_completed ? '<span class="text-xs bg-green-500 text-white px-2 py-1 rounded-full">Tercapai</span>' : ''}
                        </div>
                        <div class="w-full bg-purple-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: ${Math.min(progress, 100)}%"></div>
                        </div>
                        <p class="text-xs text-purple-700 mt-1">${progress}% tercapai</p>
                    </div>
                `;
            });
            html += '</div></div>';
        }
        
        // Render Goal Deposits
        if (goal_deposits.length > 0) {
            html += '<div><h3 class="text-sm font-bold text-slate-700 mb-2 flex items-center gap-2"><i data-lucide="piggy-bank" class="w-4 h-4"></i> Setoran Goal</h3><div class="space-y-2">';
            goal_deposits.forEach(gd => {
                html += `
                    <div class="flex items-start gap-3 p-3 bg-blue-50 border border-blue-200 rounded-xl">
                        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i data-lucide="piggy-bank" class="w-5 h-5 text-white"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-slate-800 text-sm">${gd.goal_name}</p>
                            <p class="text-xs text-slate-600">${gd.notes || 'Setoran'}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-sm text-blue-600">+${formatCurrency(gd.amount)}</p>
                        </div>
                    </div>
                `;
            });
            html += '</div></div>';
        }
        
        // Render Budgets
        if (budgets.length > 0) {
            html += '<div><h3 class="text-sm font-bold text-slate-700 mb-2 flex items-center gap-2"><i data-lucide="wallet" class="w-4 h-4"></i> Anggaran</h3><div class="space-y-2">';
            budgets.forEach(b => {
                const progress = (b.spent_amount / b.budget_amount * 100).toFixed(1);
                const isOverBudget = b.spent_amount > b.budget_amount;
                html += `
                    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-xl">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                                    <i data-lucide="wallet" class="w-4 h-4 text-white"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800 text-sm">${b.category_name}</p>
                                    <p class="text-xs text-slate-600">${formatCurrency(b.spent_amount)} / ${formatCurrency(b.budget_amount)}</p>
                                </div>
                            </div>
                            ${isOverBudget ? '<span class="text-xs bg-red-500 text-white px-2 py-1 rounded-full">Over Budget</span>' : ''}
                        </div>
                        <div class="w-full bg-yellow-200 rounded-full h-2">
                            <div class="${isOverBudget ? 'bg-red-600' : 'bg-yellow-600'} h-2 rounded-full" style="width: ${Math.min(progress, 100)}%"></div>
                        </div>
                        <p class="text-xs ${isOverBudget ? 'text-red-700' : 'text-yellow-700'} mt-1">${progress}% terpakai</p>
                    </div>
                `;
            });
            html += '</div></div>';
        }
        
        html += '</div>';
        container.innerHTML = html;
        lucide.createIcons();
    }
    
    function closePanel() {
        document.getElementById('detailPanel').classList.add('translate-x-full');
        document.getElementById('panelOverlay').classList.add('opacity-0', 'pointer-events-none');
        document.body.style.overflow = '';
    }
    
    function openAddModal() {
        document.getElementById('transactionId').value = '';
        document.getElementById('transactionForm').reset();
        document.getElementById('dateInput').value = new Date().toISOString().split('T')[0];
        updateCategories();
        document.getElementById('transactionModal').classList.remove('hidden');
        setTimeout(() => lucide.createIcons(), 100);
    }
    
    function openAddModalFromPanel() {
        if (selectedDate) document.getElementById('dateInput').value = selectedDate;
        closePanel();
        openAddModal();
    }
    
    function closeModal() {
        document.getElementById('transactionModal').classList.add('hidden');
    }
    
    function showDeleteConfirm(id) {
        deleteId = id;
        document.getElementById('deleteModal').classList.remove('hidden');
        setTimeout(() => lucide.createIcons(), 100);
    }
    
    function closeDeleteModal() {
        deleteId = null;
        document.getElementById('deleteModal').classList.add('hidden');
    }
    
    function confirmDelete() {
        if (!deleteId) return;
        fetch('<?= base_url('calendar/deleteTransaction') ?>/' + deleteId, { method: 'POST' })
            .then(r => r.json())
            .then(result => {
                closeDeleteModal();
                if (result.success) location.reload();
                else alert(result.message);
            });
    }
    
    function formatCurrency(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }
    
    document.getElementById('amountInput').addEventListener('input', e => {
        let v = e.target.value.replace(/[.,]/g, '');
        if (v) v = parseInt(v).toLocaleString('id-ID');
        e.target.value = v;
    });
    
    document.getElementById('transactionForm').addEventListener('submit', e => {
        e.preventDefault();
        const submitBtn = e.target.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin mx-auto"></div>';
        
        const fd = new FormData(e.target);
        const data = Object.fromEntries(fd.entries());
        data.amount = parseFloat(data.amount.replace(/[.,]/g, ''));
        data.is_upcoming = data.is_upcoming ? 1 : 0;
        
        const url = data.id ? '<?= base_url('calendar/editTransaction') ?>/' + data.id : '<?= base_url('calendar/addTransaction') ?>';
        fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: new URLSearchParams(data) })
            .then(r => r.json())
            .then(result => {
                if (result.success) { 
                    closeModal(); 
                    location.reload(); 
                } else {
                    alert(result.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Simpan Transaksi';
                }
            })
            .catch(err => {
                alert('Terjadi kesalahan');
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Simpan Transaksi';
            });
    });
    
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { 
            closePanel(); 
            closeModal(); 
            closeDeleteModal(); 
        }
    });
    
    // Initialize icons
    setTimeout(() => lucide.createIcons(), 100);
</script>
