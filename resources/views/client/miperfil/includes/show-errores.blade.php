@if ($errors->any())
<div class="ms-3 me-3 mb-3 alert alert-small rounded-s shadow-xl bg-red-dark" role="alert">
    <span><i class="fa fa-times"></i></span>
    <strong>Rellene bien los campos</strong>
</div>
@endif
@if (session('success'))
<div class="ms-3 me-3 mb-3 alert alert-small rounded-s shadow-xl bg-green-dark" role="alert">
    <span><i class="fa fa-check"></i></span>
    <strong>{{ session('success') }}</strong>
    <button type="button" class="close color-white opacity-60 font-16" data-bs-dismiss="alert"
        aria-label="Close">×</button>
</div>
@endif
@if (session('error'))
<div class="ms-3 me-3 mb-3 alert alert-small rounded-s shadow-xl bg-red-dark" role="alert">
    <span><i class="fa fa-times-circle "></i></span>
    <strong>{{ session('error') }}</strong>
    <button type="button" class="close color-white opacity-60 font-16" data-bs-dismiss="alert"
        aria-label="Close">×</button>
</div>
@endif
@push('scripts')
    <script>
        $(document).ready(function() {
            var alert = $(".alert");
            setTimeout(function() {
                alert.fadeIn(1000);
                alert.fadeOut(1000);
            }, 3000);
        });
    </script>
@endpush