<section x-data="{ confirmingUserDeletion: false }">
    <header style="margin-bottom: 24px;">
        <h2 class="card-title text-danger">
            {{ __('app.delete_account') }}
        </h2>
        <p class="text-sm text-muted">
            {{ __('app.delete_account_msg') }}
        </p>
    </header>
    <button type="button" class="btn btn-danger" @click="confirmingUserDeletion = true">
        {{ __('app.delete_account') }}
    </button>
    <!-- Modal structure powered by Alpine -->
    <div x-show="confirmingUserDeletion" style="display: flex; justify-content: center; align-items: center; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 1000;" x-cloak x-transition>
        <div style="background: white; padding: 24px; border-radius: 12px; max-width: 500px; width: 90%;" @click.away="confirmingUserDeletion = false">
            <h2 class="card-title mb-4">{{ __('app.delete_account') }}?</h2>
            <p class="text-sm text-muted mb-6">
                {{ __('app.delete_account_confirm') }}
            </p>

            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="form-group">
                    <label for="password">{{ __('app.password') }}</label>
                    <input type="password" id="password" name="password" placeholder="{{ __('app.password') }}" autofocus required />
                    @if($errors->userDeletion->has('password'))
                        <div class="form-error">{{ $errors->userDeletion->first('password') }}</div>
                    @endif
                </div>

                <div class="flex flex-between gap-3 mt-4" style="justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" @click="confirmingUserDeletion = false">{{ __('app.cancel') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('app.delete_account') }}</button>
                </div>
            </form>
        </div>
    </div>
</section>
