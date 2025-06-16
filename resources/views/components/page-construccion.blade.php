<div class="card card-style"  style="height:600px!important;">
    <div class="card-center text-center">
        <img src="{{asset(GlobalHelper::getValorAtributoSetting('bajo_construccion'))}}" alt="">
        <h1 class="color-white font-34 font-700 mb-2">Casi listo!</h1>
        <p class="color-white boxed-text-xl opacity-50">
            Estamos realizando los ultimos ajustes para una buena experiencia!
        </p>
        <div class="countdown row">
            <div class="disabled">
                <h1 class="mb-0 color-white font-30" id="years">7</h1>
                <p class="mt-n1 color-white font-11 opacity-30">years</p>
            </div>
            <div class="col-3">
                <h1 class="mb-0 color-white font-30" id="days">10</h1>
                <p class="mt-n1 color-white font-11 opacity-30">dias</p>
            </div>
            <div class="col-3">
                <h1 class="mb-0 color-white font-30" id="hours">09</h1>
                <p class="mt-n1 color-white font-11 opacity-30">horas</p>
            </div>
            <div class="col-3">
                <h1 class="mb-0 color-white font-30" id="minutes">06</h1>
                <p class="mt-n1 color-white font-11 opacity-30">minutos</p>
            </div>
            <div class="col-3">
                <h1 class="mb-0 color-white font-30" id="seconds">07</h1>
                <p class="mt-n1 color-white font-11 opacity-30">segundos</p>
            </div>
        </div>
        <div class="row mb-0 px-4">
            <div class="col-6">
                <a href="{{route('miperfil')}}" class="btn btn-m btn-full mb-3 rounded-xl text-uppercase font-900 shadow-s bg-mint-dark">Ir a mi perfil</a>
            </div>
            <div class="col-6">
                <a href="#" data-menu="menu-share-thumbs" class="btn btn-m btn-full mb-3 rounded-xl text-uppercase font-900 shadow-s bg-dark-light">Contacto</a>
            </div>
        </div>
    </div>
    <div class="card-overlay bg-black opacity-85"></div>
    <script>
        // Establece la fecha objetivo
        const targetDate = new Date('{{date('Y-m-d H:i:s', strtotime('2025-01-01 00:00:00'))}}'); 
        // Cambia esta fecha según sea necesario
    
        function updateCountdown() {
            const now = new Date(); // Fecha y hora actual
            const diff = targetDate - now; // Diferencia en milisegundos
    
            if (diff <= 0) {
                // Cuando se llega al objetivo, muestra ceros
                document.getElementById('years').textContent = '0';
                document.getElementById('days').textContent = '0';
                document.getElementById('hours').textContent = '0';
                document.getElementById('minutes').textContent = '0';
                document.getElementById('seconds').textContent = '0';
                clearInterval(interval); // Detiene el intervalo
                return;
            }
    
            // Calcula años, días, horas, minutos y segundos restantes
            const years = Math.floor(diff / (1000 * 60 * 60 * 24 * 365));
            const days = Math.floor((diff % (1000 * 60 * 60 * 24 * 365)) / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);
    
            // Actualiza los valores en el HTML
            document.getElementById('years').textContent = years;
            document.getElementById('days').textContent = days;
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
        }
    
        // Actualiza el contador cada segundo
        const interval = setInterval(updateCountdown, 1000);
        updateCountdown(); // Llama a la función inmediatamente para inicializar
    </script>
    
</div>
