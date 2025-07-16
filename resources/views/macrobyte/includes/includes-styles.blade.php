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
</style>
