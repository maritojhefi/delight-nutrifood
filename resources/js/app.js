import './bootstrap';
import { createIcons, icons } from 'lucide';

import './auth/logout';
import './carrito/carrito-store';
import './ventas/venta-service'
import './saldo/saldo-service'
import './planes/planes-service'
import './carrito/carrito-service'
import loaderManager from './config/loaders/loader-manager.js';
import fullPageLoader from './config/loaders/full-page-loader.js';

// Inicializar el Full Page Loader cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        fullPageLoader.init();
    });
} else {
    fullPageLoader.init();
}

createIcons({icons});

// Reinicializacion global de iconos lucide
window.reinitializeLucideIcons = () => {
    createIcons({icons});
};

window.estadoValidacionCarrito = {
    productosLimitados: {}
};