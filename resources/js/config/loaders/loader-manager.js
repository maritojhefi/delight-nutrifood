// config/loader/loader-manager.js

class LoaderManager {
    constructor() {
        this.isLoading = false;
        this.listeners = [];
    }
    
    setIsLoading(loading) {
        console.log("LoaderManager was set as loading with", loading);
        this.isLoading = loading;
        // Notificar a los listeners
        this.listeners.forEach(callback => callback(this.isLoading));
    }
    
    subscribe(callback) {
        this.listeners.push(callback);
        // Retornar
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