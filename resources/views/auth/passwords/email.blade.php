@extends('page')

@section('content')

<div class="container">
    <div class="container mx-auto pt-8 px-4">
        <div class="md:w-1/2 mx-auto bg-white p-4 shadow-lg rounded border border-gray-300">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="label" for="email">Email</label>
                    <input class="input" type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>

                    @if ($errors->has('email'))
                        <small class="mt-1 text-xs text-red-400">{{ $errors->first('email') }}</small>
                    @endif
                </div>

                <button type="submit" class="bg-indigo-600 w-full py-3 text-white rounded font-bold test-sm">{{ __('Send Password Reset Link') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
