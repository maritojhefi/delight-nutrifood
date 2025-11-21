// config/loader/loader-manager.js

class LoaderManager {
    constructor() {
        this.isLoading = false;
        this.listeners = [];
        this.safetyTimer = null; // ID del temporizador de seguridad
        this.MAX_LOAD_TIME = 10000; // 10 segundos
    }
    
    setIsLoading(loading) {
        console.log("LoaderManager was set as loading with", loading);
        
        // 1. Limpiar temporizador existente
        if (this.safetyTimer) {
            clearTimeout(this.safetyTimer);
            this.safetyTimer = null;
        }

        this.isLoading = loading;

        // 2. Establecer el temporizador de seguridad si loading es true
        if (loading) {
            this.safetyTimer = setTimeout(() => {
                console.warn("⚠️ Loader safety timeout triggered (10s). Forcing removal.");
                this.setIsLoading(false); // Recursively call to turn off
            }, this.MAX_LOAD_TIME);
        }

        // Notificar a los listeners
        this.listeners.forEach(callback => callback(this.isLoading));
    }
    
    subscribe(callback) {
        this.listeners.push(callback);
        // Retornar función de limpieza
        return () => {
            this.listeners = this.listeners.filter(cb => cb !== callback);
        };
    }
    
    getIsLoading() {
        return this.isLoading;
    }
}

const loaderManager = new LoaderManager();

window.LoaderManager = loaderManager;

export default loaderManager;