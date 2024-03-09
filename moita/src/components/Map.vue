<script setup lang="ts">
import { Ref, ref } from "vue";
import useEnvironment from "@/composables/useEnvironment";
import { MglGeoJsonSource, MglLineLayer, MglMap } from "vue-maplibre-gl";
import { LngLatLike } from "maplibre-gl";
import { FeatureCollection, LineString } from "geojson";

const { MAP_STYLE } = useEnvironment();

const props = defineProps<{ data: FeatureCollection<LineString> | null, show: boolean }>();

const center: Ref<LngLatLike> = ref({ lat: 39.64957895555594, lon: -8.667871756075451 });

const zoom: Ref<number> = ref(14);
const linePaint = ref({
    "line-color": "#FF0000",
    "line-width": 6
});

const layout = ref({
    "line-cap": "round",
    "line-join": "round",
});
</script>

<template>
    <div style="height: 100vh; width: 100vw;">
        <MglMap :mapStyle="MAP_STYLE" :zoom="zoom" :center="center">
            <MglGeoJsonSource v-if="props.data" source-id="geojson" :data="props.data">
                <MglLineLayer v-if="props.show" layer-id="geojson" :layout="layout" :paint="linePaint" />
            </MglGeoJsonSource>
        </MglMap>
    </div>
</template>

<style lang="scss">
@import "https://unpkg.com/maplibre-gl@2.3.0/dist/maplibre-gl.css";

.mgl-wrapper {
    position: absolute;
    inset: 0;
}
</style>
