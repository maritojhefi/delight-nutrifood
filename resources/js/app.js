// require('./bootstrap');

// require('./auth/logout');
// require('./carrito/carrito-store');

// resources/js/app.js
import './bootstrap';
import { createIcons, icons } from 'lucide';

// import { Star, WheatOff } from 'lucide';

import './auth/logout';
import './carrito/carrito-store';

// const usedIcons = {
//     Star,
//     WheatOff
// };

createIcons({icons});
