@extends('page')

@section('content')
    <div class="pb-8">
        <header class="page-header relative px-4">
            <nav class="px-4 py-4 flex justify-end items-center">
                <a href="{{ route('login') }}" class="text-indigo-100 px-4">Login</a>
                <a href="{{ route('register') }}" class="px-4 py-2 border-2 rounded-sm border-white text-white">Register</a>
            </nav>

            <div class="container mx-auto px-4 pt-4 md:pt-12 pb-4">
                <div class="md:w-2/3 lg:w-1/2">
                    <h1 class="text-3xl md:text-5xl font-bold tracking-wider text-indigo-100">Play Microscope online</h1>
                    <p class="text-indigo-300 mb-10">Lorem ipsum dolor sit amet, consectetur adipisicing elit. A delectus dignissimos explicabo harum id minus nisi, quisquam unde? A architecto culpa distinctio ex facere hic id nobis nulla rerum totam.</p>

                    <a
                        href="{{ route('register') }}"
                        class="bg-gray-100 text-gray-800 font-bold text-lg px-6 py-3 rounded shadow-lg hover:bg-indigo-600 hover:text-white"
                    >Sign Up for Free</a>
                </div>
            </div>
        </header>

        <main class="mt-32 container mx-auto px-4">
            <section class="mb-16">
                <h2 class="text-4xl font-bold text-gray-800 tracking-tight text-center mb-12">{{ __('What\'s inside?') }}</h2>

                <div class="flex mx-4 mb-12">
                    <div class="w-1/2 px-4">
                    </div>

                    <div class="w-1/2 px-4">
                        <h3 class="text-2xl font-bold text-gray-800">Feature 1</h3>
                        <p class="text-gray-700 pr-8">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus assumenda, atque delectus dolores ducimus ex exercitationem inventore nemo nesciunt numquam officiis quos sit tempore. Cum eum inventore laboriosam quia temporibus?</p>
                    </div>
                </div>

                <div class="flex mx-4 mb-12">
                    <div class="w-1/2 px-4">
                        <h3 class="text-2xl font-bold text-gray-800">Feature 2</h3>
                        <p class="text-gray-700 pr-8">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus assumenda, atque delectus dolores ducimus ex exercitationem inventore nemo nesciunt numquam officiis quos sit tempore. Cum eum inventore laboriosam quia temporibus?</p>
                    </div>

                    <div class="w-1/2 px-4">
                    </div>
                </div>

                <div class="flex mx-4 mb-12">
                    <div class="w-1/2 px-4">
                    </div>

                    <div class="w-1/2 px-4">
                        <h3 class="text-2xl font-bold text-gray-800">Feature 3</h3>
                        <p class="text-gray-700 pr-8">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus assumenda, atque delectus dolores ducimus ex exercitationem inventore nemo nesciunt numquam officiis quos sit tempore. Cum eum inventore laboriosam quia temporibus?</p>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="text-4xl font-bold text-gray-800 tracking-tight text-center mb-12">{{ __('Frequently Asked Questions') }}</h2>

                <ul class="px-24">
                    <li class="mb-8">
                        <p class="font-bold text-lg text-gray-800 mb-1">Will this cure my baldness?</p>
                        <p class="text-gray-700">Yes! It absolutely will. (Disclaimer: It might not actually cure your baldness).</p>
                    </li>

                    <li class="mb-8">
                        <p class="font-bold text-lg text-gray-800 mb-1">Does it cost anything?</p>
                        <p class="text-gray-700">I might invoke the Law of Surprise.</p>
                    </li>
                </ul>
            </section>
        </main>
    </div>
@endsection
