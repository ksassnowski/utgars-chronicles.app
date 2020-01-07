<template>
    <div class="flex flex-col flex-1">
        <header class="px-4 flex justify-between items-center bg-indigo-800 mb-8">
            <div class="flex items-center">
                <InertiaLink :href="$route('home')" class="text-xl font-bold tracking-tight text-indigo-100 mr-8">
                    Utgar's Chronicles
                </InertiaLink>

                <InertiaLink :href="$route('home')" class="text-indigo-100 py-6 flex items-center hover:bg-indigo-700 px-4">
                    Dashboard
                </InertiaLink>
            </div>

            <UserNavigation />
        </header>

        <main class="flex-1">
            <slot />
        </main>

        <portal-target name="modal" slim></portal-target>
    </div>
</template>

<script>
import UserNavigation from "../../components/UserNavigation";

export default {
    name: 'Layout',

    components: {
        UserNavigation,
    },
};
</script>
