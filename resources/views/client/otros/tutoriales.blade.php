@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="Videos" cabecera="bordeado" />

    @php
        $colores = ['mint', 'orange', 'red', 'pink', 'blue', 'green'];
        $coleccion = collect($colores);
    @endphp
    @foreach ($videos as $item)
    @if ($item->tipo!='youtube')
    <div class="card card-style">
        {!! $item->url!!}
    </div>
    @else
    <div class="card card-style gradient-{{ $coleccion->random() }}">
        <div class="content pb-3 pt-3">
            <h3 class="mb-1 color-white font-700">{{ $item->titulo }}</h3>
            <div id="myDiv{{ $item->id }}" class="mx-auto d-block"></div>
            <p class="color-white opacity-80">
                {{ $item->descripcion }}
            </p>

        </div>
    </div>
    @endif
  
    @endforeach


   
@endsection

@push('header')
    <link rel="stylesheet" type="text/css" href="{{ asset('videoplayer/global.css') }}">
@endpush
@push('scripts')
    <script type="text/javascript" src="{{ asset('videoplayer/FWDEVPlayer.js') }}"></script>

    <!-- Setup EVP -->
    <script type="text/javascript">
        FWDEVPUtils.onReady(function() {

            FWDEVPlayer.videoStartBehaviour = "play";
            @foreach ($videos->where('tipo','youtube') as $item)
                new FWDEVPlayer({
                    //main settings
                    instanceName: "player{{ $item->id }}",
                    parentId: "myDiv{{ $item->id }}",
                    mainFolderPath: "content",
                    skinPath: "minimal_skin_dark",
                    initializeOnlyWhenVisible: "yes",
                    displayType: "responsive",
                    autoScale: "yes",
                    fillEntireVideoScreen: "yes",
                    playsinline: "yes",
                    useWithoutVideoScreen: "no",
                    openDownloadLinkOnMobile: "no",
                    useVectorIcons: "no",
                    useResumeOnPlay: "yes",
                    goFullScreenOnButtonPlay: "no",
                    useHEXColorsForSkin: "no",
                    normalHEXButtonsColor: "#FF0000",
                    privateVideoPassword: "428c841430ea18a70f7b06525d4b748a",
                    startAtTime: "00:00:00",
                    stopAtTime: "",
                    startAtVideoSource: 1,
                    videoSource: [{
                        source: "{{ $item->url }}",
                        label: "small version"
                    }],
                    posterPath: "",
                    googleAnalyticsTrackingCode: "",
                    showErrorInfo: "no",
                    fillEntireScreenWithPoster: "yes",
                    disableDoubleClickFullscreen: "yes",
                    useChromeless: "no",
                    showPreloader: "yes",
                    preloaderColors: ["#999999", "#FFFFFF"],
                    addKeyboardSupport: "yes",
                    autoPlay: "no",
                    autoPlayText: "Click to Unmute",
                    loop: "no",
                    scrubAtTimeAtFirstPlay: "00:00:00",
                    maxWidth: 320,
                    maxHeight: 240,
                    volume: .8,
                    greenScreenTolerance: 200,
                    backgroundColor: "#000000",
                    posterBackgroundColor: "#000000",
                    //lightbox settings
                    closeLightBoxWhenPlayComplete: "yes",
                    lightBoxBackgroundOpacity: .6,
                    lightBoxBackgroundColor: "#000000",
                    //logo settings
                    showLogo: "no",
                    hideLogoWithController: "no",
                    logoPosition: "topRight",
                    logoLink: "",
                    logoMargins: 5,
                    //controller settings
                    showController: "yes",
                    showDefaultControllerForVimeo: "no",
                    showScrubberWhenControllerIsHidden: "no",
                    showControllerWhenVideoIsStopped: "no",
                    showVolumeScrubber: "no",
                    showVolumeButton: "no",
                    showTime: "yes",
                    showRewindButton: "no",
                    showQualityButton: "no",
                    showShareButton: "no",
                    showEmbedButton: "no",
                    showDownloadButton: "no",
                    showMainScrubberToolTipLabel: "no",
                    showChromecastButton: "no",
                    showFullScreenButton: "yes",
                    repeatBackground: "no",
                    controllerHeight: 41,
                    controllerHideDelay: 3,
                    startSpaceBetweenButtons: 7,
                    spaceBetweenButtons: 9,
                    mainScrubberOffestTop: 14,
                    scrubbersOffsetWidth: 4,
                    timeOffsetLeftWidth: 5,
                    timeOffsetRightWidth: 3,
                    volumeScrubberWidth: 80,
                    volumeScrubberOffsetRightWidth: 0,
                    timeColor: "#777777",
                    youtubeQualityButtonNormalColor: "#777777",
                    youtubeQualityButtonSelectedColor: "#FFFFFF",
                    scrubbersToolTipLabelBackgroundColor: "#FFFFFF",
                    scrubbersToolTipLabelFontColor: "#5a5a5a",
                    //redirect at video end
                    redirectURL: "/",
                });
            @endforeach


        });

        //Register API (an setInterval is required because the player is not available until the youtube API is loaded).
    </script>
@endpush
