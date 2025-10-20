import './bootstrap';
import { createIcons, icons } from 'lucide';

// import { Star, WheatOff } from 'lucide';

import './auth/logout';
import './carrito/carrito-store';
import './ventas/venta-service'
import './saldo/saldo-service'


createIcons({icons});

// Reinicializacion global de iconos lucide
window.reinitializeLucideIcons = () => {
    createIcons({icons});
};

window.estadoValidacionCarrito = {
    productosLimitados: {}
};