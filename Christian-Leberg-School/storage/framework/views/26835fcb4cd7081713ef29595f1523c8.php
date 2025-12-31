<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Welcome'); ?> - <?php echo e(setting('site_name', 'School Portal')); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', setting('site_description')); ?>">
    <meta name="keywords" content="<?php echo $__env->yieldContent('meta_keywords', setting('site_keywords')); ?>">
    
    <?php echo $__env->yieldPushContent('meta'); ?>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .hero-pattern {
            background-color: #ffffff;
            background-image: radial-gradient(#0ea5e9 0.5px, transparent 0.5px), radial-gradient(#0ea5e9 0.5px, #ffffff 0.5px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            opacity: 0.1;
        }
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.6s ease-out;
        }
        /* Mobile menu animation */
        .mobile-menu {
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }
        .mobile-menu.hidden {
            transform: translateX(100%);
            opacity: 0;
        }
        .mobile-menu:not(.hidden) {
            transform: translateX(0);
            opacity: 1;
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50">
    <!-- Background Elements -->
    <div class="fixed inset-0 hero-pattern z-0 pointer-events-none"></div>
    
    <!-- Navigation -->
    <nav class="relative z-50 w-full glass sticky top-0 border-b border-slate-200/60 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-2 md:gap-3">
                    <a href="<?php echo e(route('website.home')); ?>" class="flex items-center gap-2 md:gap-3">
                        <img src="<?php echo e(asset('images/school_logo.png')); ?>" alt="CLSS Logo" class="w-10 h-10 md:w-12 md:h-12 object-contain">
                        <span class="font-bold text-base md:text-xl tracking-tight text-slate-900 hidden sm:block">
                            <?php echo e(setting('site_name', 'School Portal')); ?>

                        </span>
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-4 lg:space-x-6">
                    <?php
                        $headerMenu = App\Models\Menu::byLocation('header')->active()->first();
                    ?>
                    <?php if($headerMenu): ?>
                        <?php $__currentLoopData = $headerMenu->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e($item->href); ?>" 
                               class="text-sm font-medium <?php echo e($item->isActive() ? 'text-brand-600' : 'text-slate-600 hover:text-brand-600'); ?> transition-colors relative group">
                                <?php echo e($item->title); ?>

                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-600 group-hover:w-full transition-all duration-300"></span>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <a href="<?php echo e(route('login')); ?>" class="px-5 py-2.5 bg-slate-900 text-white rounded-lg text-sm font-medium hover:bg-slate-800 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        Portal Login
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button type="button" id="mobile-menu-button" class="p-2 rounded-lg text-slate-700 hover:bg-slate-100 transition-colors" aria-label="Toggle menu">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path id="close-icon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="mobile-menu hidden md:hidden fixed inset-y-0 right-0 w-64 bg-white shadow-2xl border-l border-slate-200 overflow-y-auto z-50">
            <div class="px-4 py-6">
                <div class="flex items-center justify-between mb-8">
                    <span class="font-bold text-lg text-slate-900">Menu</span>
                    <button type="button" id="mobile-menu-close" class="p-2 rounded-lg text-slate-700 hover:bg-slate-100 transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="space-y-2">
                    <?php if($headerMenu): ?>
                        <?php $__currentLoopData = $headerMenu->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e($item->href); ?>" 
                               class="block px-4 py-3 rounded-lg text-base font-medium <?php echo e($item->isActive() ? 'bg-brand-50 text-brand-600' : 'text-slate-700 hover:bg-slate-50'); ?> transition-colors">
                                <?php echo e($item->title); ?>

                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <a href="<?php echo e(route('login')); ?>" class="block px-4 py-3 mt-4 bg-slate-900 text-white rounded-lg text-base font-medium hover:bg-slate-800 transition-all text-center">
                        Portal Login
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile menu overlay -->
        <div id="mobile-menu-overlay" class="hidden fixed inset-0 bg-black/50 z-40 md:hidden" style="backdrop-filter: blur(4px);"></div>
    </nav>

    <!-- Main Content -->
    <main class="relative">
        <?php echo $__env->yieldContent('content'); ?>
    </main>


    <!-- Footer -->
    <footer class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white mt-16 md:mt-20 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 md:w-96 md:h-96 bg-blue-500 rounded-full blur-3xl opacity-20"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 md:w-96 md:h-96 bg-cyan-500 rounded-full blur-3xl opacity-10"></div>
        <div class="max-w-7xl mx-auto py-12 md:py-16 px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 md:gap-12 mb-8 md:mb-12">
                <div class="col-span-1 sm:col-span-2">
                    <div class="flex items-center gap-3 mb-4 md:mb-6">
                        <img src="<?php echo e(asset('images/school_logo.png')); ?>" alt="CLSS Logo" class="w-12 h-12 md:w-14 md:h-14 object-contain">
                        <h3 class="text-lg md:text-xl font-bold text-white"><?php echo e(setting('site_name', 'School Portal')); ?></h3>
                    </div>
                    <p class="text-slate-300 text-sm md:text-base leading-relaxed mb-4 md:mb-6"><?php echo e(setting('site_description', 'Excellence in Education - Building futures through quality education')); ?></p>
                    <div class="flex gap-3 md:gap-4">
                        <?php if(setting('social_facebook')): ?>
                            <a href="<?php echo e(setting('social_facebook')); ?>" target="_blank" rel="noopener" class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if(setting('social_twitter')): ?>
                            <a href="<?php echo e(setting('social_twitter')); ?>" target="_blank" rel="noopener" class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if(setting('social_instagram')): ?>
                            <a href="<?php echo e(setting('social_instagram')); ?>" target="_blank" rel="noopener" class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div>
                    <h4 class="text-base md:text-lg font-bold mb-4 md:mb-6 text-white">Quick Links</h4>
                    <ul class="space-y-2 md:space-y-3">
                        <li><a href="<?php echo e(route('website.home')); ?>" class="text-slate-300 hover:text-white transition-colors text-sm md:text-base hover:translate-x-1 inline-block">Home</a></li>
                        <li><a href="<?php echo e(route('website.blog')); ?>" class="text-slate-300 hover:text-white transition-colors text-sm md:text-base hover:translate-x-1 inline-block">News & Blog</a></li>
                        <li><a href="<?php echo e(route('website.events')); ?>" class="text-slate-300 hover:text-white transition-colors text-sm md:text-base hover:translate-x-1 inline-block">Events</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-base md:text-lg font-bold mb-4 md:mb-6 text-white">Contact</h4>
                    <ul class="space-y-2 md:space-y-3 text-slate-300 text-sm md:text-base">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="break-words"><?php echo e(setting('contact_address', 'Malawi')); ?></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span><?php echo e(setting('contact_phone', '+265 xxx xxx xxx')); ?></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span class="break-all"><?php echo e(setting('contact_email', 'info@clss.mw')); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-700/50 pt-6 md:pt-8 text-center">
                <p class="text-slate-300 text-sm md:text-base">&copy; <?php echo e(date('Y')); ?> <span class="font-semibold text-white"><?php echo e(setting('site_name', 'School Portal')); ?></span>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenuClose = document.getElementById('mobile-menu-close');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');

        function toggleMobileMenu() {
            mobileMenu.classList.toggle('hidden');
            mobileMenuOverlay.classList.toggle('hidden');
            menuIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
            document.body.style.overflow = mobileMenu.classList.contains('hidden') ? '' : 'hidden';
        }

        mobileMenuButton?.addEventListener('click', toggleMobileMenu);
        mobileMenuClose?.addEventListener('click', toggleMobileMenu);
        mobileMenuOverlay?.addEventListener('click', toggleMobileMenu);

        // Close mobile menu when clicking on a link
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                if (!mobileMenu.classList.contains('hidden')) {
                    toggleMobileMenu();
                }
            });
        });

        // Close mobile menu on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
                toggleMobileMenu();
            }
        });
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\THINKPAD -T15\Christian-Leberg-School\resources\views/website/layout.blade.php ENDPATH**/ ?>