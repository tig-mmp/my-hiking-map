<script setup lang="ts">
import { trackMapDataType, type TrackMap } from "@/models/track/map";
import { onMounted } from "vue";
import useApiRoutes from "@/composables/api/useApiRoutes";
import useApiGet from "@/composables/api/useApiGet";

const { tracksApi } = useApiRoutes();

const { load: load, data: tracks, isLoading } = useApiGet<TrackMap[]>([]);
const getData = () => load(tracksApi, { dataType: trackMapDataType });

onMounted(() => getData());

</script>

<template>
</template>