<?php 
// Helper for trend display
function formatTrend($value) {
    $sign = $value >= 0 ? '+' : '';
    return $sign . number_format($value, 1) . '%';
}

function getTrendClass($value) {
    return $value >= 0 ? 'text-accent' : 'text-red';
}

function getTrendBg($value) {
    return $value >= 0 ? 'bg-accent-light' : 'bg-red-light';
}
?>

<!-- Page Header -->
<div class="mb-8">
    <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; letter-spacing: -0.3px; color: var(--text-primary);">
                Laporan Keuangan
            </h1>
            <p class="text-footnote" style="color: var(--text-secondary); margin-top: 4px;">
                Analisis tren pemasukan, pengeluaran, dan saldo
            </p>
        </div>
        
        <!-- Quick Filters -->
        <div style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
            <button onclick="setDateRange('this_month')" class="btn btn-ghost" id="filter-this_month">Bulan Ini</button>
            <button onclick="setDateRange('this_year')" class="btn btn-ghost" id="filter-this_year">Tahun Ini</button>
            
            <!-- Export Dropdown -->
            <div style="position: relative;" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open" class="inline-flex items-center gap-2 px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span>Ekspor</span>
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>
                
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50"
                     style="display: none;">
                    
                    <a href="<?= base_url('reports/exportPdf?from_date=' . $fromDate . '&to_date=' . $toDate) ?>" 
                       class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-150">
                        <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                            <i data-lucide="file-text" class="w-5 h-5 text-red-600"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-sm text-gray-900">Ekspor PDF</div>
                            <div class="text-xs text-gray-500">Format dokumen</div>
                        </div>
                    </a>
                    
                    <div class="border-t border-gray-100 my-1"></div>
                    
                    <a href="<?= base_url('reports/exportExcel?from_date=' . $fromDate . '&to_date=' . $toDate) ?>" 
                       class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-150">
                        <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                            <i data-lucide="file-spreadsheet" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-sm text-gray-900">Ekspor Excel</div>
                            <div class="text-xs text-gray-500">Format spreadsheet</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="card" style="margin-bottom: 24px; padding: 20px;">
    <form method="GET" action="<?= base_url('reports') ?>" style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end;">
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="from_date" value="<?= $fromDate ?>" class="form-input">
        </div>
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="to_date" value="<?= $toDate ?>" class="form-input">
        </div>
        <button type="submit" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Terapkan
        </button>
    </form>
</div>

<!-- Summary Metric Cards -->
<div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <!-- Total Income -->
    <div class="card-stat">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
            <span class="text-footnote" style="color: var(--text-secondary);">Total Pemasukan</span>
            <?php if ($incomeChange != 0): ?>
            <span class="badge <?= $incomeChange >= 0 ? 'badge-income' : 'badge-expense' ?>">
                <?= formatTrend($incomeChange) ?>
            </span>
            <?php endif; ?>
        </div>
        <div style="font-size: 28px; font-weight: 700; color: var(--accent);">
            <?= formatCurrency($totalIncome) ?>
        </div>
    </div>
    
    <!-- Total Expense -->
    <div class="card-stat">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
            <span class="text-footnote" style="color: var(--text-secondary);">Total Pengeluaran</span>
            <?php if ($expenseChange != 0): ?>
            <span class="badge <?= $expenseChange >= 0 ? 'badge-income' : 'badge-expense' ?>">
                <?= formatTrend($expenseChange) ?>
            </span>
            <?php endif; ?>
        </div>
        <div style="font-size: 28px; font-weight: 700; color: #FF3B30;">
            <?= formatCurrency($totalExpense) ?>
        </div>
    </div>
    
    <!-- Net Balance -->
    <div class="card-stat">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
            <span class="text-footnote" style="color: var(--text-secondary);">Saldo Bersih</span>
            <?php if ($savingsRate != 0): ?>
            <span class="badge <?= $savingsRate >= 0 ? 'badge-income' : 'badge-expense' ?>">
                <?= number_format($savingsRate, 1) ?>%
            </span>
            <?php endif; ?>
        </div>
        <div style="font-size: 28px; font-weight: 700; color: <?= $netBalance >= 0 ? 'var(--blue)' : '#FF3B30' ?>;">
            <?= formatCurrency($netBalance) ?>
        </div>
    </div>
</div>

<p style="text-align: center; padding: 40px; color: #666;">
    File sedang dalam perbaikan. Silakan tunggu beberapa saat...
</p>

<script>
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

<!-- Charts Section -->
<div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px; margin-bottom: 24px;">
    <!-- Monthly Trend Chart -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--bg-tertiary);">
            <h3 style="font-size: 17px; font-weight: 600;">Tren Bulanan</h3>
            <p style="font-size: 12px; color: var(--text-tertiary); margin-top: 4px;">Perbandingan pemasukan dan pengeluaran per bulan</p>
        </div>
        <div style="padding: 20px 24px;">
            <canvas id="monthlyChart" height="250"></canvas>
        </div>
    </div>
    
    <!-- Income Pie Chart -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--bg-tertiary);">
            <h3 style="font-size: 17px; font-weight: 600;">Distribusi Pemasukan</h3>
            <p style="font-size: 12px; color: var(--text-tertiary); margin-top: 4px;">Komposisi pemasukan per kategori</p>
        </div>
        <div style="padding: 20px 24px;">
            <?php if (empty($incomeByCategory)): ?>
            <p style="text-align: center; color: var(--text-tertiary); padding: 24px;">Tidak ada data</p>
            <?php else: ?>
            <canvas id="incomePieChart" height="250"></canvas>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Second Row Charts -->
<div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px; margin-bottom: 24px;">
    <!-- Expense Doughnut Chart -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--bg-tertiary);">
            <h3 style="font-size: 17px; font-weight: 600;">Distribusi Pengeluaran</h3>
            <p style="font-size: 12px; color: var(--text-tertiary); margin-top: 4px;">Komposisi pengeluaran per kategori</p>
        </div>
        <div style="padding: 20px 24px;">
            <?php if (empty($expenseByCategory)): ?>
            <p style="text-align: center; color: var(--text-tertiary); padding: 24px;">Tidak ada data</p>
            <?php else: ?>
            <canvas id="expenseDoughnutChart" height="250"></canvas>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Budget vs Realization Chart -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--bg-tertiary);">
            <h3 style="font-size: 17px; font-weight: 600;">Anggaran vs Realisasi</h3>
            <p style="font-size: 12px; color: var(--text-tertiary); margin-top: 4px;">Perbandingan anggaran dengan realisasi</p>
        </div>
        <div style="padding: 20px 24px;">
            <?php if (empty($budgets)): ?>
            <p style="text-align: center; color: var(--text-tertiary); padding: 24px;">Tidak ada data anggaran</p>
            <?php else: ?>
            <canvas id="budgetChart" height="250"></canvas>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Third Row Charts -->
<div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px; margin-bottom: 24px;">
    <!-- Goals Progress Chart -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--bg-tertiary);">
            <h3 style="font-size: 17px; font-weight: 600;">Progress Target</h3>
            <p style="font-size: 12px; color: var(--text-tertiary); margin-top: 4px;">Pencapaian target/goals</p>
        </div>
        <div style="padding: 20px 24px;">
            <?php if (empty($goals)): ?>
            <p style="text-align: center; color: var(--text-tertiary); padding: 24px;">Tidak ada data target</p>
            <?php else: ?>
            <canvas id="goalsChart" height="250"></canvas>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Accounts Balance Chart -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--bg-tertiary);">
            <h3 style="font-size: 17px; font-weight: 600;">Distribusi Saldo Akun</h3>
            <p style="font-size: 12px; color: var(--text-tertiary); margin-top: 4px;">Komposisi saldo per akun</p>
        </div>
        <div style="padding: 20px 24px;">
            <?php if (empty($accounts)): ?>
            <p style="text-align: center; color: var(--text-tertiary); padding: 24px;">Tidak ada data akun</p>
            <?php else: ?>
            <canvas id="accountsChart" height="250"></canvas>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Income by Category (Detailed List) -->
<div class="card" style="padding: 0; overflow: hidden; margin-bottom: 24px;">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--bg-tertiary); background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
        <h3 style="font-size: 17px; font-weight: 600; color: white;">Detail Pemasukan per Kategori</h3>
        <p style="font-size: 12px; color: rgba(255,255,255,0.9); margin-top: 4px;">Rincian sumber pemasukan</p>
    </div>
    <div style="padding: 20px 24px;">
        <?php if (empty($incomeByCategory)): ?>
        <div style="text-align: center; padding: 40px 20px;">
            <div style="width: 80px; height: 80px; margin: 0 auto 16px; background: #f0fdf4; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i data-lucide="trending-up" style="width: 40px; height: 40px; color: #10B981;"></i>
            </div>
            <p style="color: var(--text-tertiary); font-size: 14px;">Tidak ada data pemasukan</p>
        </div>
        <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px;">
            <?php 
            $totalIncomeCat = array_sum(array_column($incomeByCategory, 'total'));
            foreach ($incomeByCategory as $index => $cat): 
                $percentage = $totalIncomeCat > 0 ? ($cat['total'] / $totalIncomeCat) * 100 : 0;
                $colors = ['#10B981', '#059669', '#34D399', '#6EE7B7', '#A7F3D0'];
                $color = $colors[$index % count($colors)];
            ?>
            <div style="padding: 20px; background: white; border-radius: 12px; border: 1px solid #e5e7eb; transition: all 0.2s;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <div style="width: 48px; height: 48px; background: <?= $color ?>20; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i data-lucide="arrow-down-circle" style="width: 24px; height: 24px; color: <?= $color ?>;"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 14px; font-weight: 600; color: #1f2937; margin-bottom: 2px;"><?= htmlspecialchars($cat['name']) ?></div>
                        <div style="font-size: 12px; color: #6b7280;"><?= number_format($percentage, 1) ?>% dari total</div>
                    </div>
                </div>
                <div style="margin-bottom: 12px;">
                    <div style="height: 8px; background: #f3f4f6; border-radius: 4px; overflow: hidden;">
                        <div style="height: 100%; background: <?= $color ?>; width: <?= $percentage ?>%; transition: width 0.3s;"></div>
                    </div>
                </div>
                <div style="font-size: 20px; font-weight: 700; color: <?= $color ?>;">
                    <?= formatCurrency($cat['total']) ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Expense by Category -->
<div class="card" style="padding: 0; overflow: hidden; margin-bottom: 24px;">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--bg-tertiary); background: linear-gradient(135deg, #FF3B30 0%, #DC2626 100%);">
        <h3 style="font-size: 17px; font-weight: 600; color: white;">Pengeluaran per Kategori</h3>
        <p style="font-size: 12px; color: rgba(255,255,255,0.9); margin-top: 4px;">Rincian pos pengeluaran</p>
    </div>
    <div style="padding: 20px 24px;">
        <?php if (empty($expenseByCategory)): ?>
        <div style="text-align: center; padding: 40px 20px;">
            <div style="width: 80px; height: 80px; margin: 0 auto 16px; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i data-lucide="trending-down" style="width: 40px; height: 40px; color: #FF3B30;"></i>
            </div>
            <p style="color: var(--text-tertiary); font-size: 14px;">Tidak ada data pengeluaran</p>
        </div>
        <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px;">
            <?php 
            $totalExpenseCat = array_sum(array_column($expenseByCategory, 'total'));
            foreach ($expenseByCategory as $index => $cat): 
                $percentage = $totalExpenseCat > 0 ? ($cat['total'] / $totalExpenseCat) * 100 : 0;
                $colors = ['#FF3B30', '#DC2626', '#EF4444', '#F87171', '#FCA5A5'];
                $color = $colors[$index % count($colors)];
            ?>
            <div style="padding: 20px; background: white; border-radius: 12px; border: 1px solid #e5e7eb; transition: all 0.2s;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <div style="width: 48px; height: 48px; background: <?= $color ?>20; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i data-lucide="arrow-up-circle" style="width: 24px; height: 24px; color: <?= $color ?>;"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 14px; font-weight: 600; color: #1f2937; margin-bottom: 2px;"><?= htmlspecialchars($cat['name']) ?></div>
                        <div style="font-size: 12px; color: #6b7280;"><?= number_format($percentage, 1) ?>% dari total</div>
                    </div>
                </div>
                <div style="margin-bottom: 12px;">
                    <div style="height: 8px; background: #f3f4f6; border-radius: 4px; overflow: hidden;">
                        <div style="height: 100%; background: <?= $color ?>; width: <?= $percentage ?>%; transition: width 0.3s;"></div>
                    </div>
                </div>
                <div style="font-size: 20px; font-weight: 700; color: <?= $color ?>;">
                    <?= formatCurrency($cat['total']) ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Budgets Section -->
<div class="card" style="padding: 0; overflow: hidden; margin-bottom: 24px;">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--bg-tertiary); background: linear-gradient(135deg, #7c3aed 0%, #6366f1 100%);">
        <h3 style="font-size: 17px; font-weight: 600; color: white;">Anggaran (Budgets)</h3>
        <p style="font-size: 12px; color: rgba(255,255,255,0.9); margin-top: 4px;">Monitoring realisasi anggaran per kategori</p>
    </div>
    <div style="padding: 20px 24px;">
        <?php if (empty($budgets)): ?>
        <div style="text-align: center; padding: 40px 20px;">
            <div style="width: 80px; height: 80px; margin: 0 auto 16px; background: #f5f3ff; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i data-lucide="calculator" style="width: 40px; height: 40px; color: #7c3aed;"></i>
            </div>
            <p style="color: var(--text-tertiary); font-size: 14px;">Tidak ada data anggaran</p>
        </div>
        <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px;">
            <?php foreach ($budgets as $budget): 
                $realization = floatval($budget['realization']);
                $amount = floatval($budget['amount']);
                $percentage = $amount > 0 ? ($realization / $amount) * 100 : 0;
                $remaining = $amount - $realization;
                $statusColor = $percentage > 100 ? '#FF3B30' : ($percentage > 80 ? '#FF9500' : '#10B981');
                $statusBg = $percentage > 100 ? '#fef2f2' : ($percentage > 80 ? '#fff7ed' : '#f0fdf4');
                $statusText = $percentage > 100 ? 'Over Budget' : ($percentage > 80 ? 'Mendekati Limit' : 'Aman');
            ?>
            <div style="padding: 20px; background: white; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.2s;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <div style="width: 48px; height: 48px; background: <?= $statusBg ?>; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i data-lucide="pie-chart" style="width: 24px; height: 24px; color: <?= $statusColor ?>;"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 15px; font-weight: 600; color: #1f2937; margin-bottom: 2px;"><?= htmlspecialchars($budget['category_name']) ?></div>
                        <div style="font-size: 12px; color: #6b7280;">
                            <?= $budget['month'] ? getMonthName($budget['month']) . ' ' : '' ?><?= $budget['year'] ?>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 20px; font-weight: 700; color: <?= $statusColor ?>;"><?= number_format($percentage, 0) ?>%</div>
                        <div style="font-size: 10px; color: <?= $statusColor ?>; font-weight: 600; text-transform: uppercase;"><?= $statusText ?></div>
                    </div>
                </div>
                
                <div style="margin-bottom: 16px;">
                    <div style="height: 8px; background: #f3f4f6; border-radius: 4px; overflow: hidden; margin-bottom: 12px;">
                        <div style="height: 100%; background: <?= $statusColor ?>; width: <?= min($percentage, 100) ?>%; transition: width 0.3s;"></div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <div>
                            <div style="font-size: 11px; color: #6b7280; margin-bottom: 2px;">Realisasi</div>
                            <div style="font-size: 14px; font-weight: 600; color: #1f2937;"><?= formatCurrency($realization) ?></div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 11px; color: #6b7280; margin-bottom: 2px;">Anggaran</div>
                            <div style="font-size: 14px; font-weight: 600; color: #1f2937;"><?= formatCurrency($amount) ?></div>
                        </div>
                    </div>
                </div>
                
                <div style="padding-top: 12px; border-top: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 12px; color: #6b7280;">Sisa Anggaran</span>
                    <span style="font-size: 15px; font-weight: 700; color: <?= $remaining >= 0 ? '#10B981' : '#FF3B30' ?>;">
                        <?= $remaining >= 0 ? '+' : '-' ?><?= formatCurrency(abs($remaining)) ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Goals Section -->
<div class="card" style="padding: 0; overflow: hidden; margin-bottom: 24px;">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--bg-tertiary); background: linear-gradient(135deg, #8B5CF6 0%, #7c3aed 100%);">
        <h3 style="font-size: 17px; font-weight: 600; color: white;">Target (Goals)</h3>
        <p style="font-size: 12px; color: rgba(255,255,255,0.9); margin-top: 4px;">Progress pencapaian target keuangan</p>
    </div>
    <div style="padding: 20px 24px;">
        <?php if (empty($goals)): ?>
        <div style="text-align: center; padding: 40px 20px;">
            <div style="width: 80px; height: 80px; margin: 0 auto 16px; background: #f5f3ff; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i data-lucide="target" style="width: 40px; height: 40px; color: #8B5CF6;"></i>
            </div>
            <p style="color: var(--text-tertiary); font-size: 14px;">Tidak ada data target</p>
        </div>
        <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px;">
            <?php foreach ($goals as $goal): 
                $current = floatval($goal['current_amount']);
                $target = floatval($goal['target_amount']);
                $percentage = $target > 0 ? ($current / $target) * 100 : 0;
                $remaining = $target - $current;
                $isCompleted = $goal['is_completed'];
                $statusColor = $isCompleted ? '#10B981' : '#8B5CF6';
                $statusBg = $isCompleted ? '#f0fdf4' : '#f5f3ff';
            ?>
            <div style="padding: 20px; background: white; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.2s;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <div style="width: 48px; height: 48px; background: <?= $statusBg ?>; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <?php if ($isCompleted): ?>
                        <i data-lucide="check-circle" style="width: 24px; height: 24px; color: <?= $statusColor ?>;"></i>
                        <?php else: ?>
                        <i data-lucide="target" style="width: 24px; height: 24px; color: <?= $statusColor ?>;"></i>
                        <?php endif; ?>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 15px; font-weight: 600; color: #1f2937; margin-bottom: 2px;"><?= htmlspecialchars($goal['name']) ?></div>
                        <?php if ($goal['target_date']): ?>
                        <div style="font-size: 12px; color: #6b7280; display: flex; align-items: center; gap: 4px;">
                            <i data-lucide="calendar" style="width: 12px; height: 12px;"></i>
                            <?= formatDate($goal['target_date']) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($isCompleted): ?>
                    <div style="padding: 6px 12px; background: #10B98120; border-radius: 8px;">
                        <div style="font-size: 10px; color: #10B981; font-weight: 600; text-transform: uppercase;">Selesai</div>
                    </div>
                    <?php else: ?>
                    <div style="text-align: right;">
                        <div style="font-size: 20px; font-weight: 700; color: <?= $statusColor ?>;"><?= number_format($percentage, 0) ?>%</div>
                        <div style="font-size: 10px; color: #6b7280; font-weight: 600; text-transform: uppercase;">Progress</div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div style="margin-bottom: 16px;">
                    <div style="height: 8px; background: #f3f4f6; border-radius: 4px; overflow: hidden; margin-bottom: 12px;">
                        <div style="height: 100%; background: <?= $statusColor ?>; width: <?= min($percentage, 100) ?>%; transition: width 0.3s;"></div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <div>
                            <div style="font-size: 11px; color: #6b7280; margin-bottom: 2px;">Terkumpul</div>
                            <div style="font-size: 14px; font-weight: 600; color: #1f2937;"><?= formatCurrency($current) ?></div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 11px; color: #6b7280; margin-bottom: 2px;">Target</div>
                            <div style="font-size: 14px; font-weight: 600; color: #1f2937;"><?= formatCurrency($target) ?></div>
                        </div>
                    </div>
                </div>
                
                <?php if (!$isCompleted): ?>
                <div style="padding-top: 12px; border-top: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 12px; color: #6b7280;">Sisa Target</span>
                    <span style="font-size: 15px; font-weight: 700; color: <?= $statusColor ?>;">
                        <?= formatCurrency($remaining) ?>
                    </span>
                </div>
                <?php else: ?>
                <div style="padding-top: 12px; border-top: 1px solid #f3f4f6; text-align: center;">
                    <span style="font-size: 12px; color: #10B981; font-weight: 600;">🎉 Target tercapai!</span>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Accounts Section -->
<div class="card" style="padding: 0; overflow: hidden; margin-bottom: 24px;">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--bg-tertiary); background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);">
        <h3 style="font-size: 17px; font-weight: 600; color: white;">Arus Kas (Accounts)</h3>
        <p style="font-size: 12px; color: rgba(255,255,255,0.9); margin-top: 4px;">Distribusi saldo per akun keuangan</p>
    </div>
    <div style="padding: 20px 24px;">
        <?php if (empty($accounts)): ?>
        <div style="text-align: center; padding: 40px 20px;">
            <div style="width: 80px; height: 80px; margin: 0 auto 16px; background: #eff6ff; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i data-lucide="wallet" style="width: 40px; height: 40px; color: #3B82F6;"></i>
            </div>
            <p style="color: var(--text-tertiary); font-size: 14px;">Tidak ada data akun</p>
        </div>
        <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; margin-bottom: 20px;">
            <?php 
            $totalBalance = 0;
            foreach ($accounts as $acc) {
                $totalBalance += floatval($acc['balance']);
            }
            foreach ($accounts as $acc): 
                $balance = floatval($acc['balance']);
                $typeIcon = $acc['type'] === 'cash' ? 'wallet' : ($acc['type'] === 'bank' ? 'landmark' : 'smartphone');
                $typeColor = $acc['type'] === 'cash' ? '#10B981' : ($acc['type'] === 'bank' ? '#3B82F6' : '#8B5CF6');
                $typeBg = $acc['type'] === 'cash' ? '#f0fdf4' : ($acc['type'] === 'bank' ? '#eff6ff' : '#f5f3ff');
                $typeName = $acc['type'] === 'cash' ? 'Tunai' : ($acc['type'] === 'bank' ? 'Bank' : 'E-Wallet');
            ?>
            <div style="padding: 20px; background: white; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.2s;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <div style="width: 48px; height: 48px; background: <?= $typeBg ?>; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i data-lucide="<?= $typeIcon ?>" style="width: 24px; height: 24px; color: <?= $typeColor ?>;"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 15px; font-weight: 600; color: #1f2937; margin-bottom: 2px;"><?= htmlspecialchars($acc['name']) ?></div>
                        <div style="font-size: 12px; color: #6b7280; display: flex; align-items: center; gap: 4px;">
                            <span style="width: 6px; height: 6px; background: <?= $typeColor ?>; border-radius: 50%; display: inline-block;"></span>
                            <?= $typeName ?>
                        </div>
                    </div>
                </div>
                
                <div style="padding-top: 16px; border-top: 1px solid #f3f4f6;">
                    <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Saldo Saat Ini</div>
                    <div style="font-size: 22px; font-weight: 700; color: <?= $balance >= 0 ? '#10B981' : '#FF3B30' ?>;">
                        <?= formatCurrency($balance) ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Total Balance Card -->
        <div style="padding: 24px; background: linear-gradient(135deg, #7c3aed 0%, #6366f1 100%); border-radius: 16px; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 13px; color: rgba(255,255,255,0.8); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Total Saldo Semua Akun</div>
                    <div style="font-size: 32px; font-weight: 700; color: white;"><?= formatCurrency($totalBalance) ?></div>
                </div>
                <div style="width: 64px; height: 64px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="trending-up" style="width: 32px; height: 32px; color: white;"></i>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- JavaScript for Charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Date range quick filters
    function setDateRange(range) {
        const today = new Date();
        let fromDate, toDate;
        
        if (range === 'this_month') {
            fromDate = new Date(today.getFullYear(), today.getMonth(), 1);
            toDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        } else if (range === 'this_year') {
            fromDate = new Date(today.getFullYear(), 0, 1);
            toDate = new Date(today.getFullYear(), 11, 31);
        }
        
        document.querySelector('input[name="from_date"]').value = fromDate.toISOString().split('T')[0];
        document.querySelector('input[name="to_date"]').value = toDate.toISOString().split('T')[0];
        document.querySelector('form').submit();
    }
    
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    // Chart.js default config
    Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#666';
    
    // Color palette
    const colors = {
        primary: '#7c3aed',
        success: '#10B981',
        danger: '#FF3B30',
        warning: '#FF9500',
        info: '#3B82F6',
        purple: '#8B5CF6',
        pink: '#EC4899',
        indigo: '#6366F1',
        teal: '#14B8A6',
        orange: '#F97316'
    };
    
    const chartColors = [
        colors.success, colors.danger, colors.info, colors.warning, 
        colors.purple, colors.pink, colors.indigo, colors.teal, colors.orange
    ];
    
    // 1. Monthly Trend Chart (Bar)
    const monthlyCtx = document.getElementById('monthlyChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($monthlyData['labels']) ?>,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: <?= json_encode($monthlyData['income']) ?>,
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderRadius: 8,
                        borderSkipped: false,
                    },
                    {
                        label: 'Pengeluaran',
                        data: <?= json_encode($monthlyData['expense']) ?>,
                        backgroundColor: 'rgba(255, 59, 48, 0.8)',
                        borderRadius: 8,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 15,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    },
                    y: {
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000).toFixed(0) + 'jt';
                            },
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    }
    
    // 2. Income Pie Chart
    const incomePieCtx = document.getElementById('incomePieChart');
    if (incomePieCtx) {
        <?php 
        $incomeLabels = array_column($incomeByCategory, 'name');
        $incomeData = array_column($incomeByCategory, 'total');
        ?>
        new Chart(incomePieCtx, {
            type: 'pie',
            data: {
                labels: <?= json_encode($incomeLabels) ?>,
                datasets: [{
                    data: <?= json_encode($incomeData) ?>,
                    backgroundColor: chartColors,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 15,
                            font: { size: 12 },
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map((label, i) => {
                                        const value = data.datasets[0].data[i];
                                        const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return {
                                            text: label + ' (' + percentage + '%)',
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            hidden: false,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return context.label + ': Rp ' + value.toLocaleString('id-ID') + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
    
    // 3. Expense Doughnut Chart
    const expenseDoughnutCtx = document.getElementById('expenseDoughnutChart');
    if (expenseDoughnutCtx) {
        <?php 
        $expenseLabels = array_column($expenseByCategory, 'name');
        $expenseData = array_column($expenseByCategory, 'total');
        ?>
        new Chart(expenseDoughnutCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($expenseLabels) ?>,
                datasets: [{
                    data: <?= json_encode($expenseData) ?>,
                    backgroundColor: chartColors,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 15,
                            font: { size: 12 },
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map((label, i) => {
                                        const value = data.datasets[0].data[i];
                                        const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return {
                                            text: label + ' (' + percentage + '%)',
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            hidden: false,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return context.label + ': Rp ' + value.toLocaleString('id-ID') + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
    
    // 4. Budget vs Realization Chart
    const budgetCtx = document.getElementById('budgetChart');
    if (budgetCtx) {
        <?php 
        $budgetLabels = array_column($budgets, 'category_name');
        $budgetAmounts = array_map(function($b) { return floatval($b['amount']); }, $budgets);
        $budgetRealizations = array_map(function($b) { return floatval($b['realization']); }, $budgets);
        ?>
        new Chart(budgetCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($budgetLabels) ?>,
                datasets: [
                    {
                        label: 'Anggaran',
                        data: <?= json_encode($budgetAmounts) ?>,
                        backgroundColor: 'rgba(124, 58, 237, 0.6)',
                        borderRadius: 6,
                    },
                    {
                        label: 'Realisasi',
                        data: <?= json_encode($budgetRealizations) ?>,
                        backgroundColor: 'rgba(255, 59, 48, 0.8)',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000).toFixed(0) + 'jt';
                            }
                        }
                    }
                }
            }
        });
    }
    
    // 5. Goals Progress Chart (Horizontal Bar)
    const goalsCtx = document.getElementById('goalsChart');
    if (goalsCtx) {
        <?php 
        $goalLabels = array_column($goals, 'name');
        $goalTargets = array_map(function($g) { return floatval($g['target_amount']); }, $goals);
        $goalCurrents = array_map(function($g) { return floatval($g['current_amount']); }, $goals);
        ?>
        new Chart(goalsCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($goalLabels) ?>,
                datasets: [
                    {
                        label: 'Target',
                        data: <?= json_encode($goalTargets) ?>,
                        backgroundColor: 'rgba(124, 58, 237, 0.3)',
                        borderRadius: 6,
                    },
                    {
                        label: 'Terkumpul',
                        data: <?= json_encode($goalCurrents) ?>,
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.parsed.x.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000).toFixed(0) + 'jt';
                            }
                        }
                    },
                    y: {
                        grid: { display: false }
                    }
                }
            }
        });
    }
    
    // 6. Accounts Balance Chart (Polar Area)
    const accountsCtx = document.getElementById('accountsChart');
    if (accountsCtx) {
        <?php 
        $accountLabels = array_column($accounts, 'name');
        $accountBalances = array_map(function($a) { return floatval($a['balance']); }, $accounts);
        ?>
        new Chart(accountsCtx, {
            type: 'polarArea',
            data: {
                labels: <?= json_encode($accountLabels) ?>,
                datasets: [{
                    data: <?= json_encode($accountBalances) ?>,
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(139, 92, 246, 0.7)',
                        'rgba(236, 72, 153, 0.7)',
                        'rgba(249, 115, 22, 0.7)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 15,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return context.label + ': Rp ' + context.parsed.r.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    r: {
                        ticks: {
                            display: false
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                }
            }
        });
    }
</script>
