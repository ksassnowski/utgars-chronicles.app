<template>
    <div class="flex flex-col flex-1">
        <header class="px-4 flex justify-between items-center bg-indigo-800 mb-8">
            <nav>
                <ul class="flex">
                    <li class="py-6">
                        <InertiaLink :href="$route('home')" class="hover:text-white text-indigo-200">Home</InertiaLink>
                    </li>
                </ul>
            </nav>

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
