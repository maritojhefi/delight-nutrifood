import { vaciarCarrito } from "../carrito/carrito-store";

export const setupLogoutHandlers = () => {
    document.addEventListener('click', function(e) {
        const logoutLink = e.target.closest('#nav-logout');
        
        if (logoutLink) {
            e.preventDefault();
            // console.log("Logout clicado - vaciando carrito");
            vaciarCarrito();
            document.getElementById('logout-form').submit();
        }
    });
}

export function handleLogout() {
    // Vaciar el carrito del usuario
    vaciarCarrito();
    
    // Submit Logout
    setTimeout(() => {
        document.getElementById('logout-form').submit();
    }, 1000);
}

// Inicializar cuando el DOM este listo
document.addEventListener('DOMContentLoaded', function() {
    setupLogoutHandlers();
});

// window.handleLogout = handleLogout;