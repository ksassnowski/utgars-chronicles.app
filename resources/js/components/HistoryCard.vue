<template>
    <InertiaLink
        :href="$route('history.play', this.history)"
        class="px-8 py-6 rounded-lg shadow-lg flex flex-col items-center justify-center h-full bg-indigo-700"
    >
        <h4 class="font-bold text-xl text-indigo-100 text-center">
            {{ history.name }}
        </h4>
    </InertiaLink>
</template>

<script>
export default {
    name: 'HistoryCard',

    props: ['history'],
};
</script>
