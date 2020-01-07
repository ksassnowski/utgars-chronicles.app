@extends('page')

@section('content')
    <div class="pb-8">
        <header class="page-header relative px-4">
            <div class="flex items-center justify-between">
                <a href="/" class="text-xl font-bold tracking-tight text-indigo-100">Utgar's Chronicles</a>

                <nav class="px-4 py-4 flex justify-end items-center">
                    <a href="{{ route('login') }}" class="text-indigo-100 px-4">Login</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-sm text-indigo-700 bg-white hover:bg-indigo-100">Register</a>
                </nav>
            </div>

            <div class="container mx-auto px-4 pt-4 md:pt-12 pb-4">
                <div class="md:w-2/3 lg:w-1/2">
                    <h1 class="text-3xl md:text-5xl font-bold tracking-wider text-white brand-shadow">Play Microscope online</h1>
                    <p class="text-indigo-200 mb-10">
                        Utgar's Chronicles is a website that enables you to play Microscope with your friends all over the world.
                        Completely for free. I just really like this game!
                    </p>

                    <a
                        href="{{ route('register') }}"
                        class="bg-gray-100 text-gray-800 font-bold text-lg px-6 py-3 rounded shadow-lg hover:bg-indigo-600 hover:text-white"
                    >Sign Up for Free</a>
                </div>
            </div>
        </header>

        <main class="mt-32">
            <section class="container mx-auto px-4">
                <h2 class="text-4xl font-bold text-gray-800 tracking-tight text-center mb-12">{{ __('Frequently Asked Questions') }}</h2>

                <ul class="lg:px-48">
                    <li class="mb-8">
                        <p class="font-bold text-lg text-gray-800 mb-1">Is this an official product?</p>
                        <p class="text-gray-700">
                            No, <strong>Utgar's Chronicles</strong> is a fan-made service to help you play Microscope online. It has no affiliation with Lame Mage Productions or Ben Robbins.
                        </p>
                    </li>

                    <li class="mb-8">
                        <p class="font-bold text-lg text-gray-800 mb-1">Are the rules of the game included?</p>
                        <p class="text-gray-700">
                            No, it is assumed that you or someone you play with knows or owns the rules.
                            You can purchase them over at <a href="http://www.lamemage.com/microscope/" target="_blank" rel="noopener noreferrer" class="text-indigo-700 font-semibold">Lame Mage Productions</a>.
                            Trust me, it's worth it.
                        </p>
                    </li>

                    <li class="mb-8">
                        <p class="font-bold text-lg text-gray-800 mb-1">Does it cost anything?</p>
                        <p class="text-gray-700">
                            No, you can have as many histories as you want and play in as many games as you want.
                        </p>
                    </li>

                    <li class="mb-8">
                        <p class="font-bold text-lg text-gray-800 mb-1">Can I support the development and maintenance of Utgar's Chronicles?</p>
                        <p class="text-gray-700 mb-4">
                            Yes, thank you for asking! I do have a Patreon that you can use to help fund the development of this site.
                        </p>
                        <p class="flex justify-center">
                            <a href="https://www.patreon.com/bePatron?u=4095316" data-patreon-widget-type="become-patron-button">Become a Patron!</a>
                        </p>
                    </li>

                    <li class="mb-8">
                        <p class="font-bold text-lg text-gray-800 mb-1">What's up with the name?</p>
                        <p class="text-gray-700">
                            Utgar was a character from our very first game of Microscope and has been a sort of running gag ever since.
                        </p>
                    </li>
                </ul>
            </section>

        </main>

        <script async src="https://c6.patreon.com/becomePatronButton.bundle.js"></script>
    </div>
@endsection
