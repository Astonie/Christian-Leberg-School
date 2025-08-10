@extends('layouts.app')

@section('content')
<!-- Header -->
<header id="header" class="fixed top-0 w-full bg-white shadow z-50 transition-all">
    <nav class="container mx-auto flex justify-between items-center py-4 px-4">
        <a href="#home" class="text-2xl font-bold bg-gradient-to-r from-blue-700 to-blue-400 bg-clip-text text-transparent">Uplifta</a>
        <ul class="hidden md:flex space-x-8 list-none" id="navLinks">
            <li><a href="#home" class="nav-link active text-blue-700 font-semibold border-b-2 border-blue-700">Home</a></li>
            <li><a href="#about" class="nav-link text-gray-800 hover:text-blue-700">About</a></li>
            <li><a href="#services" class="nav-link text-gray-800 hover:text-blue-700">Services</a></li>
            <li><a href="#apply" class="nav-link text-gray-800 hover:text-blue-700">Apply</a></li>
            <li><a href="#stories" class="nav-link text-gray-800 hover:text-blue-700">Success Stories</a></li>
            <li><a href="#involved" class="nav-link text-gray-800 hover:text-blue-700">Get Involved</a></li>
            <li><a href="#contact" class="nav-link text-gray-800 hover:text-blue-700">Contact</a></li>
        </ul>
        <div class="md:hidden flex flex-col gap-1 cursor-pointer" id="mobileToggle">
            <span class="block w-6 h-1 bg-gray-800"></span>
            <span class="block w-6 h-1 bg-gray-800"></span>
            <span class="block w-6 h-1 bg-gray-800"></span>
        </div>
    </nav>
</header>

<!-- Hero Section -->
<!-- Hero Section -->
<section id="home" class="hero bg-gradient-to-br from-blue-700 to-blue-400 text-white min-h-screen flex items-center relative overflow-hidden pt-24">
    <div class="container mx-auto px-4 md:px-8 lg:px-16 relative z-10 flex flex-col items-center justify-center">
        <div class="hero-content w-full max-w-6xl mx-auto">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div class="hero-text">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6 drop-shadow text-center md:text-left">Empowering Growth, One Step at a Time</h1>
                    <p class="text-lg font-light mb-6 opacity-90 text-center md:text-left">Building stronger communities through accessible financial services</p>
                    <p class="mb-8 text-base opacity-85 leading-relaxed text-center md:text-left">
                        Uplifta provides microloans, savings programs, and financial literacy education to help underserved communities and small businesses achieve their dreams and create lasting prosperity.
                    </p>
                    <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                        <a href="#apply" class="btn btn-primary inline-flex items-center gap-2 px-6 py-3 rounded-full font-semibold bg-white text-blue-700 shadow hover:-translate-y-1 hover:shadow-lg transition">
                            <i class="fas fa-handshake"></i>
                            Apply for a Loan
                        </a>
                        <a href="#services" class="btn btn-secondary inline-flex items-center gap-2 px-6 py-3 rounded-full font-semibold border-2 border-white text-white hover:bg-white hover:text-blue-700 transition">
                            <i class="fas fa-info-circle"></i>
                            Learn More
                        </a>
                    </div>
                </div>
                <div class="flex justify-center items-center relative">
                    <div class="w-full max-w-lg h-80 rounded-2xl overflow-hidden shadow-2xl relative bg-gradient-to-br from-orange-400/90 to-orange-200/80 flex items-center justify-center">
                        <div class="absolute inset-0 bg-blue-700/10 flex flex-col justify-center items-center text-white text-center p-8">
                            <i class="fas fa-building text-5xl mb-4 text-orange-400"></i>
                            <h3 class="text-xl font-bold mb-2">Growing Together</h3>
                            <p class="text-base">Join thousands who have transformed their lives through microfinance</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid md:grid-cols-3 gap-8 mt-16">
                <div class="text-center">
                    <span class="text-3xl font-bold block mb-2" data-target="15000">0</span>
                    <span class="text-sm opacity-80">Loans Disbursed</span>
                </div>
                <div class="text-center">
                    <span class="text-3xl font-bold block mb-2" data-target="45">0</span>
                    <span class="text-sm opacity-80">Communities Reached</span>
                </div>
                <div class="text-center">
                    <span class="text-3xl font-bold block mb-2" data-target="98">0</span>
                    <span class="text-sm opacity-80">Repayment Rate %</span>
                </div>
            </div>
        </div>
    </div>
    <div class="absolute bottom-0 left-0 right-0 h-48 bg-cover bg-center opacity-60 z-0" style="background-image: url('data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1200 200\'><defs><linearGradient id=\'building1\' x1=\'0%\' y1=\'0%\' x2=\'0%25\' y2=\'100%\'><stop offset=\'0%25\' style=\'stop-color:rgba(255,255,255,0.2);stop-opacity:1\' /><stop offset=\'100%25\' style=\'stop-color:rgba(255,255,255,0.1);stop-opacity:1\' /></linearGradient></defs><rect x=\'50\' y=\'80\' width=\'80\' height=\'120\' fill=\'url(%23building1)\'/><rect x=\'160\' y=\'60\' width=\'60\' height=\'140\' fill=\'url(%23building1)\'/><rect x=\'250\' y=\'100\' width=\'90\' height=\'100\' fill=\'url(%23building1)\'/><rect x=\'370\' y=\'40\' width=\'70\' height=\'160\' fill=\'url(%23building1)\'/><rect x=\'470\' y=\'90\' width=\'85\' height=\'110\' fill=\'url(%23building1)\'/><rect x=\'580\' y=\'70\' width=\'75\' height=\'130\' fill=\'url(%23building1)\'/><rect x=\'680\' y=\'50\' width=\'95\' height=\'150\' fill=\'url(%23building1)\'/><rect x=\'800\' y=\'85\' width=\'65\' height=\'115\' fill=\'url(%23building1)\'/><rect x=\'890\' y=\'45\' width=\'80\' height=\'155\' fill=\'url(%23building1)\'/><rect x=\'995\' y=\'75\' width=\'70\' height=\'125\' fill=\'url(%23building1)\'/></svg>');"></div>
</section>

<!-- Services Section (Dynamic) -->
<section id="services" class="py-24 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center text-gray-900 mb-4">Our Services</h2>
        <p class="text-center text-lg text-gray-500 mb-12 max-w-2xl mx-auto">
            Comprehensive financial solutions designed to meet the unique needs of underserved communities and small businesses
        </p>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($services as $service)
            <div class="bg-white rounded-2xl shadow-lg p-8 flex flex-col items-center hover:-translate-y-2 hover:shadow-2xl transition group">
                <div class="w-16 h-16 flex items-center justify-center rounded-xl mb-6 group-hover:scale-110 transition {{ $service->icon_bg ?? 'bg-gradient-to-br from-blue-600 to-blue-400' }}">
                    <i class="{{ $service->icon }} text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-gray-900">{{ $service->title }}</h3>
                <p class="text-gray-500 mb-4 text-center">{{ $service->description }}</p>
                @if($service->features)
                <ul class="space-y-2 text-gray-500 text-sm mb-6">
                    @foreach(json_decode($service->features, true) as $feature)
                    <li class="flex items-center gap-2"><i class="fas fa-check text-green-500"></i> {{ $feature }}</li>
                    @endforeach
                </ul>
                @endif
                @if($service->cta_label && $service->cta_link)
                <a href="{{ $service->cta_link }}" class="inline-block px-6 py-2 rounded-full bg-blue-600 text-white font-semibold shadow hover:bg-blue-700 transition">{{ $service->cta_label }}</a>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- About Section (Dynamic) -->
<section id="about" class="py-24 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-16 items-center mb-16">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 mb-6">About Uplifta</h2>
                @if(isset($about))
                    <p class="text-gray-600 mb-4">{{ $about->description_1 }}</p>
                    <p class="text-gray-600 mb-4">{{ $about->description_2 }}</p>
                    <p class="text-gray-600 mb-4">{{ $about->description_3 }}</p>
                @else
                    <p class="text-gray-600 mb-4">
                        Founded with a mission to break the cycle of poverty through financial inclusion, Uplifta has been empowering underserved communities for over a decade. We believe that everyone deserves access to financial services that can help them build a better future.
                    </p>
                    <p class="text-gray-600 mb-4">
                        Our approach combines traditional microfinance with modern technology and comprehensive financial education to create sustainable impact. We work closely with local communities to understand their unique needs and provide tailored solutions that drive real change.
                    </p>
                    <p class="text-gray-600 mb-4">
                        With a 98% loan repayment rate and thousands of success stories, we've proven that when people are given the right tools and support, they can achieve extraordinary things.
                    </p>
                @endif
            </div>
            <div class="flex justify-center items-center">
                <div class="relative w-full max-w-md h-72 rounded-2xl overflow-hidden shadow-xl bg-gradient-to-br from-blue-600 to-blue-300 flex items-center justify-center">
                    <div class="absolute inset-0 bg-blue-700/10 flex flex-col justify-center items-center text-white text-center p-8">
                        <i class="fas fa-users text-4xl mb-4 text-orange-400"></i>
                        <h3 class="text-lg font-bold mb-2">Community Impact</h3>
                        <p class="text-base">Connecting people, building futures</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid md:grid-cols-4 gap-8">
            @foreach($values as $value)
            <div class="bg-gray-50 rounded-xl p-8 text-center shadow hover:shadow-lg transition">
                <i class="{{ $value->icon }} text-3xl text-blue-600 mb-4"></i>
                <h4 class="font-semibold text-lg mb-2">{{ $value->title }}</h4>
                <p class="text-gray-500 text-sm">{{ $value->description }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Success Stories Section (Dynamic) -->
<section id="stories" class="py-24 bg-gradient-to-br from-blue-50 to-white">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center text-gray-900 mb-4">Success Stories</h2>
        <p class="text-center text-lg text-gray-500 mb-12 max-w-2xl mx-auto">
            Real stories from real people whose lives have been transformed through microfinance
        </p>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($stories as $story)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:-translate-y-2 hover:shadow-2xl transition flex flex-col">
                <div class="flex items-center justify-center h-40 {{ $story->icon_bg ?? 'bg-gradient-to-br from-blue-600 to-blue-400' }}">
                    <i class="{{ $story->icon }} text-5xl text-white"></i>
                </div>
                <div class="p-8 flex-1 flex flex-col">
                    <p class="italic text-gray-500 mb-6 flex-1">"{{ $story->quote }}"</p>
                    <div class="flex items-center gap-3">
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $story->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $story->role }}, {{ $story->country }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Application Section (Dynamic Form) -->
<section id="apply" class="py-24 bg-white">
    <div class="max-w-3xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center text-gray-900 mb-4">Apply for a Loan</h2>
        <p class="text-center text-lg text-gray-500 mb-12 max-w-2xl mx-auto">
            Start your journey towards financial empowerment with our simple application process
        </p>
        <div class="bg-gray-50 rounded-2xl shadow-xl p-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('applications.store') }}" class="space-y-6">
                @csrf
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">First Name *</label>
                        <input type="text" name="first_name" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none" value="{{ old('first_name') }}">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Last Name *</label>
                        <input type="text" name="last_name" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none" value="{{ old('last_name') }}">
                    </div>
                </div>
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Email Address *</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none" value="{{ old('email') }}">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Phone Number *</label>
                        <input type="tel" name="phone" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none" value="{{ old('phone') }}">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Address *</label>
                    <textarea name="address" rows="3" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none">{{ old('address') }}</textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Country *</label>
                    <input type="text" name="country" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none" value="{{ old('country') }}">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Business Name *</label>
                    <input type="text" name="business_name" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none" value="{{ old('business_name') }}">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Business Type *</label>
                    <input type="text" name="business_type" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none" value="{{ old('business_type') }}">
                </div>
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Years in Business</label>
                        <input type="number" name="years_in_business" min="0" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none" value="{{ old('years_in_business') }}">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Number of Employees</label>
                        <input type="number" name="employees" min="0" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none" value="{{ old('employees') }}">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Average Monthly Revenue (USD)</label>
                    <input type="number" name="monthly_revenue" min="0" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none" value="{{ old('monthly_revenue') }}">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Business Description *</label>
                    <textarea name="business_description" rows="4" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none">{{ old('business_description') }}</textarea>
                </div>
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Loan Amount (USD) *</label>
                        <input type="number" name="loan_amount" min="500" max="50000" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none" value="{{ old('loan_amount') }}">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Loan Term (months) *</label>
                        <input type="number" name="loan_term" min="6" max="36" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none" value="{{ old('loan_term') }}">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Loan Purpose *</label>
                    <input type="text" name="loan_purpose" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none" value="{{ old('loan_purpose') }}">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">How will you use this loan? *</label>
                    <textarea name="loan_description" rows="4" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none">{{ old('loan_description') }}</textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Repayment Plan *</label>
                    <textarea name="repayment_plan" rows="3" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none">{{ old('repayment_plan') }}</textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 rounded-full bg-blue-600 text-white font-semibold shadow hover:bg-blue-700 transition">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Get Involved Section (Dynamic) -->
<section id="involved" class="py-24 bg-gradient-to-br from-blue-700 to-blue-400 text-white">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-4">Get Involved</h2>
        <p class="text-center text-lg mb-12 max-w-2xl mx-auto opacity-90">
            Join us in creating lasting change in underserved communities around the world
        </p>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($involvements as $involvement)
            <div class="bg-white/10 rounded-2xl p-8 text-center shadow-lg border border-white/20 hover:bg-white/20 hover:-translate-y-2 transition flex flex-col items-center">
                <i class="{{ $involvement->icon }} text-4xl mb-4 text-orange-400"></i>
                <h3 class="text-xl font-semibold mb-2">{{ $involvement->title }}</h3>
                <p class="mb-6 opacity-90">{{ $involvement->description }}</p>
                @if($involvement->cta_label && $involvement->cta_link)
                <a href="{{ $involvement->cta_link }}" class="inline-block px-6 py-2 rounded-full border-2 border-white text-white font-semibold hover:bg-white hover:text-blue-700 transition">{{ $involvement->cta_label }}</a>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Contact Section (Enhanced) -->
<section id="contact" class="py-24 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center text-gray-900 mb-4">Contact Us</h2>
        <p class="text-center text-lg text-gray-500 mb-12 max-w-2xl mx-auto">
            Ready to get started? Have questions? We're here to help you every step of the way.
        </p>
        <div class="grid md:grid-cols-2 gap-12">
            <div class="space-y-8">
                <h3 class="text-2xl font-semibold text-blue-700 mb-4">Get in Touch</h3>
                <div class="flex items-start gap-4">
                    <span class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-blue-400 text-white text-xl shadow-lg"><i class="fas fa-map-marker-alt"></i></span>
                    <div>
                        <h4 class="font-semibold text-gray-900">Our Office</h4>
                        <p class="text-gray-500 text-sm">123 Financial District<br>Microfinance Plaza, Suite 500<br>Global City, GC 12345</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <span class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-blue-400 text-white text-xl shadow-lg"><i class="fas fa-phone"></i></span>
                    <div>
                        <h4 class="font-semibold text-gray-900">Phone</h4>
                        <p class="text-gray-500 text-sm">+1 (555) 123-4567<br>Mon-Fri: 9AM-6PM</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <span class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-blue-400 text-white text-xl shadow-lg"><i class="fas fa-envelope"></i></span>
                    <div>
                        <h4 class="font-semibold text-gray-900">Email</h4>
                        <p class="text-gray-500 text-sm">info@uplifta.org<br>support@uplifta.org</p>
                    </div>
                </div>
                <div class="flex gap-3 mt-4">
                    <a href="#" aria-label="Facebook" class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 text-white hover:bg-blue-700 shadow transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter" class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 text-white hover:bg-blue-700 shadow transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="LinkedIn" class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 text-white hover:bg-blue-700 shadow transition"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" aria-label="Instagram" class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 text-white hover:bg-blue-700 shadow transition"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-2xl p-8 border border-blue-100">
                @if(session('contact_success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('contact_success') }}</div>
                @endif
                <h3 class="text-xl font-semibold text-blue-700 mb-4">Send us a Message</h3>
                <form method="POST" action="{{ route('contact-messages.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Name *</label>
                        <input type="text" name="name" required class="w-full px-4 py-2 border-2 border-blue-100 rounded-lg focus:border-blue-500 focus:outline-none" value="{{ old('name') }}">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Email *</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border-2 border-blue-100 rounded-lg focus:border-blue-500 focus:outline-none" value="{{ old('email') }}">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Subject *</label>
                        <input type="text" name="subject" required class="w-full px-4 py-2 border-2 border-blue-100 rounded-lg focus:border-blue-500 focus:outline-none" value="{{ old('subject') }}">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Message *</label>
                        <textarea name="message" rows="5" required class="w-full px-4 py-2 border-2 border-blue-100 rounded-lg focus:border-blue-500 focus:outline-none">{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" class="w-full px-6 py-3 rounded-full bg-blue-600 text-white font-semibold shadow hover:bg-blue-700 transition">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Footer (Enhanced) -->
<footer class="bg-gradient-to-br from-blue-900 to-blue-700 text-white pt-16 pb-6 mt-24">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-8 mb-8">
            <div>
                <h3 class="text-2xl font-bold mb-2">Uplifta</h3>
                <p class="text-blue-100 mb-4">Empowering growth, one step at a time. Building stronger communities through accessible financial services and education.</p>
                <div class="flex gap-3 mt-2">
                    <a href="#" aria-label="Facebook" class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-600 text-white hover:bg-blue-700 shadow transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter" class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-600 text-white hover:bg-blue-700 shadow transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="LinkedIn" class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-600 text-white hover:bg-blue-700 shadow transition"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" aria-label="Instagram" class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-600 text-white hover:bg-blue-700 shadow transition"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-2">Services</h3>
                <ul class="space-y-1">
                    <li><a href="#services" class="text-blue-100 hover:text-white transition">Microloans</a></li>
                    <li><a href="#services" class="text-blue-100 hover:text-white transition">Savings Programs</a></li>
                    <li><a href="#services" class="text-blue-100 hover:text-white transition">Financial Literacy</a></li>
                    <li><a href="#apply" class="text-blue-100 hover:text-white transition">Loan Application</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-2">Company</h3>
                <ul class="space-y-1">
                    <li><a href="#about" class="text-blue-100 hover:text-white transition">About Us</a></li>
                    <li><a href="#stories" class="text-blue-100 hover:text-white transition">Success Stories</a></li>
                    <li><a href="#involved" class="text-blue-100 hover:text-white transition">Get Involved</a></li>
                    <li><a href="#contact" class="text-blue-100 hover:text-white transition">Contact</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-2">Resources</h3>
                <ul class="space-y-1">
                    <li><a href="#" class="text-blue-100 hover:text-white transition">Financial Guides</a></li>
                    <li><a href="#" class="text-blue-100 hover:text-white transition">FAQ</a></li>
                    <li><a href="#" class="text-blue-100 hover:text-white transition">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-blue-800 pt-6 text-center text-blue-200 text-sm">
            &copy; {{ date('Y') }} Uplifta. All rights reserved. | Empowering communities through financial inclusion.
        </div>
    </div>
</footer>
@endsection
