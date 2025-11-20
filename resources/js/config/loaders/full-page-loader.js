// config/loader/full-page-loader.js

import loaderManager from './loader-manager.js';

class FullPageLoader {
    constructor() {
        this.loaderElement = null;
        this.isVisible = false;
        this.fadeOutTimer = null;
        this.initialized = false;
    }
    
    createLoaderHTML() {
        return `
            <!-- Backdrop -->
            <div id="loader-backdrop" class="loader-backdrop"></div>
            
            <!-- Loader Container -->
            <div id="loader-container" class="loader-container bg-red-dark">
                <div class="loader-content">
                    <!-- Animated logo -->
                    <img 
                        src="/imagenes/delight/optimal-logo.png" 
                        alt="Loading" 
                        class="loader-logo"
                    />
                    
                    <!-- Pulsing glow effect -->
                    <div class="loader-glow"></div>
                </div>
                
                <!-- Loading text -->
                <div class="loader-text-container">
                    <p class="loader-text">Cargando</p>
                    <div class="loader-dots">
                        <span class="loader-dot"></span>
                        <span class="loader-dot delay-1"></span>
                        <span class="loader-dot delay-2"></span>
                    </div>
                </div>
            </div>
        `;
    }
    
    init() {
        if (this.initialized) return;
        
        // Create loader elements if they don't exist
        if (!this.loaderElement) {
            const container = document.createElement('div');
            container.id = 'full-page-loader';
            container.innerHTML = this.createLoaderHTML();
            container.style.display = 'none';
            document.body.appendChild(container);
            this.loaderElement = container;
        }
        
        // Subscribe to loader state changes
        loaderManager.subscribe((isLoading) => {
            if (isLoading) {
                this.show();
            } else {
                this.hide();
            }
        });
        
        this.initialized = true;
    }
    
    show() {
        if (!this.loaderElement) return;
        
        clearTimeout(this.fadeOutTimer);
        this.isVisible = true;
        
        this.loaderElement.style.display = 'block';

        console.log("loaderElement should be block now", this.loaderElement.style.display);
        
        // Force reflow for animation
        this.loaderElement.offsetHeight;
        
        const backdrop = this.loaderElement.querySelector('#loader-backdrop');
        const container = this.loaderElement.querySelector('#loader-container');
        
        if (backdrop) backdrop.classList.add('visible');
        // // if (backdrop) backdrop.style.display = 'block';
        if (container) container.classList.add('visible');
    }
    
    hide() {
        if (!this.loaderElement) return;
        
        const backdrop = this.loaderElement.querySelector('#loader-backdrop');
        const container = this.loaderElement.querySelector('#loader-container');
        
        if (backdrop) backdrop.classList.remove('visible');
        if (container) container.classList.remove('visible');
        
        // Keep visible for 600ms to allow fade-out animation
        this.fadeOutTimer = setTimeout(() => {
            if (this.loaderElement) {
                this.loaderElement.style.display = 'none';
            }
            this.isVisible = false;
        }, 600);
    }
}

// Create singleton instance
const fullPageLoader = new FullPageLoader();

export default fullPageLoader;