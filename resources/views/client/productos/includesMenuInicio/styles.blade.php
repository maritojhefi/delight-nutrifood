<style>
    .gradient-border {
        --borderWidth: 3px;
        background: #1D1F20;
        position: relative;
        border-radius: var(--borderWidth);
    }

    .gradient-border:after {
        content: '';
        position: absolute;
        top: calc(-1 * var(--borderWidth));
        left: calc(-1 * var(--borderWidth));
        height: calc(100% + var(--borderWidth) * 2);
        width: calc(100% + var(--borderWidth) * 2);
        background: linear-gradient(60deg, #f79533, #f37055, #ef4e7b, #a166ab, #5073b8, #1098ad, #07b39b, #6fba82);
        border-radius: calc(2 * var(--borderWidth));
        z-index: -1;
        animation: animatedgradient 3s ease alternate infinite;
        background-size: 300% 300%;
    }


    @keyframes animatedgradient {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    /* Efecto shake sutil y controlable para el SVG */
    .shake-svg {
        /* Controla la intensidad del shake (cambia este valor para ajustar) */
        --shake-intensity: 2px;
        /* Controla la duración de la animación (cambia este valor para ajustar) */
        --shake-duration: 0.5s;
        /* Controla cuántas veces se repite (infinite = infinito, o usa un número) */
        --shake-iteration: infinite;

        animation: shake var(--shake-duration) ease-in-out var(--shake-iteration);
    }

    @keyframes shake {

        0%,
        100% {
            transform: translate(0, 0) rotate(0deg);
        }

        10% {
            transform: translate(calc(-1 * var(--shake-intensity)), calc(var(--shake-intensity) * -0.5)) rotate(-1deg);
        }

        20% {
            transform: translate(calc(var(--shake-intensity) * 0.8), calc(var(--shake-intensity) * 0.5)) rotate(1deg);
        }

        30% {
            transform: translate(calc(-1 * var(--shake-intensity) * 0.6), calc(var(--shake-intensity) * 0.3)) rotate(-0.5deg);
        }

        40% {
            transform: translate(calc(var(--shake-intensity) * 0.4), calc(-1 * var(--shake-intensity) * 0.4)) rotate(0.5deg);
        }

        50% {
            transform: translate(calc(-1 * var(--shake-intensity) * 0.2), calc(var(--shake-intensity) * 0.2)) rotate(-0.3deg);
        }

        60% {
            transform: translate(calc(var(--shake-intensity) * 0.3), calc(-1 * var(--shake-intensity) * 0.3)) rotate(0.3deg);
        }

        70% {
            transform: translate(calc(-1 * var(--shake-intensity) * 0.1), calc(var(--shake-intensity) * 0.1)) rotate(-0.2deg);
        }

        80% {
            transform: translate(calc(var(--shake-intensity) * 0.15), calc(-1 * var(--shake-intensity) * 0.15)) rotate(0.2deg);
        }

        90% {
            transform: translate(calc(-1 * var(--shake-intensity) * 0.05), calc(var(--shake-intensity) * 0.05)) rotate(-0.1deg);
        }
    }
</style>
<!-- Estilos para el acordeón de convenios -->
<style>
    .convenio-card {
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        transition: all 0.3s ease;
    }

    .convenio-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
    }

    .convenio-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
        pointer-events: none;
    }

    .convenio-badge {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    .convenio-content {
        finalizarPlanTodos: diario
            /* background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%); */
            padding: 25px;
        border-radius: 0 0 15px 15px;
        border: 1px solid rgba(102, 126, 234, 0.1);
    }

    .convenio-icon {
        width: 50px;
        height: 50px;
        /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .productos-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin: 20px 0;
    }

    .producto-badge {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 8px 15px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 3px 10px rgba(40, 167, 69, 0.3);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .producto-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        border-color: rgba(255, 255, 255, 0.3);
    }

    .convenio-footer .alert {
        border: none;
        border-radius: 15px;
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-left: 4px solid #28a745;
        box-shadow: 0 3px 10px rgba(40, 167, 69, 0.2);
    }

    .convenio-footer .alert i {
        color: #28a745;
        font-size: 1.2rem;
    }

    /* Animación de entrada para los badges */
    .producto-badge {
        animation: slideInUp 0.5s ease forwards;
        opacity: 0;
        transform: translateY(20px);
    }

    .producto-badge:nth-child(1) {
        animation-delay: 0.1s;
    }

    .producto-badge:nth-child(2) {
        animation-delay: 0.2s;
    }

    .producto-badge:nth-child(3) {
        animation-delay: 0.3s;
    }

    .producto-badge:nth-child(4) {
        animation-delay: 0.4s;
    }

    .producto-badge:nth-child(5) {
        animation-delay: 0.5s;
    }

    .producto-badge:nth-child(6) {
        animation-delay: 0.6s;
    }

    @keyframes slideInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .convenio-content {
            padding: 20px 15px;
            padding-top: 10px !important;
        }

        .producto-badge {
            font-size: 0.8rem;
            padding: 6px 12px;
        }

        .convenio-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
    }
</style>

<style>
    .gradient-morning {
        background: linear-gradient(135deg, #1b8dbb 0%, #9ad3ea 30%, #18b4c8 70%, #4fa6f2 100%);
        box-shadow: 0 4px 15px rgba(135, 206, 235, 0.3);
    }

    .gradient-afternoon {
        background: linear-gradient(135deg, #c16a00 0%, #d1b000 30%, #e59708 70% 70%, #ffc35a 100%);
        box-shadow: 0 4px 15px rgba(255, 140, 0, 0.3);
    }

    .gradient-night {
        background: linear-gradient(135deg, #191970 0%, #000080 30%, #483D8B 70%, #000000 100%);
        box-shadow: 0 4px 15px rgba(25, 25, 112, 0.3);
    }

    .gradient-morning:hover,
    .gradient-afternoon:hover,
    .gradient-night:hover {
        transform: translateY(-2px);
        transition: transform 0.3s ease;
    }

    .day-icon {
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-5px);
        }
    }

    /* .day-icon svg {
                                                                                                                filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
                                                                                                            } */

    .plan-accordion-button:not(.collapsed)::after {
        background: none !important;
        border: none !important;
    }

    .plan-accordion-button::after {
        background: none !important;
        border: none !important;
    }
</style>

<style>
    /*.accordion .accordion-button {
                                                                                                                transition: all 0.3s ease !important;
                                                                                                            }*/

    .btn.pedido-pendiente {
        transition: all 0s ease !important;
    }
</style>