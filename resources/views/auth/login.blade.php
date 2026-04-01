<x-guest-layout>
    <div class="log_form">
        <div class="logo"><span></span></div>
        <h2>Нэвтрэх</h2>
        {{-- <div :status="session('status')"></div> --}}

        <form method="POST" action="{{ route('authenticate') }}">
            @csrf
            <div class="form_item">
                <label class="form_label" for="email">Нэвтрэх нэр</label>
                <input id="email" class="form_input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="form_item">
                <label for="password" class="form_label">Нууц үг</label>
                <input id="password" class="form_input"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                <error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="dcsb">
                <label for="remember_me" class="form-checkbox-label">
                    <input id="remember_me" type="checkbox" class="form-checkbox" name="remember">
                    <span class="form-checkmark"></span>
                    <span class="label-text">Сануулах</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="" href="{{ route('password.request') }}">Нууц үг мартсан?</a>
                @endif
            </div>
            <div class="mt1_6"><button class="__btn btn_primary w10">Нэвтрэх</button></div>
        </form>
    </div>
</x-guest-layout>
