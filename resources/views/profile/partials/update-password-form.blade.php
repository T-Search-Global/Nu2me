
<section class="container mt-5">
    <header class="mb-4">
        <h2 class="h5 text-dark">
            {{ __('Update Password') }}
        </h2>
        <p class="text-muted small">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="form-group mb-3">
            <label for="update_password_current_password">{{ __('Current Password') }}</label>
            <input type="password" name="current_password" id="update_password_current_password" class="form-control" autocomplete="current-password">
            @if ($errors->updatePassword->has('current_password'))
                <small class="text-danger">{{ $errors->updatePassword->first('current_password') }}</small>
            @endif
        </div>

        <div class="form-group mb-3">
            <label for="update_password_password">{{ __('New Password') }}</label>
            <input type="password" name="password" id="update_password_password" class="form-control" autocomplete="new-password">
            @if ($errors->updatePassword->has('password'))
                <small class="text-danger">{{ $errors->updatePassword->first('password') }}</small>
            @endif
        </div>

        <div class="form-group mb-3">
            <label for="update_password_password_confirmation">{{ __('Confirm Password') }}</label>
            <input type="password" name="password_confirmation" id="update_password_password_confirmation" class="form-control" autocomplete="new-password">
            @if ($errors->updatePassword->has('password_confirmation'))
                <small class="text-danger">{{ $errors->updatePassword->first('password_confirmation') }}</small>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'password-updated')
                <p class="text-muted small mb-0" id="password-saved">{{ __('Saved.') }}</p>
                <script>
                    setTimeout(() => {
                        document.getElementById('password-saved')?.remove();
                    }, 2000);
                </script>
            @endif
        </div>
    </form>
</section>

