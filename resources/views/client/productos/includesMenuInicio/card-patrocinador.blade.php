@if (auth()->user()->esPartner())
    <div class="card card-style round-medium shadow-huge top-30 mb-3"
        style="height: 125px;background-image:url('{{ asset(GlobalHelper::getValorAtributoSetting('avatar_partner_imagen')) }}')">
        <div class="card-top mt-4 ms-3">
            <h2 class="color-white pt-3 pb-3">Patrocinador</h2>
        </div>
        <div class="card-top mt-4 me-3">
            <a href="#" data-menu="menu-cookie-modal"
                class="float-end bg-white color-black btn btn-s rounded-xl font-700 mt-2 text-uppercase font-11"><i
                    class="fa fa-info-circle"></i> Descubre cómo</a>
        </div>
        <div class="card-bottom ms-3 mb-4">
            <div class="d-flex align-items-center justify-content-between w-100">
                <div class="text-start" style="line-height: normal;">
                    <p class="color-white opacity-90 mb-0 font-400 font-11 mb-0">
                        <i class="fa fa-gift me-1"></i>Comparte tu link o código de referido y gana puntos!
                    </p>
                </div>
            </div>
        </div>
        <div class="card-overlay bg-black opacity-70"></div>
    </div>
@endif
@push('modals')
    @if (auth()->user()->perfilesPuntos->count() > 0)
        <div id="menu-cookie-modal" class="menu menu-box-modal menu-box-detached rounded-s" data-menu-effect="menu-over"
            data-menu-select="page-components" style="display: block; width: 340px; height: auto;">
            <!-- add data-cookie-activate above to auto-activate the menu on cookie detection -->
            <h2 class="text-center pt-3">

                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="512.000000pt" height="512.000000pt"
                    viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet"
                    style="width: 100px; height: auto;" class="">

                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" id="icono-cookie"
                        fill="currentColor" stroke="none">
                        <path
                            d="M4180 4788 c-16 -5 -54 -50 -104 -123 l-79 -115 -144 0 c-139 0 -145
                                                                                                                -1 -168 -25 -43 -42 -34 -70 61 -195 l85 -113 -40 -127 c-23 -71 -41 -139 -41
                                                                                                                -152 0 -32 39 -68 73 -68 14 0 82 20 150 44 l124 44 64 -47 c180 -132 201
                                                                                                                -140 244 -96 24 23 25 29 25 172 l1 148 110 79 c112 80 134 107 123 151 -9 38
                                                                                                                -27 47 -168 90 l-131 40 -42 129 c-24 71 -50 137 -59 147 -19 21 -52 28 -84
                                                                                                                17z m60 -243 c18 -55 42 -110 54 -121 12 -13 61 -33 116 -49 52 -15 101 -31
                                                                                                                109 -35 9 -5 -17 -29 -82 -76 -53 -38 -101 -80 -107 -93 -7 -15 -8 -62 -4
                                                                                                                -138 8 -134 17 -131 -106 -38 -41 30 -86 59 -100 62 -16 4 -62 -6 -134 -31
                                                                                                                -60 -20 -111 -35 -113 -32 -3 2 12 55 32 117 43 132 44 123 -50 244 -36 46
                                                                                                                -65 86 -65 89 0 3 54 6 121 6 66 0 128 4 137 9 10 5 46 52 82 104 36 53 68 92
                                                                                                                71 89 4 -4 21 -52 39 -107z" />
                        <path
                            d="M2180 4648 c-30 -4 -154 -36 -274 -71 -243 -71 -264 -83 -232 -132 9
                                                                                                                -14 26 -25 37 -25 11 0 122 29 247 65 240 69 288 75 365 49 49 -17 117 -75
                                                                                                                140 -121 8 -15 51 -158 97 -318 45 -159 89 -300 96 -312 19 -30 59 -30 79 0
                                                                                                                14 22 9 45 -76 338 -50 172 -102 333 -115 356 -74 124 -216 191 -364 171z" />
                        <path
                            d="M3242 4537 c-114 -114 -131 -144 -100 -175 31 -31 61 -13 175 101
                                                                                                                114 114 132 144 101 175 -31 31 -62 14 -176 -101z" />
                        <path
                            d="M915 4291 c-126 -36 -247 -75 -268 -85 -60 -30 -125 -100 -160 -174
                                                                                                                -62 -133 -92 -6 437 -1842 258 -894 479 -1645 491 -1670 29 -57 112 -135 171
                                                                                                                -161 62 -28 139 -39 203 -28 87 14 1479 417 1530 444 65 32 145 122 170 190
                                                                                                                45 119 47 108 -225 1055 -135 470 -252 870 -260 888 -10 25 -19 32 -41 32 -33
                                                                                                                0 -53 -16 -53 -43 0 -10 113 -411 251 -890 233 -808 251 -876 246 -932 -7 -98
                                                                                                                -59 -174 -144 -213 -26 -12 -369 -114 -762 -227 -533 -153 -728 -205 -768
                                                                                                                -205 -91 0 -172 46 -217 123 -16 27 -196 635 -491 1659 -377 1312 -465 1628
                                                                                                                -465 1675 1 92 47 176 125 223 17 9 136 48 265 85 134 38 240 74 248 83 25 32
                                                                                                                2 83 -36 81 -9 0 -120 -31 -247 -68z" />
                        <path
                            d="M1480 4152 c-124 -38 -235 -74 -247 -80 -36 -20 -74 -78 -80 -122 -8
                                                                                                                -56 20 -124 64 -157 62 -48 107 -44 362 32 240 71 264 80 298 117 66 71 52
                                                                                                                192 -29 249 -60 43 -115 37 -368 -39z m319 -58 c26 -33 26 -55 4 -82 -20 -23
                                                                                                                -456 -156 -494 -150 -12 2 -31 15 -42 30 -17 23 -18 30 -7 57 7 17 22 34 34
                                                                                                                38 93 32 436 131 458 132 18 1 34 -8 47 -25z" />
                        <path
                            d="M3010 3917 c-16 -8 -47 -53 -87 -127 -34 -63 -64 -119 -66 -124 -1
                                                                                                                -4 -67 -16 -146 -25 -127 -15 -146 -20 -162 -40 -39 -48 -27 -77 79 -191 l98
                                                                                                                -104 -27 -135 c-18 -89 -24 -142 -19 -157 10 -26 47 -54 72 -54 10 0 75 27
                                                                                                                145 60 l127 60 113 -64 c62 -36 122 -68 133 -71 28 -9 79 21 86 52 4 15 -1 87
                                                                                                                -10 160 l-17 133 68 62 c138 127 143 133 143 170 0 59 -27 74 -179 100 l-134
                                                                                                                23 -63 132 c-50 105 -69 135 -89 143 -33 13 -35 12 -65 -3z m120 -317 c0 -3
                                                                                                                10 -16 23 -28 16 -15 54 -27 136 -41 63 -12 116 -22 118 -24 2 -2 -36 -39 -83
                                                                                                                -82 -47 -43 -89 -88 -94 -101 -5 -13 -3 -58 5 -116 23 -151 26 -146 -47 -103
                                                                                                                -35 20 -86 48 -115 62 l-52 27 -115 -57 c-70 -34 -116 -51 -116 -44 0 7 9 53
                                                                                                                20 103 32 144 33 142 -66 243 -96 100 -97 95 26 106 103 9 148 22 164 48 7 12
                                                                                                                35 62 61 112 l48 89 44 -93 c24 -52 43 -97 43 -101z" />
                        <path d="M3888 3132 c-65 -65 -118 -125 -118 -133 0 -27 26 -51 54 -51 22 0
                                                                                                                51 22 137 108 61 60 112 119 116 132 7 28 -21 62 -51 62 -12 0 -64 -44 -138
                                                                                                                -118z" />
                        <path
                            d="M2018 3029 c-27 -10 -32 -20 -104 -176 l-33 -71 -53 -11 c-29 -6 -84
                                                                                                                -16 -122 -22 -80 -13 -116 -40 -116 -87 0 -24 16 -45 86 -111 94 -89 92 -84
                                                                                                                58 -174 -22 -56 -13 -80 30 -85 39 -5 51 9 81 93 35 95 27 122 -60 203 l-63
                                                                                                                60 96 17 c53 9 108 24 120 32 14 9 41 52 65 104 22 49 43 89 46 89 4 0 28 -41
                                                                                                                54 -90 27 -50 56 -94 65 -99 15 -8 172 -31 211 -31 8 0 -20 -35 -62 -79 -42
                                                                                                                -43 -77 -86 -77 -95 0 -21 35 -56 58 -56 9 0 59 43 110 97 71 75 95 94 104 85
                                                                                                                7 -7 24 -12 39 -12 26 0 29 -5 54 -87 14 -49 32 -107 39 -129 8 -23 11 -46 7
                                                                                                                -51 -8 -13 -1118 -345 -1139 -341 -22 4 -95 248 -78 264 6 6 65 27 131 45 115
                                                                                                                33 155 54 155 82 0 24 -26 50 -51 49 -26 0 -262 -68 -292 -84 -24 -13 -57 -68
                                                                                                                -57 -96 0 -12 19 -86 42 -164 56 -186 93 -224 194 -197 38 11 836 249 1042
                                                                                                                311 80 24 109 38 128 61 41 48 40 68 -7 225 -53 180 -72 207 -148 207 -40 0
                                                                                                                -52 4 -57 18 -9 28 -38 37 -167 53 -67 8 -122 16 -123 17 -1 1 -27 50 -58 109
                                                                                                                -31 58 -62 111 -69 116 -21 16 -51 21 -79 11z" />
                        <path
                            d="M3678 2253 c-121 -120 -135 -144 -102 -177 8 -9 23 -16 32 -16 26 0
                                                                                                                252 230 252 257 0 25 -25 53 -47 53 -9 0 -69 -53 -135 -117z" />
                        <path d="M2416 1037 c-281 -82 -352 -106 -363 -124 -17 -25 -5 -58 26 -68 21
                                                                                                                -6 683 179 719 201 29 19 29 58 0 78 -12 9 -25 16 -28 15 -3 0 -162 -46 -354
                                                                                                                -102z" />
                    </g>
                </svg>

            </h2>

            {{-- @dd(auth()->user()->perfilesPuntos()->first()->pivot->codigo) --}}

            <p class="text-center  mb-n1 font-600 color-highlight">Link de Referido </p>
            <h1 class="text-center font-30 ps-0">Patrocinador</h1>
            <div class="content text-center mt-0">
                <p class="pe-3 mb-2">
                    Comparte tu link de referido o tu codigo con tus amigos, familiares o conocidos para ganar puntos de
                    consumo
                    para tus futuras compras!.
                </p>
                <!-- Input readonly con el código de referido -->
                <div class="mb-2">
                    <label class="font-12 color-theme opacity-70 mb-0 d-block" style="line-height: normal;"> <strong>Tu
                            código
                            de referido</strong></label>
                    <div class="input-group">
                        <input type="text" id="codigoReferido" class="form-control form-control-lg rounded-s border-0"
                            value="{{ auth()->user()->perfilesPuntos()->first()->pivot->codigo }}" readonly
                            style="background-color: #f8f9fa00; color: #2c2c2c; font-weight: 600; letter-spacing: 1.5px; text-align: center; font-size: 1.1rem; padding: 12px 15px;">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <a href="#" id="btnCopiarLink"
                            class="close-menu btn btn-m btn-full rounded-s font-600 color-white bg-blue-dark"
                            onclick="copiarLink(); return false;">Compartir Link</a>
                    </div>
                    <div class="col-6">
                        <a href="#" id="btnCopiarCodigoBtn"
                            class="close-menu btn btn-m btn-full rounded-s font-600 color-white bg-green-dark"
                            onclick="copiarCodigo(); return false;">Copiar Mi Codigo</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endpush
