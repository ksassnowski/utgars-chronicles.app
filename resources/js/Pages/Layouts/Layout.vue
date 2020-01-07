<template>
    <div class="flex flex-col flex-1">
        <header class="px-4 flex justify-between items-center bg-indigo-800 mb-8">
            <InertiaLink :href="$route('home')" class="text-xl font-bold tracking-tight text-indigo-100">
                Utgar's Chronicles
            </InertiaLink>

            <UserNavigation />
        </header>

        <main class="flex-1">
            <FlashMessage />

            <slot />
        </main>

        <portal-target name="modal" slim></portal-target>
    </div>
</template>

<script>
import UserNavigation from "../../components/UserNavigation";
import FlashMessage from "../../components/FlashMessage";

export default {
    name: 'Layout',

    components: {
        FlashMessage,
        UserNavigation,
    },
};
</script>
