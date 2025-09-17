import './bootstrap';
import { createIcons, icons } from 'lucide';

// import { Star, WheatOff } from 'lucide';

import './auth/logout';
import './carrito/carrito-store';
import './ventas/venta-service'


createIcons({icons});

// window.lucide = { createIcons };
window.reinitializeLucideIcons = () => {
    createIcons({icons});
};