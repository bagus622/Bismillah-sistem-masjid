/**
 * Currency Formatter for Indonesian Rupiah
 * Auto-format input dengan pemisah ribuan (.)
 * 
 * Usage:
 * <input type="text" class="currency-input" name="amount">
 * <script src="public/js/currency-formatter.js"></script>
 */

(function () {
    'use strict';

    /**
     * Format number dengan pemisah ribuan
     * @param {string|number} value - Nilai yang akan diformat
     * @returns {string} - Nilai terformat (contoh: 100.000)
     */
    function formatCurrency(value) {
        // Hapus semua karakter non-digit
        let number = value.toString().replace(/\D/g, '');

        // Jika kosong, return 0
        if (number === '') return '0';

        // Hapus leading zeros
        number = number.replace(/^0+/, '') || '0';

        // Format dengan pemisah ribuan (.)
        return number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    /**
     * Parse formatted currency ke number
     * @param {string} value - Nilai terformat (contoh: 100.000)
     * @returns {number} - Nilai numeric (contoh: 100000)
     */
    function parseCurrency(value) {
        return parseInt(value.replace(/\./g, '')) || 0;
    }

    /**
     * Initialize currency formatter pada input element
     * @param {HTMLInputElement} input - Input element
     */
    function initCurrencyInput(input) {
        // Set initial value jika ada
        if (input.value) {
            input.value = formatCurrency(input.value);
        }

        // Format saat user mengetik
        input.addEventListener('input', function (e) {
            const cursorPosition = e.target.selectionStart;
            const oldLength = e.target.value.length;
            const oldValue = e.target.value;

            // Format value
            const formatted = formatCurrency(e.target.value);
            e.target.value = formatted;

            // Adjust cursor position
            const newLength = formatted.length;
            const diff = newLength - oldLength;

            // Set cursor position (handle pemisah ribuan yang ditambahkan)
            if (diff > 0) {
                e.target.setSelectionRange(cursorPosition + diff, cursorPosition + diff);
            } else {
                e.target.setSelectionRange(cursorPosition, cursorPosition);
            }
        });

        // Format saat blur (kehilangan focus)
        input.addEventListener('blur', function (e) {
            if (e.target.value === '' || e.target.value === '0') {
                e.target.value = '0';
            } else {
                e.target.value = formatCurrency(e.target.value);
            }
        });

        // Handle paste
        input.addEventListener('paste', function (e) {
            e.preventDefault();
            const pastedText = (e.clipboardData || window.clipboardData).getData('text');
            const formatted = formatCurrency(pastedText);
            e.target.value = formatted;

            // Trigger input event
            e.target.dispatchEvent(new Event('input', { bubbles: true }));
        });

        // Prevent non-numeric input (kecuali backspace, delete, arrow keys, dll)
        input.addEventListener('keydown', function (e) {
            const allowedKeys = [
                'Backspace', 'Delete', 'Tab', 'Escape', 'Enter',
                'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown',
                'Home', 'End'
            ];

            // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            if ((e.ctrlKey || e.metaKey) && ['a', 'c', 'v', 'x'].includes(e.key.toLowerCase())) {
                return;
            }

            // Allow: allowed keys
            if (allowedKeys.includes(e.key)) {
                return;
            }

            // Prevent: non-numeric
            if (!/^\d$/.test(e.key)) {
                e.preventDefault();
            }
        });

        // Add hidden input untuk submit value tanpa format
        const form = input.closest('form');
        if (form && !input.dataset.hiddenCreated) {
            input.dataset.hiddenCreated = 'true';

            // Create hidden input
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = input.name;
            hiddenInput.id = input.id + '_raw';

            // Change original input name
            input.name = input.name + '_display';
            input.removeAttribute('required'); // Remove required dari display input

            // Add hidden input after original
            input.parentNode.insertBefore(hiddenInput, input.nextSibling);

            // Update hidden input saat form submit
            form.addEventListener('submit', function (e) {
                hiddenInput.value = parseCurrency(input.value);
            });

            // Update hidden input saat input berubah (untuk validation)
            input.addEventListener('input', function () {
                hiddenInput.value = parseCurrency(input.value);
            });

            // Set initial value
            hiddenInput.value = parseCurrency(input.value);
        }
    }

    /**
     * Initialize semua currency inputs di halaman
     */
    function initAllCurrencyInputs() {
        // Find all inputs dengan class 'currency-input' atau id yang mengandung 'amount', 'balance', 'target'
        const selectors = [
            '.currency-input',
            'input[name="amount"]',
            'input[name="initial_balance"]',
            'input[name="target_amount"]',
            'input[id="amount"]',
            'input[id="initial_balance"]',
            'input[id="target_amount"]'
        ];

        const inputs = document.querySelectorAll(selectors.join(', '));

        inputs.forEach(function (input) {
            // Skip jika sudah diinisialisasi
            if (input.dataset.currencyInitialized) return;

            input.dataset.currencyInitialized = 'true';
            initCurrencyInput(input);
        });
    }

    // Initialize saat DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAllCurrencyInputs);
    } else {
        initAllCurrencyInputs();
    }

    // Re-initialize jika ada dynamic content
    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (mutation.addedNodes.length) {
                initAllCurrencyInputs();
            }
        });
    });

    // Observe body untuk dynamic content
    if (document.body) {
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    // Export functions untuk digunakan di tempat lain
    window.CurrencyFormatter = {
        format: formatCurrency,
        parse: parseCurrency,
        init: initCurrencyInput,
        initAll: initAllCurrencyInputs
    };

})();
