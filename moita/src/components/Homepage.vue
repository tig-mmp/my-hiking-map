<script setup lang="ts">
import { defineAsyncComponent } from "vue";

const ShowTracks = defineAsyncComponent(() => import("@/components/ShowTracks.vue"));
const ListLandmarks = defineAsyncComponent(() => import("@/components/ListLandmarks.vue"));

</script>

<template>
    <ShowTracks />
    <ListLandmarks />
</template>
