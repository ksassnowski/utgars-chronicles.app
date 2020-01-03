@extends('page')

@section('content')
<div class="container mx-auto pt-8 px-4">
    <div class="md:w-1/2 mx-auto bg-white p-4 shadow-lg rounded border border-gray-300">
        <form action="{{ route('password.update') }}" method="POST">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-4">
                <label class="label" for="email">Email</label>
                <input class="input" type="email" id="email" name="email" value="{{ old('email', $email) }}" required>

                @if ($errors->has('email'))
                    <small class="mt-1 text-xs text-red-400">{{ $errors->first('email') }}</small>
                @endif
            </div>

            <div class="mb-4">
                <label class="label" for="password">New Password</label>
                <input class="input" type="password" name="password" id="password" autofocus required>

                @if ($errors->has('password'))
                    <small class="mt-1 text-xs text-red-400">{{ $errors->first('password') }}</small>
                @endif
            </div>

            <div class="mb-4">
                <label class="label" for="password_confirmation">Confirm Password</label>
                <input class="input" type="password" name="password_confirmation" id="password_confirmation" required>
            </div>

            <button type="submit" class="bg-indigo-600 w-full py-3 text-white rounded font-bold test-sm">Change Password</button>
        </form>
    </div>
</div>
@endsection
