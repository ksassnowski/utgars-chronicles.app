<template>
    <img :src="url" />
</template>

<script lang="ts">
import { defineComponent } from "vue";
import md5 from "blueimp-md5";

export default defineComponent({
    name: "Gravatar",

    props: {
        email: String,
        size: Number,
    },

    setup(props) {
        const hash = md5(props.email.trim().toLowerCase());

        return {
            url: `https://www.gravatar.com/avatar/${hash}?s=${props.size}&d=retro&r=g`
        };
    }
});
</script>
