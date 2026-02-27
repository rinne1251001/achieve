@extends('layouts.app')
@section('title', '新しいパスワードの設定')

@section('content')
<div class="auth_wrapper" style="width: 100%; min-height: 100vh; display: flex; justify-content: center; align-items: center;">
    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 100vh;">
        <div style="width: 80%; max-width: 400px;">
            
            <div class="auth_form active">
                <h2 style="text-align: center;">新しいパスワード</h2>

                <form method="POST" action="{{ route('password.update') }}" style="display: grid; gap: 20px;">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div style="display: grid;">
                        <label for="email">email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required readonly>
                        <span class="error_msg" style="color: red;">
                            @error('email') {{ $message }} @enderror
                        </span>
                    </div>

                    <div style="display: grid;">
                        <label for="password">new password</label>
                        <input id="password" type="password" name="password" placeholder="新しいパスワード" required>
                        <span class="error_msg" style="color: red;">
                            @error('password') {{ $message }} @enderror
                        </span>
                    </div>

                    <div style="display: grid;">
                        <label for="password_confirmation">confirm password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="パスワード確認" required>
                    </div>

                    <button id="submitBtn" type="submit">パスワードを変更する</button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const submitBtn = document.getElementById('submitBtn') ;
    if(submitBtn){
            submitBtn.addEventListener('click', (e) => {
                if (window.Loader) Loader.show();
            });
        }
</script>
@endpush