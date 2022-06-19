@extends('admin.master')
@section('content')
    
    @livewire('admin.modal-cambiar-usuario', ['usuario' => $usuario,'plan'=>$plan])
    @livewire('admin.render-calendar', ['usuario' => $usuario,'plan'=>$plan])
@endsection
