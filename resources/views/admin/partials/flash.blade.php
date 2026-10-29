@if (session('success') || session('error'))
    <div class="admin-toast-stack" aria-live="polite">
        @if (session('success'))
            <div class="admin-toast admin-toast-success" role="status">
                <span>{{ session('success') }}</span>
                <button type="button" class="admin-toast-close" aria-label="Cerrar">&times;</button>
            </div>
        @endif
        @if (session('error'))
            <div class="admin-toast admin-toast-error" role="alert">
                <span>{{ session('error') }}</span>
                <button type="button" class="admin-toast-close" aria-label="Cerrar">&times;</button>
            </div>
        @endif
    </div>
    <style>
        .admin-toast-stack {
            position: fixed;
            z-index: 2000;
            left: 50%;
            bottom: 1.25rem;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            width: min(36rem, calc(100vw - 2rem));
            pointer-events: none;
        }

        .admin-toast {
            pointer-events: auto;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin: 0;
            padding: 0.85rem 1rem;
            border-radius: 0.35rem;
            color: #fff;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.2);
            opacity: 1;
            transition: opacity 0.8s ease;
        }

        .admin-toast.is-leaving {
            opacity: 0;
        }

        .admin-toast-success {
            background: #1e7e34;
        }

        .admin-toast-error {
            background: #bd2130;
        }

        .admin-toast-close {
            flex: none;
            border: 0;
            background: transparent;
            color: inherit;
            font-size: 1.4rem;
            line-height: 1;
            padding: 0;
            cursor: pointer;
        }
    </style>
    <script>
        (function () {
            var holdMs = 4500;
            var fadeMs = 800;

            function dismiss(toast) {
                if (!toast || toast.classList.contains('is-leaving')) {
                    return;
                }

                toast.classList.add('is-leaving');
                window.setTimeout(function () {
                    toast.remove();
                }, fadeMs);
            }

            document.querySelectorAll('.admin-toast').forEach(function (toast) {
                var close = toast.querySelector('.admin-toast-close');
                if (close) {
                    close.addEventListener('click', function () {
                        dismiss(toast);
                    });
                }

                window.setTimeout(function () {
                    dismiss(toast);
                }, holdMs);
            });
        })();
    </script>
@endif
