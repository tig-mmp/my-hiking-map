<script setup lang="ts">
import { defineAsyncComponent } from "vue";

const Homepage = defineAsyncComponent(() => import("@/components/Homepage.vue"));

</script>

<template>
    <Homepage />
</template>
