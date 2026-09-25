<section>
    <header style="margin-bottom: 24px;">
            {{ __('app.update_password') }}
        <p class="text-sm text-muted">
            {{ __('app.password_update_msg') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="current_password">{{ __('app.current_password') }}</label>
            <input id="current_password" name="current_password" type="password" autocomplete="current-password" />
            @if($errors->updatePassword->has('current_password'))
                <div class="form-error">{{ $errors->updatePassword->first('current_password') }}</div>
            @endif
        </div>

        <div class="form-group">
            <label for="password">{{ __('app.new_password') }}</label>
            <input id="password" name="password" type="password" autocomplete="new-password" />
            @if($errors->updatePassword->has('password'))
                <div class="form-error">{{ $errors->updatePassword->first('password') }}</div>
            @endif
        </div>

        <div class="form-group">
            <label for="password_confirmation">{{ __('app.confirm_password') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
            @if($errors->updatePassword->has('password_confirmation'))
                <div class="form-error">{{ $errors->updatePassword->first('password_confirmation') }}</div>
            @endif
        </div>

        <div class="flex flex-center gap-3">
            <button type="submit" class="btn btn-primary">{{ __('app.save_changes') }}</button>

            @if (session('status') === 'password-updated')
                <p class="text-sm text-success" style="color:var(--success); font-weight: 500;">{{ __('app.saved') }}</p>
            @endif
        </div>
    </form>
</section>
