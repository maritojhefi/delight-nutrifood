<div class="row">
    <div class="col-xl-8 col-lg-12 col-xxl-5 col-sm-12">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Nuevo Evento</h4>
                </div>
                <div class="card-body">
                    <div class="">
                        <x-input-create-defer :lista="[
                            'Titulo' => ['titulo', 'text'],
                            'Descripcion' => ['descripcion', 'textarea'],
                            'Imagen' => ['foto', 'file'],
                        ]">

                            @if ($foto)
                                <img src="{{ $foto->temporaryUrl() }}" class="w-100 border-radius-lg shadow-sm">
                            @endif

                            </x-input-create>

                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="col-xl-4 col-lg-12 col-xxl-7 col-sm-12">
        <div class="card overflow-hidden">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Galeria de fotos <span
                            class="badge badge-success light">total:{{ $fotos->total() }}</span></h4>
                </div>
                <div class="card-body">
                    <div class="row mt-4 sp4" id="lightgallery">

                        @foreach ($fotos as $foto)
                            <div class="card col-4">
                                <div class="card-body">
                                    <div class="profile-blog">

                                        <div class="mb-1 col-lg-4 col-xl-4 col-sm-4 col-6" >
                                            <img src="{{ asset('imagenes/galeria/' . $foto->foto) }}" alt="" style="width:150px;max-height=150px"  
                                                class="">
                                        </div>
                                        <h4><a href="#" class="text-black">{{ $foto->titulo }}</a></h4>
                                        <small class="mb-0">{{ $foto->descripcion }}</small>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button wire:click="delete({{$foto->id}})" class="btn btn-danger btn-block btn-xxs"><span
                                            class="fa fa-trash"></span></button>
                                </div>
                            </div>
                        @endforeach
                        <div class="row d-block mx-auto">
                            <div class=" col">{{ $fotos->links() }}</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>
