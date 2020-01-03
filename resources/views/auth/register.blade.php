@extends('page')

@section('content')
<div class="container mx-auto pt-8 px-4">
    <div class="md:w-1/2 mx-auto bg-white p-4 shadow-lg rounded border border-gray-300">
        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="label" for="name">Name</label>
                <input class="input" type="text" id="name" name="name" autofocus required>
                <small class="text-xs text-gray-600 mt-1">Your name will show up to other players when playing a game. You can use a nickname if you want.</small>

                @if ($errors->has('name'))
                    <small class="mt-1 text-xs text-red-400">{{ $errors->first('name') }}</small>
                @endif
            </div>

            <div class="mb-4">
                <label class="label" for="email">Email</label>
                <input class="input" type="email" id="email" name="email" required>

                @if ($errors->has('email'))
                    <small class="mt-1 text-xs text-red-400">{{ $errors->first('email') }}</small>
                @endif
            </div>

            <div class="mb-4">
                <label class="label" for="password">Password</label>
                <input class="input" type="password" id="password" name="password" required>

                @if ($errors->has('password'))
                    <small class="mt-1 text-xs text-red-400">{{ $errors->first('password') }}</small>
                @endif
            </div>

            <div class="mb-4">
                <label class="label" for="password_confirmation">Confirm Password</label>
                <input class="input" type="password" id="password_confirmation" name="password_confirmation">
            </div>

            <button type="submit" class="bg-indigo-600 w-full py-3 text-white rounded font-bold test-sm">Login</button>
        </form>
    </div>
</div>
@endsection
