@extends('admin.master')
@section('content')
<div class="col-xl-6 col-lg-12 col-xxl-6 col-sm-12">
    <div class="row" style="margin: 0px">
        <div class="card">
            <div class="card">
                <div class="card-header">
                    Importar productos
                </div>
                <div class="card-body">
                    <form action="{{route('importar.excel')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <label class="card-category" for="alumnos">Seleccionar archivo</label>
                        <div class="input-group mb-3">
                            <button class="btn btn-primary btn-sm" type="button"><span class="fa fa-check"></span></button>
                            <div class="form-file">
                                <input type="file" class="form-file-input form-control" name="registros">
                            </div>
                        </div>
                       
                              
                        <button type="submit" class="btn btn-primary btn-round">Importar</button>
                          
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    
<div class="col-xl-6 col-lg-12 col-xxl-6 col-sm-12">
    <div class="row" style="margin: 0px">
        <div class="card">
            <div class="card">
                <div class="card-header">
                    Importar usuarios
                </div>
                <div class="card-body">
                    <form action="{{route('importarUser.excel')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <label class="card-category" for="alumnos">Seleccionar archivo</label>
                        <div class="input-group mb-3">
                            <button class="btn btn-primary btn-sm" type="button"><span class="fa fa-check"></span></button>
                            <div class="form-file">
                                <input type="file" class="form-file-input form-control" name="registros">
                            </div>
                        </div>
                       
                              
                        <button type="submit" class="btn btn-primary btn-round">Importar</button>
                          
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    

@endsection