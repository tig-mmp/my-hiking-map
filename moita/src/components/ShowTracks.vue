<script setup lang="ts">
import { trackMapDataType, type TrackMap } from "@/models/track/map";
import { Ref, defineAsyncComponent, onMounted, ref } from "vue";
import useApiRoutes from "@/composables/api/useApiRoutes";
import useApiGet from "@/composables/api/useApiGet";
import { FeatureCollection, LineString } from "geojson";

const Map = defineAsyncComponent(() => import("@/components/Map.vue"));
const Loading = defineAsyncComponent(() => import("@/components/Loading.vue"));

const { tracksApi } = useApiRoutes();

const geojsonSource: Ref<{ data: FeatureCollection<LineString>, show: boolean }> = ref({
  data: {
    type: "FeatureCollection",
    features: [],
  },
  show: false,
});

const { load: load, data: tracks, isLoading } = useApiGet<TrackMap[]>([]);
const getData = () => load(tracksApi, { dataType: trackMapDataType })
  .then(() => {
    showTracksAtATime(1000);
  });

const showTracksAtATime = (delay: number) => {
  let index = 0;
  const showATrackAtATime = () => {
    if (index < tracks.value.length) {
      const track = tracks.value[index];
      if (track.points) {
        geojsonSource.value.data.features[0] = {
          type: "Feature",
          properties: { color: generateRandomColor() },
          geometry: {
            type: "LineString",
            coordinates: track.points,
          },
        };
        updateMap();
      }
      index++;
      setTimeout(showATrackAtATime, delay);
    } else {
      showAllTracks();
    }
  };
  showATrackAtATime();
};
const showAllTracks = () => {
  geojsonSource.value.data.features = [];
  tracks.value.forEach(track => {
    geojsonSource.value.data.features.push({
      type: "Feature",
      properties: { color: generateRandomColor() },
      geometry: {
        type: "LineString",
        coordinates: track.points,
      },
    });
  });
  updateMap();
};

const updateMap = () => {
  geojsonSource.value.show = false;
  setTimeout(() => geojsonSource.value.show = true, 1);
};
const generateRandomColor = () => ("#" + Math.floor(Math.random() * 16777215).toString(16));

onMounted(() => getData());

</script>

<template>
  <Loading v-if="isLoading" />
  <Map v-else :show="geojsonSource.show" :data="geojsonSource.show ? geojsonSource.data : null" />
</template>