<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-2">
                    <a href="{{ route('dashboard') }}" class="text-purple-600 hover:text-purple-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight truncate">
                        Contact School
                    </h2>
                </div>
                <p class="text-xs sm:text-sm text-gray-600">Send a message or inquiry to the school administration</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8">
            
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-lg p-4 sm:p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm sm:text-base font-semibold text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-5">
                            <h3 class="text-xl font-bold text-white flex items-center gap-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Send a Message
                            </h3>
                            <p class="text-purple-100 text-sm mt-1">We'll get back to you as soon as possible</p>
                        </div>
                        
                        <form action="{{ route('guardian.contact.submit') }}" method="POST" class="p-6 space-y-6">
                            @csrf
                            
                            <!-- Student Selection -->
                            <div>
                                <label for="student_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Regarding Child <span class="text-red-500">*</span>
                                </label>
                                <select name="student_id" id="student_id" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 @error('student_id') border-red-500 @enderror">
                                    <option value="">Select a child</option>
                                    @foreach(auth()->user()->guardian->students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->user->name }} ({{ $student->admission_number }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Subject/Topic -->
                            <div>
                                <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Subject <span class="text-red-500">*</span>
                                </label>
                                <select name="subject" id="subject" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 @error('subject') border-red-500 @enderror">
                                    <option value="">Select a topic</option>
                                    <option value="Academic Performance" {{ old('subject') == 'Academic Performance' ? 'selected' : '' }}>Academic Performance</option>
                                    <option value="Attendance Issue" {{ old('subject') == 'Attendance Issue' ? 'selected' : '' }}>Attendance Issue</option>
                                    <option value="Behavioral Concern" {{ old('subject') == 'Behavioral Concern' ? 'selected' : '' }}>Behavioral Concern</option>
                                    <option value="Fee Inquiry" {{ old('subject') == 'Fee Inquiry' ? 'selected' : '' }}>Fee Inquiry</option>
                                    <option value="Health/Medical" {{ old('subject') == 'Health/Medical' ? 'selected' : '' }}>Health/Medical</option>
                                    <option value="Schedule/Timetable" {{ old('subject') == 'Schedule/Timetable' ? 'selected' : '' }}>Schedule/Timetable</option>
                                    <option value="Teacher Meeting Request" {{ old('subject') == 'Teacher Meeting Request' ? 'selected' : '' }}>Teacher Meeting Request</option>
                                    <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                    <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('subject')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Priority -->
                            <div>
                                <label for="priority" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Priority <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-3 gap-3">
                                    <label class="relative flex cursor-pointer">
                                        <input type="radio" name="priority" value="low" {{ old('priority', 'normal') == 'low' ? 'checked' : '' }}
                                               class="peer sr-only" required>
                                        <div class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 bg-white peer-checked:border-green-500 peer-checked:bg-green-50 transition-all text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <div class="w-3 h-3 bg-green-500 rounded-full peer-checked:block hidden"></div>
                                                <span class="font-semibold text-gray-700 peer-checked:text-green-700">Low</span>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <label class="relative flex cursor-pointer">
                                        <input type="radio" name="priority" value="normal" {{ old('priority', 'normal') == 'normal' ? 'checked' : '' }}
                                               class="peer sr-only">
                                        <div class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 bg-white peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <div class="w-3 h-3 bg-blue-500 rounded-full peer-checked:block hidden"></div>
                                                <span class="font-semibold text-gray-700 peer-checked:text-blue-700">Normal</span>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <label class="relative flex cursor-pointer">
                                        <input type="radio" name="priority" value="urgent" {{ old('priority') == 'urgent' ? 'checked' : '' }}
                                               class="peer sr-only">
                                        <div class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 bg-white peer-checked:border-red-500 peer-checked:bg-red-50 transition-all text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <div class="w-3 h-3 bg-red-500 rounded-full peer-checked:block hidden"></div>
                                                <span class="font-semibold text-gray-700 peer-checked:text-red-700">Urgent</span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @error('priority')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Message -->
                            <div>
                                <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Message <span class="text-red-500">*</span>
                                </label>
                                <textarea name="message" id="message" rows="8" required
                                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 @error('message') border-red-500 @enderror"
                                          placeholder="Please provide detailed information about your inquiry or concern...">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Minimum 20 characters</p>
                            </div>
                            
                            <!-- Contact Preferences -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Preferred Contact Method
                                </label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="contact_method" value="email" {{ old('contact_method', 'email') == 'email' ? 'checked' : '' }}
                                               class="w-4 h-4 text-purple-600 focus:ring-purple-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Email</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="contact_method" value="phone" {{ old('contact_method') == 'phone' ? 'checked' : '' }}
                                               class="w-4 h-4 text-purple-600 focus:ring-purple-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Phone Call</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="contact_method" value="meeting" {{ old('contact_method') == 'meeting' ? 'checked' : '' }}
                                               class="w-4 h-4 text-purple-600 focus:ring-purple-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">In-Person Meeting</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="flex gap-3 pt-4">
                                <button type="submit"
                                        class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold rounded-lg transition-all active:scale-95 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Send Message
                                </button>
                                <a href="{{ route('dashboard') }}"
                                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Contact Information Sidebar -->
                <div class="space-y-6">
                    
                    <!-- School Contact Info -->
                    <div class="bg-gradient-to-br from-purple-600 to-indigo-700 rounded-xl shadow-lg overflow-hidden text-white">
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold">School Office</h3>
                                    <p class="text-sm text-purple-100">Direct Contact</p>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-purple-100">Phone</p>
                                        <p class="font-semibold">+254 123 456 789</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-purple-100">Email</p>
                                        <p class="font-semibold text-sm">info@clss.ac.ke</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-purple-200 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-purple-100">Address</p>
                                        <p class="font-semibold text-sm">Christian Leberg School<br>Nairobi, Kenya</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Office Hours -->
                    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-4 py-3 border-b border-blue-100">
                            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Office Hours
                            </h3>
                        </div>
                        <div class="p-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Monday - Friday</span>
                                <span class="font-semibold text-gray-900">8:00 AM - 5:00 PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Saturday</span>
                                <span class="font-semibold text-gray-900">9:00 AM - 1:00 PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Sunday</span>
                                <span class="font-semibold text-red-600">Closed</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Tips -->
                    <div class="bg-amber-50 rounded-xl border-2 border-amber-200 p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-amber-900 mb-1">Quick Tips</h4>
                                <ul class="text-xs text-amber-800 space-y-1">
                                    <li>• Be specific about your concern</li>
                                    <li>• Include relevant dates if applicable</li>
                                    <li>• For urgent matters, call directly</li>
                                    <li>• We respond within 24-48 hours</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
