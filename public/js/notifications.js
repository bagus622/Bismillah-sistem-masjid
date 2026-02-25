// Modern Toast Notification System
class ToastNotification {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // Create toast container if not exists
        if (!document.getElementById('toast-container')) {
            this.container = document.createElement('div');
            this.container.id = 'toast-container';
            this.container.className = 'fixed top-4 right-4 z-[9999] flex flex-col gap-3';
            document.body.appendChild(this.container);
        } else {
            this.container = document.getElementById('toast-container');
        }
    }

    show(message, type = 'info', duration = 5000) {
        const toast = document.createElement('div');
        const id = 'toast-' + Date.now();
        toast.id = id;

        // Icon and color based on type
        const config = {
            success: {
                icon: 'check-circle',
                bgColor: 'bg-green-50',
                borderColor: 'border-green-200',
                textColor: 'text-green-800',
                iconColor: 'text-green-600'
            },
            error: {
                icon: 'x-circle',
                bgColor: 'bg-red-50',
                borderColor: 'border-red-200',
                textColor: 'text-red-800',
                iconColor: 'text-red-600'
            },
            warning: {
                icon: 'alert-triangle',
                bgColor: 'bg-yellow-50',
                borderColor: 'border-yellow-200',
                textColor: 'text-yellow-800',
                iconColor: 'text-yellow-600'
            },
            info: {
                icon: 'info',
                bgColor: 'bg-blue-50',
                borderColor: 'border-blue-200',
                textColor: 'text-blue-800',
                iconColor: 'text-blue-600'
            }
        };

        const style = config[type] || config.info;

        toast.className = `${style.bgColor} ${style.borderColor} border rounded-xl p-4 shadow-lg min-w-[320px] max-w-md transform transition-all duration-300 ease-out translate-x-[400px] opacity-0`;
        toast.innerHTML = `
            <div class="flex items-start gap-3">
                <i data-lucide="${style.icon}" class="w-5 h-5 ${style.iconColor} flex-shrink-0 mt-0.5"></i>
                <div class="flex-1">
                    <p class="${style.textColor} text-sm font-medium">${message}</p>
                </div>
                <button onclick="window.toast.close('${id}')" class="${style.textColor} hover:opacity-70 transition-opacity">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        `;

        this.container.appendChild(toast);

        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-[400px]', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        }, 10);

        // Auto remove
        if (duration > 0) {
            setTimeout(() => {
                this.close(id);
            }, duration);
        }

        return id;
    }

    close(id) {
        const toast = document.getElementById(id);
        if (toast) {
            toast.classList.add('translate-x-[400px]', 'opacity-0');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    }

    success(message, duration = 5000) {
        return this.show(message, 'success', duration);
    }

    error(message, duration = 5000) {
        return this.show(message, 'error', duration);
    }

    warning(message, duration = 5000) {
        return this.show(message, 'warning', duration);
    }

    info(message, duration = 5000) {
        return this.show(message, 'info', duration);
    }
}

// Loading Overlay
class LoadingOverlay {
    constructor() {
        this.overlay = null;
        this.init();
    }

    init() {
        if (!document.getElementById('loading-overlay')) {
            this.overlay = document.createElement('div');
            this.overlay.id = 'loading-overlay';
            this.overlay.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm z-[9998] hidden items-center justify-center';
            this.overlay.innerHTML = `
                <div class="bg-white rounded-2xl p-8 shadow-2xl">
                    <div class="flex flex-col items-center gap-4">
                        <div class="relative w-16 h-16">
                            <div class="absolute inset-0 border-4 border-violet-200 rounded-full"></div>
                            <div class="absolute inset-0 border-4 border-violet-600 rounded-full border-t-transparent animate-spin"></div>
                        </div>
                        <p class="text-slate-700 font-medium" id="loading-text">Loading...</p>
                    </div>
                </div>
            `;
            document.body.appendChild(this.overlay);
        } else {
            this.overlay = document.getElementById('loading-overlay');
        }
    }

    show(message = 'Loading...') {
        const textElement = document.getElementById('loading-text');
        if (textElement) {
            textElement.textContent = message;
        }
        this.overlay.classList.remove('hidden');
        this.overlay.classList.add('flex');
    }

    hide() {
        this.overlay.classList.add('hidden');
        this.overlay.classList.remove('flex');
    }
}

// Initialize globally
window.toast = new ToastNotification();
window.loading = new LoadingOverlay();

// Auto-show flash messages on page load
document.addEventListener('DOMContentLoaded', function () {
    // Check for flash messages in old format and convert to toast
    const flashSuccess = document.querySelector('[data-flash-success]');
    const flashError = document.querySelector('[data-flash-error]');
    const flashWarning = document.querySelector('[data-flash-warning]');
    const flashInfo = document.querySelector('[data-flash-info]');

    if (flashSuccess) {
        window.toast.success(flashSuccess.dataset.flashSuccess);
        flashSuccess.remove();
    }
    if (flashError) {
        window.toast.error(flashError.dataset.flashError);
        flashError.remove();
    }
    if (flashWarning) {
        window.toast.warning(flashWarning.dataset.flashWarning);
        flashWarning.remove();
    }
    if (flashInfo) {
        window.toast.info(flashInfo.dataset.flashInfo);
        flashInfo.remove();
    }
});
