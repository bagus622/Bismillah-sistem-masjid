/**
 * Page Loader Animation
 * Animasi loading yang muncul saat navigasi antar halaman
 */

class PageLoader {
    constructor() {
        this.createLoader();
        this.attachEventListeners();
    }

    createLoader() {
        const loaderHTML = `
            <div id="page-loader" class="page-loader">
                <div class="loader-bg-pattern"></div>
                <div class="loader-content">
                    <div class="mosque-container">
                        <div class="mosque-icon">
                            <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M32 8L28 16H36L32 8Z" fill="currentColor" class="dome"/>
                                <circle cx="32" cy="20" r="2" fill="currentColor" class="star"/>
                                <path d="M20 24C20 20 24 16 32 16C40 16 44 20 44 24V48H20V24Z" fill="currentColor" class="building"/>
                                <rect x="28" y="36" width="8" height="12" fill="white" class="door"/>
                                <circle cx="24" cy="28" r="2" fill="white" class="window"/>
                                <circle cx="40" cy="28" r="2" fill="white" class="window"/>
                            </svg>
                        </div>
                        <div class="mosque-shadow"></div>
                    </div>
                    <div class="loader-text">
                        <h3>Bismillah</h3>
                        <p class="loader-subtitle">Memuat halaman...</p>
                        <div class="loading-bar">
                            <div class="loading-bar-fill"></div>
                        </div>
                    </div>
                </div>
                <div class="loader-particles">
                    <span></span><span></span><span></span><span></span><span></span>
                    <span></span><span></span><span></span><span></span><span></span>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', loaderHTML);
        
        // Add styles
        const style = document.createElement('style');
        style.textContent = `
            .page-loader {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 99999;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.4s ease, visibility 0.4s ease;
                overflow: hidden;
            }
            
            .page-loader.active {
                opacity: 1;
                visibility: visible;
            }
            
            .loader-bg-pattern {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: 
                    radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 0%, transparent 50%),
                    radial-gradient(circle at 40% 20%, rgba(255,255,255,0.05) 0%, transparent 50%);
                animation: patternMove 20s ease-in-out infinite;
            }
            
            @keyframes patternMove {
                0%, 100% { transform: translate(0, 0) scale(1); }
                50% { transform: translate(20px, 20px) scale(1.1); }
            }
            
            .loader-content {
                position: relative;
                text-align: center;
                transform: scale(0.8);
                opacity: 0;
                animation: loaderFadeIn 0.6s ease forwards 0.2s;
                z-index: 2;
            }
            
            @keyframes loaderFadeIn {
                to {
                    transform: scale(1);
                    opacity: 1;
                }
            }
            
            .mosque-container {
                position: relative;
                width: 140px;
                height: 140px;
                margin: 0 auto 32px;
            }
            
            .mosque-icon {
                position: relative;
                width: 120px;
                height: 120px;
                margin: 0 auto;
                color: white;
                animation: mosqueFloat 3s ease-in-out infinite;
                z-index: 2;
            }
            
            @keyframes mosqueFloat {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                25% { transform: translateY(-8px) rotate(-2deg); }
                75% { transform: translateY(-8px) rotate(2deg); }
            }
            
            .mosque-icon svg {
                width: 100%;
                height: 100%;
                filter: drop-shadow(0 15px 35px rgba(0,0,0,0.4));
            }
            
            .mosque-icon .dome {
                animation: domeGlow 2s ease-in-out infinite;
                transform-origin: center;
            }
            
            @keyframes domeGlow {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; transform: scale(1.05); }
            }
            
            .mosque-icon .star {
                animation: starTwinkle 1.5s ease-in-out infinite;
                transform-origin: center;
            }
            
            @keyframes starTwinkle {
                0%, 100% { transform: scale(1) rotate(0deg); opacity: 1; }
                50% { transform: scale(1.5) rotate(180deg); opacity: 0.6; }
            }
            
            .mosque-icon .window {
                animation: windowBlink 2s ease-in-out infinite;
            }
            
            @keyframes windowBlink {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.3; }
            }
            
            .mosque-shadow {
                position: absolute;
                bottom: -10px;
                left: 50%;
                transform: translateX(-50%);
                width: 100px;
                height: 20px;
                background: radial-gradient(ellipse, rgba(0,0,0,0.3) 0%, transparent 70%);
                animation: shadowPulse 3s ease-in-out infinite;
            }
            
            @keyframes shadowPulse {
                0%, 100% { transform: translateX(-50%) scale(1); opacity: 0.3; }
                50% { transform: translateX(-50%) scale(0.9); opacity: 0.5; }
            }
            
            .loader-text h3 {
                font-size: 36px;
                font-weight: 700;
                color: white;
                margin: 0 0 8px 0;
                letter-spacing: 2px;
                text-shadow: 0 4px 15px rgba(0,0,0,0.3);
                animation: textGlow 2s ease-in-out infinite;
            }
            
            @keyframes textGlow {
                0%, 100% { text-shadow: 0 4px 15px rgba(0,0,0,0.3); }
                50% { text-shadow: 0 4px 25px rgba(255,255,255,0.4), 0 0 30px rgba(255,255,255,0.2); }
            }
            
            .loader-subtitle {
                font-size: 14px;
                color: rgba(255,255,255,0.8);
                margin: 0 0 20px 0;
                letter-spacing: 1px;
            }
            
            .loading-bar {
                width: 200px;
                height: 4px;
                background: rgba(255,255,255,0.2);
                border-radius: 2px;
                margin: 0 auto;
                overflow: hidden;
                box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            }
            
            .loading-bar-fill {
                height: 100%;
                background: linear-gradient(90deg, 
                    rgba(255,255,255,0.8) 0%, 
                    rgba(255,255,255,1) 50%, 
                    rgba(255,255,255,0.8) 100%);
                border-radius: 2px;
                animation: barProgress 1.5s ease-in-out infinite;
                box-shadow: 0 0 10px rgba(255,255,255,0.5);
            }
            
            @keyframes barProgress {
                0% { width: 0%; transform: translateX(0); }
                50% { width: 70%; }
                100% { width: 100%; transform: translateX(0); }
            }
            
            .loader-particles {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                pointer-events: none;
                z-index: 1;
            }
            
            .loader-particles span {
                position: absolute;
                width: 4px;
                height: 4px;
                background: white;
                border-radius: 50%;
                opacity: 0;
                animation: particleFloat 4s ease-in-out infinite;
                box-shadow: 0 0 10px rgba(255,255,255,0.8);
            }
            
            .loader-particles span:nth-child(1) { left: 10%; animation-delay: 0s; }
            .loader-particles span:nth-child(2) { left: 20%; animation-delay: 0.5s; }
            .loader-particles span:nth-child(3) { left: 30%; animation-delay: 1s; }
            .loader-particles span:nth-child(4) { left: 40%; animation-delay: 1.5s; }
            .loader-particles span:nth-child(5) { left: 50%; animation-delay: 2s; }
            .loader-particles span:nth-child(6) { left: 60%; animation-delay: 2.5s; }
            .loader-particles span:nth-child(7) { left: 70%; animation-delay: 3s; }
            .loader-particles span:nth-child(8) { left: 80%; animation-delay: 3.5s; }
            .loader-particles span:nth-child(9) { left: 90%; animation-delay: 4s; }
            .loader-particles span:nth-child(10) { left: 95%; animation-delay: 4.5s; }
            
            @keyframes particleFloat {
                0% {
                    bottom: 0;
                    opacity: 0;
                    transform: translateY(0) scale(1);
                }
                10% {
                    opacity: 1;
                }
                90% {
                    opacity: 1;
                }
                100% {
                    bottom: 100%;
                    opacity: 0;
                    transform: translateY(-20px) scale(0.5);
                }
            }
            
            /* Fade out animation */
            .page-loader.fade-out {
                animation: fadeOut 0.6s ease forwards;
            }
            
            @keyframes fadeOut {
                to {
                    opacity: 0;
                    visibility: hidden;
                    transform: scale(1.1);
                }
            }
        `;
        document.head.appendChild(style);
    }

    show() {
        const loader = document.getElementById('page-loader');
        if (loader) {
            loader.classList.add('active');
            loader.classList.remove('fade-out');
        }
    }

    hide() {
        const loader = document.getElementById('page-loader');
        if (loader) {
            loader.classList.add('fade-out');
            setTimeout(() => {
                loader.classList.remove('active', 'fade-out');
            }, 600);
        }
    }

    attachEventListeners() {
        // Intercept navigation links
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href]');
            
            if (link && !link.hasAttribute('target') && !link.hasAttribute('download')) {
                const href = link.getAttribute('href');
                
                // Check if it's an internal link and should show loader
                if (href && !href.startsWith('#') && !href.startsWith('javascript:')) {
                    // Check if it's a navigation to login or other pages
                    if (href.includes('login') || href.includes('register') || href.includes('home')) {
                        e.preventDefault();
                        this.show();
                        
                        // Navigate after animation starts
                        setTimeout(() => {
                            window.location.href = href;
                        }, 400);
                    }
                }
            }
        });

        // Hide loader when page loads
        window.addEventListener('load', () => {
            this.hide();
        });

        // Show loader on page unload
        window.addEventListener('beforeunload', () => {
            this.show();
        });
    }
}

// Initialize loader when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new PageLoader();
    });
} else {
    new PageLoader();
}
