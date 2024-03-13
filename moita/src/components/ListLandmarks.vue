<script setup lang="ts">
import { landmarkMapDataType, type LandmarkMap } from "@/models/landmark/map";
import { defineAsyncComponent, onMounted } from "vue";
import useApiRoutes from "@/composables/api/useApiRoutes";
import useApiGet from "@/composables/api/useApiGet";
import useEnvironment from "@/composables/useEnvironment";

const Loading = defineAsyncComponent(() => import("@/components/Loading.vue"));

const { landmarksApi } = useApiRoutes();
const { BASE } = useEnvironment();

const { load: load, data: landmarks, isLoading } = useApiGet<LandmarkMap[]>([]);
const getData = () => load(landmarksApi, { dataType: landmarkMapDataType })
  .then(() => landmarks.value = landmarks.value.filter((l) => !!l.file));

onMounted(() => getData());

</script>

<template>
  <Loading v-if="isLoading" />
  <div class="grid">
    <div v-for="landmark in landmarks" :key="landmark.id" class="wrapper padded-container">
      <img :src="`${BASE}${landmark.file?.url}`" :alt="landmark.file?.name" class="img centered" />
    </div>
  </div>
</template>

<style>
div.landmarks {
  text-align: center;
}

div.landmarks>img.landmark {
  margin: 6px;
}

.grid {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}

.wrapper {
  width: 24%;
  margin: 8px;
  border: 4px solid #2196f3;
}

.wrapper:hover {
  border-color: #008494;
}

.padded-container {
  position: relative;
  overflow: hidden;
  padding-bottom: 12%;
}

.img {
  width: 100%;
  height: auto;
}

.centered {
  position: absolute;
  top: -50%;
  left: -50%;
  right: -50%;
  bottom: -50%;
  margin: auto;
}
</style>