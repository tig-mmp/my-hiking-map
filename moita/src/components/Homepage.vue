<script setup lang="ts">
import { defineAsyncComponent } from "vue";

const ShowTracks = defineAsyncComponent(() => import("@/components/ShowTracks.vue"));

</script>

<template>
    <ShowTracks />
</template>
