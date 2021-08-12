<template>
    <div class="w-full items-center flex-grow sm:flex sm:w-auto">
        <div class="flex items-center flex-grow space-x-4">
            <NavigationLink
                :href="$route('home')"
                :is-active="
                    $page.component === 'Home' ||
                    $page.url.startsWith('/histories') ||
                    $page.url.startsWith('/games')
                "
            >
                Dashboard
            </NavigationLink>

            <NavigationLink
                v-if="$page.props.environment === 'local'"
                :href="$route('lfg.index')"
                :is-active="$page.url.startsWith('/lfg')"
                >Find a game</NavigationLink
            >
        </div>

        <div class="sm:flex items-center relative">
            <ul class="sm:mr-4">
                <li>
                    <FeedbackModal>
                        <button
                            class="
                                text-sm
                                font-medium
                                inline-flex
                                items-center
                                justify-center
                                text-gray-300
                                py-2
                                px-3
                                hover:bg-gray-700 hover:text-white
                                rounded-md
                            "
                        >
                            <SpeakerphoneIcon
                                class="w-5 h-5 text-gray-400 mr-2"
                            />
                            Submit Feedback
                        </button>
                    </FeedbackModal>
                </li>
            </ul>

            <Menu as="div" class="relative">
                <MenuButton class="flex items-center">
                    <div
                        class="
                            bg-gradient-to-br
                            from-purple-400
                            to-indigo-700
                            p-[0.15rem]
                            mr-2
                            rounded-full
                        "
                    >
                        <Gravatar
                            :email="$page.props.auth.user.email"
                            :size="35"
                            class="rounded-full"
                        />
                    </div>

                    <div class="relative py-4 sm:py-6 flex items-center">
                        <span class="text-indigo-400">Hello,&nbsp;</span>
                        <span class="font-bold text-indigo-100 mr-1">{{
                            $page.props.auth.user.name
                        }}</span>
                        <ChevronDownIcon class="h-4 w-4 text-gray-300" />
                    </div>
                </MenuButton>

                <transition
                    enter-active-class="transition duration-100 ease-out"
                    enter-from-class="transform scale-95 opacity-0"
                    enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-100 ease-in"
                    leave-from-class="transform scale-100 opacity-100"
                    leave-to-class="transform scale-95 opacity-0"
                >
                    <MenuItems
                        class="
                            absolute
                            right-0
                            origin-top-right
                            -mt-2
                            w-48
                            bg-white
                            rounded-md
                            shadow-lg
                            text-sm
                            font-medium
                            divide-y divide-gray-200
                            ring-1 ring-black ring-opacity-5
                        "
                    >
                        <div class="p-1">
                            <MenuItem class="w-full">
                                <Link
                                    :href="$route('profile')"
                                    class="
                                        text-gray-800
                                        flex
                                        items-center
                                        space-x-2
                                        rounded-md
                                        p-2
                                        group
                                        hover:bg-indigo-500 hover:text-white
                                    "
                                >
                                    <UserIcon
                                        class="
                                            h-5
                                            w-5
                                            sm:h-5 sm:w-5
                                            mr-2
                                            text-indigo-400
                                            group-hover:text-indigo-200
                                        "
                                    />
                                    Profile
                                </Link>
                            </MenuItem>
                        </div>

                        <div class="p-1">
                            <MenuItem class="w-full">
                                <Link
                                    as="button"
                                    method="POST"
                                    :href="$route('logout')"
                                    class="
                                        text-gray-900
                                        flex
                                        items-center
                                        rounded-md
                                        p-2
                                        font-medium
                                        text-sm
                                        group
                                        hover:bg-indigo-500 hover:text-white
                                    "
                                >
                                    <LogoutIcon
                                        class="
                                            w-5
                                            h-5
                                            text-gray-400
                                            group-hover:text-indigo-200
                                            mr-2
                                        "
                                    />
                                    Logout
                                </Link>
                            </MenuItem>
                        </div>
                    </MenuItems>
                </transition>
            </Menu>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import {
    SpeakerphoneIcon,
    UserIcon,
    ChevronDownIcon,
    LogoutIcon,
} from "@heroicons/vue/outline";
import { Link } from "@inertiajs/inertia-vue3";

import Gravatar from "./Gravatar.vue";
import FeedbackModal from "./Modal/FeedbackModal.vue";
import NavigationLink from "./UI/NavigationLink.vue";

export default defineComponent({
    name: "UserNavigation",

    components: {
        NavigationLink,
        FeedbackModal,
        Gravatar,
        SpeakerphoneIcon,
        LogoutIcon,
        UserIcon,
        ChevronDownIcon,
        Link,
        Menu,
        MenuItems,
        MenuItem,
        MenuButton,
    },
});
</script>
