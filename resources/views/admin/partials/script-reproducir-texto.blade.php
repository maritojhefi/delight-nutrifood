    <!-- Text-to-Speech Functions -->
    <script>
        /**
         * Reproduce texto usando ResponsiveVoice (requiere conexión a internet)
         * @param {string} texto - El texto a reproducir
         * @param {string} voz - La voz a usar (por defecto 'Spanish Latin American Female')
         * @param {object} opciones - Opciones adicionales (rate, pitch, volume)
         */
        function reproducirTexto(texto, voz = 'Spanish Latin American Female', opciones = {}) {
            if (typeof responsiveVoice !== 'undefined') {
                const configuracion = {
                    rate: opciones.rate || 1, // Velocidad (0.1 a 1.5)
                    pitch: opciones.pitch || 1, // Tono (0 a 2)
                    volume: opciones.volume || 1, // Volumen (0 a 1)
                    onstart: opciones.onstart || null,
                    onend: opciones.onend || null
                };

                responsiveVoice.speak(texto, voz, configuracion);
            } else {
                console.error('ResponsiveVoice no está cargado');
                // Fallback a Web Speech API nativa
                reproducirTextoNativo(texto, opciones);
            }
        }

        /**
         * Reproduce texto usando Web Speech API nativa del navegador (funciona sin internet)
         * @param {string} texto - El texto a reproducir
         * @param {object} opciones - Opciones adicionales (rate, pitch, volume, lang)
         */
        function reproducirTextoNativo(texto, opciones = {}) {
            if ('speechSynthesis' in window) {
                // Cancelar cualquier reproducción anterior
                window.speechSynthesis.cancel();

                const utterance = new SpeechSynthesisUtterance(texto);
                utterance.lang = opciones.lang || 'es-ES';
                utterance.rate = opciones.rate || 1; // Velocidad (0.1 a 10)
                utterance.pitch = opciones.pitch || 1; // Tono (0 a 2)
                utterance.volume = opciones.volume || 1; // Volumen (0 a 1)

                if (opciones.onstart) utterance.onstart = opciones.onstart;
                if (opciones.onend) utterance.onend = opciones.onend;

                window.speechSynthesis.speak(utterance);
            } else {
                console.error('Text-to-Speech no está soportado en este navegador');
            }
        }

        /**
         * Detiene la reproducción actual
         */
        function detenerTexto() {
            if (typeof responsiveVoice !== 'undefined') {
                responsiveVoice.cancel();
            }
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
            }
        }

        /**
         * Pausa la reproducción actual
         */
        function pausarTexto() {
            if (typeof responsiveVoice !== 'undefined') {
                responsiveVoice.pause();
            }
            if ('speechSynthesis' in window) {
                window.speechSynthesis.pause();
            }
        }

        /**
         * Reanuda la reproducción pausada
         */
        function reanudarTexto() {
            if (typeof responsiveVoice !== 'undefined') {
                responsiveVoice.resume();
            }
            if ('speechSynthesis' in window) {
                window.speechSynthesis.resume();
            }
        }

        /**
         * Verifica si hay una reproducción en curso
         */
        function estaReproduciendo() {
            if (typeof responsiveVoice !== 'undefined') {
                return responsiveVoice.isPlaying();
            }
            if ('speechSynthesis' in window) {
                return window.speechSynthesis.speaking;
            }
            return false;
        }

        /**
         * Obtiene lista de voces disponibles en español
         */
        function obtenerVocesEspanol() {
            if (typeof responsiveVoice !== 'undefined') {
                return responsiveVoice.getVoices().filter(v => v.name.includes('Spanish'));
            }
            return [];
        }

        // Event listener para Livewire
        window.addEventListener('reproducirTexto', ({
            detail: {
                texto,
                voz,
                opciones
            }
        }) => {
            reproducirTexto(texto, voz, opciones || {});
        });

        window.addEventListener('detenerTexto', () => {
            detenerTexto();
        });
    </script>
