<script>
    document.querySelectorAll('form[data-disable-on-submit]').forEach(function (form) {
        form.addEventListener('submit', function () {
            form.querySelectorAll('[type="submit"]').forEach(function (button) {
                button.disabled = true;
            });
        });
    });
</script>
