<template>
    <img :src="url" />
</template>

<script lang="ts" setup>
import md5 from "blueimp-md5";

const props = defineProps<{ email: string, size: number }>();

const hash = md5(props.email.trim().toLowerCase());
const url = `https://www.gravatar.com/avatar/${hash}?s=${props.size}&d=retro&r=g`;
</script>
