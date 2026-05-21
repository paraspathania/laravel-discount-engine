<x-guest-layout>
    <div class="mb-8 text-center sm:text-left">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Create an account</h1>
        <p class="mt-2 text-gray-500">Start shopping smarter and saving more today.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                pattern="^[a-zA-Z\s]+$" title="The name field must contain only alphabets and spaces."
                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 focus:bg-white transition-all text-sm">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 focus:bg-white transition-all text-sm"
                oninput="checkEmail(this.value)">

            {{-- Server-side error (e.g. already taken, invalid format) --}}
            <x-input-error :messages="$errors->get('email')" class="mt-2" />

            {{-- Live client-side hints (hidden until user starts typing) --}}
            <div id="email-hints" class="mt-2 space-y-1 hidden">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Email requirements</p>
                <div class="flex items-center gap-2 text-xs" id="hint-has-at">
                    <span id="icon-has-at" class="w-4 h-4 flex items-center justify-center rounded-full bg-gray-200 text-gray-400 font-bold text-[10px] shrink-0">✕</span>
                    <span class="text-gray-500">Contains @ symbol</span>
                </div>
                <div class="flex items-center gap-2 text-xs" id="hint-has-domain">
                    <span id="icon-has-domain" class="w-4 h-4 flex items-center justify-center rounded-full bg-gray-200 text-gray-400 font-bold text-[10px] shrink-0">✕</span>
                    <span class="text-gray-500">Valid domain (e.g. gmail.com)</span>
                </div>
                <div class="flex items-center gap-2 text-xs" id="hint-no-spaces">
                    <span id="icon-no-spaces" class="w-4 h-4 flex items-center justify-center rounded-full bg-gray-200 text-gray-400 font-bold text-[10px] shrink-0">✕</span>
                    <span class="text-gray-500">No spaces allowed</span>
                </div>
                <div class="flex items-center gap-2 text-xs" id="hint-local-part">
                    <span id="icon-local-part" class="w-4 h-4 flex items-center justify-center rounded-full bg-gray-200 text-gray-400 font-bold text-[10px] shrink-0">✕</span>
                    <span class="text-gray-500">Valid local part (before @)</span>
                </div>
                <div class="flex items-center gap-2 text-xs" id="hint-tld">
                    <span id="icon-tld" class="w-4 h-4 flex items-center justify-center rounded-full bg-gray-200 text-gray-400 font-bold text-[10px] shrink-0">✕</span>
                    <span class="text-gray-500">Has a valid extension (.com, .in, etc.)</span>
                </div>
            </div>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" 
                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 focus:bg-white transition-all text-sm">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 focus:bg-white transition-all text-sm">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="w-full bg-gray-900 hover:bg-indigo-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg hover:-translate-y-0.5 mt-2">
            Create account
        </button>

        <div class="pt-4 text-center text-sm font-medium text-gray-600">
            Already have an account? 
            <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-500 transition-colors">
                Log in
            </a>
        </div>
    </form>

    <script>
        function checkEmail(value) {
            const hintsBox = document.getElementById('email-hints');

            if (value.length === 0) {
                hintsBox.classList.add('hidden');
                return;
            }
            hintsBox.classList.remove('hidden');

            const checks = {
                'has-at':     value.includes('@'),
                'no-spaces':  !/\s/.test(value),
                'has-domain': /^[^@]+@[^@]+\.[^@]+$/.test(value),
                'local-part': /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@/.test(value),
                'tld':        /\.[a-zA-Z]{2,}$/.test(value),
            };

            Object.entries(checks).forEach(([key, passed]) => {
                const icon = document.getElementById('icon-' + key);
                if (!icon) return;
                if (passed) {
                    icon.textContent = '✓';
                    icon.className = 'w-4 h-4 flex items-center justify-center rounded-full bg-green-100 text-green-600 font-bold text-[10px] shrink-0';
                } else {
                    icon.textContent = '✕';
                    icon.className = 'w-4 h-4 flex items-center justify-center rounded-full bg-red-100 text-red-500 font-bold text-[10px] shrink-0';
                }
            });
        }

        // Run on load if old() value is pre-filled after a validation error
        const emailInput = document.getElementById('email');
        if (emailInput && emailInput.value) {
            checkEmail(emailInput.value);
        }
    </script>
</x-guest-layout>
