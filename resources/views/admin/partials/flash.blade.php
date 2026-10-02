@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show js-auto-dismiss" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show js-auto-dismiss" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
<script>
    setTimeout(function () {
        document.querySelectorAll('.js-auto-dismiss').forEach(function (alert) {
            if (window.jQuery) {
                window.jQuery(alert).alert('close');
            } else {
                alert.remove();
            }
        });
    }, 4000);
</script>
