// config/loader/loader-manager.js

class LoaderManager {
    constructor() {
        this.isLoading = false;
        this.listeners = [];
    }
    
    setIsLoading(loading) {
        console.log("LoaderManager was set as loading");
        this.isLoading = loading;
        // Notify all listeners
        this.listeners.forEach(callback => callback(this.isLoading));
    }
    
    subscribe(callback) {
        this.listeners.push(callback);
        // Return unsubscribe function
        return () => {
            this.listeners = this.listeners.filter(cb => cb !== callback);
        };
    }
    
    getIsLoading() {
        return this.isLoading;
    }
}

// Create singleton instance
const loaderManager = new LoaderManager();

// Also attach to window for global access (useful for debugging)
window.LoaderManager = loaderManager;

export default loaderManager;