<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(setting('site_name', config('app.name', 'School Portal'))); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        
        <style>
            .glass {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }
            .hero-pattern {
                background-color: #ffffff;
                background-image: radial-gradient(#0ea5e9 0.5px, transparent 0.5px), radial-gradient(#0ea5e9 0.5px, #ffffff 0.5px);
                background-size: 20px 20px;
                background-position: 0 0, 10px 10px;
                opacity: 0.1;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-50 h-full">
        <div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 overflow-hidden">
            <!-- Background Elements -->
            <div class="absolute inset-0 hero-pattern z-0 pointer-events-none"></div>
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-gradient-to-br from-blue-200 to-cyan-200 blur-3xl opacity-40 z-0"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 rounded-full bg-gradient-to-br from-brand-200 to-blue-200 blur-3xl opacity-40 z-0"></div>

            <!-- Logo -->
            <div class="relative z-10">
                <a href="/" class="flex items-center gap-3 mb-8 group">
                    <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-105 transition-transform p-2">
                        <img src="<?php echo e(asset('images/school_logo.png')); ?>" alt="CLSS Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="font-bold text-2xl tracking-tight text-slate-900">
                        <?php echo e(setting('site_name', 'School Portal')); ?>

                    </span>
                </a>
            </div>

            <!-- Content Card -->
            <div class="w-full sm:max-w-md relative z-10">
                <div class="glass shadow-2xl border border-white/50 rounded-3xl p-8">
                    <?php echo e($slot); ?>

                </div>
                
                <!-- Back to Home Link -->
                <div class="text-center mt-6">
                    <a href="/" class="text-sm text-slate-600 hover:text-blue-600 transition-colors inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
<?php /**PATH C:\Users\THINKPAD -T15\Christian-Leberg-School\resources\views/layouts/guest.blade.php ENDPATH**/ ?>