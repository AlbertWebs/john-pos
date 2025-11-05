<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Spare Parts POS</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center" x-data="pinPad()">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Spare Parts POS</h1>
                <p class="text-gray-600">Enter your PIN to continue</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                
                <!-- Username Input -->
                <div class="mb-6">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username"
                        x-model="username"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Enter username"
                        autocomplete="username"
                        required
                    >
                </div>

                <!-- PIN Display -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">PIN</label>
                    <div class="flex gap-2 justify-center">
                        <template x-for="(digit, index) in 4" :key="index">
                            <div 
                                class="w-16 h-16 border-2 rounded-lg flex items-center justify-center text-2xl font-bold transition-all"
                                :class="pin.length > index ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-300 bg-gray-50 text-gray-400'"
                            >
                                <span x-show="pin.length > index" x-text="pin[index]"></span>
                                <span x-show="pin.length <= index">•</span>
                            </div>
                        </template>
                    </div>
                    <!-- Hidden PIN input for form submission -->
                    <input type="hidden" name="pin" x-model="pin" :value="pin" required>
                </div>

                <!-- PIN Pad -->
                <div class="grid grid-cols-3 gap-3 mb-6">
                    <template x-for="num in [1,2,3,4,5,6,7,8,9,0]" :key="num">
                        <button
                            type="button"
                            @click="enterDigit(num)"
                            class="h-16 bg-gray-100 hover:bg-blue-500 hover:text-white text-gray-800 font-bold text-xl rounded-lg transition-all transform hover:scale-105 active:scale-95 shadow-sm"
                            x-transition
                        >
                            <span x-show="num !== 0" x-text="num"></span>
                            <span x-show="num === 0" class="flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </span>
                        </button>
                    </template>
                    <button
                        type="button"
                        @click="clearPin()"
                        class="h-16 bg-red-100 hover:bg-red-500 hover:text-white text-red-700 font-bold rounded-lg transition-all transform hover:scale-105 active:scale-95 shadow-sm col-span-2"
                    >
                        Clear
                    </button>
                </div>

                <button
                    type="submit"
                    :disabled="pin.length !== 4 || !username"
                    @click="ensureFormValues()"
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 rounded-lg font-semibold text-lg hover:from-blue-700 hover:to-indigo-700 transition-all transform hover:scale-105 active:scale-95 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Login
                </button>
            </form>
        </div>
    </div>

    <script>
        function pinPad() {
            return {
                username: '',
                pin: '',
                
                enterDigit(num) {
                    if (this.pin.length < 4) {
                        this.pin += num.toString();
                        
                        // Update hidden input immediately
                        const pinInput = document.querySelector('input[name="pin"]');
                        if (pinInput) {
                            pinInput.value = this.pin;
                        }
                        
                        // Auto-submit when PIN is complete
                        if (this.pin.length === 4 && this.username) {
                            // Use a small delay to ensure all values are set
                            setTimeout(() => {
                                this.submitForm();
                            }, 100);
                        }
                    }
                },
                
                submitForm() {
                    const form = document.getElementById('loginForm');
                    if (!form) return;
                    
                    // Ensure all form values are set
                    const pinInput = form.querySelector('input[name="pin"]');
                    if (pinInput) {
                        pinInput.value = this.pin;
                    }
                    
                    const usernameInput = form.querySelector('input[name="username"]');
                    if (usernameInput) {
                        usernameInput.value = this.username;
                    }
                    
                    // Ensure CSRF token is present
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (csrfToken) {
                        let tokenInput = form.querySelector('input[name="_token"]');
                        if (!tokenInput) {
                            tokenInput = document.createElement('input');
                            tokenInput.type = 'hidden';
                            tokenInput.name = '_token';
                            form.appendChild(tokenInput);
                        }
                        tokenInput.value = csrfToken.getAttribute('content');
                    }
                    
                    // Submit the form normally (this preserves CSRF token)
                    // Use form.submit() which will include all form fields including CSRF token
                    form.submit();
                },
                
                clearPin() {
                    this.pin = '';
                    const form = document.getElementById('loginForm');
                    const pinInput = form.querySelector('input[name="pin"]');
                    if (pinInput) {
                        pinInput.value = '';
                    }
                },
                
                ensureFormValues() {
                    const form = document.getElementById('loginForm');
                    const pinInput = form.querySelector('input[name="pin"]');
                    if (pinInput) {
                        pinInput.value = this.pin;
                    }
                    const usernameInput = form.querySelector('input[name="username"]');
                    if (usernameInput) {
                        usernameInput.value = this.username;
                    }
                    // Ensure CSRF token is present and up-to-date
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (csrfToken) {
                        let tokenInput = form.querySelector('input[name="_token"]');
                        if (!tokenInput) {
                            tokenInput = document.createElement('input');
                            tokenInput.type = 'hidden';
                            tokenInput.name = '_token';
                            form.appendChild(tokenInput);
                        }
                        tokenInput.value = csrfToken.getAttribute('content');
                    }
                }
            }
        }
    </script>
</body>
</html>
