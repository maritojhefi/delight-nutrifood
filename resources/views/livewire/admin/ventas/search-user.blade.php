<div class="col-md-12">
    <div class="basic-list-group">
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
               <input type="text" class="form-control" wire:model.debounce.1000ms='user'>
            </li>
            @foreach ($usuarios as $item)
            <li class="list-group-item d-flex justify-content-between align-items-center">
               {{$item->name}} <span class="badge badge-primary badge-pill"><i class="fa fa-check"></i></span>
            </li>
            @endforeach
           
        </ul>
    </div>
</div>
