<script type="text/javascript">
    $('#myModalExito').modal('show');
    var myModal = new bootstrap.Modal(document.getElementById("addNewCustomer"), {});
    myModal.show();
window.addEventListener('abrirmodal', event => {
    var myModal = new bootstrap.Modal(document.getElementById("addNewCustomer"), {});
    myModal.show();
})

</script>