@if(session('success') || session('error') || session('status'))
    <div id="toasts" class="fixed top-4 right-4 z-50 space-y-3">
        @if(session('success'))
            <div class="js-toast rounded-md bg-green-600 text-white px-4 py-3 shadow-lg" role="alert">
                <button type="button" class="js-toast-close float-right text-white opacity-75">&times;</button>
                <div class="text-sm">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="js-toast rounded-md bg-red-600 text-white px-4 py-3 shadow-lg" role="alert">
                <button type="button" class="js-toast-close float-right text-white opacity-75">&times;</button>
                <div class="text-sm">{{ session('error') }}</div>
            </div>
        @endif

        @if(session('status'))
            <div class="js-toast rounded-md bg-blue-600 text-white px-4 py-3 shadow-lg" role="alert">
                <button type="button" class="js-toast-close float-right text-white opacity-75">&times;</button>
                <div class="text-sm">{{ session('status') }}</div>
            </div>
        @endif
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.js-toast').forEach(function(el) {
            var close = el.querySelector('.js-toast-close');
            if (close) {
                close.addEventListener('click', function() { el.remove(); });
            }
            setTimeout(function() { el.classList.add('opacity-0', 'transition', 'duration-300'); setTimeout(function(){ if (el.parentNode) el.remove(); }, 300); }, 6000);
        });
    });
    </script>
@endif
