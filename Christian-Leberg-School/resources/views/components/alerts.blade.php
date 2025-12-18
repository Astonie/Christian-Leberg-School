@if(session('success'))
    <div class="js-alert mb-4 rounded-md bg-green-50 border border-green-200 p-4 text-green-800 relative">
        <button type="button" class="js-alert-close absolute top-2 right-3 text-green-700">&times;</button>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="js-alert mb-4 rounded-md bg-red-50 border border-red-200 p-4 text-red-800 relative">
        <button type="button" class="js-alert-close absolute top-2 right-3 text-red-700">&times;</button>
        {{ session('error') }}
    </div>
@endif

@if(session('status'))
    <div class="js-alert mb-4 rounded-md bg-blue-50 border border-blue-200 p-4 text-blue-800 relative">
        <button type="button" class="js-alert-close absolute top-2 right-3 text-blue-700">&times;</button>
        {{ session('status') }}
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.js-alert').forEach(function(el) {
        var close = el.querySelector('.js-alert-close');
        if (close) {
            close.addEventListener('click', function() {
                el.remove();
            });
        }
        setTimeout(function() {
            // Fade out then remove
            el.classList.add('opacity-0', 'transition', 'duration-300');
            setTimeout(function() { if (el.parentNode) el.remove(); }, 300);
        }, 6000);
    });
});
</script>
