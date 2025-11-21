// config/loader/full-page-loader.js
import loaderManager from "./loader-manager.js";
const LOGO_SMALL_URL = window.AppConfig.logoSmallUrl;

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
            <div id="loader-backdrop" class="loader-backdrop" style="
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background-color: rgba(0, 0, 0, 0.4);
                backdrop-filter: blur(4px);
                -webkit-backdrop-filter: blur(4px);
                z-index: 9998;
                opacity: 0;
                transition: opacity 600ms ease-in-out;
            "></div>

            <!-- Loader Container -->
            <div id="loader-container" class="loader-container" style="
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                opacity: 0;
                transform: scale(0.95);
                transition: opacity 600ms ease-in-out, transform 600ms ease-in-out;
            ">
                <div class="loader-content">
                    <!-- Animated logo -->
                    <img
                        src="${LOGO_SMALL_URL}"
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

        // Suscribir a cambios de estado del loader
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
        // Retirar loader existente de estar presente
        if (this.loaderElement) {
            this.loaderElement.remove();
        }

        clearTimeout(this.fadeOutTimer);
        this.isVisible = true;

        // Crear un elemento loader nuevo
        const container = document.createElement("div");
        container.id = "full-page-loader";
        container.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 99999;
            pointer-events: all;
        `;
        container.innerHTML = this.createLoaderHTML();

        // Incrustar al final del body
        document.body.appendChild(container);
        this.loaderElement = container;

        const backdrop = container.querySelector("#loader-backdrop");
        const loaderContainer = container.querySelector("#loader-container");

        // Forzar reflow para reiniciar animaciones
        container.offsetHeight;

        // Animar entrada con doble requestAnimationFrame
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                if (backdrop) backdrop.style.opacity = "1";
                if (loaderContainer) {
                    loaderContainer.style.opacity = "1";
                    loaderContainer.style.transform = "scale(1)";
                }
            });
        });
    }

    hide() {
        if (!this.loaderElement) return;

        const backdrop = this.loaderElement.querySelector("#loader-backdrop");
        const container = this.loaderElement.querySelector("#loader-container");

        // Desvanecer el loader
        if (backdrop) backdrop.style.opacity = "0";
        if (container) {
            container.style.opacity = "0";
            container.style.transform = "scale(0.95)";
        }

        // Retirar del DOM al finalizar la animación
        this.fadeOutTimer = setTimeout(() => {
            if (this.loaderElement) {
                this.loaderElement.remove();
                this.loaderElement = null;
            }
            this.isVisible = false;
        }, 600);
    }
}

const fullPageLoader = new FullPageLoader();

export default fullPageLoader;
