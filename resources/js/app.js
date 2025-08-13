// require('./bootstrap');

// require('./auth/logout');
// require('./carrito/carrito-store');

// resources/js/app.js
import './bootstrap';
console.log("App.js loaded"); // Should appear first

import './auth/logout';  // Changed from require() to import
import './carrito/carrito-store';