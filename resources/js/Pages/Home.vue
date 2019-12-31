<template>
    <div class="container mx-auto pt-8">
        <section class="mb-8">
            <div class="flex justify-between items-center">
                <h2 class="font-bold text-xl">
                    Histories
                </h2>

                <InertiaLink :href="$route('history.create')">Start new history</InertiaLink>
            </div>

            <ul>
                <li v-for="history in $page.histories" :key="history.id">
                    <inertia-link :href="$route('history.play', history)">
                        {{ history.name }}
                    </inertia-link>
                </li>
            </ul>
        </section>

        <section>
            <h2 class="font-bold text-xl">
                Your Games
            </h2>

            <ul>
                <li v-for="game in $page.games" :key="game.id">
                    <inertia-link :href="$route('history.play', game)">
                        {{ game.name }}
                    </inertia-link>
                </li>
            </ul>
        </section>
    </div>
</template>

<script>
import Layout from "./Layout";

export default {
    name: 'HomePage',

    layout: Layout,
};
</script>
