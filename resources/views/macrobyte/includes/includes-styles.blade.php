<style>
    .loader:after {
        content: "{{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }} ";
    }

    @keyframes loading-text {
        0% {
            content: "{{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }} ";
        }

        25% {
            content: "{{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }} ";
        }

        50% {
            content: "{{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }} ";
        }

        75% {
            content: "{{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }} ";
        }
    }



    /* estilos para los graficos */
    .img-graficos {
        width: 100%;
        height: 95%;
        object-fit: cover;
    }

    #contenedor-grafico {
        height: 400px;
        overflow: hidden;
    }

    @media (max-width: 768px) {
        .img-graficos {
            height: 90% !important;
        }

        #contenedor-grafico {
            height: 350px !important;
        }
    }

    @media (max-width: 576px) {
        .img-graficos {
            height: 85% !important;
        }

        #contenedor-grafico {
            height: 300px !important;
        }
    }

    @media (max-width: 480px) {
        .img-graficos {
            height: 80% !important;
        }

        #contenedor-grafico {
            height: 250px !important;
        }
    }

    @media (max-width: 320px) {
        .img-graficos {
            height: 75% !important;
        }

        #contenedor-grafico {
            height: 200px !important;
        }
    }

    @media (max-width: 240px) {
        .img-graficos {
            height: 70% !important;
        }

        #contenedor-grafico {
            height: 150px !important;
        }
    }

    @media (max-width: 160px) {
        .img-graficos {
            height: 65% !important;
        }

        #contenedor-grafico {
            height: 100px !important;
        }
    }

    @media (max-width: 160px) {
        .img-graficos {
            height: 65% !important;
        }

        #contenedor-grafico {
            height: 50px !important;
        }
    }

    @media (max-width: 120px) {
        .img-graficos {
            height: 60% !important;
        }

        #contenedor-grafico {
            height: 25px !important;
        }
    }

    @media (max-width: 80px) {
        .img-graficos {
            height: 55% !important;
        }

        #contenedor-grafico {
            height: 20px !important;
        }
    }

    @media (max-width: 40px) {
        .img-graficos {
            height: 50% !important;
        }

        #contenedor-grafico {
            height: 15px !important;
        }
    }
</style>
