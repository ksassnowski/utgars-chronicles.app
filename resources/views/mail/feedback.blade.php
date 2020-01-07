@component('mail::message')
The user `{{ $user->email }}` just submitted the following feedback:

@component('mail::panel')
    {{ $message }}
@endcomponent
@endcomponent
