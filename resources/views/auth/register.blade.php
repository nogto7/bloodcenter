<x-guest-layout>
    <div class="log_form">
        <h2>Бүртгүүлэх</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="form_item">
                <label class="form_label" for="name">Нэр</label>
                <input id="name" class="form_input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="form_item">
                <label class="form_label" for="email">И-мэйл хаяг</label>
                <input id="email" class="form_input" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="form_item">
                <label class="form_label" for="password">Нууц үг</label>

                <input id="password" class="form_input"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="form_item">
                <label class="form_label" for="password_confirmation">Нууц үг</label>
                <input id="password_confirmation" class="form_input"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <button class="__btn btn_primary mt2">Бүртгүүлэх</button>
            </div>
        </form>
    </div>
</x-guest-layout>
