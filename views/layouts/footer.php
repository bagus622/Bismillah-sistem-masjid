            <!-- Footer -->
            <?php if (isLoggedIn()): ?>
            <footer class="mt-8 py-4 border-t border-slate-200">
                <div class="text-center text-slate-500">
                    <small>&copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</small>
                </div>
            </footer>
            <?php endif; ?>
        </main>
    </div>
    
    <!-- Re-initialize Lucide icons after content loads -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
        
        // Also reinitialize after Alpine.js updates
        document.addEventListener('alpine:initialized', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>
